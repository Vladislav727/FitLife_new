@extends('layouts.app')
@section('title', $group->name)

@section('styles')
<style>
.mobile-bottom-nav { display: none !important; }
@media (max-width: 900px) {
    .chat-input__field,
    .chat-search-bar__input,
    .chat-sidebar__input,
    .chat-sidebar__textarea,
    input[type="text"],
    textarea {
        font-size: 16px !important;
        -webkit-text-size-adjust: 100% !important;
    }

    .main-content {
        padding: 0 !important;
        overflow: hidden !important;
        height: 100dvh !important;
        height: 100vh !important;
    }
    .content-wrapper {
        padding: 0 !important;
        height: 100% !important;
        overflow: hidden !important;
    }
    .main-header {
        display: none !important;
    }
    .messenger {
        height: 100dvh !important;
        height: 100vh !important;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        z-index: 100 !important;
        border-radius: 0 !important;
    }
    .messenger__main {
        height: 100% !important;
        display: flex !important;
        flex-direction: column !important;
    }
    .chat-page {
        height: 100% !important;
        display: flex !important;
        flex-direction: column !important;
    }
    .chat-main {
        height: 100% !important;
        display: flex !important;
        flex-direction: column !important;
    }
    .chat-header {
        flex-shrink: 0 !important;
        z-index: 10 !important;
    }
    .chat-messages {
        flex: 1 !important;
        min-height: 0 !important;
        overflow-y: auto !important;
        -webkit-overflow-scrolling: touch !important;
    }
    .chat-input {
        flex-shrink: 0 !important;
        position: relative !important;
        z-index: 10 !important;
        padding: 8px 10px !important;
        padding-bottom: max(8px, env(safe-area-inset-bottom)) !important;
        background: var(--bg-surface, #111114) !important;
        border-top: 1px solid var(--border-default, rgba(255,255,255,0.06)) !important;
    }
    .chat-input__field {
        min-height: 36px !important;
        max-height: 100px !important;
    }
    .chat-input__send,
    .chat-input__media,
    .chat-input__voice,
    .chat-input__poll {
        width: 36px !important;
        height: 36px !important;
        flex-shrink: 0 !important;
    }
}
</style>
@endsection

@section('content')
<div class="messenger">
    @include('chats.partials.sidebar')

    <div class="messenger__main">
<div class="chat-page chat-page--group chat-theme--{{ $chatTheme }}" data-theme="{{ $chatTheme }}">
    <div class="chat-main">
        <div class="chat-header">
            <a href="{{ route('chats.index') }}" class="chat-back">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M19 12H5m0 0l7 7m-7-7l7-7"/></svg>
            </a>
            <div class="chat-user" id="groupInfoToggle" style="cursor:pointer">
                @if($group->avatar)
                    <img src="{{ asset('storage/' . $group->avatar) }}" alt="{{ $group->name }}" class="chat-user__avatar">
                @else
                    <div class="chat-user__group-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                @endif
                <div>
                    <span class="chat-user__name">{{ $group->name }}</span>
                    <span class="chat-user__meta">{{ $members->count() }} {{ __('messages.members') }}</span>
                </div>
            </div>
            <div class="chat-header__actions">
                <button type="button" class="chat-header__btn" id="searchToggle" title="{{ __('messages.search_messages') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </button>
                <button type="button" class="chat-header__btn" id="themeToggle" title="{{ __('messages.chat_theme') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 0 0 20 4 4 0 0 1 0-8 4 4 0 0 0 0-8"/><circle cx="8" cy="10" r="1.5" fill="currentColor"/><circle cx="12" cy="7" r="1.5" fill="currentColor"/><circle cx="16" cy="10" r="1.5" fill="currentColor"/></svg>
                </button>
                <button type="button" class="chat-header__btn" id="pinnedToggle" title="{{ __('messages.pinned_messages') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M12 17v5"/><path d="M9 2h6l-1 7h4l-7 8 1-5H8l1-10z"/></svg>
                    @if($pinnedMessages->count())
                        <span class="chat-header__badge">{{ $pinnedMessages->count() }}</span>
                    @endif
                </button>
                @if($group->owner_id === Auth::id())
                    <form action="{{ route('groups.avatar', $group) }}" method="POST" enctype="multipart/form-data" id="groupAvatarForm" style="display:inline">
                        @csrf
                        <input type="file" name="avatar" id="groupAvatarInput" accept="image/jpeg,image/png,image/gif,image/webp" style="display:none" onchange="document.getElementById('groupAvatarForm').submit()">
                        <button type="button" onclick="document.getElementById('groupAvatarInput').click()" class="chat-header__btn" title="{{ __('messages.change_avatar') }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                        </button>
                    </form>
                @endif
                <a href="{{ route('groups.invite', $group) }}" class="chat-header__btn" title="{{ __('messages.invite') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                </a>
                @if($group->owner_id !== Auth::id())
                    <form action="{{ route('groups.leave', $group) }}" method="POST" onsubmit="return confirm('{{ __('messages.leave_confirm') }}')">
                        @csrf
                        <button type="submit" class="chat-header__btn chat-header__btn--danger" title="{{ __('messages.leave_group') }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        </button>
                    </form>
                @else
                    <form action="{{ route('groups.destroy', $group) }}" method="POST" onsubmit="return confirm('{{ __('messages.delete_group_confirm') }}')">
                        @csrf @method('DELETE')
                        <button type="submit" class="chat-header__btn chat-header__btn--danger" title="{{ __('messages.delete_group') }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="chat-search-bar" id="searchBar" style="display:none">
            <input type="text" id="searchInput" class="chat-search-bar__input" placeholder="{{ __('messages.search_messages') }}" autocomplete="off">
            <button type="button" id="searchClose" class="chat-search-bar__close">&times;</button>
            <div class="chat-search-results" id="searchResults"></div>
        </div>

        <div class="chat-pinned-panel" id="pinnedPanel" style="display:none">
            <div class="chat-pinned-panel__header">
                <span>{{ __('messages.pinned_messages') }}</span>
                <button type="button" id="pinnedClose" class="chat-pinned-panel__close">&times;</button>
            </div>
            <div class="chat-pinned-panel__list">
                @forelse($pinnedMessages as $pm)
                    <div class="chat-pinned-item" data-msg-id="{{ $pm->id }}">
                        <strong>{{ $pm->user->name }}</strong>
                        <p>{{ Str::limit($pm->body, 100) ?: ($pm->media_path ? __('messages.photo') : __('messages.file')) }}</p>
                        <span>{{ $pm->created_at->format('d.m.Y H:i') }}</span>
                    </div>
                @empty
                    <div class="chat-pinned-empty">{{ __('messages.no_results') }}</div>
                @endforelse
            </div>
        </div>

        <div class="chat-messages" id="chatMessages">
            <div id="loadMoreWrap" class="chat-load-more" style="display:none">
                <button id="loadMoreBtn" class="chat-load-more__btn">{{ __('messages.load_more') }}</button>
            </div>
            @foreach($messages as $message)
                @php
                    $isMine = $message->user_id === Auth::id();
                    $isAdmin = in_array($memberRole, ['owner','admin']);
                    $reactions = $message->reactions->groupBy('emoji')->map(fn($g) => [
                        'emoji' => $g->first()->emoji,
                        'count' => $g->count(),
                        'reacted' => $g->contains('user_id', Auth::id()),
                    ]);
                @endphp
                <div class="chat-msg {{ $isMine ? 'chat-msg--mine' : 'chat-msg--theirs' }}{{ $message->pinned_at ? ' chat-msg--pinned' : '' }}" data-msg-id="{{ $message->id }}" data-user-id="{{ $message->user_id }}">
                    @if(!$isMine)
                        <img src="{{ $message->user->avatar ? asset('storage/' . $message->user->avatar) : asset('storage/logo/defaultPhoto.jpg') }}" alt="" class="chat-msg__avatar">
                    @endif
                    <div class="chat-msg__wrap">
                        @if($message->forwarded_from_id)
                            <div class="chat-msg__forwarded">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><polyline points="15 17 20 12 15 7"/><path d="M4 20v-7a4 4 0 0 1 4-4h12"/></svg>
                                {{ __('messages.forwarded_message') }}
                            </div>
                        @endif
                        @if($message->replyTo)
                            <div class="chat-msg__reply-ref" data-reply-id="{{ $message->replyTo->id }}">
                                <span class="chat-msg__reply-author">{{ $message->replyTo->user->name ?? '' }}</span>
                                <span class="chat-msg__reply-text">{{ Str::limit($message->replyTo->body, 60) ?: ($message->replyTo->media_path ? __('messages.photo') : __('messages.file')) }}</span>
                            </div>
                        @endif
                        <div class="chat-msg__bubble">
                            @if(!$isMine)
                                <span class="chat-msg__author">{{ $message->user->name }}</span>
                            @endif
                            @if($message->audio_path)
                                <div class="chat-msg__voice">
                                    <button class="chat-msg__voice-play"><svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><polygon points="5,3 19,12 5,21"/></svg></button>
                                    <div class="chat-msg__voice-wave"></div>
                                    <span class="chat-msg__voice-dur">{{ floor(($message->audio_duration ?? 0) / 60) }}:{{ str_pad(($message->audio_duration ?? 0) % 60, 2, '0', STR_PAD_LEFT) }}</span>
                                    <audio src="{{ asset('storage/' . $message->audio_path) }}" preload="none"></audio>
                                </div>
                            @endif
                            @if($message->poll)
                                <div class="chat-poll" data-poll-id="{{ $message->poll->id }}">
                                    <div class="chat-poll__question">{{ $message->poll->question }}</div>
                                    @foreach($message->poll->options as $opt)
                                        @php
                                            $totalVotes = $message->poll->votes->count();
                                            $optVotes = $message->poll->votes->where('group_poll_option_id', $opt->id)->count();
                                            $pct = $totalVotes > 0 ? round($optVotes / $totalVotes * 100) : 0;
                                            $userVoted = $message->poll->votes->where('user_id', Auth::id())->where('group_poll_option_id', $opt->id)->count() > 0;
                                        @endphp
                                        <button class="chat-poll__option{{ $userVoted ? ' chat-poll__option--voted' : '' }}" data-option-id="{{ $opt->id }}">
                                            <div class="chat-poll__bar" style="width:{{ $pct }}%"></div>
                                            <span class="chat-poll__option-text">{{ $opt->text }}</span>
                                            <span class="chat-poll__pct">{{ $pct }}%</span>
                                        </button>
                                    @endforeach
                                    <div class="chat-poll__meta">{{ $totalVotes }} {{ __('messages.votes') }}{{ $message->poll->is_anonymous ? ' · ' . __('messages.anonymous_poll') : '' }}</div>
                                </div>
                            @endif
                            @if($message->media_path)
                                @if($message->media_type === 'video')
                                    <video src="{{ asset('storage/' . $message->media_path) }}" controls class="chat-msg__media"></video>
                                @else
                                    <img src="{{ asset('storage/' . $message->media_path) }}" class="chat-msg__media" loading="lazy">
                                @endif
                            @endif
                            @if($message->file_path)
                                <a href="{{ asset('storage/' . $message->file_path) }}" class="chat-msg__file" target="_blank" download>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    <div class="chat-msg__file-info">
                                        <span class="chat-msg__file-name">{{ $message->file_name }}</span>
                                        <span class="chat-msg__file-size">{{ number_format($message->file_size / 1024, 0) }} KB</span>
                                    </div>
                                </a>
                            @endif
                            @if($message->body)
                                <p class="chat-msg__text">{{ $message->body }}</p>
                            @endif
                            <span class="chat-msg__time">
                                {{ $message->created_at->format('H:i') }}
                                @if($message->edited_at)
                                    <span class="chat-msg__edited">{{ __('messages.edited') }}</span>
                                @endif
                                @if($message->pinned_at)
                                    <svg class="chat-msg__pin-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="10" height="10"><path d="M12 17v5"/><path d="M9 2h6l-1 7h4l-7 8 1-5H8l1-10z"/></svg>
                                @endif
                            </span>
                        </div>
                        <div class="chat-msg__reactions-display">
                            @foreach($reactions as $r)
                                <button class="chat-msg__reaction-badge{{ $r['reacted'] ? ' reacted' : '' }}" data-emoji="{{ $r['emoji'] }}">{{ $r['emoji'] }} {{ $r['count'] }}</button>
                            @endforeach
                        </div>
                        <div class="chat-msg__hover-actions">
                            <div class="chat-msg__react-bar">
                                @foreach(['👍','❤️','😂','😮','😢','🔥'] as $emoji)
                                    <button class="chat-msg__react-btn" data-emoji="{{ $emoji }}">{{ $emoji }}</button>
                                @endforeach
                            </div>
                            <div class="chat-msg__dropdown-wrap">
                                <button class="chat-msg__arrow-btn">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M6 9l6 6 6-6"/></svg>
                                </button>
                                <div class="chat-msg__dropdown">
                                    <button class="chat-msg__dropdown-item" data-action="reply">{{ __('messages.reply') }}</button>
                                    @if($isMine && $message->body)
                                        <button class="chat-msg__dropdown-item" data-action="edit">{{ __('messages.edit') }}</button>
                                    @endif
                                    <button class="chat-msg__dropdown-item" data-action="forward">{{ __('messages.forward') }}</button>
                                    @if($isAdmin)
                                        <button class="chat-msg__dropdown-item" data-action="pin">{{ $message->pinned_at ? __('messages.unpin') : __('messages.pin') }}</button>
                                    @endif
                                    <button class="chat-msg__dropdown-item" data-action="favorite">{{ __('messages.favorite') }}</button>
                                    @if($isMine || $isAdmin)
                                        <button class="chat-msg__dropdown-item chat-msg__dropdown-item--danger" data-action="delete">{{ __('messages.delete') }}</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div id="typingIndicator" class="chat-typing" style="display:none">
            <span class="chat-typing__dots"><span></span><span></span><span></span></span>
            <span class="chat-typing__text" id="typingText"></span>
        </div>

        <div id="replyPreview" class="chat-reply-preview" style="display:none">
            <div class="chat-reply-preview__content">
                <span class="chat-reply-preview__author"></span>
                <span class="chat-reply-preview__text"></span>
            </div>
            <button type="button" id="replyClear" class="chat-reply-preview__close">&times;</button>
        </div>

        <div id="mediaPreview" class="chat-media-preview" style="display:none">
            <span id="mediaFileName"></span>
            <button type="button" id="mediaClear" class="chat-media-clear">&times;</button>
        </div>

        <form id="chatForm" class="chat-input">
            <input type="file" id="chatMedia" accept="image/jpeg,image/png,image/gif,image/webp,video/mp4,video/webm,video/quicktime" style="display:none">
            <input type="file" id="chatFile" style="display:none">
            <button type="button" id="chatMediaBtn" class="chat-input__media" title="{{ __('messages.attach_media') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
            </button>
            <button type="button" id="chatFileBtn" class="chat-input__media" title="{{ __('messages.file') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </button>
            <input type="text" id="chatBody" placeholder="{{ __('messages.type_message') }}" autocomplete="off" class="chat-input__field">
            <div class="chat-mention-dropdown" id="mentionDropdown" style="display:none"></div>
            <button type="button" id="voiceRecordBtn" class="chat-input__voice" title="{{ __('messages.voice_message') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><line x1="12" y1="19" x2="12" y2="23"/><line x1="8" y1="23" x2="16" y2="23"/></svg>
            </button>
            <div id="voiceRecording" class="chat-input__voice-recording" style="display:none">
                <span class="chat-input__voice-dot"></span>
                <span class="chat-input__voice-timer" id="voiceTimer">0:00</span>
                <button type="button" id="voiceCancel" class="chat-input__voice-cancel">&times;</button>
                <button type="button" id="voiceSend" class="chat-input__voice-send">
                    <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                </button>
            </div>
            <button type="button" id="createPollBtn" class="chat-input__poll" title="{{ __('messages.create_poll') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M4 6h16M4 12h10M4 18h14"/><rect x="18" y="3" width="2" height="6" rx="1" fill="currentColor"/><rect x="14" y="9" width="2" height="6" rx="1" fill="currentColor"/><rect x="18" y="15" width="2" height="6" rx="1" fill="currentColor"/></svg>
            </button>
            <button type="submit" class="chat-input__send">
                <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
            </button>
        </form>
    </div>

    <div class="chat-sidebar" id="chatSidebar" style="display:none">
        <div class="chat-sidebar__header">
            <h3>{{ __('messages.group_info') }}</h3>
            <button type="button" id="sidebarClose" class="chat-sidebar__close">&times;</button>
        </div>
        <div class="chat-sidebar__body">
            @if(in_array($memberRole, ['owner','admin']))
                <div class="chat-sidebar__section">
                    <label class="chat-sidebar__label">{{ __('messages.group_name') }}</label>
                    <div class="chat-sidebar__edit-row">
                        <input type="text" id="groupNameInput" class="chat-sidebar__input" value="{{ $group->name }}" maxlength="100">
                        <button type="button" id="saveGroupName" class="chat-sidebar__save-btn">&#10003;</button>
                    </div>
                </div>
                <div class="chat-sidebar__section">
                    <label class="chat-sidebar__label">{{ __('messages.description') }}</label>
                    <div class="chat-sidebar__edit-row">
                        <textarea id="groupDescInput" class="chat-sidebar__textarea" rows="2" maxlength="500">{{ $group->description }}</textarea>
                        <button type="button" id="saveGroupDesc" class="chat-sidebar__save-btn">&#10003;</button>
                    </div>
                </div>
            @else
                @if($group->description)
                    <div class="chat-sidebar__section">
                        <label class="chat-sidebar__label">{{ __('messages.description') }}</label>
                        <p class="chat-sidebar__text">{{ $group->description }}</p>
                    </div>
                @endif
            @endif
            <div class="chat-sidebar__section">
                <label class="chat-sidebar__label">{{ __('messages.members') }} ({{ $members->count() }})</label>
                <div class="chat-sidebar__members">
                    @foreach($members as $m)
                        <div class="chat-member" data-user-id="{{ $m->id }}">
                            <img src="{{ $m->avatar ? asset('storage/' . $m->avatar) : asset('storage/logo/defaultPhoto.jpg') }}" alt="" class="chat-member__avatar">
                            <div class="chat-member__info">
                                <span class="chat-member__name">{{ $m->name }}</span>
                                <span class="chat-member__role chat-member__role--{{ $m->pivot->role }}">{{ __('messages.' . $m->pivot->role) }}</span>
                            </div>
                            @if($memberRole === 'owner' && $m->id !== Auth::id())
                                <div class="chat-member__actions">
                                    @if($m->pivot->role === 'member')
                                        <button class="chat-member__action-btn" data-action="make-admin" data-uid="{{ $m->id }}" title="{{ __('messages.make_admin') }}">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                        </button>
                                    @elseif($m->pivot->role === 'admin')
                                        <button class="chat-member__action-btn" data-action="remove-admin" data-uid="{{ $m->id }}" title="{{ __('messages.remove_admin') }}">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/><line x1="4" y1="4" x2="20" y2="20"/></svg>
                                        </button>
                                    @endif
                                    <button class="chat-member__action-btn chat-member__action-btn--danger" data-action="remove-member" data-uid="{{ $m->id }}" title="{{ __('messages.remove_member') }}">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    </button>
                                </div>
                            @elseif($memberRole === 'admin' && $m->pivot->role === 'member' && $m->id !== Auth::id())
                                <div class="chat-member__actions">
                                    <button class="chat-member__action-btn chat-member__action-btn--danger" data-action="remove-member" data-uid="{{ $m->id }}" title="{{ __('messages.remove_member') }}">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="chat-forward-modal" id="forwardModal" style="display:none">
    <div class="chat-forward-modal__overlay"></div>
    <div class="chat-forward-modal__content">
        <div class="chat-forward-modal__header">
            <h3>{{ __('messages.forward_to') }}</h3>
            <button type="button" id="forwardClose" class="chat-forward-modal__close">&times;</button>
        </div>
        <div class="chat-forward-modal__list" id="forwardList"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('chatMessages');
    const form = document.getElementById('chatForm');
    const bodyInput = document.getElementById('chatBody');
    const mediaInput = document.getElementById('chatMedia');
    const mediaBtn = document.getElementById('chatMediaBtn');
    const fileInput = document.getElementById('chatFile');
    const fileBtn = document.getElementById('chatFileBtn');
    const mediaPreview = document.getElementById('mediaPreview');
    const mediaFileName = document.getElementById('mediaFileName');
    const mediaClear = document.getElementById('mediaClear');
    const sendUrl = @json(route('groups.send', $group));
    const pollUrl = @json(route('groups.poll', $group));
    const historyUrl = @json(route('groups.history', $group));
    const searchUrl = @json(route('groups.search', $group));
    const typingUrl = @json(route('groups.typing', $group));
    const typingStatusUrl = @json(route('groups.typingStatus', $group));
    const groupUrl = @json(url('groups/' . $group->id));
    const baseUrl = @json(url('groups/' . $group->id . '/messages'));
    const themeUrl = @json(route('groups.setTheme', $group));
    const favoriteBaseUrl = @json(url('groups/' . $group->id . '/messages'));
    const createPollUrl = @json(route('groups.createPoll', $group));
    const votePollUrl = @json(url('groups/' . $group->id . '/polls'));
    const searchMembersUrl = @json(route('groups.searchMembers', $group));
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const authUserId = {{ Auth::id() }};
    const isAdmin = {{ in_array($memberRole, ['owner','admin']) ? 'true' : 'false' }};
    let lastMsgId = {{ $messages->last()?->id ?? 0 }};
    let firstMsgId = {{ $messages->first()?->id ?? 0 }};
    let sending = false;
    let replyToId = null;
    let forwardMsgId = null;
    let typingTimeout = null;
    const forwardTargets = @json($forwardTargets);

    const labels = {
        edited: @json(__('messages.edited')),
        edit: @json(__('messages.edit')),
        delete: @json(__('messages.delete')),
        deleteConfirm: @json(__('messages.delete_message_confirm')),
        reply: @json(__('messages.reply')),
        forward: @json(__('messages.forward')),
        forwarded: @json(__('messages.forwarded_message')),
        pin: @json(__('messages.pin')),
        unpin: @json(__('messages.unpin')),
        photo: @json(__('messages.photo')),
        file: @json(__('messages.file')),
        typing: @json(__('messages.typing')),
        noResults: @json(__('messages.no_results')),
        removeMemberConfirm: @json(__('messages.remove_member_confirm')),
        favorite: @json(__('messages.favorite')),
        favorited: @json(__('messages.favorited')),
        voiceMessage: @json(__('messages.voice_message')),
        votes: @json(__('messages.votes')),
    };
    const emojis = ['👍','❤️','😂','😮','😢','🔥'];

    if (container) container.scrollTop = container.scrollHeight;

    if (firstMsgId > 0) {
        document.getElementById('loadMoreWrap').style.display = 'block';
    }

    // Sidebar toggle
    document.getElementById('groupInfoToggle').addEventListener('click', function() {
        const sb = document.getElementById('chatSidebar');
        sb.style.display = sb.style.display === 'none' ? 'block' : 'none';
    });
    document.getElementById('sidebarClose').addEventListener('click', function() {
        document.getElementById('chatSidebar').style.display = 'none';
    });

    // Group name/desc edit
    const nameBtn = document.getElementById('saveGroupName');
    if (nameBtn) {
        nameBtn.addEventListener('click', function() {
            const val = document.getElementById('groupNameInput').value.trim();
            if (!val) return;
            fetch(groupUrl + '/name', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ name: val }),
            })
            .then(r => r.json())
            .then(data => {
                if (data.name) document.querySelector('.chat-user__name').textContent = data.name;
            });
        });
    }
    const descBtn = document.getElementById('saveGroupDesc');
    if (descBtn) {
        descBtn.addEventListener('click', function() {
            fetch(groupUrl + '/description', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ description: document.getElementById('groupDescInput').value.trim() }),
            });
        });
    }

    // Member management
    document.querySelector('.chat-sidebar__members')?.addEventListener('click', function(e) {
        const btn = e.target.closest('.chat-member__action-btn');
        if (!btn) return;
        const uid = parseInt(btn.dataset.uid);
        const action = btn.dataset.action;

        if (action === 'make-admin') {
            fetch(groupUrl + '/members/' + uid + '/role', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ role: 'admin' }),
            }).then(() => location.reload());
        } else if (action === 'remove-admin') {
            fetch(groupUrl + '/members/' + uid + '/role', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ role: 'member' }),
            }).then(() => location.reload());
        } else if (action === 'remove-member') {
            if (!confirm(labels.removeMemberConfirm)) return;
            fetch(groupUrl + '/members/' + uid, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
            }).then(() => {
                const memberEl = btn.closest('.chat-member');
                if (memberEl) memberEl.remove();
            });
        }
    });

    // Media / File attach
    mediaBtn.addEventListener('click', () => mediaInput.click());
    fileBtn.addEventListener('click', () => fileInput.click());

    mediaInput.addEventListener('change', function() {
        if (this.files.length) {
            mediaPreview.style.display = 'flex';
            mediaFileName.textContent = this.files[0].name;
            fileInput.value = '';
        }
    });
    fileInput.addEventListener('change', function() {
        if (this.files.length) {
            mediaPreview.style.display = 'flex';
            mediaFileName.textContent = this.files[0].name;
            mediaInput.value = '';
        }
    });
    mediaClear.addEventListener('click', function() {
        mediaInput.value = '';
        fileInput.value = '';
        mediaPreview.style.display = 'none';
    });

    // Typing indicator
    bodyInput.addEventListener('input', function() {
        if (typingTimeout) clearTimeout(typingTimeout);
        fetch(typingUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
        }).catch(() => {});
        typingTimeout = setTimeout(() => {}, 3000);
    });

    // Send message
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (sending) return;
        const body = bodyInput.value.trim();
        const hasMedia = mediaInput.files.length > 0;
        const hasFile = fileInput.files.length > 0;
        if (!body && !hasMedia && !hasFile) return;

        sending = true;
        const fd = new FormData();
        fd.append('_token', csrfToken);
        if (body) fd.append('body', body);
        if (hasMedia) fd.append('media', mediaInput.files[0]);
        if (hasFile) fd.append('file', fileInput.files[0]);
        if (replyToId) fd.append('reply_to_id', replyToId);

        fetch(sendUrl, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: fd,
        })
        .then(r => {
            if (!r.ok) throw r;
            return r.json();
        })
        .then(msg => {
            if (msg.id) {
                appendMessage(msg);
                lastMsgId = msg.id;
            }
            bodyInput.value = '';
            mediaInput.value = '';
            fileInput.value = '';
            mediaPreview.style.display = 'none';
            clearReply();
        })
        .catch(err => {
            if (err && typeof err.json === 'function') {
                err.json().then(data => {
                    const msg = data.message || data.error || 'Upload failed';
                    alert(msg);
                }).catch(() => alert('Upload failed. The file may be too large.'));
            } else {
                alert('Upload failed. The file may be too large.');
            }
        })
        .finally(() => { sending = false; });
    });

    // Polling
    setInterval(function() {
        fetch(pollUrl + '?after=' + lastMsgId, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            const msgs = data.messages || data;
            if (Array.isArray(msgs)) {
                msgs.forEach(msg => {
                    if (msg.id > lastMsgId) {
                        appendMessage(msg);
                        lastMsgId = msg.id;
                    }
                });
            }
        })
        .catch(() => {});
    }, 3000);

    // Typing status polling
    setInterval(function() {
        fetch(typingStatusUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            const indicator = document.getElementById('typingIndicator');
            if (data.users && data.users.length > 0) {
                document.getElementById('typingText').textContent = data.users.join(', ') + ' ' + labels.typing;
                indicator.style.display = 'flex';
            } else {
                indicator.style.display = 'none';
            }
        })
        .catch(() => {});
    }, 2000);

    // Load history
    document.getElementById('loadMoreBtn').addEventListener('click', function() {
        const btn = this;
        btn.disabled = true;
        fetch(historyUrl + '?before=' + firstMsgId, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.messages && data.messages.length > 0) {
                const prevHeight = container.scrollHeight;
                const wrap = document.getElementById('loadMoreWrap');
                data.messages.forEach(msg => {
                    const el = buildMessageElement(msg);
                    wrap.after(el);
                });
                firstMsgId = data.messages[0].id;
                container.scrollTop = container.scrollHeight - prevHeight;
            }
            if (!data.messages || data.messages.length < 30) {
                document.getElementById('loadMoreWrap').style.display = 'none';
            }
            btn.disabled = false;
        })
        .catch(() => { btn.disabled = false; });
    });

    // Search
    document.getElementById('searchToggle').addEventListener('click', function() {
        const bar = document.getElementById('searchBar');
        bar.style.display = bar.style.display === 'none' ? 'block' : 'none';
        if (bar.style.display !== 'none') document.getElementById('searchInput').focus();
    });
    document.getElementById('searchClose').addEventListener('click', function() {
        document.getElementById('searchBar').style.display = 'none';
        document.getElementById('searchResults').innerHTML = '';
    });

    let searchDebounce = null;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchDebounce);
        const q = this.value.trim();
        if (q.length < 2) { document.getElementById('searchResults').innerHTML = ''; return; }
        searchDebounce = setTimeout(() => {
            fetch(searchUrl + '?q=' + encodeURIComponent(q), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                const wrap = document.getElementById('searchResults');
                if (!data.results || data.results.length === 0) {
                    wrap.innerHTML = '<div class="chat-search-empty">' + labels.noResults + '</div>';
                    return;
                }
                wrap.innerHTML = data.results.map(r =>
                    '<div class="chat-search-item" data-msg-id="' + r.id + '">' +
                    '<strong>' + esc(r.user_name) + '</strong> <span class="chat-search-item__time">' + esc(r.time) + '</span>' +
                    '<p>' + esc(r.body) + '</p></div>'
                ).join('');
            });
        }, 300);
    });

    document.getElementById('searchResults').addEventListener('click', function(e) {
        const item = e.target.closest('.chat-search-item');
        if (!item) return;
        const msgEl = container.querySelector('[data-msg-id="' + item.dataset.msgId + '"]');
        if (msgEl) {
            msgEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
            msgEl.classList.add('chat-msg--highlight');
            setTimeout(() => msgEl.classList.remove('chat-msg--highlight'), 2000);
        }
    });

    // Pinned panel
    document.getElementById('pinnedToggle').addEventListener('click', function() {
        const panel = document.getElementById('pinnedPanel');
        panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    });
    document.getElementById('pinnedClose').addEventListener('click', function() {
        document.getElementById('pinnedPanel').style.display = 'none';
    });
    document.querySelector('.chat-pinned-panel__list').addEventListener('click', function(e) {
        const item = e.target.closest('.chat-pinned-item');
        if (!item) return;
        const msgEl = container.querySelector('[data-msg-id="' + item.dataset.msgId + '"]');
        if (msgEl) {
            msgEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
            msgEl.classList.add('chat-msg--highlight');
            setTimeout(() => msgEl.classList.remove('chat-msg--highlight'), 2000);
        }
    });

    // Reply
    function setReply(msgId, authorName, text) {
        replyToId = msgId;
        const preview = document.getElementById('replyPreview');
        preview.querySelector('.chat-reply-preview__author').textContent = authorName;
        preview.querySelector('.chat-reply-preview__text').textContent = text || '';
        preview.style.display = 'flex';
        bodyInput.focus();
    }
    function clearReply() {
        replyToId = null;
        document.getElementById('replyPreview').style.display = 'none';
    }
    document.getElementById('replyClear').addEventListener('click', clearReply);

    // Forward modal
    function openForwardModal(msgId) {
        forwardMsgId = msgId;
        const list = document.getElementById('forwardList');
        list.innerHTML = forwardTargets.map(t =>
            '<button class="chat-forward-item" data-type="' + esc(t.type) + '" data-id="' + t.id + '">' +
            '<div class="chat-forward-item__icon">' +
            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>' +
            '</div><span>' + esc(t.name) + '</span></button>'
        ).join('');
        document.getElementById('forwardModal').style.display = 'flex';
    }

    document.getElementById('forwardClose').addEventListener('click', () => {
        document.getElementById('forwardModal').style.display = 'none';
    });
    document.querySelector('.chat-forward-modal__overlay').addEventListener('click', () => {
        document.getElementById('forwardModal').style.display = 'none';
    });

    document.getElementById('forwardList').addEventListener('click', function(e) {
        const item = e.target.closest('.chat-forward-item');
        if (!item || !forwardMsgId) return;
        fetch(baseUrl + '/' + forwardMsgId + '/forward', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ target_type: item.dataset.type, target_id: parseInt(item.dataset.id) }),
        })
        .then(r => r.json())
        .then(() => {
            document.getElementById('forwardModal').style.display = 'none';
        });
    });

    // Delegated event handlers
    container.addEventListener('click', function(e) {
        const voicePlay = e.target.closest('.chat-msg__voice-play');
        if (voicePlay) {
            const wrap = voicePlay.closest('.chat-msg__voice');
            const audio = wrap.querySelector('audio');
            if (audio.paused) {
                document.querySelectorAll('.chat-msg__voice audio').forEach(a => { a.pause(); a.currentTime = 0; a.closest('.chat-msg__voice')?.querySelector('.chat-msg__voice-play')?.classList.remove('playing'); });
                audio.play();
                voicePlay.classList.add('playing');
                audio.onended = () => voicePlay.classList.remove('playing');
            } else {
                audio.pause();
                audio.currentTime = 0;
                voicePlay.classList.remove('playing');
            }
            return;
        }

        const pollOption = e.target.closest('.chat-poll__option');
        if (pollOption) {
            const pollEl = pollOption.closest('.chat-poll');
            const pollId = pollEl.dataset.pollId;
            const optionId = pollOption.dataset.optionId;
            fetch(votePollUrl + '/' + pollId + '/vote', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ option_id: parseInt(optionId) }),
            })
            .then(r => r.json())
            .then(data => {
                if (data.poll) {
                    const options = data.poll.options || [];
                    const totalVotes = data.poll.total_votes || 0;
                    options.forEach(opt => {
                        const btn = pollEl.querySelector('[data-option-id="' + opt.id + '"]');
                        if (!btn) return;
                        const pct = opt.percentage || 0;
                        btn.querySelector('.chat-poll__bar').style.width = pct + '%';
                        btn.querySelector('.chat-poll__pct').textContent = pct + '%';
                        btn.classList.toggle('chat-poll__option--voted', opt.user_voted);
                    });
                    const meta = pollEl.querySelector('.chat-poll__meta');
                    if (meta) meta.textContent = totalVotes + ' ' + labels.votes;
                }
            });
            return;
        }

        const reactBtn = e.target.closest('.chat-msg__react-btn');
        if (reactBtn) {
            const msgEl = reactBtn.closest('.chat-msg');
            toggleReaction(msgEl.dataset.msgId, reactBtn.dataset.emoji, msgEl);
            return;
        }

        const badge = e.target.closest('.chat-msg__reaction-badge');
        if (badge) {
            const msgEl = badge.closest('.chat-msg');
            toggleReaction(msgEl.dataset.msgId, badge.dataset.emoji, msgEl);
            return;
        }

        const replyRef = e.target.closest('.chat-msg__reply-ref');
        if (replyRef) {
            const target = container.querySelector('[data-msg-id="' + replyRef.dataset.replyId + '"]');
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'center' });
                target.classList.add('chat-msg--highlight');
                setTimeout(() => target.classList.remove('chat-msg--highlight'), 2000);
            }
            return;
        }

        const arrowBtn = e.target.closest('.chat-msg__arrow-btn');
        if (arrowBtn) {
            const dropdown = arrowBtn.nextElementSibling;
            document.querySelectorAll('.chat-msg__dropdown.show').forEach(d => { if (d !== dropdown) d.classList.remove('show'); });
            dropdown.classList.toggle('show');
            return;
        }

        const dropdownItem = e.target.closest('.chat-msg__dropdown-item');
        if (dropdownItem) {
            const action = dropdownItem.dataset.action;
            const msgEl = dropdownItem.closest('.chat-msg');
            const msgId = msgEl.dataset.msgId;
            dropdownItem.closest('.chat-msg__dropdown').classList.remove('show');

            if (action === 'edit') startEdit(msgEl, msgId);
            if (action === 'delete') deleteMessage(msgEl, msgId);
            if (action === 'reply') {
                const author = msgEl.querySelector('.chat-msg__author')?.textContent || '';
                const text = msgEl.querySelector('.chat-msg__text')?.textContent || labels.photo;
                setReply(msgId, author, text);
            }
            if (action === 'forward') openForwardModal(msgId);
            if (action === 'pin') pinMessage(msgEl, msgId);
            if (action === 'favorite') toggleFavorite(msgEl, msgId);
            return;
        }

        document.querySelectorAll('.chat-msg__dropdown.show').forEach(d => d.classList.remove('show'));
    });

    function toggleReaction(msgId, emoji, msgEl) {
        fetch(baseUrl + '/' + msgId + '/react', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ emoji: emoji }),
        })
        .then(r => r.json())
        .then(data => {
            const display = msgEl.querySelector('.chat-msg__reactions-display');
            display.innerHTML = '';
            data.reactions.forEach(r => {
                const btn = document.createElement('button');
                btn.className = 'chat-msg__reaction-badge' + (r.reacted ? ' reacted' : '');
                btn.dataset.emoji = r.emoji;
                btn.textContent = r.emoji + ' ' + r.count;
                display.appendChild(btn);
            });
        });
    }

    function pinMessage(msgEl, msgId) {
        fetch(baseUrl + '/' + msgId + '/pin', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
        })
        .then(r => r.json())
        .then(data => {
            if (data.pinned) {
                msgEl.classList.add('chat-msg--pinned');
            } else {
                msgEl.classList.remove('chat-msg--pinned');
            }
        });
    }

    function toggleFavorite(msgEl, msgId) {
        fetch(favoriteBaseUrl + '/' + msgId + '/favorite', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
        })
        .then(r => r.json())
        .then(data => {
            const btn = msgEl.querySelector('[data-action="favorite"]');
            if (btn) btn.textContent = data.favorited ? labels.favorited : labels.favorite;
            msgEl.classList.toggle('chat-msg--favorited', data.favorited);
        });
    }

    function startEdit(msgEl, msgId) {
        const textEl = msgEl.querySelector('.chat-msg__text');
        if (!textEl) return;
        const oldText = textEl.textContent;
        const input = document.createElement('input');
        input.type = 'text';
        input.className = 'chat-msg__edit-input';
        input.value = oldText;
        textEl.replaceWith(input);
        input.focus();

        function save() {
            const newText = input.value.trim();
            if (!newText || newText === oldText) {
                const p = document.createElement('p');
                p.className = 'chat-msg__text';
                p.textContent = oldText;
                input.replaceWith(p);
                return;
            }
            fetch(baseUrl + '/' + msgId, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ body: newText }),
            })
            .then(r => r.json())
            .then(data => {
                const p = document.createElement('p');
                p.className = 'chat-msg__text';
                p.textContent = data.body;
                input.replaceWith(p);
                const timeEl = msgEl.querySelector('.chat-msg__time');
                if (timeEl && !timeEl.querySelector('.chat-msg__edited')) {
                    const span = document.createElement('span');
                    span.className = 'chat-msg__edited';
                    span.textContent = labels.edited;
                    timeEl.appendChild(document.createTextNode(' '));
                    timeEl.appendChild(span);
                }
            });
        }

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') { e.preventDefault(); save(); }
            if (e.key === 'Escape') {
                const p = document.createElement('p');
                p.className = 'chat-msg__text';
                p.textContent = oldText;
                input.replaceWith(p);
            }
        });
        input.addEventListener('blur', save);
    }

    function deleteMessage(msgEl, msgId) {
        if (!confirm(labels.deleteConfirm)) return;
        fetch(baseUrl + '/' + msgId, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) msgEl.remove();
        });
    }

    function buildMessageElement(msg) {
        const div = document.createElement('div');
        div.className = 'chat-msg ' + (msg.is_mine ? 'chat-msg--mine' : 'chat-msg--theirs') + (msg.pinned ? ' chat-msg--pinned' : '');
        div.dataset.msgId = msg.id;
        div.dataset.userId = msg.user_id;
        div.innerHTML = buildMessageHtml(msg);
        return div;
    }

    function appendMessage(msg) {
        const el = buildMessageElement(msg);
        container.appendChild(el);
        container.scrollTop = container.scrollHeight;
    }

    function buildMessageHtml(msg) {
        let html = '';
        if (!msg.is_mine) {
            html += '<img src="' + esc(msg.user_avatar) + '" class="chat-msg__avatar">';
        }
        html += '<div class="chat-msg__wrap">';
        if (msg.forwarded) {
            html += '<div class="chat-msg__forwarded"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><polyline points="15 17 20 12 15 7"/><path d="M4 20v-7a4 4 0 0 1 4-4h12"/></svg> ' + esc(labels.forwarded) + '</div>';
        }
        if (msg.reply_to) {
            html += '<div class="chat-msg__reply-ref" data-reply-id="' + msg.reply_to.id + '">';
            html += '<span class="chat-msg__reply-author">' + esc(msg.reply_to.user_name || '') + '</span>';
            html += '<span class="chat-msg__reply-text">' + esc(msg.reply_to.body || labels.photo) + '</span>';
            html += '</div>';
        }
        html += '<div class="chat-msg__bubble">';
        if (!msg.is_mine) {
            html += '<span class="chat-msg__author">' + esc(msg.user_name) + '</span>';
        }
        if (msg.audio_path) {
            html += '<div class="chat-msg__voice">';
            html += '<button class="chat-msg__voice-play"><svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><polygon points="5,3 19,12 5,21"/></svg></button>';
            html += '<div class="chat-msg__voice-wave"></div>';
            html += '<span class="chat-msg__voice-dur">' + (msg.audio_duration ? Math.floor(msg.audio_duration / 60) + ':' + ('0' + (msg.audio_duration % 60)).slice(-2) : '0:00') + '</span>';
            html += '<audio src="' + esc(msg.audio_path) + '" preload="none"></audio>';
            html += '</div>';
        }
        if (msg.poll) {
            html += '<div class="chat-poll" data-poll-id="' + msg.poll.id + '">';
            html += '<div class="chat-poll__question">' + esc(msg.poll.question) + '</div>';
            if (msg.poll.options) {
                msg.poll.options.forEach(function(opt) {
                    html += '<button class="chat-poll__option' + (opt.user_voted ? ' chat-poll__option--voted' : '') + '" data-option-id="' + opt.id + '">';
                    html += '<div class="chat-poll__bar" style="width:' + (opt.percentage || 0) + '%"></div>';
                    html += '<span class="chat-poll__option-text">' + esc(opt.option_text) + '</span>';
                    html += '<span class="chat-poll__pct">' + (opt.percentage || 0) + '%</span>';
                    html += '</button>';
                });
            }
            html += '<div class="chat-poll__meta">' + (msg.poll.total_votes || 0) + ' ' + labels.votes + '</div>';
            html += '</div>';
        }
        if (msg.media_path) {
            if (msg.media_type === 'video') {
                html += '<video src="' + esc(msg.media_path) + '" controls class="chat-msg__media"></video>';
            } else {
                html += '<img src="' + esc(msg.media_path) + '" class="chat-msg__media" loading="lazy">';
            }
        }
        if (msg.file_path) {
            html += '<a href="' + esc(msg.file_path) + '" class="chat-msg__file" target="_blank" download>';
            html += '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>';
            html += '<div class="chat-msg__file-info"><span class="chat-msg__file-name">' + esc(msg.file_name) + '</span>';
            html += '<span class="chat-msg__file-size">' + Math.round((msg.file_size || 0) / 1024) + ' KB</span></div></a>';
        }
        if (msg.body) {
            html += '<p class="chat-msg__text">' + esc(msg.body) + '</p>';
        }
        html += '<span class="chat-msg__time">' + esc(msg.time);
        if (msg.edited) html += ' <span class="chat-msg__edited">' + esc(labels.edited) + '</span>';
        if (msg.pinned) html += ' <svg class="chat-msg__pin-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="10" height="10"><path d="M12 17v5"/><path d="M9 2h6l-1 7h4l-7 8 1-5H8l1-10z"/></svg>';
        html += '</span></div>';
        html += '<div class="chat-msg__reactions-display">';
        if (msg.reactions) {
            msg.reactions.forEach(r => {
                html += '<button class="chat-msg__reaction-badge' + (r.reacted ? ' reacted' : '') + '" data-emoji="' + esc(r.emoji) + '">' + r.emoji + ' ' + r.count + '</button>';
            });
        }
        html += '</div>';
        html += '<div class="chat-msg__hover-actions">';
        html += '<div class="chat-msg__react-bar">';
        emojis.forEach(e => { html += '<button class="chat-msg__react-btn" data-emoji="' + e + '">' + e + '</button>'; });
        html += '</div>';
        html += '<div class="chat-msg__dropdown-wrap">';
        html += '<button class="chat-msg__arrow-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M6 9l6 6 6-6"/></svg></button>';
        html += '<div class="chat-msg__dropdown">';
        html += '<button class="chat-msg__dropdown-item" data-action="reply">' + esc(labels.reply) + '</button>';
        if (msg.is_mine && msg.body) html += '<button class="chat-msg__dropdown-item" data-action="edit">' + esc(labels.edit) + '</button>';
        html += '<button class="chat-msg__dropdown-item" data-action="forward">' + esc(labels.forward) + '</button>';
        if (isAdmin) html += '<button class="chat-msg__dropdown-item" data-action="pin">' + (msg.pinned ? esc(labels.unpin) : esc(labels.pin)) + '</button>';
        html += '<button class="chat-msg__dropdown-item" data-action="favorite">' + (msg.favorited ? esc(labels.favorited) : esc(labels.favorite)) + '</button>';
        if (msg.is_mine || isAdmin) html += '<button class="chat-msg__dropdown-item chat-msg__dropdown-item--danger" data-action="delete">' + esc(labels.delete) + '</button>';
        html += '</div></div></div></div>';
        return html;
    }

    function esc(s) {
        if (!s) return '';
        const d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    /* ===== Voice Recording (MediaRecorder) ===== */
    const voiceRecordBtn = document.getElementById('voiceRecordBtn');
    const voiceRecording = document.getElementById('voiceRecording');
    const voiceCancel = document.getElementById('voiceCancel');
    const voiceSend = document.getElementById('voiceSend');
    const voiceTimer = document.getElementById('voiceTimer');
    let mediaRecorder = null;
    let audioChunks = [];
    let voiceInterval = null;
    let voiceSeconds = 0;

    if (voiceRecordBtn) {
        voiceRecordBtn.addEventListener('click', async function() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                audioChunks = [];
                voiceSeconds = 0;
                voiceTimer.textContent = '0:00';
                mediaRecorder = new MediaRecorder(stream, { mimeType: MediaRecorder.isTypeSupported('audio/webm') ? 'audio/webm' : 'audio/ogg' });
                mediaRecorder.ondataavailable = e => { if (e.data.size > 0) audioChunks.push(e.data); };
                mediaRecorder.start();
                voiceRecordBtn.style.display = 'none';
                voiceRecording.style.display = 'flex';
                voiceInterval = setInterval(() => {
                    voiceSeconds++;
                    const m = Math.floor(voiceSeconds / 60);
                    const s = voiceSeconds % 60;
                    voiceTimer.textContent = m + ':' + (s < 10 ? '0' : '') + s;
                }, 1000);
            } catch (err) {
                console.error('Mic access denied', err);
            }
        });
    }

    function stopRecording() {
        if (mediaRecorder && mediaRecorder.state !== 'inactive') {
            mediaRecorder.stop();
            mediaRecorder.stream.getTracks().forEach(t => t.stop());
        }
        clearInterval(voiceInterval);
        voiceRecording.style.display = 'none';
        voiceRecordBtn.style.display = '';
    }

    if (voiceCancel) {
        voiceCancel.addEventListener('click', function() {
            audioChunks = [];
            stopRecording();
        });
    }

    if (voiceSend) {
        voiceSend.addEventListener('click', function() {
            if (!mediaRecorder) return;
            mediaRecorder.onstop = function() {
                const ext = mediaRecorder.mimeType.includes('webm') ? 'webm' : 'ogg';
                const blob = new Blob(audioChunks, { type: mediaRecorder.mimeType });
                const fd = new FormData();
                fd.append('audio', blob, 'voice.' + ext);
                fd.append('audio_duration', voiceSeconds);
                if (replyToId) { fd.append('reply_to_id', replyToId); }
                fetch(sendUrl, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                    body: fd,
                })
                .then(r => { if (!r.ok) throw r; return r.json(); })
                .then(data => { appendMessage(data); if (replyToId) { replyToId = null; clearReply(); } })
                .catch(err => console.error('Voice send failed', err));
                audioChunks = [];
            };
            stopRecording();
        });
    }

    /* ===== Theme Picker ===== */
    const themeToggle = document.getElementById('themeToggle');
    const themeModal = document.getElementById('themeModal');
    const themeClose = document.getElementById('themeClose');
    const themePage = document.querySelector('.chat-page');

    if (themeToggle && themeModal) {
        themeToggle.addEventListener('click', () => themeModal.style.display = 'flex');
        themeClose?.addEventListener('click', () => themeModal.style.display = 'none');
        themeModal.querySelector('.chat-theme-modal__overlay')?.addEventListener('click', () => themeModal.style.display = 'none');

        themeModal.addEventListener('click', function(e) {
            const swatch = e.target.closest('.chat-theme-swatch');
            if (!swatch) return;
            const theme = swatch.dataset.theme;
            themeModal.querySelectorAll('.chat-theme-swatch').forEach(s => s.classList.remove('active'));
            swatch.classList.add('active');
            themePage.className = themePage.className.replace(/chat-theme--\S+/g, '') + ' chat-theme--' + theme;
            themePage.dataset.theme = theme;
            themeModal.style.display = 'none';
            fetch(themeUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ theme_key: theme }),
            });
        });
    }

    /* ===== @Mentions Autocomplete ===== */
    const mentionDropdown = document.getElementById('mentionDropdown');
    let mentionDebounce = null;

    bodyInput.addEventListener('input', function() {
        const val = this.value;
        const atPos = val.lastIndexOf('@');
        if (atPos === -1 || atPos < val.lastIndexOf(' ', this.selectionStart - 1)) {
            mentionDropdown.style.display = 'none';
            return;
        }
        const query = val.substring(atPos + 1, this.selectionStart);
        if (query.length < 1) { mentionDropdown.style.display = 'none'; return; }
        clearTimeout(mentionDebounce);
        mentionDebounce = setTimeout(() => {
            fetch(searchMembersUrl + '?q=' + encodeURIComponent(query), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if (!data.members || data.members.length === 0) {
                    mentionDropdown.style.display = 'none';
                    return;
                }
                mentionDropdown.innerHTML = data.members.map(m =>
                    '<div class="chat-mention-item" data-name="' + esc(m.name) + '">' +
                    '<img src="' + esc(m.avatar) + '" class="chat-mention-item__avatar">' +
                    '<span>' + esc(m.name) + '</span></div>'
                ).join('');
                mentionDropdown.style.display = 'block';
            });
        }, 200);
    });

    mentionDropdown.addEventListener('click', function(e) {
        const item = e.target.closest('.chat-mention-item');
        if (!item) return;
        const name = item.dataset.name;
        const val = bodyInput.value;
        const atPos = val.lastIndexOf('@');
        bodyInput.value = val.substring(0, atPos) + '@' + name + ' ';
        mentionDropdown.style.display = 'none';
        bodyInput.focus();
    });

    /* ===== Create Poll ===== */
    const createPollBtn = document.getElementById('createPollBtn');
    const pollModal = document.getElementById('pollModal');

    if (createPollBtn && pollModal) {
        createPollBtn.addEventListener('click', () => pollModal.style.display = 'flex');
        document.getElementById('pollModalClose')?.addEventListener('click', () => pollModal.style.display = 'none');
        pollModal.querySelector('.chat-poll-modal__overlay')?.addEventListener('click', () => pollModal.style.display = 'none');

        document.getElementById('addPollOption').addEventListener('click', function() {
            const wrap = document.getElementById('pollOptions');
            const count = wrap.querySelectorAll('.poll-option-input').length + 1;
            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'chat-poll-modal__input poll-option-input';
            input.placeholder = '{{ __('messages.poll_option') }} ' + count;
            input.maxLength = 255;
            wrap.appendChild(input);
        });

        document.getElementById('pollSubmit').addEventListener('click', function() {
            const question = document.getElementById('pollQuestion').value.trim();
            const options = Array.from(document.querySelectorAll('.poll-option-input')).map(i => i.value.trim()).filter(Boolean);
            if (!question || options.length < 2) return;

            fetch(createPollUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({
                    question: question,
                    options: options,
                    is_multiple: document.getElementById('pollMultiple').checked,
                    is_anonymous: document.getElementById('pollAnonymous').checked,
                }),
            })
            .then(r => { if (!r.ok) throw r; return r.json(); })
            .then(data => {
                if (data.id) appendMessage(data);
                pollModal.style.display = 'none';
                document.getElementById('pollQuestion').value = '';
                const wrap = document.getElementById('pollOptions');
                wrap.innerHTML = '';
                for (let i = 1; i <= 2; i++) {
                    const inp = document.createElement('input');
                    inp.type = 'text';
                    inp.className = 'chat-poll-modal__input poll-option-input';
                    inp.placeholder = '{{ __('messages.poll_option') }} ' + i;
                    inp.maxLength = 255;
                    wrap.appendChild(inp);
                }
                document.getElementById('pollMultiple').checked = false;
                document.getElementById('pollAnonymous').checked = false;
            })
            .catch(err => console.error('Poll creation failed', err));
        });
    }
});
</script>
    </div>
</div>
</div>

<div class="chat-theme-modal" id="themeModal" style="display:none">
    <div class="chat-theme-modal__overlay"></div>
    <div class="chat-theme-modal__content">
        <div class="chat-theme-modal__header">
            <h3>{{ __('messages.chat_theme') }}</h3>
            <button type="button" id="themeClose" class="chat-theme-modal__close">&times;</button>
        </div>
        <div class="chat-theme-modal__grid">
            @foreach(['default','ocean','sunset','forest','neon','aurora','cherry','midnight','lavender','ember','arctic','tropical','vintage','cyberpunk','rose_gold'] as $t)
                <button class="chat-theme-swatch chat-theme-swatch--{{ $t }}{{ $chatTheme === $t ? ' active' : '' }}" data-theme="{{ $t }}">
                    <span class="chat-theme-swatch__name">{{ __('messages.theme_' . $t) }}</span>
                </button>
            @endforeach
        </div>
    </div>
</div>

<div class="chat-poll-modal" id="pollModal" style="display:none">
    <div class="chat-poll-modal__overlay"></div>
    <div class="chat-poll-modal__content">
        <div class="chat-poll-modal__header">
            <h3>{{ __('messages.create_poll') }}</h3>
            <button type="button" id="pollModalClose" class="chat-poll-modal__close">&times;</button>
        </div>
        <div class="chat-poll-modal__body">
            <input type="text" id="pollQuestion" class="chat-poll-modal__input" placeholder="{{ __('messages.poll_question') }}" maxlength="255">
            <div id="pollOptions">
                <input type="text" class="chat-poll-modal__input poll-option-input" placeholder="{{ __('messages.poll_option') }} 1" maxlength="255">
                <input type="text" class="chat-poll-modal__input poll-option-input" placeholder="{{ __('messages.poll_option') }} 2" maxlength="255">
            </div>
            <button type="button" id="addPollOption" class="chat-poll-modal__add-btn">+ {{ __('messages.add_option') }}</button>
            <label class="chat-poll-modal__checkbox"><input type="checkbox" id="pollMultiple"> {{ __('messages.multiple_choice') }}</label>
            <label class="chat-poll-modal__checkbox"><input type="checkbox" id="pollAnonymous"> {{ __('messages.anonymous_poll') }}</label>
        </div>
        <div class="chat-poll-modal__footer">
            <button type="button" id="pollSubmit" class="chat-poll-modal__submit">{{ __('messages.create_poll') }}</button>
        </div>
    </div>
</div>

<div class="media-lightbox" id="mediaLightbox">
    <button class="media-lightbox__close" id="mediaLightboxClose">&times;</button>
    <div class="media-lightbox__content" id="mediaLightboxContent"></div>
</div>
<script>
(function() {
    const lightbox = document.getElementById('mediaLightbox');
    const content = document.getElementById('mediaLightboxContent');
    const closeBtn = document.getElementById('mediaLightboxClose');

    function openLightbox(el) {
        content.innerHTML = '';
        if (el.tagName === 'VIDEO') {
            const video = document.createElement('video');
            video.src = el.src;
            video.controls = true;
            video.autoplay = true;
            video.playsInline = true;
            content.appendChild(video);
        } else {
            const img = document.createElement('img');
            img.src = el.src;
            img.alt = el.alt || '';
            content.appendChild(img);
        }
        lightbox.classList.add('active');
    }

    function closeLightbox() {
        lightbox.classList.remove('active');
        setTimeout(function() {
            const v = content.querySelector('video');
            if (v) v.pause();
            content.innerHTML = '';
        }, 250);
    }

    document.querySelector('.chat-messages')?.addEventListener('click', function(e) {
        const media = e.target.closest('.chat-msg__media');
        if (media) {
            e.preventDefault();
            e.stopPropagation();
            openLightbox(media);
        }
    });

    closeBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        closeLightbox();
    });

    lightbox.addEventListener('click', function(e) {
        if (e.target === lightbox) closeLightbox();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && lightbox.classList.contains('active')) closeLightbox();
    });
})();
</script>

<script>
// Mobile keyboard handler — keeps chat input visible like Telegram
(function() {
    if (window.innerWidth > 900) return;
    var messenger = document.querySelector('.messenger');
    var chatMessages = document.getElementById('chatMessages');
    var chatInput = document.querySelector('.chat-input');
    if (!messenger || !chatInput) return;

    if (window.visualViewport) {
        function onViewportResize() {
            var vv = window.visualViewport;
            var keyboardHeight = window.innerHeight - vv.height;
            if (keyboardHeight > 50) {
                messenger.style.height = vv.height + 'px';
                messenger.style.top = vv.offsetTop + 'px';
            } else {
                messenger.style.height = '';
                messenger.style.top = '';
            }
            if (chatMessages) {
                setTimeout(function() {
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }, 50);
            }
        }
        window.visualViewport.addEventListener('resize', onViewportResize);
        window.visualViewport.addEventListener('scroll', onViewportResize);
    }

    var chatField = document.getElementById('chatBody');
    if (chatField) {
        chatField.addEventListener('focus', function() {
            setTimeout(function() {
                if (chatMessages) chatMessages.scrollTop = chatMessages.scrollHeight;
                chatField.scrollIntoView({ block: 'nearest' });
            }, 300);
        });
    }
})();
</script>
@endsection
