<?php

namespace App\Http\Controllers;

use App\Models\ChatTheme;
use App\Models\Group;
use App\Models\GroupInvite;
use App\Models\GroupMessage;
use App\Models\GroupPoll;
use App\Models\GroupPollOption;
use App\Models\GroupPollVote;
use App\Models\MessageFavorite;
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
            ->with('user', 'reactions', 'replyTo.user', 'poll.options.votes')
            ->orderByDesc('created_at')
            ->take(50)
            ->get()
            ->reverse()
            ->values();

        $members = $group->members;
        $memberRole = $group->getMemberRole($user);

        $pinnedMessages = $group->pinnedMessages()
            ->with('user')
            ->take(10)
            ->get();

        $pivot = $group->members()->where('group_members.user_id', $user->id)->first()?->pivot;
        if ($pivot && $messages->last()) {
            $pivot->update(['last_read_message_id' => $messages->last()->id]);
        }

        $forwardTargets = $this->getForwardTargets($user);

        $conversations = \App\Models\Conversation::forUser($user->id)
            ->with(['userOne', 'userTwo', 'latestMessage'])
            ->get()
            ->sortByDesc(fn ($c) => $c->latestMessage?->created_at)
            ->values();
        $userGroups = $user->groups()->with('owner', 'latestMessage.user')->withCount('members')->get();
        $activeGroupId = $group->id;
        $chatTheme = ChatTheme::where('user_id', $user->id)->where('chat_type', Group::class)->where('chat_id', $group->id)->value('theme_key') ?? 'default';

        return view('groups.show', compact('group', 'messages', 'members', 'memberRole', 'pinnedMessages', 'forwardTargets', 'conversations', 'activeGroupId', 'chatTheme'))
            ->with('groups', $userGroups);
    }

    public function send(Request $request, Group $group)
    {
        $user = Auth::user();

        if (!$group->hasMember($user)) {
            abort(403);
        }

        $request->validate([
            'body' => 'nullable|string|max:2000',
            'media' => 'nullable|file|mimes:jpeg,png,gif,webp,mp4,webm,mov|max:51200',
            'file' => 'nullable|file|max:51200',
            'audio' => 'nullable|file|mimes:webm,ogg,mp3,wav,mp4|max:10240',
            'audio_duration' => 'nullable|integer|min:0|max:600',
            'reply_to_id' => 'nullable|exists:group_messages,id',
        ]);

        if (!$request->body && !$request->hasFile('media') && !$request->hasFile('file') && !$request->hasFile('audio')) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Message or media required'], 422);
            }
            return back();
        }

        if ($request->reply_to_id) {
            $replyMsg = GroupMessage::find($request->reply_to_id);
            if (!$replyMsg || $replyMsg->group_id !== $group->id) {
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

        $message = $group->messages()->create($data);

        $group->members()->where('group_members.user_id', $user->id)
            ->update(['last_read_message_id' => $message->id]);

        if ($request->ajax()) {
            $message->load('user', 'reactions', 'replyTo.user');
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
            ->with('user', 'reactions', 'replyTo.user', 'poll.options.votes')
            ->where('id', '>', $afterId)
            ->orderBy('id')
            ->get();

        if ($messages->isNotEmpty()) {
            $group->members()->where('group_members.user_id', $user->id)
                ->update(['last_read_message_id' => $messages->last()->id]);
        }

        $mapped = $messages->map(fn ($m) => $this->formatMessage($m, $user->id));

        return response()->json(['messages' => $mapped]);
    }

    public function loadHistory(Request $request, Group $group)
    {
        $user = Auth::user();

        if (!$group->hasMember($user)) {
            abort(403);
        }

        $beforeId = $request->integer('before', 0);

        $messages = $group->messages()
            ->with('user', 'reactions', 'replyTo.user', 'poll.options.votes')
            ->where('id', '<', $beforeId)
            ->orderByDesc('id')
            ->take(30)
            ->get()
            ->reverse()
            ->values()
            ->map(fn ($m) => $this->formatMessage($m, $user->id));

        return response()->json(['messages' => $messages]);
    }

    public function search(Request $request, Group $group)
    {
        $user = Auth::user();

        if (!$group->hasMember($user)) {
            abort(403);
        }

        $request->validate(['q' => 'required|string|max:200']);

        $messages = $group->messages()
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

    public function typing(Group $group)
    {
        $user = Auth::user();

        if (!$group->hasMember($user)) {
            abort(403);
        }

        $cacheKey = "typing.group.{$group->id}.{$user->id}";
        cache()->put($cacheKey, $user->name, now()->addSeconds(4));

        return response()->json(['ok' => true]);
    }

    public function typingStatus(Group $group)
    {
        $user = Auth::user();

        if (!$group->hasMember($user)) {
            abort(403);
        }

        $typingUsers = [];
        foreach ($group->members as $member) {
            if ($member->id === $user->id) continue;
            $cacheKey = "typing.group.{$group->id}.{$member->id}";
            $name = cache()->get($cacheKey);
            if ($name) {
                $typingUsers[] = $name;
            }
        }

        return response()->json(['users' => $typingUsers]);
    }

    public function updateAvatar(Request $request, Group $group)
    {
        if (!$group->isAdmin(Auth::user())) {
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

    public function updateName(Request $request, Group $group)
    {
        if (!$group->isAdmin(Auth::user())) {
            abort(403);
        }

        $request->validate(['name' => 'required|string|max:100']);

        $group->update(['name' => $request->name]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'name' => $group->name]);
        }
        return back();
    }

    public function updateDescription(Request $request, Group $group)
    {
        if (!$group->isAdmin(Auth::user())) {
            abort(403);
        }

        $request->validate(['description' => 'nullable|string|max:500']);

        $group->update(['description' => $request->description]);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        return back();
    }

    public function invite(Group $group)
    {
        $user = Auth::user();

        if (!$group->hasMember($user)) {
            abort(403);
        }

        $memberIds = $group->members->pluck('id')->toArray();
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

    public function setRole(Request $request, Group $group, User $user)
    {
        $me = Auth::user();

        if ($group->owner_id !== $me->id) {
            abort(403);
        }

        if ($user->id === $me->id) {
            abort(403);
        }

        $request->validate(['role' => 'required|in:member,admin']);

        $group->members()->updateExistingPivot($user->id, ['role' => $request->role]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'role' => $request->role]);
        }
        return back();
    }

    public function removeMember(Group $group, User $user)
    {
        $me = Auth::user();

        if (!$group->isAdmin($me)) {
            abort(403);
        }

        if ($user->id === $group->owner_id) {
            abort(403);
        }

        if ($group->getMemberRole($user) === 'admin' && $group->owner_id !== $me->id) {
            abort(403);
        }

        $group->members()->detach($user->id);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return back();
    }

    public function forward(Request $request, Group $group, GroupMessage $message)
    {
        $user = Auth::user();

        if ($message->group_id !== $group->id) {
            abort(404);
        }
        if (!$group->hasMember($user)) {
            abort(403);
        }

        $request->validate([
            'target_type' => 'required|in:conversation,group',
            'target_id' => 'required|integer',
        ]);

        if ($request->target_type === 'conversation') {
            $targetConv = \App\Models\Conversation::findOrFail($request->target_id);
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
            ]);
        } else {
            $targetGroup = Group::findOrFail($request->target_id);
            if (!$targetGroup->hasMember($user)) {
                abort(403);
            }

            $targetGroup->messages()->create([
                'user_id' => $user->id,
                'body' => $message->body,
                'media_path' => $message->media_path,
                'media_type' => $message->media_type,
                'file_path' => $message->file_path,
                'file_name' => $message->file_name,
                'file_size' => $message->file_size,
                'forwarded_from_id' => $message->id,
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function pinMessage(Group $group, GroupMessage $message)
    {
        $user = Auth::user();

        if ($message->group_id !== $group->id) {
            abort(404);
        }
        if (!$group->isAdmin($user)) {
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
            'user_avatar' => $m->user->avatar ? asset('storage/' . $m->user->avatar) : asset('storage/default-avatar/default-avatar.avif'),
            'time' => $m->created_at->format('H:i'),
            'date' => $m->created_at->format('d.m.Y'),
            'is_mine' => $m->user_id === $authId,
            'edited' => $m->edited_at !== null,
            'pinned' => $m->pinned_at !== null,
            'forwarded' => $m->forwarded_from_id !== null,
            'favorited' => MessageFavorite::where('user_id', $authId)->where('message_type', GroupMessage::class)->where('message_id', $m->id)->exists(),
            'reply_to' => $replyData,
            'reactions' => $reactions,
            'poll' => $m->poll ? $this->formatPoll($m->poll, $authId) : null,
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

        if ($message->user_id !== $user->id && !$group->isAdmin($user)) {
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

    private function getForwardTargets($user): array
    {
        $conversations = \App\Models\Conversation::forUser($user->id)
            ->with(['userOne', 'userTwo'])
            ->get()
            ->map(fn ($c) => [
                'type' => 'conversation',
                'id' => $c->id,
                'name' => $c->otherUser($user)->name,
                'avatar' => $c->otherUser($user)->avatar
                    ? asset('storage/' . $c->otherUser($user)->avatar)
                    : asset('storage/default-avatar/default-avatar.avif'),
            ]);

        $groups = $user->groups->map(fn ($g) => [
            'type' => 'group',
            'id' => $g->id,
            'name' => $g->name,
            'avatar' => $g->avatar ? asset('storage/' . $g->avatar) : null,
        ]);

        return $conversations->merge($groups)->toArray();
    }

    public function toggleFavorite(Group $group, GroupMessage $message)
    {
        $user = Auth::user();

        if ($message->group_id !== $group->id) {
            abort(404);
        }
        if (!$group->hasMember($user)) {
            abort(403);
        }

        $existing = MessageFavorite::where('user_id', $user->id)
            ->where('message_type', GroupMessage::class)
            ->where('message_id', $message->id)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['favorited' => false]);
        }

        MessageFavorite::create([
            'user_id' => $user->id,
            'message_type' => GroupMessage::class,
            'message_id' => $message->id,
        ]);

        return response()->json(['favorited' => true]);
    }

    public function setTheme(Request $request, Group $group)
    {
        $user = Auth::user();

        if (!$group->hasMember($user)) {
            abort(403);
        }

        $request->validate(['theme_key' => 'required|string|max:50']);

        ChatTheme::updateOrCreate(
            ['user_id' => $user->id, 'chat_type' => Group::class, 'chat_id' => $group->id],
            ['theme_key' => $request->theme_key]
        );

        return response()->json(['success' => true]);
    }

    public function createPoll(Request $request, Group $group)
    {
        $user = Auth::user();

        if (!$group->hasMember($user)) {
            abort(403);
        }

        $request->validate([
            'question' => 'required|string|max:300',
            'options' => 'required|array|min:2|max:10',
            'options.*' => 'required|string|max:200',
            'is_anonymous' => 'boolean',
            'is_multiple' => 'boolean',
        ]);

        $message = $group->messages()->create([
            'user_id' => $user->id,
            'body' => '📊 ' . $request->question,
        ]);

        $poll = GroupPoll::create([
            'group_id' => $group->id,
            'user_id' => $user->id,
            'group_message_id' => $message->id,
            'question' => $request->question,
            'is_anonymous' => $request->boolean('is_anonymous'),
            'is_multiple' => $request->boolean('is_multiple'),
        ]);

        foreach ($request->options as $i => $text) {
            $poll->options()->create(['text' => $text, 'sort_order' => $i]);
        }

        $group->members()->where('group_members.user_id', $user->id)
            ->update(['last_read_message_id' => $message->id]);

        $message->load('user', 'reactions', 'replyTo.user', 'poll.options.votes');

        $formatted = $this->formatMessage($message, $user->id);
        $formatted['poll'] = $this->formatPoll($poll, $user->id);

        return response()->json($formatted);
    }

    public function votePoll(Request $request, Group $group, GroupPoll $poll)
    {
        $user = Auth::user();

        if (!$group->hasMember($user) || $poll->group_id !== $group->id) {
            abort(403);
        }

        if ($poll->isClosed()) {
            return response()->json(['error' => 'Poll is closed'], 422);
        }

        $optionIds = $request->option_ids ?? ($request->option_id ? [(int)$request->option_id] : []);

        if (empty($optionIds)) {
            return response()->json(['error' => 'Option required'], 422);
        }

        if (!$poll->is_multiple && count($optionIds) > 1) {
            return response()->json(['error' => 'Single choice only'], 422);
        }

        GroupPollVote::where('group_poll_id', $poll->id)->where('user_id', $user->id)->delete();

        foreach ($optionIds as $optionId) {
            $option = GroupPollOption::where('id', $optionId)->where('group_poll_id', $poll->id)->first();
            if ($option) {
                GroupPollVote::create([
                    'group_poll_id' => $poll->id,
                    'group_poll_option_id' => $option->id,
                    'user_id' => $user->id,
                ]);
            }
        }

        return response()->json(['poll' => $this->formatPoll($poll->fresh(), $user->id)]);
    }

    private function formatPoll(GroupPoll $poll, int $authId): array
    {
        $poll->load('options.votes');
        $totalVotes = $poll->votes()->count();
        $userVotes = GroupPollVote::where('group_poll_id', $poll->id)->where('user_id', $authId)->pluck('group_poll_option_id')->toArray();

        return [
            'id' => $poll->id,
            'question' => $poll->question,
            'is_anonymous' => $poll->is_anonymous,
            'is_multiple' => $poll->is_multiple,
            'is_closed' => $poll->isClosed(),
            'total_votes' => $totalVotes,
            'user_voted' => !empty($userVotes),
            'options' => $poll->options->map(function ($opt) use ($totalVotes, $userVotes) {
                $optVotes = $opt->votes->count();
                return [
                    'id' => $opt->id,
                    'option_text' => $opt->text,
                    'votes' => $optVotes,
                    'percentage' => $totalVotes > 0 ? round($optVotes / $totalVotes * 100) : 0,
                    'user_voted' => in_array($opt->id, $userVotes),
                ];
            })->toArray(),
        ];
    }

    public function searchMembers(Request $request, Group $group)
    {
        $user = Auth::user();

        if (!$group->hasMember($user)) {
            abort(403);
        }

        $q = $request->input('q', '');
        $members = $group->members()
            ->where('users.name', 'like', '%' . $q . '%')
            ->take(10)
            ->get(['users.id', 'users.name', 'users.avatar']);

        return response()->json(['members' => $members->map(fn ($m) => [
            'id' => $m->id,
            'name' => $m->name,
            'avatar' => $m->avatar ? asset('storage/' . $m->avatar) : asset('storage/default-avatar/default-avatar.avif'),
        ])]);
    }
}
