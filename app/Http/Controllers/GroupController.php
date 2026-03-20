<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupInvite;
use App\Models\GroupMessage;
use App\Models\MessageReaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GroupController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $groups = $user->groups()->with('owner', 'latestMessage')->withCount('members')->get();

        return view('groups.index', compact('groups'));
    }

    public function create()
    {
        return view('groups.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,gif,webp|max:2048',
        ]);

        $data = [
            'owner_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
        ];

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('group-avatars', 'public');
        }

        $group = Group::create($data);
        $group->members()->attach(Auth::id(), ['role' => 'owner']);

        return redirect()->route('groups.show', $group);
    }

    public function show(Group $group)
    {
        $user = Auth::user();

        if (!$group->hasMember($user)) {
            abort(403);
        }

        $messages = $group->messages()
            ->with('user', 'reactions')
            ->orderBy('created_at', 'asc')
            ->get();

        $members = $group->members;

        return view('groups.show', compact('group', 'messages', 'members'));
    }

    public function send(Request $request, Group $group)
    {
        $user = Auth::user();

        if (!$group->hasMember($user)) {
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

        $message = $group->messages()->create($data);

        if ($request->ajax()) {
            $message->load('user', 'reactions');
            return response()->json($this->formatMessage($message, $user->id));
        }

        return back();
    }

    public function poll(Request $request, Group $group)
    {
        $user = Auth::user();

        if (!$group->hasMember($user)) {
            abort(403);
        }

        $afterId = $request->integer('after', 0);

        $messages = $group->messages()
            ->with('user', 'reactions')
            ->where('id', '>', $afterId)
            ->orderBy('id')
            ->get()
            ->map(fn ($m) => $this->formatMessage($m, $user->id));

        return response()->json($messages);
    }

    public function updateAvatar(Request $request, Group $group)
    {
        if ($group->owner_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,gif,webp|max:2048',
        ]);

        if ($group->avatar) {
            Storage::disk('public')->delete($group->avatar);
        }

        $group->update([
            'avatar' => $request->file('avatar')->store('group-avatars', 'public'),
        ]);

        return back();
    }

    public function invite(Group $group)
    {
        $user = Auth::user();

        if (!$group->hasMember($user)) {
            abort(403);
        }

        $memberIds = $group->members()->pluck('users.id')->toArray();
        $pendingInviteUserIds = $group->invites()->where('status', 'pending')->pluck('user_id')->toArray();
        $excludeIds = array_merge($memberIds, $pendingInviteUserIds);

        $mutualFollowers = $user->followers()
            ->whereIn('users.id', $user->followings()->pluck('users.id'))
            ->whereNotIn('users.id', $excludeIds)
            ->get();

        return view('groups.invite', compact('group', 'mutualFollowers'));
    }

    public function sendInvite(Request $request, Group $group)
    {
        $user = Auth::user();

        if (!$group->hasMember($user)) {
            abort(403);
        }

        $request->validate(['user_id' => 'required|exists:users,id']);

        $target = User::findOrFail($request->user_id);

        if (!$user->isMutualFollow($target)) {
            return back()->with('error', __('messages.mutual_follow_required'));
        }

        if ($group->hasMember($target)) {
            return back()->with('error', __('messages.already_member'));
        }

        GroupInvite::updateOrCreate(
            ['group_id' => $group->id, 'user_id' => $target->id],
            ['sender_id' => $user->id, 'status' => 'pending']
        );

        return back()->with('success', __('messages.invite_sent'));
    }

    public function leave(Group $group)
    {
        $user = Auth::user();

        if ($group->owner_id === $user->id) {
            return back()->with('error', __('messages.owner_cannot_leave'));
        }

        $group->members()->detach($user->id);

        return redirect()->route('groups.index');
    }

    public function destroy(Group $group)
    {
        if ($group->owner_id !== Auth::id()) {
            abort(403);
        }

        $group->delete();

        return redirect()->route('groups.index');
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

    public function editMessage(Request $request, Group $group, GroupMessage $message)
    {
        $user = Auth::user();

        if ($message->group_id !== $group->id) {
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

    public function deleteMessage(Group $group, GroupMessage $message)
    {
        $user = Auth::user();

        if ($message->group_id !== $group->id) {
            abort(404);
        }

        // Owner can delete any message; users can delete own messages
        if ($message->user_id !== $user->id && $group->owner_id !== $user->id) {
            abort(403);
        }

        if ($message->media_path) {
            Storage::disk('public')->delete($message->media_path);
        }

        $message->delete();

        return response()->json(['success' => true]);
    }

    public function reactMessage(Request $request, Group $group, GroupMessage $message)
    {
        $user = Auth::user();

        if ($message->group_id !== $group->id) {
            abort(404);
        }

        if (!$group->hasMember($user)) {
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
