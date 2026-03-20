@extends('layouts.app')
@section('title', $group->name)

@section('content')
<div class="chat-page">
    <div class="chat-header">
        <a href="{{ route('chats.index') }}" class="chat-back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M19 12H5m0 0l7 7m-7-7l7-7"/></svg>
        </a>
        <div class="chat-user">
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

    <div class="chat-messages" id="chatMessages">
        @foreach($messages as $message)
            @php
                $isMine = $message->user_id === Auth::id();
                $isOwner = $group->owner_id === Auth::id();
                $reactions = $message->reactions->groupBy('emoji')->map(fn($g) => [
                    'emoji' => $g->first()->emoji,
                    'count' => $g->count(),
                    'reacted' => $g->contains('user_id', Auth::id()),
                ]);
            @endphp
            <div class="chat-msg {{ $isMine ? 'chat-msg--mine' : 'chat-msg--theirs' }}" data-msg-id="{{ $message->id }}" data-user-id="{{ $message->user_id }}">
                @if(!$isMine)
                    <img src="{{ $message->user->avatar ? asset('storage/' . $message->user->avatar) : asset('storage/logo/defaultPhoto.jpg') }}" alt="" class="chat-msg__avatar">
                @endif
                <div class="chat-msg__wrap">
                    <div class="chat-msg__bubble">
                        @if(!$isMine)
                            <span class="chat-msg__author">{{ $message->user->name }}</span>
                        @endif
                        @if($message->media_path)
                            @if($message->media_type === 'video')
                                <video src="{{ asset('storage/' . $message->media_path) }}" controls class="chat-msg__media"></video>
                            @else
                                <img src="{{ asset('storage/' . $message->media_path) }}" class="chat-msg__media" loading="lazy">
                            @endif
                        @endif
                        @if($message->body)
                            <p class="chat-msg__text">{{ $message->body }}</p>
                        @endif
                        <span class="chat-msg__time">
                            {{ $message->created_at->format('H:i') }}
                            @if($message->edited_at)
                                <span class="chat-msg__edited">{{ __('messages.edited') }}</span>
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
                        @if($isMine || $isOwner)
                            <div class="chat-msg__dropdown-wrap">
                                <button class="chat-msg__arrow-btn">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M6 9l6 6 6-6"/></svg>
                                </button>
                                <div class="chat-msg__dropdown">
                                    @if($isMine && $message->body)
                                        <button class="chat-msg__dropdown-item" data-action="edit">{{ __('messages.edit') }}</button>
                                    @endif
                                    <button class="chat-msg__dropdown-item chat-msg__dropdown-item--danger" data-action="delete">{{ __('messages.delete') }}</button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div id="mediaPreview" class="chat-media-preview" style="display:none">
        <span id="mediaFileName"></span>
        <button type="button" id="mediaClear" class="chat-media-clear">&times;</button>
    </div>

    <form id="chatForm" class="chat-input">
        <input type="file" id="chatMedia" accept="image/jpeg,image/png,image/gif,image/webp,video/mp4,video/webm,video/quicktime" style="display:none">
        <button type="button" id="chatMediaBtn" class="chat-input__media" title="{{ __('messages.attach_media') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
        </button>
        <input type="text" id="chatBody" placeholder="{{ __('messages.type_message') }}" autocomplete="off" class="chat-input__field">
        <button type="submit" class="chat-input__send">
            <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
        </button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('chatMessages');
    const form = document.getElementById('chatForm');
    const bodyInput = document.getElementById('chatBody');
    const mediaInput = document.getElementById('chatMedia');
    const mediaBtn = document.getElementById('chatMediaBtn');
    const mediaPreview = document.getElementById('mediaPreview');
    const mediaFileName = document.getElementById('mediaFileName');
    const mediaClear = document.getElementById('mediaClear');
    const sendUrl = @json(route('groups.send', $group));
    const pollUrl = @json(route('groups.poll', $group));
    const baseUrl = @json(url('groups/' . $group->id . '/messages'));
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const authUserId = {{ Auth::id() }};
    const isOwner = {{ $group->owner_id === Auth::id() ? 'true' : 'false' }};
    let lastMsgId = {{ $messages->last()?->id ?? 0 }};
    let sending = false;
    const editedLabel = @json(__('messages.edited'));
    const editLabel = @json(__('messages.edit'));
    const deleteLabel = @json(__('messages.delete'));
    const deleteConfirm = @json(__('messages.delete_message_confirm'));
    const emojis = ['👍','❤️','😂','😮','😢','🔥'];

    if (container) container.scrollTop = container.scrollHeight;

    mediaBtn.addEventListener('click', () => mediaInput.click());
    mediaInput.addEventListener('change', function() {
        if (this.files.length) {
            mediaPreview.style.display = 'flex';
            mediaFileName.textContent = this.files[0].name;
        }
    });
    mediaClear.addEventListener('click', function() {
        mediaInput.value = '';
        mediaPreview.style.display = 'none';
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (sending) return;
        const body = bodyInput.value.trim();
        const hasMedia = mediaInput.files.length > 0;
        if (!body && !hasMedia) return;

        sending = true;
        const fd = new FormData();
        fd.append('_token', csrfToken);
        if (body) fd.append('body', body);
        if (hasMedia) fd.append('media', mediaInput.files[0]);

        fetch(sendUrl, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: fd,
        })
        .then(r => r.json())
        .then(msg => {
            if (msg.id) {
                appendMessage(msg);
                lastMsgId = msg.id;
            }
            bodyInput.value = '';
            mediaInput.value = '';
            mediaPreview.style.display = 'none';
        })
        .finally(() => { sending = false; });
    });

    setInterval(function() {
        fetch(pollUrl + '?after=' + lastMsgId, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(messages => {
            messages.forEach(msg => {
                if (msg.id > lastMsgId) {
                    appendMessage(msg);
                    lastMsgId = msg.id;
                }
            });
        })
        .catch(() => {});
    }, 3000);

    function appendMessage(msg) {
        const div = document.createElement('div');
        div.className = 'chat-msg ' + (msg.is_mine ? 'chat-msg--mine' : 'chat-msg--theirs');
        div.dataset.msgId = msg.id;
        div.dataset.userId = msg.user_id;
        let html = '';
        if (!msg.is_mine) {
            html += '<img src="' + esc(msg.user_avatar) + '" class="chat-msg__avatar">';
        }
        html += '<div class="chat-msg__wrap">';
        html += '<div class="chat-msg__bubble">';
        if (!msg.is_mine) {
            html += '<span class="chat-msg__author">' + esc(msg.user_name) + '</span>';
        }
        if (msg.media_path) {
            if (msg.media_type === 'video') {
                html += '<video src="' + esc(msg.media_path) + '" controls class="chat-msg__media"></video>';
            } else {
                html += '<img src="' + esc(msg.media_path) + '" class="chat-msg__media" loading="lazy">';
            }
        }
        if (msg.body) {
            html += '<p class="chat-msg__text">' + esc(msg.body) + '</p>';
        }
        html += '<span class="chat-msg__time">' + esc(msg.time);
        if (msg.edited) html += ' <span class="chat-msg__edited">' + esc(editedLabel) + '</span>';
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
        if (msg.is_mine || isOwner) {
            html += '<div class="chat-msg__dropdown-wrap">';
            html += '<button class="chat-msg__arrow-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M6 9l6 6 6-6"/></svg></button>';
            html += '<div class="chat-msg__dropdown">';
            if (msg.is_mine && msg.body) html += '<button class="chat-msg__dropdown-item" data-action="edit">' + esc(editLabel) + '</button>';
            html += '<button class="chat-msg__dropdown-item chat-msg__dropdown-item--danger" data-action="delete">' + esc(deleteLabel) + '</button>';
            html += '</div></div>';
        }
        html += '</div></div>';
        div.innerHTML = html;
        container.appendChild(div);
        container.scrollTop = container.scrollHeight;
    }

    // Delegated event handlers
    container.addEventListener('click', function(e) {
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
            dropdownItem.closest('.chat-msg__dropdown').classList.remove('show');
            if (action === 'edit') startEdit(msgEl, msgEl.dataset.msgId);
            if (action === 'delete') deleteMessage(msgEl, msgEl.dataset.msgId);
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
                    span.textContent = editedLabel;
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
        if (!confirm(deleteConfirm)) return;
        fetch(baseUrl + '/' + msgId, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) msgEl.remove();
        });
    }

    function esc(s) {
        if (!s) return '';
        const d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }
});
</script>
@endsection
