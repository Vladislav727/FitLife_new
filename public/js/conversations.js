// Чистый JavaScript для chat conversations (PHP vars перемещены в Blade window.conversationsData)
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
    const conversationsData = window.conversationsData || {};
    const sendUrl = conversationsData.sendUrl;
    const pollUrl = conversationsData.pollUrl;
    const historyUrl = conversationsData.historyUrl;
    const searchUrl = conversationsData.searchUrl;
    const typingUrl = conversationsData.typingUrl;
    const typingStatusUrl = conversationsData.typingStatusUrl;
    const baseUrl = conversationsData.baseUrl;
    const themeUrl = conversationsData.themeUrl;
    const favoriteBaseUrl = conversationsData.favoriteBaseUrl;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const authUserId = conversationsData.authUserId;
    let lastMsgId = conversationsData.lastMsgId || 0;
    let firstMsgId = conversationsData.firstMsgId || 0;
    let sending = false;
    let replyToId = null;
    let forwardMsgId = null;
    let typingTimeout = null;
    const forwardTargets = conversationsData.forwardTargets || [];
    const labels = conversationsData.labels || {};
    const emojis = ['👍','❤️','😂','😮','😢','🔥'];

    // Utility
    function esc(s) {
        const d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    // Polling
    function poll() {
        fetch(pollUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(data => {
            if (data.messages && data.messages.length) {
                data.messages.forEach(m => appendMessage(m));
            }
            if (data.typing) updateTyping(data.typing);
        })
        .catch(err => console.error('Poll error', err));
    }
    setInterval(poll, 1500);

    // Typing
    function sendTyping() {
        if (typingTimeout) clearTimeout(typingTimeout);
        fetch(typingUrl, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' } });
        typingTimeout = setTimeout(() => fetch(typingUrl, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' } }), 1000);
    }
    bodyInput?.addEventListener('input', sendTyping);

    function updateTyping(data) {
        const el = document.getElementById('userStatus');
        if (!el) return;
        if (data.isTyping) {
            el.textContent = labels.typing || 'typing...';
        } else {
            el.textContent = data.lastSeen || '';
        }
    }

    // Send
    form?.addEventListener('submit', async function(e) {
        e.preventDefault();
        if (sending) return;
        const body = bodyInput.value.trim();
        const media = mediaInput.files[0];
        const file = fileInput.files[0];
        if (!body && !media && !file) return;
        sending = true;
        const fd = new FormData();
        fd.append('body', body);
        if (media) fd.append('media', media);
        if (file) fd.append('file', file);
        if (replyToId) fd.append('reply_to_id', replyToId);
        try {
            const res = await fetch(sendUrl, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                body: fd
            });
            const data = await res.json();
            if (data.success && data.message) {
                appendMessage(data.message);
                bodyInput.value = '';
                mediaInput.value = '';
                fileInput.value = '';
                clearMediaPreview();
                if (replyToId) { replyToId = null; document.getElementById('replyPreview')?.remove(); }
            }
        } catch (err) {
            console.error('Send error', err);
        } finally {
            sending = false;
        }
    });

    // Append message
    function appendMessage(m) {
        const isMe = m.user_id === authUserId;
        const msgEl = document.createElement('div');
        msgEl.className = 'chat-msg' + (isMe ? ' chat-msg--mine' : '');
        msgEl.dataset.msgId = m.id;
        msgEl.dataset.userId = m.user_id;
        msgEl.innerHTML = `
            <div class="chat-msg__bubble">
                ${m.reply_to ? `
                    <div class="chat-msg__reply">
                        <div class="chat-msg__reply-author">${esc(m.reply_to.author)}</div>
                        <div class="chat-msg__reply-text">${esc(m.reply_to.text)}</div>
                    </div>
                ` : ''}
                ${m.body ? `<div class="chat-msg__text">${esc(m.body)}</div>` : ''}
                ${m.media ? `
                    <div class="chat-msg__media">
                        ${m.media.type === 'image' ? `<img src="${esc(m.media.url)}" alt="${esc(m.media.name)}" loading="lazy">` : ''}
                        ${m.media.type === 'video' ? `<video src="${esc(m.media.url)}" controls preload="metadata"></video>` : ''}
                        ${m.media.type === 'audio' ? `
                            <div class="chat-msg__voice">
                                <button class="chat-msg__voice-play" type="button" data-src="${esc(m.media.url)}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                </button>
                                <span class="chat-msg__voice-duration">${esc(m.media.duration)}</span>
                                <audio src="${esc(m.media.url)}" preload="none"></audio>
                            </div>
                        ` : ''}
                    </div>
                ` : ''}
                ${m.file ? `
                    <div class="chat-msg__file">
                        <a href="${esc(m.file.url)}" download="${esc(m.file.name)}" class="chat-msg__file-link">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10,9 9,9 8,9"/></svg>
                            <span>${esc(m.file.name)}</span>
                        </a>
                    </div>
                ` : ''}
                <div class="chat-msg__meta">
                    <span class="chat-msg__time">${esc(m.created_at_diff)}</span>
                    ${m.edited ? `<span class="chat-msg__edited">${labels.edited || 'edited'}</span>` : ''}
                </div>
                <div class="chat-msg__actions">
                    <button class="chat-msg__action chat-msg__action--reply" title="${labels.reply || 'Reply'}" data-msg-id="${m.id}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="23 3 23 8 18 8"/></svg>
                    </button>
                    <button class="chat-msg__action chat-msg__action--forward" title="${labels.forward || 'Forward'}" data-msg-id="${m.id}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/><polyline points="15.5 2 20 6.5 15.5 11"/></svg>
                    </button>
                    ${m.can_update ? `
                        <button class="chat-msg__action chat-msg__action--edit" title="${labels.edit || 'Edit'}" data-msg-id="${m.id}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                    ` : ''}
                    ${m.can_delete ? `
                        <button class="chat-msg__action chat-msg__action--delete" title="${labels.delete || 'Delete'}" data-msg-id="${m.id}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><polyline points="3,6 5,6 21,6"/><path d="M19,6v14a2,2,0,0,1-2,2H7a2,2,0,0,1-2-2V6m3,0V4a2,2,0,0,1,2-2h4a2,2,0,0,1,2,2V6"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                        </button>
                    ` : ''}
                    <button class="chat-msg__action chat-msg__action--favorite" title="${labels.favorite || 'Favorite'}" data-msg-id="${m.id}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </button>
                    <button class="chat-msg__action chat-msg__action--pin" title="${labels.pin || 'Pin'}" data-msg-id="${m.id}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M12 17v5"/><path d="M9 2h6l-1 7h4l-7 8 1-5H8l1-10z"/></svg>
                    </button>
                </div>
                <div class="chat-msg__reactions">
                    ${m.reactions ? Object.entries(m.reactions).map(([emoji, count]) => `
                        <button class="chat-msg__reaction-badge" data-emoji="${esc(emoji)}" data-count="${count}">
                            ${esc(emoji)} <span>${count}</span>
                        </button>
                    `).join('') : ''}
                    <button class="chat-msg__react-btn" data-emoji="👍">👍</button>
                    <button class="chat-msg__react-btn" data-emoji="❤️">❤️</button>
                    <button class="chat-msg__react-btn" data-emoji="😂">😂</button>
                    <button class="chat-msg__react-btn" data-emoji="😮">😮</button>
                    <button class="chat-msg__react-btn" data-emoji="😢">😢</button>
                    <button class="chat-msg__react-btn" data-emoji="🔥">🔥</button>
                </div>
            </div>
        `;
        container.appendChild(msgEl);
        container.scrollTop = container.scrollHeight;
    }

    // Media
    mediaBtn?.addEventListener('click', () => mediaInput.click());
    fileBtn?.addEventListener('click', () => fileInput.click());

    mediaInput?.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;
        const url = URL.createObjectURL(file);
        mediaPreview.innerHTML = '';
        if (file.type.startsWith('image/')) {
            const img = document.createElement('img');
            img.src = url;
            img.alt = file.name;
            mediaPreview.appendChild(img);
        } else if (file.type.startsWith('video/')) {
            const video = document.createElement('video');
            video.src = url;
            video.controls = true;
            video.preload = 'metadata';
            mediaPreview.appendChild(video);
        } else if (file.type.startsWith('audio/')) {
            const audio = document.createElement('audio');
            audio.src = url;
            audio.controls = true;
            audio.preload = 'none';
            mediaPreview.appendChild(audio);
        }
        mediaFileName.textContent = file.name;
        mediaPreview.style.display = 'block';
    });

    mediaClear?.addEventListener('click', clearMediaPreview);

    function clearMediaPreview() {
        mediaPreview.innerHTML = '';
        mediaFileName.textContent = '';
        mediaPreview.style.display = 'none';
        mediaInput.value = '';
    }

    // Search
    const searchToggle = document.getElementById('searchToggle');
    const searchPanel = document.getElementById('searchPanel');
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const searchClose = document.getElementById('searchClose');

    searchToggle?.addEventListener('click', () => {
        searchPanel.style.display = searchPanel.style.display === 'none' ? 'block' : 'none';
        if (searchPanel.style.display === 'block') searchInput.focus();
    });
    searchClose?.addEventListener('click', () => searchPanel.style.display = 'none');

    searchInput?.addEventListener('input', function() {
        const q = this.value.trim();
        if (!q) { searchResults.innerHTML = ''; return; }
        fetch(searchUrl + '?q=' + encodeURIComponent(q), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(data => {
            searchResults.innerHTML = data.messages.map(m => `
                <div class="chat-search-item" data-msg-id="${m.id}">
                    <div class="chat-search-item__meta">${esc(m.created_at_diff)} • ${m.user_id === authUserId ? 'You' : esc(m.user_name)}</div>
                    <div class="chat-search-item__text">${esc(m.body || m.media?.name || m.file?.name)}</div>
                </div>
            `).join('');
        });
    });

    searchResults?.addEventListener('click', function(e) {
        const item = e.target.closest('.chat-search-item');
        if (!item) return;
        const msgEl = container.querySelector('[data-msg-id="' + item.dataset.msgId + '"]');
        if (msgEl) {
            msgEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
            msgEl.classList.add('chat-msg--highlight');
            setTimeout(() => msgEl.classList.remove('chat-msg--highlight'), 2000);
        }
    });

    // Pinned
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

        const replyBtn = e.target.closest('.chat-msg__action--reply');
        if (replyBtn) {
            const msgEl = replyBtn.closest('.chat-msg');
            const authorName = msgEl.querySelector('.chat-msg__author')?.textContent || 'User';
            const text = msgEl.querySelector('.chat-msg__text')?.textContent || '';
            setReply(msgEl.dataset.msgId, authorName, text);
            return;
        }

        const forwardBtn = e.target.closest('.chat-msg__action--forward');
        if (forwardBtn) {
            openForwardModal(forwardBtn.dataset.msgId);
            return;
        }

        const editBtn = e.target.closest('.chat-msg__action--edit');
        if (editBtn) {
            const msgEl = editBtn.closest('.chat-msg');
            const textEl = msgEl.querySelector('.chat-msg__text');
            const editForm = document.createElement('form');
            editForm.className = 'chat-msg__edit-form';
            editForm.innerHTML = `
                <textarea name="body" maxlength="1000">${textEl?.textContent || ''}</textarea>
                <div class="chat-msg__edit-actions">
                    <button type="submit">${labels.save || 'Save'}</button>
                    <button type="button" class="chat-msg__edit-cancel">${labels.cancel || 'Cancel'}</button>
                </div>
            `;
            msgEl.querySelector('.chat-msg__bubble').appendChild(editForm);
            textEl.style.display = 'none';
            editForm.querySelector('textarea').focus();
            editForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const body = this.querySelector('textarea').value.trim();
                try {
                    const res = await fetch(baseUrl + '/' + msgEl.dataset.msgId, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                        body: JSON.stringify({ _method: 'PUT', body }),
                    });
                    const data = await res.json();
                    if (data.success) {
                        textEl.textContent = body;
                        textEl.style.display = 'block';
                        editForm.remove();
                    }
                } catch (err) {
                    console.error('Edit error', err);
                }
            });
            editForm.querySelector('.chat-msg__edit-cancel').addEventListener('click', () => {
                textEl.style.display = 'block';
                editForm.remove();
            });
            return;
        }

        const deleteBtn = e.target.closest('.chat-msg__action--delete');
        if (deleteBtn) {
            if (!confirm(labels.deleteConfirm || 'Delete this message?')) return;
            fetch(baseUrl + '/' + deleteBtn.dataset.msgId, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ _method: 'DELETE' }),
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const msgEl = deleteBtn.closest('.chat-msg');
                    msgEl.remove();
                }
            });
            return;
        }

        const favoriteBtn = e.target.closest('.chat-msg__action--favorite');
        if (favoriteBtn) {
            const msgEl = favoriteBtn.closest('.chat-msg');
            const isFav = favoriteBtn.classList.toggle('favorited');
            fetch(favoriteBaseUrl + '/' + msgEl.dataset.msgId + '/favorite', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ favorite: isFav }),
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    favoriteBtn.title = isFav ? (labels.favorited || 'Favorited') : (labels.favorite || 'Favorite');
                }
            });
            return;
        }

        const pinBtn = e.target.closest('.chat-msg__action--pin');
        if (pinBtn) {
            const msgEl = pinBtn.closest('.chat-msg');
            const isPinned = pinBtn.classList.toggle('pinned');
            fetch(baseUrl + '/' + msgEl.dataset.msgId + '/pin', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ pin: isPinned }),
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    pinBtn.title = isPinned ? (labels.unpin || 'Unpin') : (labels.pin || 'Pin');
                }
            });
            return;
        }
    });

    // Reactions
    function toggleReaction(msgId, emoji, msgEl) {
        fetch(baseUrl + '/' + msgId + '/react', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ emoji }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.reactions) {
                const reactionsEl = msgEl.querySelector('.chat-msg__reactions');
                reactionsEl.innerHTML = Object.entries(data.reactions).map(([e, count]) => `
                    <button class="chat-msg__reaction-badge" data-emoji="${esc(e)}" data-count="${count}">
                        ${esc(e)} <span>${count}</span>
                    </button>
                `).join('');
                reactionsEl.innerHTML += emojis.map(e => `<button class="chat-msg__react-btn" data-emoji="${esc(e)}">${esc(e)}</button>`).join('');
            }
        });
    }

    // Voice recording
    let mediaRecorder = null;
    let audioChunks = [];
    let voiceSeconds = 0;
    const voiceBtn = document.getElementById('voiceBtn');
    const voiceModal = document.getElementById('voiceModal');
    const voiceStart = document.getElementById('voiceStart');
    const voiceStop = document.getElementById('voiceStop');
    const voiceCancel = document.getElementById('voiceCancel');
    const voiceSend = document.getElementById('voiceSend');
    const voiceTimer = document.getElementById('voiceTimer');

    if (voiceBtn && voiceModal) {
        voiceBtn.addEventListener('click', () => {
            voiceModal.style.display = 'flex';
            voiceSeconds = 0;
            voiceTimer.textContent = '00:00';
        });
        voiceModal.querySelector('.chat-voice-modal__overlay').addEventListener('click', () => voiceModal.style.display = 'none');
        document.getElementById('voiceClose').addEventListener('click', () => voiceModal.style.display = 'none');
    }

    if (voiceStart && voiceStop && voiceCancel && voiceSend) {
        voiceStart.addEventListener('click', async function() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                mediaRecorder = new MediaRecorder(stream);
                audioChunks = [];
                mediaRecorder.ondataavailable = e => audioChunks.push(e.data);
                mediaRecorder.start();
                voiceStart.style.display = 'none';
                voiceStop.style.display = 'inline-flex';
                const timer = setInterval(() => {
                    voiceSeconds++;
                    const m = Math.floor(voiceSeconds / 60).toString().padStart(2, '0');
                    const s = (voiceSeconds % 60).toString().padStart(2, '0');
                    voiceTimer.textContent = m + ':' + s;
                }, 1000);
                voiceStop.onclick = function() {
                    clearInterval(timer);
                    mediaRecorder.stop();
                    voiceStop.style.display = 'none';
                    voiceSend.style.display = 'inline-flex';
                };
            } catch (err) {
                console.error('Mic error', err);
            }
        });

        voiceCancel.addEventListener('click', function() {
            audioChunks = [];
            stopRecording();
        });

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

    function stopRecording() {
        if (mediaRecorder && mediaRecorder.state !== 'inactive') {
            mediaRecorder.stop();
        }
        voiceStart.style.display = 'inline-flex';
        voiceStop.style.display = 'none';
        voiceSend.style.display = 'none';
        voiceTimer.textContent = '00:00';
    }

    // Theme Picker
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

// Lightbox
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

// Mobile keyboard
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
            if (available < 200) available = vv.height;
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