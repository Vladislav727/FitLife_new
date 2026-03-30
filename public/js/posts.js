document.addEventListener("DOMContentLoaded", () => {
    const main = document.querySelector('#main-content') || document.querySelector('.main-content');
    if (!main) return;

    const els = {
        mobileToggle: document.getElementById('mobile-toggle'),
        sidebar: document.getElementById('sidebar'),
        postForm: document.getElementById('post-form'),
        postPhoto: document.getElementById('post-photo'),
        postVideo: document.getElementById('post-video'),
        imagePreview: document.getElementById('image-preview'),
        videoPreview: document.getElementById('video-preview'),
        removeMedia: document.getElementById('remove-media'),
        postCharCount: document.getElementById('post-char-count'),
        postTextarea: document.querySelector('#post-form textarea[name="content"]'),
        alert: document.querySelector('.alert-container')
    };

    const userId = document.querySelector('meta[name="user-id"]')?.content || 'guest';
    const viewedPostsKey = `viewedPosts_${userId}`;
    const viewedPosts = new Set(JSON.parse(localStorage.getItem(viewedPostsKey) || '[]'));
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrfToken) return;

    const t = (key, fallback) => window.toastMessages?.[key] || fallback || key;

    const SVG = {
        heart: '<path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/>',
        dislike: '<path d="M240-840h440v520L400-40l-50-50q-7-7-11.5-19t-4.5-23v-14l44-174H120q-32 0-56-24t-24-56v-80q0-7 2-15t4-15l120-282q9-20 30-34t44-14Zm360 80H240L120-480v80h360l-54 220 174-174v-406Zm0 406v-406 406Zm80 34v-80h120v-360H680v-80h200v520H680Z"/>',
        comment: '<path d="M240-400h480v-80H240v80Zm0-120h480v-80H240v80Zm0-120h480v-80H240v80ZM880-80 720-240H160q-33 0-56.5-23.5T80-320v-480q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v720ZM160-320h594l46 45v-525H160v480Zm0-480 0 480-0 0Z"/>',
        eye: '<path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z"/>',
        send: '<path d="M120-160v-640l760 320-760 320Zm80-120 474-200-474-200v140l240 60-240 60v140Zm0 0v-400 400Z"/>',
        camera: '<path d="M160-160q-33 0-56.5-23.5T80-240v-400q0-33 23.5-56.5T160-720h240l80-80h320q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm73-280h207v-207L233-440Zm-73-40 160-160H160v160Zm0 120v120h640v-480H520v280q0 33-23.5 56.5T440-360H160Zm280-160Z"/>',
        video: '<path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm0-80h640v-480H160v480Zm160-80 240-160-240-160v320Zm-160 80v-480 480Z"/>',
        heartSmall: '<path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>',
        dislikeSmall: '<path d="M15 3H6c-.83 0-1.54.5-1.84 1.22l-3.02 7.05c-.09.23-.14.47-.14.73v2c0 1.1.9 2 2 2h6.31l-.95 4.57-.03.32c0 .41.17.79.44 1.06L9.83 23l6.59-6.59c.36-.36.58-.86.58-1.41V5c0-1.1-.9-2-2-2zm4 0v12h4V3h-4z"/>',
        reply: '<path d="M10 9V5l-7 7 7 7v-4.1c5 0 8.5 1.6 11 5.1-1-5-4-10-11-11z"/>',
        edit: '<path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34a.9959.9959 0 00-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>',
        trash: '<path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>',
        chevron: '<path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z"/>'
    };

    const svg960 = (path, fill = 'currentColor') => `<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="${fill}">${path}</svg>`;
    const svg24 = (path) => `<svg viewBox="0 0 24 24">${path}</svg>`;

    const showAlert = (message, type) => {
        if (window.toast) { (window.toast[type] || window.toast.info)(message); return; }
        if (!els.alert) return;
        els.alert.innerHTML = `<div class="alert alert--${type}">${message}</div>`;
        els.alert.style.display = 'block';
        setTimeout(() => { els.alert.style.display = 'none'; els.alert.innerHTML = ''; }, 5000);
    };

    const apiFetch = (url, body) => fetch(url, {
        method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }, body
    }).then(r => r.json());

    const previewFile = (input, showEl, hideEl, container, removeBtn, clearInput) => {
        input.addEventListener('change', e => {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = ev => {
                showEl.src = ev.target.result; showEl.style.display = 'block';
                hideEl.style.display = 'none'; container.style.display = 'block';
                removeBtn.style.display = 'block'; clearInput.value = '';
            };
            reader.readAsDataURL(file);
        });
    };

    const rebind = (scope, sel, event, handler) => {
        scope.querySelectorAll(sel).forEach(el => { el.removeEventListener(event, handler); el.addEventListener(event, handler); });
    };

    const setupMobileMenu = () => {
        if (!els.mobileToggle || !els.sidebar) return;
        const close = () => { els.sidebar.classList.remove('active'); els.mobileToggle.setAttribute('aria-expanded', 'false'); };
        els.mobileToggle.addEventListener('click', () => {
            const isOpen = els.sidebar.classList.toggle('active');
            els.mobileToggle.setAttribute('aria-expanded', isOpen);
        });
        document.addEventListener('click', e => {
            if (els.sidebar.classList.contains('active') && !els.sidebar.contains(e.target) && !els.mobileToggle.contains(e.target)) close();
        });
        document.addEventListener('keydown', e => { if (e.key === 'Escape' && els.sidebar.classList.contains('active')) close(); });
    };

    const setupEmojiPicker = () => {
        const trigger = document.querySelector('.emoji-trigger'), panel = document.querySelector('.emoji-panel'), ta = els.postTextarea;
        if (!trigger || !panel || !ta) return;
        trigger.addEventListener('click', e => { e.preventDefault(); e.stopPropagation(); panel.style.display = panel.style.display === 'none' ? 'block' : 'none'; });
        document.querySelectorAll('.emoji-btn').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                const emoji = btn.textContent, start = ta.selectionStart, end = ta.selectionEnd;
                ta.value = ta.value.substring(0, start) + emoji + ta.value.substring(end);
                ta.selectionStart = ta.selectionEnd = start + emoji.length;
                ta.focus();
                if (els.postCharCount) els.postCharCount.textContent = `${ta.value.length}/1000`;
            });
        });
        document.addEventListener('click', e => { if (!trigger.contains(e.target) && !panel.contains(e.target)) panel.style.display = 'none'; });
    };

    const setupMediaPreview = () => {
        if (!els.postPhoto || !els.postVideo || !els.imagePreview || !els.videoPreview || !els.removeMedia) return;
        const pc = els.imagePreview.parentElement;
        previewFile(els.postPhoto, els.imagePreview, els.videoPreview, pc, els.removeMedia, els.postVideo);
        previewFile(els.postVideo, els.videoPreview, els.imagePreview, pc, els.removeMedia, els.postPhoto);
        els.removeMedia.addEventListener('click', () => {
            [els.postPhoto, els.postVideo].forEach(i => i.value = '');
            [els.imagePreview, els.videoPreview].forEach(el => { el.src = ''; el.style.display = 'none'; });
            pc.style.display = 'none'; els.removeMedia.style.display = 'none';
        });
    };

    const setupCharCounter = () => {
        if (!els.postTextarea || !els.postCharCount) return;
        els.postTextarea.addEventListener('input', () => { els.postCharCount.textContent = `${els.postTextarea.value.length}/1000`; });
    };

    const setupCommentToggle = (scope = main) => rebind(scope, '.comment-toggle', 'click', toggleComments);
    const toggleComments = e => {
        const comments = document.getElementById(`comments-${e.currentTarget.dataset.postId}`);
        if (comments) comments.style.display = window.getComputedStyle(comments).display === 'none' ? 'block' : 'none';
    };

    const setupShowRepliesButtons = (scope = main) => rebind(scope, '.show-replies-btn', 'click', toggleReplies);
    const toggleReplies = e => {
        const id = e.currentTarget.dataset.commentId, container = document.getElementById(`replies-${id}`);
        const span = e.currentTarget.querySelector('span'), count = parseInt(span.textContent.match(/\d+/)[0]);
        const word = count === 1 ? 'Reply' : 'Replies', showing = container.style.display === 'block';
        container.style.display = showing ? 'none' : 'block';
        span.textContent = `${showing ? 'Show' : 'Hide'} ${count} ${word}`;
        e.currentTarget.querySelector('svg').style.transform = showing ? 'rotate(0deg)' : 'rotate(180deg)';
    };

    const handleReaction = async (btn, type, id, isComment) => {
        try {
            const fd = new FormData(); fd.append('type', type);
            const data = await apiFetch(isComment ? `/comments/${id}/toggle-reaction` : `/posts/${id}/reaction`, fd);
            if (!data.success) throw new Error(data.message || 'Failed');
            const parent = btn.closest(isComment ? '.comment-actions' : '.post-votes') || btn.closest('.post-actions');
            const likeBtn = parent.querySelector('.vote-btn.upvote, .like-btn, .comment-btn.like');
            const dislikeBtn = parent.querySelector('.vote-btn.downvote, .dislike-btn, .comment-btn.dislike');
            if (likeBtn) likeBtn.classList.toggle('active', data.type === 'like');
            if (dislikeBtn) dislikeBtn.classList.toggle('active', data.type === 'dislike');
            const voteCountEl = parent.querySelector('.vote-count');
            const likeCountEl = likeBtn?.querySelector('.count-like, span');
            const dislikeCountEl = dislikeBtn?.querySelector('.count-dislike, span');
            if (voteCountEl) voteCountEl.textContent = (data.likeCount ?? 0) - (data.dislikeCount ?? 0);
            if (likeCountEl) likeCountEl.textContent = data.likeCount ?? 0;
            if (dislikeCountEl) dislikeCountEl.textContent = data.dislikeCount ?? 0;
        } catch {
            showAlert(t('reaction_error', 'Failed to toggle reaction'), 'error');
        }
    };

    const setupReactionButtons = () => {
        main.addEventListener('click', e => {
            const likeBtn = e.target.closest('.vote-btn.upvote, .like-btn, .comment-btn.like');
            const dislikeBtn = e.target.closest('.vote-btn.downvote, .dislike-btn, .comment-btn.dislike');
            const btn = likeBtn || dislikeBtn;
            if (!btn) return;
            e.preventDefault(); e.stopPropagation();
            const isComment = !!btn.dataset.commentId;
            const id = isComment ? btn.dataset.commentId : btn.dataset.postId;
            handleReaction(btn, likeBtn ? 'like' : 'dislike', id, isComment);
        });
    };

    const setupViewCounter = () => {
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                const postId = entry.target.dataset.postId;
                if (entry.isIntersecting && !viewedPosts.has(postId)) {
                    entry.target.dataset.viewTimer = setTimeout(async () => {
                        try {
                            const data = await apiFetch(`/posts/${postId}/views`);
                            document.querySelectorAll(`[data-post-id="${postId}"] .count-view`).forEach(el => el.textContent = data.views);
                            viewedPosts.add(postId);
                            localStorage.setItem(viewedPostsKey, JSON.stringify([...viewedPosts]));
                        } catch {}
                    }, 2000);
                } else {
                    clearTimeout(entry.target.dataset.viewTimer);
                }
            });
        }, { threshold: 0.3 });
        main.querySelectorAll('.post-card').forEach(post => observer.observe(post));
    };

    const setupRealTimePolling = () => {
        setInterval(async () => {
            const postCards = main.querySelectorAll('.post-card[data-post-id]');
            if (postCards.length === 0) return;
            const postIds = Array.from(postCards).map(c => c.dataset.postId);
            try {
                const response = await fetch('/posts/stats/bulk', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ post_ids: postIds })
                });
                if (!response.ok) return;
                const data = await response.json();
                if (!data.posts) return;
                Object.entries(data.posts).forEach(([postId, stats]) => {
                    const card = main.querySelector(`.post-card[data-post-id="${postId}"]`);
                    if (!card) return;
                    card.querySelectorAll('.count-view').forEach(el => el.textContent = stats.views);
                    card.querySelectorAll('.like-btn .count-like').forEach(el => el.textContent = stats.likes);
                    card.querySelectorAll('.dislike-btn .count-dislike').forEach(el => el.textContent = stats.dislikes);
                    card.querySelectorAll('.comment-count').forEach(el => el.textContent = stats.comments);
                    const likeBtn = card.querySelector('.like-btn[data-post-id]');
                    const dislikeBtn = card.querySelector('.dislike-btn[data-post-id]');
                    if (likeBtn) { likeBtn.classList.toggle('active', stats.user_liked); likeBtn.querySelector('svg')?.setAttribute('fill', stats.user_liked ? '#ef4444' : 'currentColor'); }
                    if (dislikeBtn) { dislikeBtn.classList.toggle('active', stats.user_disliked); dislikeBtn.querySelector('svg')?.setAttribute('fill', stats.user_disliked ? '#ffffff' : 'currentColor'); }
                    const contentEl = card.querySelector('.post-content p');
                    if (contentEl && contentEl.dataset.updatedAt !== stats.updated_at) { contentEl.textContent = stats.content; contentEl.dataset.updatedAt = stats.updated_at; }
                });
            } catch {}
        }, 5000);
    };

    const setupSortButtons = () => {
        const sortButtons = document.querySelectorAll('.sort-btn');
        if (sortButtons.length === 0) return;
        const sortMap = { top: ['top', 'топ'], hot: ['hot', 'гор'], newest: ['new', 'нов'] };
        const getSortParam = text => { for (const [key, words] of Object.entries(sortMap)) { if (words.some(w => text.includes(w))) return key; } return 'newest'; };
        sortButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault(); e.stopPropagation();
                sortButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const url = new URL(window.location.href);
                url.searchParams.set('sort', getSortParam(this.textContent.trim().toLowerCase()));
                window.location.href = url.toString();
            });
        });
        const currentSort = new URLSearchParams(window.location.search).get('sort') || 'newest';
        sortButtons.forEach(btn => btn.classList.toggle('active', getSortParam(btn.textContent.trim().toLowerCase()) === currentSort));
    };

    const defaultAvatar = '/storage/logo/defaultPhoto.jpg';
    const mediaHtml = (path, type) => !path ? '' : type === 'image'
        ? `<img src="/storage/${path}" alt="Post image" class="post-img" loading="lazy" />`
        : `<video src="/storage/${path}" controls class="post-video" style="max-height: 200px; border-radius: var(--radius);"></video>`;

    const createPostElement = (post, token) => {
        const el = document.createElement('article');
        el.className = 'post-card'; el.id = `post-${post.id}`; el.dataset.postId = post.id;
        const avatar = post.user.avatar ? '/storage/' + post.user.avatar : defaultAvatar;
        const editPreview = (type, id) => {
            const hasSrc = post.media_path && post.media_type === type;
            const src = hasSrc ? `/storage/${post.media_path}` : '';
            if (type === 'image') return `<img id="edit-image-preview-${id}" ${hasSrc ? `src="${src}"` : 'style="display: none;"'} alt="Image preview" />`;
            return `<video id="edit-video-preview-${id}" ${hasSrc ? `src="${src}"` : 'style="display: none; max-height: 200px; border-radius: var(--radius);"'} controls style="max-height: 200px; border-radius: var(--radius);" alt="Video preview"></video>`;
        };
        el.innerHTML = `
            <div class="post-top">
                <div class="avatar"><img src="${avatar}" alt="${post.user.name}'s Avatar"></div>
                <div class="meta">
                    <a href="${post.user.profile_url}" class="name">${post.user.name}</a>
                    <div class="username">@${post.user.username}</div>
                    <div class="time">${post.created_at_diff || 'just now'}</div>
                </div>
            </div>
            <div class="post-body" id="post-body-${post.id}"><p>${post.content}</p>${mediaHtml(post.media_path, post.media_type)}</div>
            <form id="edit-post-form-${post.id}" action="/posts/${post.id}" method="POST" enctype="multipart/form-data" style="display: none;">
                <input type="hidden" name="_token" value="${token}"><input type="hidden" name="_method" value="PUT">
                <textarea name="content" rows="3" maxlength="1000">${post.content}</textarea>
                <div class="preview-container" style="position: relative;">
                    ${editPreview('image', post.id)}${editPreview('video', post.id)}
                    <button type="button" class="remove-media" data-post-id="${post.id}" style="display: ${post.media_path ? 'block' : 'none'};">×</button>
                </div>
                <label class="file-label" title="Attach photo"><input type="file" name="photo" accept="image/*" class="edit-post-photo">${svg960(SVG.camera, '#3b82f6')}</label>
                <label class="file-label" title="Attach video"><input type="file" name="video" accept="video/mp4,video/mpeg,video/ogg,video/webm" class="edit-post-video">${svg960(SVG.video, '#3b82f6')}</label>
                <button type="submit" class="btn">Save</button>
                <button type="button" class="btn cancel-edit" data-post-id="${post.id}">Cancel</button>
            </form>
            <div class="post-actions">
                <button class="action-btn like-btn ${post.user_liked ? 'active' : ''}" data-post-id="${post.id}">${svg960(SVG.heart, post.user_liked ? '#ef4444' : 'currentColor')}<span class="count-like">${post.like_count || 0}</span></button>
                <button class="action-btn dislike-btn ${post.user_disliked ? 'active' : ''}" data-post-id="${post.id}">${svg960(SVG.dislike, post.user_disliked ? '#ffffffff' : 'currentColor')}<span class="count-dislike">${post.dislike_count || 0}</span></button>
                <button class="action-btn comment-toggle" data-post-id="${post.id}" data-count="${post.comment_count || 0}">${svg960(SVG.comment, '#3b82f6')}<span class="comment-count">${post.comment_count || 0}</span> Comments</button>
                <span class="view-count">${svg960(SVG.eye, '#6b7280')}<span class="count-view">${post.views || 0}</span></span>
                ${post.can_update ? `<button type="button" class="action-btn edit-post-btn" data-post-id="${post.id}">Edit</button>` : ''}
                ${post.can_delete ? `<form action="/posts/${post.id}" method="POST" class="inline-form delete-post-form"><input type="hidden" name="_token" value="${token}"><input type="hidden" name="_method" value="DELETE"><button type="submit" class="action-btn delete-btn">Delete</button></form>` : ''}
            </div>
            <div class="comments" id="comments-${post.id}" style="display:none">
                <form action="/posts/${post.id}/comment" method="POST" class="comment-form" data-post-id="${post.id}">
                    <input type="hidden" name="_token" value="${token}">
                    <textarea name="content" placeholder="Write a comment..." rows="1" maxlength="500"></textarea>
                    <button type="submit" class="btn"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#006400">${SVG.send}</svg></button>
                </form>
            </div>`;
        return el;
    };

    const createCommentElement = (comment, postId, token) => {
        const el = document.createElement('div');
        el.className = `comment-item ${comment.parent_id ? 'is-reply' : ''}`;
        el.id = `comment-${comment.id}`; el.dataset.commentId = comment.id;
        const avatar = comment.user_avatar ? `/storage/${comment.user_avatar}` : defaultAvatar;
        const esc = comment.content.replace(/</g, '&lt;').replace(/>/g, '&gt;');
        const aBtn = (cls, attr, icon, label) => `<button class="comment-btn ${cls}" ${attr}>${svg24(icon)}<span>${label}</span></button>`;
        el.innerHTML = `
            <img src="${avatar}" alt="${comment.user_name}" class="comment-avatar">
            <div class="comment-content">
                <div class="comment-header">
                    <a href="/profile/${comment.user_id}" class="comment-author">${comment.user_name}</a>
                    <span class="comment-username">@${comment.user?.username || ''}</span>
                    ${comment.parent_id ? `<span class="comment-reply-to">→ @${comment.parent_username || ''}</span>` : ''}
                    <span class="comment-time">${comment.created_at_diff || 'just now'}</span>
                </div>
                <div class="comment-text" id="comment-text-${comment.id}"><p>${esc}</p></div>
                <form id="edit-comment-form-${comment.id}" class="comment-edit-form" action="/comments/${comment.id}" method="POST" style="display: none;">
                    <input type="hidden" name="_token" value="${token}"><input type="hidden" name="_method" value="PUT">
                    <textarea name="content" maxlength="500">${esc}</textarea>
                    <div class="comment-edit-btns">
                        <button type="submit" class="btn-save">Save</button>
                        <button type="button" class="btn-cancel cancel-edit-comment" data-comment-id="${comment.id}">Cancel</button>
                    </div>
                </form>
                <div class="comment-actions">
                    ${aBtn(`like ${comment.user_liked ? 'active' : ''}`, `data-comment-id="${comment.id}"`, SVG.heartSmall, comment.like_count || 0)}
                    ${aBtn(`dislike ${comment.user_disliked ? 'active' : ''}`, `data-comment-id="${comment.id}"`, SVG.dislikeSmall, comment.dislike_count || 0)}
                    ${aBtn('reply reply-btn', `data-comment-id="${comment.id}" data-post-id="${postId}"`, SVG.reply, 'Reply')}
                    ${comment.can_update ? aBtn('edit edit-comment-btn', `data-comment-id="${comment.id}"`, SVG.edit, 'Edit') : ''}
                    ${comment.can_delete ? `<form action="/comments/${comment.id}" method="POST" class="inline-delete"><input type="hidden" name="_token" value="${token}"><input type="hidden" name="_method" value="DELETE"><button type="submit" class="comment-btn delete">${svg24(SVG.trash)}<span>Delete</span></button></form>` : ''}
                    ${comment.reply_count > 0 ? aBtn('show-replies show-replies-btn', `data-comment-id="${comment.id}"`, SVG.chevron, `Show ${comment.reply_count} ${comment.reply_count === 1 ? 'Reply' : 'Replies'}`) : ''}
                </div>
                <form action="/posts/${postId}/comment" method="POST" class="comment-reply-form" data-post-id="${postId}" data-parent-id="${comment.id}" style="display: none;">
                    <input type="hidden" name="_token" value="${token}"><input type="hidden" name="parent_id" value="${comment.id}">
                    <textarea name="content" placeholder="Write a reply..." maxlength="500"></textarea>
                    <div class="comment-reply-btns">
                        <button type="submit" class="btn-reply">Reply</button>
                        <button type="button" class="btn-cancel cancel-reply" data-comment-id="${comment.id}">Cancel</button>
                    </div>
                </form>
                <div class="comment-replies" id="replies-${comment.id}" style="display: none;"></div>
            </div>`;
        return el;
    };

    const restoreReplyDrafts = (scope = main) => {
        scope.querySelectorAll('.reply-form, .comment-reply-form').forEach(form => {
            const key = `replyDraft_${form.dataset.postId}_${form.dataset.parentId}`;
            const draft = localStorage.getItem(key);
            if (draft) { form.querySelector('textarea').value = draft; form.style.display = 'block'; }
            form.querySelector('textarea').addEventListener('input', () => localStorage.setItem(key, form.querySelector('textarea').value));
        });
    };

    const setupReplyButtons = (scope = main) => rebind(scope, '.reply-btn', 'click', toggleReplyForm);
    const toggleReplyForm = e => {
        const btn = e.target.closest('.reply-btn'), { commentId, postId } = btn.dataset;
        const form = document.querySelector(`.reply-form[data-parent-id="${commentId}"], .comment-reply-form[data-parent-id="${commentId}"]`);
        if (!form) return;
        const isVisible = form.style.display === 'block';
        document.querySelectorAll(`.reply-form[data-post-id="${postId}"], .comment-reply-form[data-post-id="${postId}"]`).forEach(f => f.style.display = 'none');
        form.style.display = isVisible ? 'none' : 'block';
        if (!isVisible) form.querySelector('textarea').focus();
    };

    const initializeReplies = (scope = main) => {
        scope.querySelectorAll('.comment, .comment-item').forEach(comment => {
            const id = comment.dataset.commentId;
            const container = comment.querySelector(`#replies-${id}`) || comment.querySelector('.comment-replies');
            if (!container || container.children.length === 0) return;
            if (comment.querySelector(`.show-replies-btn[data-comment-id="${id}"]`)) return;
            const count = container.querySelectorAll('.reply, .is-reply').length;
            if (count === 0) return;
            const btn = document.createElement('button');
            btn.className = 'comment-btn show-replies show-replies-btn'; btn.dataset.commentId = id;
            btn.innerHTML = `${svg24(SVG.chevron)}<span>Show ${count} ${count === 1 ? 'Reply' : 'Replies'}</span>`;
            comment.querySelector('.comment-actions').appendChild(btn);
            setupShowRepliesButtons(comment);
        });
    };

    const attachCommentFormListeners = (scope = main) => {
        scope.querySelectorAll('.comment-form, .comment-reply-form').forEach(form => {
            form.removeEventListener('submit', submitCommentForm); form.addEventListener('submit', submitCommentForm);
            const cancel = form.querySelector('.cancel-reply');
            if (cancel) { cancel.removeEventListener('click', cancelReplyForm); cancel.addEventListener('click', cancelReplyForm); }
        });
    };

    const submitCommentForm = async e => {
        e.preventDefault();
        const form = e.target, { postId, parentId } = form.dataset, textarea = form.querySelector('textarea');
        if (!textarea.value.trim()) { showAlert(t('comment_empty', 'Comment cannot be empty'), 'error'); return; }
        form.querySelector('button[type="submit"]').disabled = true;
        try {
            const data = await apiFetch(form.action, new FormData(form));
            if (!data.success) { showAlert(data.message || t('comment_add_error', 'Failed to add comment'), 'error'); return; }
            showAlert(t('comment_added', 'Comment added'), 'success');
            textarea.value = '';
            if (parentId) { localStorage.removeItem(`replyDraft_${postId}_${parentId}`); form.style.display = 'none'; }
            const comments = document.getElementById(`comments-${postId}`);
            const commentsList = comments?.querySelector('.comments-list');
            if (!comments) { showAlert(t('comment_add_error', 'Failed to add comment'), 'error'); return; }
            if (document.getElementById(`comment-${data.comment.id}`)) return;
            const commentEl = createCommentElement(data.comment, postId, csrfToken);
            let parent;
            if (data.comment.parent_id) {
                parent = document.getElementById(`replies-${data.comment.parent_id}`);
                if (!parent) {
                    parent = document.createElement('div');
                    parent.className = 'comment-replies'; parent.id = `replies-${data.comment.parent_id}`; parent.style.display = 'none';
                    const parentComment = document.getElementById(`comment-${data.comment.parent_id}`);
                    if (!parentComment) { showAlert(t('comment_add_error', 'Failed to add reply'), 'error'); return; }
                    (parentComment.querySelector('.comment-content') || parentComment).appendChild(parent);
                }
            } else {
                parent = commentsList || comments;
            }
            parent.appendChild(commentEl);
            [attachCommentEventListeners, attachCommentFormListeners, setupReplyButtons, setupShowRepliesButtons, restoreReplyDrafts].forEach(fn => fn(commentEl));
            if (data.comment.parent_id) {
                const pid = data.comment.parent_id;
                let showBtn = document.querySelector(`.show-replies-btn[data-comment-id="${pid}"]`);
                const repliesEl = document.getElementById(`replies-${pid}`);
                if (showBtn) {
                    const rc = parseInt(showBtn.querySelector('span').textContent.match(/\d+/)[0]) + 1;
                    showBtn.querySelector('span').textContent = `Hide ${rc} ${rc === 1 ? 'Reply' : 'Replies'}`;
                } else {
                    showBtn = document.createElement('button');
                    showBtn.className = 'comment-btn show-replies show-replies-btn'; showBtn.dataset.commentId = pid;
                    showBtn.innerHTML = `${svg24(SVG.chevron)}<span>Hide 1 Reply</span>`;
                    const pc = document.getElementById(`comment-${pid}`);
                    if (pc) { pc.querySelector('.comment-actions').appendChild(showBtn); setupShowRepliesButtons(pc); }
                }
                if (repliesEl) repliesEl.style.display = 'block';
                if (showBtn) showBtn.querySelector('svg').style.transform = 'rotate(180deg)';
            }
            const toggle = document.querySelector(`.comment-toggle[data-post-id="${postId}"]`);
            if (toggle) { toggle.querySelector('.comment-count').textContent = +toggle.dataset.count + 1; toggle.dataset.count = +toggle.dataset.count + 1; }
            comments.style.display = 'block';
        } catch {} finally {
            form.querySelector('button[type="submit"]').disabled = false;
        }
    };

    const cancelReplyForm = e => {
        const form = e.target.closest('.reply-form, .comment-reply-form');
        form.querySelector('textarea').value = '';
        localStorage.removeItem(`replyDraft_${form.dataset.postId}_${form.dataset.parentId}`);
        form.style.display = 'none';
    };

    const attachCommentEventListeners = (scope = main) => {
        scope.querySelectorAll('.comment, .comment-item').forEach(comment => {
            const id = comment.dataset.commentId;
            const editBtn = comment.querySelector('.edit-comment-btn');
            const editForm = comment.querySelector(`#edit-comment-form-${id}`);
            const body = comment.querySelector('.comment-body, .comment-text');
            const cancelEdit = comment.querySelector(`.cancel-edit-comment[data-comment-id="${id}"]`);
            const deleteForm = comment.querySelector('.delete-comment-form, .inline-delete');
            if (editBtn && editForm && body && cancelEdit) {
                rebind(comment, '.edit-comment-btn', 'click', toggleEditForm);
                rebind(comment, `.cancel-edit-comment[data-comment-id="${id}"]`, 'click', cancelEditForm);
                editForm.removeEventListener('submit', submitEditForm); editForm.addEventListener('submit', submitEditForm);
            }
            if (deleteForm) { deleteForm.removeEventListener('submit', submitDeleteForm); deleteForm.addEventListener('submit', submitDeleteForm); }
        });
    };

    const getCommentParts = e => {
        const comment = e.target.closest('.comment, .comment-item'), id = comment.dataset.commentId;
        return { comment, id, body: comment.querySelector('.comment-body, .comment-text'), form: comment.querySelector(`#edit-comment-form-${id}`) };
    };
    const toggleEditForm = e => { const { body, form } = getCommentParts(e); body.style.display = 'none'; form.style.display = 'block'; };
    const cancelEditForm = e => { const { body, form } = getCommentParts(e); body.style.display = 'block'; form.style.display = 'none'; };

    const submitEditForm = async e => {
        e.preventDefault();
        const form = e.target, commentId = form.id.replace('edit-comment-form-', '');
        const body = document.querySelector(`#comment-${commentId}`)?.querySelector('.comment-body, .comment-text');
        try {
            const data = await apiFetch(form.action, new FormData(form));
            if (data.success) { showAlert(t('comment_updated', 'Comment updated'), 'success'); body.innerHTML = `<p>${data.comment.content}</p>`; body.style.display = 'block'; form.style.display = 'none'; }
            else showAlert(data.message || t('comment_update_error', 'Failed to update comment'), 'error');
        } catch { showAlert(t('comment_update_error', 'Failed to update comment'), 'error'); }
    };

    const submitDeleteForm = async e => {
        e.preventDefault();
        const form = e.target, comment = form.closest('.comment, .comment-item');
        const postId = comment.closest('.post-card')?.dataset.postId;
        if (!confirm('Are you sure you want to delete this comment?')) return;
        try {
            const data = await apiFetch(form.action, new FormData(form));
            if (!data.success) { showAlert(data.message || t('comment_delete_error', 'Failed to delete comment'), 'error'); return; }
            showAlert(t('comment_deleted', 'Comment deleted'), 'success');
            comment.remove();
            if (postId) {
                const toggle = document.querySelector(`.comment-toggle[data-post-id="${postId}"]`);
                if (toggle) { toggle.querySelector('.comment-count').textContent = +toggle.dataset.count - 1; toggle.dataset.count = +toggle.dataset.count - 1; }
            }
            if (data.parent_id) {
                const btn = document.querySelector(`.show-replies-btn[data-comment-id="${data.parent_id}"]`);
                if (btn) {
                    const rc = parseInt(btn.querySelector('span').textContent.match(/\d+/)[0]) - 1;
                    if (rc > 0) btn.querySelector('span').textContent = `Show ${rc} ${rc === 1 ? 'Reply' : 'Replies'}`;
                    else { btn.remove(); document.getElementById(`replies-${data.parent_id}`)?.remove(); }
                }
            }
        } catch { showAlert(t('comment_delete_error', 'Failed to delete comment'), 'error'); }
    };

    const attachPostEventListeners = post => {
        const id = post.dataset.postId;
        const editBtn = post.querySelector('.edit-post-btn'), editForm = post.querySelector(`#edit-post-form-${id}`);
        const postBody = post.querySelector(`#post-body-${id}`), cancelEdit = post.querySelector(`.cancel-edit[data-post-id="${id}"]`);
        const editPhoto = post.querySelector('.edit-post-photo'), editVideo = post.querySelector('.edit-post-video');
        const editImgPrev = post.querySelector(`#edit-image-preview-${id}`), editVidPrev = post.querySelector(`#edit-video-preview-${id}`);
        const rmMedia = post.querySelector(`.remove-media[data-post-id="${id}"]`);
        const deleteForm = post.querySelector('.delete-post-form');

        if (editBtn) {
            editBtn.addEventListener('click', () => {
                postBody.style.display = 'none'; editForm.style.display = 'block';
                if (editImgPrev.src && editImgPrev.src !== window.location.origin + defaultAvatar) {
                    editImgPrev.style.display = 'block'; editVidPrev.style.display = 'none'; rmMedia.style.display = 'block';
                } else if (editVidPrev.src) {
                    editVidPrev.style.display = 'block'; editImgPrev.style.display = 'none'; rmMedia.style.display = 'block';
                }
            });
        }
        if (cancelEdit) {
            cancelEdit.addEventListener('click', () => {
                postBody.style.display = 'block'; editForm.style.display = 'none';
                editImgPrev.src = post.querySelector('.post-img')?.src || '';
                editVidPrev.src = post.querySelector('.post-video')?.src || '';
                editImgPrev.style.display = post.querySelector('.post-img') ? 'block' : 'none';
                editVidPrev.style.display = post.querySelector('.post-video') ? 'block' : 'none';
                rmMedia.style.display = (editImgPrev.src || editVidPrev.src) ? 'block' : 'none';
                editPhoto.value = ''; editVideo.value = '';
            });
        }
        if (editPhoto) previewFile(editPhoto, editImgPrev, editVidPrev, editImgPrev.parentElement, rmMedia, editVideo);
        if (editVideo) previewFile(editVideo, editVidPrev, editImgPrev, editVidPrev.parentElement, rmMedia, editPhoto);
        if (rmMedia) {
            rmMedia.addEventListener('click', () => {
                [editPhoto, editVideo].forEach(i => i.value = '');
                [editImgPrev, editVidPrev].forEach(el => { el.src = ''; el.style.display = 'none'; });
                rmMedia.style.display = 'none';
                const inp = document.createElement('input'); inp.type = 'hidden'; inp.name = 'remove_media'; inp.value = '1';
                editForm.appendChild(inp);
            });
        }
        if (editForm) {
            editForm.addEventListener('submit', async e => {
                e.preventDefault();
                try {
                    const data = await apiFetch(editForm.action, new FormData(editForm));
                    if (data.success) {
                        showAlert(t('post_updated', 'Post updated'), 'success');
                        postBody.innerHTML = `<p>${data.post.content}</p>${mediaHtml(data.post.media_path, data.post.media_type)}`;
                        postBody.style.display = 'block'; editForm.style.display = 'none';
                    } else showAlert(data.message || t('post_update_error', 'Failed to update post'), 'error');
                } catch { showAlert(t('post_update_error', 'Failed to update post'), 'error'); }
            });
        }
        if (deleteForm) {
            deleteForm.addEventListener('submit', async e => {
                e.preventDefault();
                if (!confirm('Are you sure you want to delete this post?')) return;
                try {
                    const data = await apiFetch(deleteForm.action, new FormData(deleteForm));
                    if (data.success) { showAlert(t('post_deleted', 'Post deleted'), 'success'); post.remove(); }
                    else showAlert(data.message || t('post_delete_error', 'Failed to delete post'), 'error');
                } catch { showAlert(t('post_delete_error', 'Failed to delete post'), 'error'); }
            });
        }
    };

    const setupShareButtons = (scope = main) => rebind(scope, '.post-action.share', 'click', handleShare);
    const handleShare = async e => {
        const url = `${window.location.origin}/posts#post-${e.currentTarget.dataset.postId}`;
        try { await navigator.clipboard.writeText(url); showAlert(t('link_copied', 'Link copied to clipboard!'), 'success'); }
        catch { const inp = document.createElement('input'); inp.value = url; document.body.appendChild(inp); inp.select(); document.execCommand('copy'); document.body.removeChild(inp); showAlert(t('link_copied', 'Link copied to clipboard!'), 'success'); }
    };

    setupMobileMenu(); setupEmojiPicker(); setupMediaPreview(); setupCharCounter();
    setupSortButtons(); setupCommentToggle(); setupReactionButtons(); setupShareButtons();
    setupViewCounter(); setupRealTimePolling();
    attachCommentFormListeners(); attachCommentEventListeners();
    setupReplyButtons(); setupShowRepliesButtons(); restoreReplyDrafts(); initializeReplies();
    main.querySelectorAll('.post-card').forEach(attachPostEventListeners);
});
