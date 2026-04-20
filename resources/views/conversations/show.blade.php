@extends('layouts.app')
@section('title', $otherUser->name)

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

    /* Мобильный layout чата */
    .main-content {
        padding: 0 !important;
        padding-top: 64px !important;
        overflow: hidden !important;
    }
    .content-wrapper {
        padding: 0 !important;
        height: calc(100vh - 64px) !important;
        height: calc(100dvh - 64px) !important;
        overflow: hidden !important;
    }
    .messenger {
        height: 100% !important;
        display: flex !important;
        border-radius: 0 !important;
    }
    .messenger__main {
        height: 100% !important;
        min-height: 0 !important;
        display: flex !important;
        flex-direction: column !important;
    }
    .chat-page {
        height: 100% !important;
        min-height: 0 !important;
        display: flex !important;
        flex-direction: column !important;
    }
    .chat-header {
        flex-shrink: 0 !important;
    }
    .chat-messages {
        flex: 1 1 0% !important;
        min-height: 0 !important;
        overflow-y: auto !important;
        -webkit-overflow-scrolling: touch !important;
    }
    .chat-input {
        flex-shrink: 0 !important;
        display: flex !important;
        align-items: center !important;
        gap: 6px !important;
        padding: 8px 10px !important;
        padding-bottom: max(8px, env(safe-area-inset-bottom)) !important;
        background: var(--bg-surface, #111114) !important;
        border-top: 1px solid var(--border-default, rgba(255,255,255,0.06)) !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }
    .chat-input__field {
        flex: 1 !important;
        min-width: 0 !important;
        min-height: 36px !important;
        max-height: 100px !important;
    }
    .chat-input__send,
    .chat-input__media,
    .chat-input__voice {
        width: 34px !important;
        height: 34px !important;
        flex-shrink: 0 !important;
        padding: 0 !important;
    }
    .chat-reply-preview,
    .chat-media-preview {
        flex-shrink: 0 !important;
    }
    #typingIndicator {
        flex-shrink: 0 !important;
    }
}
</style>
@endsection

@section('content')
<div class="messenger">
    @include('chats.partials.sidebar')

    <div class="messenger__main">
<div class="chat-page chat-theme--{{ $chatTheme }}" data-theme="{{ $chatTheme }}">
    <div class="chat-header">
        <a href="{{ route('chats.index') }}" class="chat-back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M19 12H5m0 0l7 7m-7-7l7-7"/></svg>
        </a>
        <a href="{{ route('profile.show', $otherUser) }}" class="chat-user">
            <div class="chat-user__avatar-wrap">
                <img src="{{ $otherUser->avatar ? asset('storage/' . $otherUser->avatar) : asset('storage/logo/default-avatar.avif') }}" alt="{{ $otherUser->name }}" class="chat-user__avatar">
                <span class="chat-user__status {{ $otherUser->isOnline() ? 'chat-user__status--online' : '' }}"></span>
            </div>
            <div>
                <span class="chat-user__name">{{ $otherUser->name }}</span>
                <span class="chat-user__meta" id="userStatus">
                    @if($otherUser->isOnline())
                        {{ __('messages.online') }}
                    @elseif($otherUser->last_seen_at)
                        {{ __('messages.last_seen') }} {{ $otherUser->last_seen_at->diffForHumans() }}
                    @endif
                </span>
            </div>
        </a>
        <div class="chat-header__actions">
            <button type="button" class="chat-header__btn" id="searchToggle" title="{{ __('messages.search_messages') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </button>
            <button type="button" class="chat-header__btn" id="pinnedToggle" title="{{ __('messages.pinned_messages') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M12 17v5"/><path d="M9 2h6l-1 7h4l-7 8 1-5H8l1-10z"/></svg>
                @if($pinnedMessages->count())
                    <span class="chat-header__badge">{{ $pinnedMessages->count() }}</span>
                @endif
            </button>
            <button type="button" class="chat-header__btn" id="themeToggle" title="{{ __('messages.chat_theme') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 0 0 20c1.1 0 2-.9 2-2v-.7c0-.5.2-1 .6-1.3.6-.5.4-1.5-.4-1.7a8 8 0 1 1 5.8-7.3"/><circle cx="8" cy="10" r="1.5" fill="currentColor"/><circle cx="12" cy="8" r="1.5" fill="currentColor"/><circle cx="16" cy="10" r="1.5" fill="currentColor"/></svg>
            </button>
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
                $reactions = $message->reactions->groupBy('emoji')->map(fn($g) => [
                    'emoji' => $g->first()->emoji,
                    'count' => $g->count(),
                    'reacted' => $g->contains('user_id', Auth::id()),
                ]);
            @endphp
            <div class="chat-msg {{ $isMine ? 'chat-msg--mine' : 'chat-msg--theirs' }}{{ $message->pinned_at ? ' chat-msg--pinned' : '' }}" data-msg-id="{{ $message->id }}" data-user-id="{{ $message->user_id }}">
                @if(!$isMine)
                    <img src="{{ $message->user->avatar ? asset('storage/' . $message->user->avatar) : asset('storage/logo/default-avatar.avif') }}" alt="" class="chat-msg__avatar">
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
                        @if($message->audio_path)
                            <div class="chat-msg__voice">
                                <button class="chat-msg__voice-play">
                                    <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                </button>
                                <div class="chat-msg__voice-wave"></div>
                                <span class="chat-msg__voice-dur">{{ $message->audio_duration ? gmdate('i:s', $message->audio_duration) : '0:00' }}</span>
                                <audio src="{{ asset('storage/' . $message->audio_path) }}" preload="metadata"></audio>
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
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
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
                            @if($isMine)
                                @if($message->read_at)
                                    <svg class="chat-msg__read-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14"><polyline points="1 13 5 17 11 9"/><polyline points="7 13 11 17 17 9"/></svg>
                                @else
                                    <svg class="chat-msg__read-icon chat-msg__read-icon--unread" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14"><polyline points="4 13 8 17 16 9"/></svg>
                                @endif
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
                                <button class="chat-msg__dropdown-item" data-action="pin">{{ $message->pinned_at ? __('messages.unpin') : __('messages.pin') }}</button>
                                <button class="chat-msg__dropdown-item" data-action="favorite">{{ __('messages.favorite') }}</button>
                                @if($isMine)
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
        <span class="chat-typing__text">{{ $otherUser->name }} {{ __('messages.typing') }}</span>
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
        <button type="button" id="voiceRecordBtn" class="chat-input__voice" title="{{ __('messages.voice_message') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><line x1="12" y1="19" x2="12" y2="23"/><line x1="8" y1="23" x2="16" y2="23"/></svg>
        </button>
        <div id="voiceRecording" class="chat-input__voice-recording" style="display:none">
            <span class="chat-input__voice-dot"></span>
            <span id="voiceTimer" class="chat-input__voice-timer">0:00</span>
            <button type="button" id="voiceCancel" class="chat-input__voice-cancel">&times;</button>
            <button type="button" id="voiceSend" class="chat-input__voice-send">
                <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
            </button>
        </div>
        <button type="submit" class="chat-input__send">
            <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
        </button>
    </form>
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
    const sendUrl = @json(route('conversations.send', $conversation));
    const pollUrl = @json(route('conversations.poll', $conversation));
    const historyUrl = @json(route('conversations.history', $conversation));
    const searchUrl = @json(route('conversations.search', $conversation));
    const typingUrl = @json(route('conversations.typing', $conversation));
    const typingStatusUrl = @json(route('conversations.typingStatus', $conversation));
    const baseUrl = @json(url('conversations/' . $conversation->id . '/messages'));
    const themeUrl = @json(route('conversations.setTheme', $conversation));
    const favoriteBaseUrl = @json(url('conversations/' . $conversation->id . '/messages'));
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const authUserId = {{ Auth::id() }};
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
        online: @json(__('messages.online')),
        lastSeen: @json(__('messages.last_seen')),
        noResults: @json(__('messages.no_results')),
        favorite: @json(__('messages.favorite')),
        favorited: @json(__('messages.favorited')),
        voiceMessage: @json(__('messages.voice_message')),
    };
    const emojis = ['👍','❤️','😂','😮','😢','🔥'];

    if (container) container.scrollTop = container.scrollHeight;

    if (firstMsgId > 0) {
        document.getElementById('loadMoreWrap').style.display = 'block';
    }

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
            if (data.messages) {
                data.messages.forEach(msg => {
                    if (msg.id > lastMsgId) {
                        appendMessage(msg);
                        lastMsgId = msg.id;
                    }
                });
            }
            const statusEl = document.getElementById('userStatus');
            const dotEl = document.querySelector('.chat-user__status');
            if (data.other_user_online) {
                statusEl.textContent = labels.online;
                dotEl.classList.add('chat-user__status--online');
            } else if (data.other_user_last_seen) {
                statusEl.textContent = labels.lastSeen + ' ' + data.other_user_last_seen;
                dotEl.classList.remove('chat-user__status--online');
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
            document.getElementById('typingIndicator').style.display = data.typing ? 'flex' : 'none';
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
                const text = msgEl.querySelector('.chat-msg__text')?.textContent || labels.photo;
                const authorName = msgEl.dataset.userId == authUserId ? labels.reply : (document.querySelector('.chat-user__name')?.textContent || '');
                setReply(msgId, authorName, text);
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
        if (msg.audio_path) {
            html += '<div class="chat-msg__voice">';
            html += '<button class="chat-msg__voice-play"><svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><polygon points="5,3 19,12 5,21"/></svg></button>';
            html += '<div class="chat-msg__voice-wave"></div>';
            html += '<span class="chat-msg__voice-dur">' + (msg.audio_duration ? Math.floor(msg.audio_duration / 60) + ':' + ('0' + (msg.audio_duration % 60)).slice(-2) : '0:00') + '</span>';
            html += '<audio src="' + esc(msg.audio_path) + '" preload="none"></audio>';
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
        if (msg.is_mine) {
            if (msg.read) {
                html += ' <svg class="chat-msg__read-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14"><polyline points="1 13 5 17 11 9"/><polyline points="7 13 11 17 17 9"/></svg>';
            } else {
                html += ' <svg class="chat-msg__read-icon chat-msg__read-icon--unread" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14"><polyline points="4 13 8 17 16 9"/></svg>';
            }
        }
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
        html += '<button class="chat-msg__dropdown-item" data-action="pin">' + (msg.pinned ? esc(labels.unpin) : esc(labels.pin)) + '</button>';
        html += '<button class="chat-msg__dropdown-item" data-action="favorite">' + (msg.favorited ? esc(labels.favorited) : esc(labels.favorite)) + '</button>';
        if (msg.is_mine) html += '<button class="chat-msg__dropdown-item chat-msg__dropdown-item--danger" data-action="delete">' + esc(labels.delete) + '</button>';
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
                .then(data => { appendMessage(data); if (replyToId) { replyToId = null; document.getElementById('chatReplyBar')?.remove(); } })
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
// Mobile keyboard handler
(function() {
    if (window.innerWidth > 900) return;
    var wrapper = document.querySelector('.content-wrapper');
    var messenger = document.querySelector('.messenger');
    var chatMessages = document.getElementById('chatMessages');
    var chatField = document.getElementById('chatBody');
    if (!messenger) return;
    var headerH = 64;

    if (window.visualViewport) {
        function onViewportResize() {
            var vv = window.visualViewport;
            var available = vv.height - headerH;
            if (available < 200) available = vv.height; // keyboard pushed header off
            if (wrapper) wrapper.style.height = available + 'px';
            messenger.style.height = '100%';
            if (chatMessages) {
                setTimeout(function() { chatMessages.scrollTop = chatMessages.scrollHeight; }, 50);
            }
        }
        window.visualViewport.addEventListener('resize', onViewportResize);
        window.visualViewport.addEventListener('scroll', onViewportResize);
    }

    if (chatField) {
        chatField.addEventListener('focus', function() {
            setTimeout(function() {
                if (chatMessages) chatMessages.scrollTop = chatMessages.scrollHeight;
            }, 300);
        });
    }
})();
</script>
@endsection
