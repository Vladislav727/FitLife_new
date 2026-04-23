<div class="chat-sidebar-list" id="chatSidebarList" data-default-tab="{{ isset($activeGroupId) ? 'groups' : 'messages' }}">
    <div class="chat-sidebar-list__header">
        <h2 class="chat-sidebar-list__title">{{ __('messages.chats') }}</h2>
        <a href="{{ route('groups.create') }}" class="chat-sidebar-list__new" title="{{ __('messages.create_group') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        </a>
    </div>

    <div class="chats-tabs">
        <button class="chats-tab active" data-tab="messages">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            {{ __('messages.conversations') }}
            @php
                $totalUnread = 0;
                foreach ($conversations as $c) {
                    $totalUnread += $c->messages()->where('user_id', '!=', Auth::id())->whereNull('read_at')->count();
                }
            @endphp
            @if($totalUnread > 0)
                <span class="chats-tab__badge">{{ $totalUnread }}</span>
            @endif
        </button>
        <button class="chats-tab" data-tab="groups">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            {{ __('messages.groups') }}
            @if($groups->count() > 0)
                <span class="chats-tab__count">{{ $groups->count() }}</span>
            @endif
        </button>
    </div>

    <div class="chats-panel active" id="panel-messages">
        <div class="msg-list">
            @forelse($conversations as $conversation)
                @php $other = $conversation->otherUser(Auth::user()); @endphp
                <a href="{{ route('conversations.show', $conversation) }}" class="msg-item{{ isset($activeConversationId) && $activeConversationId === $conversation->id ? ' msg-item--active' : '' }}">
                    <div class="msg-item__avatar-wrap">
                        <img src="{{ $other->avatar ? asset('storage/' . $other->avatar) : asset('storage/default-avatar/default-avatar.avif') }}" alt="{{ $other->name }}" class="msg-item__avatar">
                        @if($other->isOnline())
                            <span class="msg-item__online-dot"></span>
                        @endif
                    </div>
                    <div class="msg-item__content">
                        <div class="msg-item__top">
                            <span class="msg-item__name">{{ $other->name }}</span>
                            @if($conversation->latestMessage)
                                <span class="msg-item__time">{{ $conversation->latestMessage->created_at->diffForHumans(null, true) }}</span>
                            @endif
                        </div>
                        <p class="msg-item__preview">
                            @if($conversation->latestMessage)
                                {{ Str::limit($conversation->latestMessage->body, 50) }}
                            @else
                                {{ __('messages.no_messages_yet') }}
                            @endif
                        </p>
                    </div>
                    @php
                        $unread = $conversation->messages()->where('user_id', '!=', Auth::id())->whereNull('read_at')->count();
                    @endphp
                    @if($unread > 0)
                        <span class="msg-item__badge">{{ $unread }}</span>
                    @endif
                </a>
            @empty
                <div class="msg-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="40" height="40"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    <p>{{ __('messages.no_conversations') }}</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="chats-panel" id="panel-groups">
        <div class="msg-list">
            @forelse($groups as $group)
                <a href="{{ route('groups.show', $group) }}" class="msg-item{{ isset($activeGroupId) && $activeGroupId === $group->id ? ' msg-item--active' : '' }}">
                    @if($group->avatar)
                        <img src="{{ asset('storage/' . $group->avatar) }}" alt="{{ $group->name }}" class="msg-item__avatar">
                    @else
                        <div class="msg-item__group-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </div>
                    @endif
                    <div class="msg-item__content">
                        <div class="msg-item__top">
                            <span class="msg-item__name">{{ $group->name }}</span>
                            <span class="msg-item__meta">{{ $group->members_count }} {{ __('messages.members') }}</span>
                        </div>
                        <p class="msg-item__preview">
                            @if($group->latestMessage)
                                <strong>{{ $group->latestMessage->user->name }}:</strong> {{ Str::limit($group->latestMessage->body, 40) }}
                            @else
                                {{ __('messages.no_messages_yet') }}
                            @endif
                        </p>
                    </div>
                </a>
            @empty
                <div class="msg-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="40" height="40"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    <p>{{ __('messages.no_groups') }}</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script src="{{ asset('js/chats-sidebar.js') }}"></script>
