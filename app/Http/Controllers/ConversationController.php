<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\MessageReaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ConversationController extends Controller
{
    public function chats()
    {
        $user = Auth::user();

        $conversations = Conversation::forUser($user->id)
            ->with(['userOne', 'userTwo', 'latestMessage'])
            ->get()
            ->sortByDesc(fn ($c) => $c->latestMessage?->created_at)
            ->values();

        $groups = $user->groups()->with('owner', 'latestMessage.user')->withCount('members')->get();

        return view('chats.index', compact('conversations', 'groups'));
    }

    public function index()
    {
        $user = Auth::user();

        $conversations = Conversation::forUser($user->id)
            ->with(['userOne', 'userTwo', 'latestMessage'])
            ->get()
            ->sortByDesc(fn ($c) => $c->latestMessage?->created_at)
            ->values();

        return view('conversations.index', compact('conversations'));
    }

    public function start(User $user)
    {
        $me = Auth::user();

        if ($me->id === $user->id) {
            abort(403);
        }

        if (!$me->isMutualFollow($user)) {
            return back()->with('error', __('messages.mutual_follow_required'));
        }

        $conversation = Conversation::where(function ($q) use ($me, $user) {
            $q->where('user_one_id', $me->id)->where('user_two_id', $user->id);
        })->orWhere(function ($q) use ($me, $user) {
            $q->where('user_one_id', $user->id)->where('user_two_id', $me->id);
        })->first();

        if (!$conversation) {
            $ids = [$me->id, $user->id];
            sort($ids);
            $conversation = Conversation::create([
                'user_one_id' => $ids[0],
                'user_two_id' => $ids[1],
            ]);
        }

        return redirect()->route('conversations.show', $conversation);
    }

    public function show(Conversation $conversation)
    {
        $user = Auth::user();

        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403);
        }

        $conversation->messages()
            ->where('user_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = $conversation->messages()
            ->with('user', 'reactions')
            ->orderBy('created_at', 'asc')
            ->get();

        $otherUser = $conversation->otherUser($user);

        return view('conversations.show', compact('conversation', 'messages', 'otherUser'));
    }

    public function send(Request $request, Conversation $conversation)
    {
        $user = Auth::user();

        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'body' => 'nullable|string|max:2000',
            'media' => 'nullable|file|mimes:jpeg,png,gif,webp,mp4,webm,mov|max:10240',
        ]);

        if (!$request->body && !$request->hasFile('media')) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Message or media required'], 422);
            }
            return back();
        }

        $data = ['user_id' => $user->id, 'body' => $request->body];

        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $data['media_path'] = $file->store('chat-media', 'public');
            $data['media_type'] = str_starts_with($file->getMimeType(), 'video/') ? 'video' : 'image';
        }

        $message = $conversation->messages()->create($data);

        if ($request->ajax()) {
            $message->load('user', 'reactions');
            return response()->json($this->formatMessage($message, $user->id));
        }

        return back();
    }

    public function poll(Request $request, Conversation $conversation)
    {
        $user = Auth::user();

        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403);
        }

        $afterId = $request->integer('after', 0);

        $conversation->messages()
            ->where('user_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = $conversation->messages()
            ->with('user', 'reactions')
            ->where('id', '>', $afterId)
            ->orderBy('id')
            ->get()
            ->map(fn ($m) => $this->formatMessage($m, $user->id));

        return response()->json($messages);
    }

    private function formatMessage($m, $authId): array
    {
        $reactions = $m->reactions->groupBy('emoji')->map(function ($group) use ($authId) {
            return [
                'emoji' => $group->first()->emoji,
                'count' => $group->count(),
                'reacted' => $group->contains('user_id', $authId),
            ];
        })->values()->toArray();

        return [
            'id' => $m->id,
            'body' => $m->body,
            'media_path' => $m->media_path ? asset('storage/' . $m->media_path) : null,
            'media_type' => $m->media_type,
            'user_id' => $m->user_id,
            'user_name' => $m->user->name,
            'user_avatar' => $m->user->avatar ? asset('storage/' . $m->user->avatar) : asset('storage/logo/defaultPhoto.jpg'),
            'time' => $m->created_at->format('H:i'),
            'is_mine' => $m->user_id === $authId,
            'edited' => $m->edited_at !== null,
            'reactions' => $reactions,
        ];
    }

    public function editMessage(Request $request, Conversation $conversation, ConversationMessage $message)
    {
        $user = Auth::user();

        if ($message->conversation_id !== $conversation->id) {
            abort(404);
        }

        if ($message->user_id !== $user->id) {
            abort(403);
        }

        $request->validate(['body' => 'required|string|max:2000']);

        $message->update([
            'body' => $request->body,
            'edited_at' => now(),
        ]);

        return response()->json(['success' => true, 'body' => $message->body]);
    }

    public function deleteMessage(Conversation $conversation, ConversationMessage $message)
    {
        $user = Auth::user();

        if ($message->conversation_id !== $conversation->id) {
            abort(404);
        }

        if ($message->user_id !== $user->id) {
            abort(403);
        }

        if ($message->media_path) {
            Storage::disk('public')->delete($message->media_path);
        }

        $message->delete();

        return response()->json(['success' => true]);
    }

    public function reactMessage(Request $request, Conversation $conversation, ConversationMessage $message)
    {
        $user = Auth::user();

        if ($message->conversation_id !== $conversation->id) {
            abort(404);
        }

        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403);
        }

        $request->validate(['emoji' => 'required|string|max:8']);

        $existing = $message->reactions()
            ->where('user_id', $user->id)
            ->where('emoji', $request->emoji)
            ->first();

        if ($existing) {
            $existing->delete();
        } else {
            $message->reactions()->create([
                'user_id' => $user->id,
                'emoji' => $request->emoji,
            ]);
        }

        $reactions = $message->reactions()->get()->groupBy('emoji')->map(function ($group) use ($user) {
            return [
                'emoji' => $group->first()->emoji,
                'count' => $group->count(),
                'reacted' => $group->contains('user_id', $user->id),
            ];
        })->values()->toArray();

        return response()->json(['reactions' => $reactions]);
    }
}
