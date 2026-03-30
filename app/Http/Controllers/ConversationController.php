<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\ChatTheme;
use App\Models\MessageFavorite;
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
            ->with('user', 'reactions', 'replyTo.user')
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get()
            ->reverse()
            ->values();

        $pinnedMessages = $conversation->pinnedMessages()
            ->with('user')
            ->take(10)
            ->get();

        $otherUser = $conversation->otherUser($user);

        $forwardTargets = $this->getForwardTargets($user);

        $conversations = Conversation::forUser($user->id)
            ->with(['userOne', 'userTwo', 'latestMessage'])
            ->get()
            ->sortByDesc(fn ($c) => $c->latestMessage?->created_at)
            ->values();
        $groups = $user->groups()->with('owner', 'latestMessage.user')->withCount('members')->get();
        $activeConversationId = $conversation->id;
        $chatTheme = ChatTheme::where('user_id', $user->id)->where('chat_type', Conversation::class)->where('chat_id', $conversation->id)->value('theme_key') ?? 'default';

        return view('conversations.show', compact('conversation', 'messages', 'otherUser', 'pinnedMessages', 'forwardTargets', 'conversations', 'groups', 'activeConversationId', 'chatTheme'));
    }

    public function send(Request $request, Conversation $conversation)
    {
        $user = Auth::user();

        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'body' => 'nullable|string|max:2000',
            'media' => 'nullable|file|mimes:jpeg,png,gif,webp,mp4,webm,mov|max:51200',
            'file' => 'nullable|file|max:51200',
            'audio' => 'nullable|file|mimes:webm,ogg,mp3,wav,mp4|max:10240',
            'audio_duration' => 'nullable|integer|min:0|max:600',
            'reply_to_id' => 'nullable|exists:conversation_messages,id',
        ]);

        if (!$request->body && !$request->hasFile('media') && !$request->hasFile('file') && !$request->hasFile('audio')) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Message or media required'], 422);
            }
            return back();
        }

        if ($request->reply_to_id) {
            $replyMsg = ConversationMessage::find($request->reply_to_id);
            if (!$replyMsg || $replyMsg->conversation_id !== $conversation->id) {
                $request->merge(['reply_to_id' => null]);
            }
        }

        $data = [
            'user_id' => $user->id,
            'body' => $request->body,
            'reply_to_id' => $request->reply_to_id,
        ];

        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $data['media_path'] = $file->store('chat-media', 'public');
            $data['media_type'] = str_starts_with($file->getMimeType(), 'video/') ? 'video' : 'image';
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $data['file_path'] = $file->store('chat-files', 'public');
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
        }

        if ($request->hasFile('audio')) {
            $data['audio_path'] = $request->file('audio')->store('chat-audio', 'public');
            $data['audio_duration'] = $request->integer('audio_duration', 0);
        }

        $message = $conversation->messages()->create($data);

        if ($request->ajax()) {
            $message->load('user', 'reactions', 'replyTo.user');
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
            ->with('user', 'reactions', 'replyTo.user')
            ->where('id', '>', $afterId)
            ->orderBy('id')
            ->get()
            ->map(fn ($m) => $this->formatMessage($m, $user->id));

        $otherUser = $conversation->otherUser($user);

        return response()->json([
            'messages' => $messages,
            'other_user_online' => $otherUser->isOnline(),
            'other_user_last_seen' => $otherUser->last_seen_at?->diffForHumans(),
        ]);
    }

    public function loadHistory(Request $request, Conversation $conversation)
    {
        $user = Auth::user();

        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403);
        }

        $beforeId = $request->integer('before', 0);

        $messages = $conversation->messages()
            ->with('user', 'reactions', 'replyTo.user')
            ->where('id', '<', $beforeId)
            ->orderByDesc('id')
            ->take(30)
            ->get()
            ->reverse()
            ->values()
            ->map(fn ($m) => $this->formatMessage($m, $user->id));

        return response()->json(['messages' => $messages]);
    }

    public function search(Request $request, Conversation $conversation)
    {
        $user = Auth::user();

        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403);
        }

        $request->validate(['q' => 'required|string|max:200']);

        $messages = $conversation->messages()
            ->with('user')
            ->where('body', 'like', '%' . $request->q . '%')
            ->orderByDesc('created_at')
            ->take(30)
            ->get()
            ->map(fn ($m) => [
                'id' => $m->id,
                'body' => $m->body,
                'user_name' => $m->user->name,
                'time' => $m->created_at->format('d.m.Y H:i'),
            ]);

        return response()->json(['results' => $messages]);
    }

    public function typing(Conversation $conversation)
    {
        $user = Auth::user();

        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403);
        }

        $cacheKey = "typing.conv.{$conversation->id}.{$user->id}";
        cache()->put($cacheKey, true, now()->addSeconds(4));

        return response()->json(['ok' => true]);
    }

    public function typingStatus(Conversation $conversation)
    {
        $user = Auth::user();

        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403);
        }

        $otherUser = $conversation->otherUser($user);
        $cacheKey = "typing.conv.{$conversation->id}.{$otherUser->id}";

        return response()->json(['typing' => cache()->get($cacheKey, false)]);
    }

    public function forward(Request $request, Conversation $conversation, ConversationMessage $message)
    {
        $user = Auth::user();

        if ($message->conversation_id !== $conversation->id) {
            abort(404);
        }
        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'target_type' => 'required|in:conversation,group',
            'target_id' => 'required|integer',
        ]);

        if ($request->target_type === 'conversation') {
            $targetConv = Conversation::findOrFail($request->target_id);
            if ($targetConv->user_one_id !== $user->id && $targetConv->user_two_id !== $user->id) {
                abort(403);
            }

            $targetConv->messages()->create([
                'user_id' => $user->id,
                'body' => $message->body,
                'media_path' => $message->media_path,
                'media_type' => $message->media_type,
                'file_path' => $message->file_path,
                'file_name' => $message->file_name,
                'file_size' => $message->file_size,
                'forwarded_from_id' => $message->id,
            ]);
        } else {
            $group = \App\Models\Group::findOrFail($request->target_id);
            if (!$group->hasMember($user)) {
                abort(403);
            }

            $group->messages()->create([
                'user_id' => $user->id,
                'body' => $message->body,
                'media_path' => $message->media_path,
                'media_type' => $message->media_type,
                'file_path' => $message->file_path,
                'file_name' => $message->file_name,
                'file_size' => $message->file_size,
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function pinMessage(Conversation $conversation, ConversationMessage $message)
    {
        $user = Auth::user();

        if ($message->conversation_id !== $conversation->id) {
            abort(404);
        }
        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403);
        }

        $message->update(['pinned_at' => $message->pinned_at ? null : now()]);

        return response()->json([
            'success' => true,
            'pinned' => $message->pinned_at !== null,
        ]);
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

        $replyData = null;
        if ($m->replyTo) {
            $replyData = [
                'id' => $m->replyTo->id,
                'body' => $m->replyTo->body ? \Illuminate\Support\Str::limit($m->replyTo->body, 80) : null,
                'user_name' => $m->replyTo->user->name ?? null,
                'has_media' => $m->replyTo->media_path !== null,
                'has_file' => $m->replyTo->file_path !== null,
            ];
        }

        return [
            'id' => $m->id,
            'body' => $m->body,
            'media_path' => $m->media_path ? asset('storage/' . $m->media_path) : null,
            'media_type' => $m->media_type,
            'audio_path' => $m->audio_path ? asset('storage/' . $m->audio_path) : null,
            'audio_duration' => $m->audio_duration,
            'file_path' => $m->file_path ? asset('storage/' . $m->file_path) : null,
            'file_name' => $m->file_name,
            'file_size' => $m->file_size,
            'user_id' => $m->user_id,
            'user_name' => $m->user->name,
            'user_avatar' => $m->user->avatar ? asset('storage/' . $m->user->avatar) : asset('storage/logo/defaultPhoto.jpg'),
            'time' => $m->created_at->format('H:i'),
            'date' => $m->created_at->format('d.m.Y'),
            'is_mine' => $m->user_id === $authId,
            'edited' => $m->edited_at !== null,
            'pinned' => $m->pinned_at !== null,
            'forwarded' => $m->forwarded_from_id !== null,
            'read' => $m->read_at !== null,
            'favorited' => MessageFavorite::where('user_id', $authId)->where('message_type', ConversationMessage::class)->where('message_id', $m->id)->exists(),
            'reply_to' => $replyData,
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
        if ($message->file_path) {
            Storage::disk('public')->delete($message->file_path);
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

    private function getForwardTargets($user): array
    {
        $conversations = Conversation::forUser($user->id)
            ->with(['userOne', 'userTwo'])
            ->get()
            ->map(fn ($c) => [
                'type' => 'conversation',
                'id' => $c->id,
                'name' => $c->otherUser($user)->name,
                'avatar' => $c->otherUser($user)->avatar
                    ? asset('storage/' . $c->otherUser($user)->avatar)
                    : asset('storage/logo/defaultPhoto.jpg'),
            ]);

        $groups = $user->groups->map(fn ($g) => [
            'type' => 'group',
            'id' => $g->id,
            'name' => $g->name,
            'avatar' => $g->avatar ? asset('storage/' . $g->avatar) : null,
        ]);

        return $conversations->merge($groups)->toArray();
    }

    public function toggleFavorite(Conversation $conversation, ConversationMessage $message)
    {
        $user = Auth::user();

        if ($message->conversation_id !== $conversation->id) {
            abort(404);
        }
        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403);
        }

        $existing = MessageFavorite::where('user_id', $user->id)
            ->where('message_type', ConversationMessage::class)
            ->where('message_id', $message->id)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['favorited' => false]);
        }

        MessageFavorite::create([
            'user_id' => $user->id,
            'message_type' => ConversationMessage::class,
            'message_id' => $message->id,
        ]);

        return response()->json(['favorited' => true]);
    }

    public function setTheme(Request $request, Conversation $conversation)
    {
        $user = Auth::user();

        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403);
        }

        $request->validate(['theme_key' => 'required|string|max:50']);

        ChatTheme::updateOrCreate(
            ['user_id' => $user->id, 'chat_type' => Conversation::class, 'chat_id' => $conversation->id],
            ['theme_key' => $request->theme_key]
        );

        return response()->json(['success' => true]);
    }

    public function favorites()
    {
        $user = Auth::user();

        $favs = MessageFavorite::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($fav) {
                $msg = $fav->message_type::with('user')->find($fav->message_id);
                if (!$msg) return null;
                return [
                    'id' => $fav->id,
                    'message_id' => $msg->id,
                    'type' => str_contains($fav->message_type, 'Conversation') ? 'conversation' : 'group',
                    'body' => $msg->body,
                    'audio_path' => $msg->audio_path ? asset('storage/' . $msg->audio_path) : null,
                    'media_path' => $msg->media_path ? asset('storage/' . $msg->media_path) : null,
                    'user_name' => $msg->user->name ?? '',
                    'time' => $msg->created_at->format('d.m.Y H:i'),
                ];
            })
            ->filter()
            ->values();

        return response()->json(['favorites' => $favs]);
    }
}
