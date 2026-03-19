document.addEventListener("DOMContentLoaded", () => {
    const main = document.querySelector('#main-content') || document.querySelector('.main-content');
    if (!main) return;

    // DOM elements
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

    if (!csrfToken) {
        console.error('CSRF token not found');
        return;
    }

    // Helper to get translated message
    const t = (key, fallback) => window.toastMessages?.[key] || fallback || key;

    const showAlert = (message, type) => {
        // Use toast notifications if available
        if (window.toast) {
            if (type === 'success') window.toast.success(message);
            else if (type === 'error') window.toast.error(message);
            else if (type === 'warning') window.toast.warning(message);
            else window.toast.info(message);
            return;
        }
        // Fallback to old alert system
        if (!els.alert) return;
        els.alert.innerHTML = `<div class="alert alert--${type}">${message}</div>`;
        els.alert.style.display = 'block';
        setTimeout(() => {
            els.alert.style.display = 'none';
            els.alert.innerHTML = '';
        }, 5000);
    };

    const setupMobileMenu = () => {
        if (!els.mobileToggle || !els.sidebar) return;
        els.mobileToggle.addEventListener('click', () => {
            const isOpen = els.sidebar.classList.toggle('active');
            els.mobileToggle.setAttribute('aria-expanded', isOpen);
        });

        document.addEventListener('click', e => {
            if (els.sidebar.classList.contains('active') && 
                !els.sidebar.contains(e.target) && 
                !els.mobileToggle.contains(e.target)) {
                els.sidebar.classList.remove('active');
                els.mobileToggle.setAttribute('aria-expanded', 'false');
            }
        });

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && els.sidebar.classList.contains('active')) {
                els.sidebar.classList.remove('active');
                els.mobileToggle.setAttribute('aria-expanded', 'false');
            }
        });
    };

    // Emoji Picker
    const setupEmojiPicker = () => {
        const emojiTrigger = document.querySelector('.emoji-trigger');
        const emojiPanel = document.querySelector('.emoji-panel');
        const textarea = els.postTextarea;
        
        if (!emojiTrigger || !emojiPanel || !textarea) return;
        
        emojiTrigger.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            emojiPanel.style.display = emojiPanel.style.display === 'none' ? 'block' : 'none';
        });
        
        document.querySelectorAll('.emoji-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const emoji = btn.textContent;
                const start = textarea.selectionStart;
                const end = textarea.selectionEnd;
                textarea.value = textarea.value.substring(0, start) + emoji + textarea.value.substring(end);
                textarea.selectionStart = textarea.selectionEnd = start + emoji.length;
                textarea.focus();
                // Update char counter
                if (els.postCharCount) {
                    els.postCharCount.textContent = `${textarea.value.length}/1000`;
                }
            });
        });
        
        document.addEventListener('click', (e) => {
            if (!emojiTrigger.contains(e.target) && !emojiPanel.contains(e.target)) {
                emojiPanel.style.display = 'none';
            }
        });
    };

    const setupMediaPreview = () => {
        if (!els.postPhoto || !els.postVideo || !els.imagePreview || !els.videoPreview || !els.removeMedia) return;
        const previewContainer = els.imagePreview.parentElement;

        els.postPhoto.addEventListener('change', e => {
            const file = e.target.files[0];
            console.log('Photo input changed:', file);
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    console.log('FileReader result:', e.target.result);
                    els.imagePreview.src = e.target.result;
                    els.imagePreview.style.display = 'block';
                    els.videoPreview.style.display = 'none';
                    previewContainer.style.display = 'block';
                    els.removeMedia.style.display = 'block';
                    els.postVideo.value = '';
                };
                reader.onerror = () => console.error('FileReader error');
                reader.readAsDataURL(file);
            }
        });

        els.postVideo.addEventListener('change', e => {
            const file = e.target.files[0];
            console.log('Video input changed:', file);
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    console.log('FileReader result:', e.target.result);
                    els.videoPreview.src = e.target.result;
                    els.videoPreview.style.display = 'block';
                    els.imagePreview.style.display = 'none';
                    previewContainer.style.display = 'block';
                    els.removeMedia.style.display = 'block';
                    els.postPhoto.value = '';
                };
                reader.onerror = () => console.error('FileReader error');
                reader.readAsDataURL(file);
            }
        });

        els.removeMedia.addEventListener('click', () => {
            els.postPhoto.value = '';
            els.postVideo.value = '';
            els.imagePreview.src = '';
            els.videoPreview.src = '';
            els.imagePreview.style.display = 'none';
            els.videoPreview.style.display = 'none';
            previewContainer.style.display = 'none';
            els.removeMedia.style.display = 'none';
        });
    };

    const setupCharCounter = () => {
        if (!els.postTextarea || !els.postCharCount) return;
        els.postTextarea.addEventListener('input', () => {
            els.postCharCount.textContent = `${els.postTextarea.value.length}/1000`;
        });
    };

    const setupCommentToggle = (scope = main) => {
        scope.querySelectorAll('.comment-toggle').forEach(btn => {
            btn.removeEventListener('click', toggleComments);
            btn.addEventListener('click', toggleComments);
        });
    };

    const toggleComments = (e) => {
        const btn = e.currentTarget;
        const comments = document.getElementById(`comments-${btn.dataset.postId}`);
        if (!comments) return;
        // Get computed style to handle initial state
        const isHidden = window.getComputedStyle(comments).display === 'none';
        comments.style.display = isHidden ? 'block' : 'none';
    };

    const setupShowRepliesButtons = (scope = main) => {
        scope.querySelectorAll('.show-replies-btn').forEach(btn => {
            btn.removeEventListener('click', toggleReplies);
            btn.addEventListener('click', toggleReplies);
        });
    };

    const toggleReplies = (e) => {
        const commentId = e.currentTarget.dataset.commentId;
        const repliesContainer = document.getElementById(`replies-${commentId}`);
        const btnText = e.currentTarget.querySelector('span');
        const replyCount = parseInt(btnText.textContent.match(/\d+/)[0]);

        if (repliesContainer.style.display === 'block') {
            repliesContainer.style.display = 'none';
            btnText.textContent = `Show ${replyCount} ${replyCount === 1 ? 'Reply' : 'Replies'}`;
            e.currentTarget.querySelector('svg').style.transform = 'rotate(0deg)';
        } else {
            repliesContainer.style.display = 'block';
            btnText.textContent = `Hide ${replyCount} ${replyCount === 1 ? 'Reply' : 'Replies'}`;
            e.currentTarget.querySelector('svg').style.transform = 'rotate(180deg)';
        }
    };

    const handleReaction = async (btn, type, id, isComment) => {
        try {
            const formData = new FormData();
            formData.append('type', type);
            
            const response = await fetch(isComment ? `/comments/${id}/toggle-reaction` : `/posts/${id}/reaction`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            if (!data.success) {
                throw new Error(data.message || 'Failed to toggle reaction');
            }
            
            const parent = btn.closest(isComment ? '.comment-actions' : '.post-votes') || btn.closest('.post-actions');
            // Support vote-btn, like-btn, and comment-btn selectors
            const likeBtn = parent.querySelector('.vote-btn.upvote') || parent.querySelector('.like-btn') || parent.querySelector('.comment-btn.like');
            const dislikeBtn = parent.querySelector('.vote-btn.downvote') || parent.querySelector('.dislike-btn') || parent.querySelector('.comment-btn.dislike');
            
            if (likeBtn) likeBtn.classList.toggle('active', data.type === 'like');
            if (dislikeBtn) dislikeBtn.classList.toggle('active', data.type === 'dislike');
            
            // Support vote-count, count-like, and span selectors
            const voteCountEl = parent.querySelector('.vote-count');
            const likeCountEl = likeBtn?.querySelector('.count-like') || likeBtn?.querySelector('span');
            const dislikeCountEl = dislikeBtn?.querySelector('.count-dislike') || dislikeBtn?.querySelector('span');
            
            // Update vote count (upvotes - downvotes)
            if (voteCountEl) {
                voteCountEl.textContent = (data.likeCount ?? 0) - (data.dislikeCount ?? 0);
            }
            
            if (likeCountEl) likeCountEl.textContent = data.likeCount ?? 0;
            if (dislikeCountEl) dislikeCountEl.textContent = data.dislikeCount ?? 0;
            
            // SVG fill is handled by CSS .active class
        } catch (error) {
            console.error('Reaction error:', error);
            showAlert(t('reaction_error', 'Failed to toggle reaction'), 'error');
        }
    };

    // Use event delegation for reaction buttons to handle dynamically added elements
    const setupReactionButtons = () => {
        console.log('Setting up reaction buttons on:', main);
        main.addEventListener('click', (e) => {
            console.log('Click detected on:', e.target);
            // Support vote-btn, like-btn, and comment-btn selectors
            const likeBtn = e.target.closest('.vote-btn.upvote') || e.target.closest('.like-btn') || e.target.closest('.comment-btn.like');
            const dislikeBtn = e.target.closest('.vote-btn.downvote') || e.target.closest('.dislike-btn') || e.target.closest('.comment-btn.dislike');
            console.log('Like btn:', likeBtn, 'Dislike btn:', dislikeBtn);
            
            if (likeBtn) {
                e.preventDefault();
                e.stopPropagation();
                const isComment = !!likeBtn.dataset.commentId;
                const id = isComment ? likeBtn.dataset.commentId : likeBtn.dataset.postId;
                console.log('Like clicked - isComment:', isComment, 'id:', id);
                handleReaction(likeBtn, 'like', id, isComment);
            } else if (dislikeBtn) {
                e.preventDefault();
                e.stopPropagation();
                const isComment = !!dislikeBtn.dataset.commentId;
                const id = isComment ? dislikeBtn.dataset.commentId : dislikeBtn.dataset.postId;
                console.log('Dislike clicked - isComment:', isComment, 'id:', id);
                handleReaction(dislikeBtn, 'dislike', id, isComment);
            }
        });
    };

    const setupViewCounter = () => {
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                const postId = entry.target.dataset.postId;
                if (entry.isIntersecting && !viewedPosts.has(postId)) {
                    const timer = setTimeout(async () => {
                        try {
                            const response = await fetch(`/posts/${postId}/views`, {
                                method: 'POST',
                                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                            });
                            const data = await response.json();
                            document.querySelectorAll(`[data-post-id="${postId}"] .count-view`).forEach(el => el.textContent = data.views);
                            viewedPosts.add(postId);
                            localStorage.setItem(viewedPostsKey, JSON.stringify([...viewedPosts]));
                        } catch {
                            console.error('Failed to increment view count');
                        }
                    }, 2000);
                    entry.target.dataset.viewTimer = timer;
                } else {
                    clearTimeout(entry.target.dataset.viewTimer);
                }
            });
        }, { threshold: 0.3 });

        main.querySelectorAll('.post-card').forEach(post => observer.observe(post));
    };

    // Poll for real-time updates every 5 seconds
    const setupRealTimePolling = () => {
        setInterval(async () => {
            const postCards = main.querySelectorAll('.post-card[data-post-id]');
            if (postCards.length === 0) return;
            
            const postIds = Array.from(postCards).map(card => card.dataset.postId);
            
            try {
                const response = await fetch('/posts/stats/bulk', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ post_ids: postIds })
                });
                
                if (response.ok) {
                    const data = await response.json();
                    if (data.posts) {
                        Object.entries(data.posts).forEach(([postId, stats]) => {
                            const postCard = main.querySelector(`.post-card[data-post-id="${postId}"]`);
                            if (!postCard) return;

                            // Update views
                            postCard.querySelectorAll('.count-view').forEach(el => {
                                el.textContent = stats.views;
                            });

                            // Update likes
                            postCard.querySelectorAll('.like-btn .count-like').forEach(el => {
                                el.textContent = stats.likes;
                            });

                            // Update dislikes
                            postCard.querySelectorAll('.dislike-btn .count-dislike').forEach(el => {
                                el.textContent = stats.dislikes;
                            });

                            // Update comment count
                            postCard.querySelectorAll('.comment-count').forEach(el => {
                                el.textContent = stats.comments;
                            });

                            // Update like/dislike button states
                            const likeBtn = postCard.querySelector('.like-btn[data-post-id]');
                            const dislikeBtn = postCard.querySelector('.dislike-btn[data-post-id]');
                            
                            if (likeBtn) {
                                likeBtn.classList.toggle('active', stats.user_liked);
                                const likeSvg = likeBtn.querySelector('svg');
                                if (likeSvg) {
                                    likeSvg.setAttribute('fill', stats.user_liked ? '#ef4444' : 'currentColor');
                                }
                            }
                            
                            if (dislikeBtn) {
                                dislikeBtn.classList.toggle('active', stats.user_disliked);
                                const dislikeSvg = dislikeBtn.querySelector('svg');
                                if (dislikeSvg) {
                                    dislikeSvg.setAttribute('fill', stats.user_disliked ? '#ffffff' : 'currentColor');
                                }
                            }

                            // Update post content if changed (check by updated_at)
                            const contentEl = postCard.querySelector('.post-content p');
                            if (contentEl && contentEl.dataset.updatedAt !== stats.updated_at) {
                                contentEl.textContent = stats.content;
                                contentEl.dataset.updatedAt = stats.updated_at;
                            }
                        });
                    }
                }
            } catch (error) {
                console.error('Failed to fetch post stats:', error);
            }
        }, 5000); // Every 5 seconds
    };

    // Sort buttons functionality
    const setupSortButtons = () => {
        const sortButtons = document.querySelectorAll('.sort-btn');
        const postsFeed = document.querySelector('.posts-feed');
        if (sortButtons.length === 0) return;
        
        sortButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Remove active class from all
                sortButtons.forEach(b => b.classList.remove('active'));
                // Add to clicked
                this.classList.add('active');
                
                // Get sort type from button text
                const sortText = this.textContent.trim().toLowerCase();
                let sortParam = 'newest';
                if (sortText.includes('top') || sortText.includes('топ')) sortParam = 'top';
                else if (sortText.includes('hot') || sortText.includes('гор')) sortParam = 'hot';
                
                // Reload page with sort parameter
                const url = new URL(window.location.href);
                url.searchParams.set('sort', sortParam);
                window.location.href = url.toString();
            });
        });
        
        // Set active button based on URL param
        const urlParams = new URLSearchParams(window.location.search);
        const currentSort = urlParams.get('sort') || 'newest';
        sortButtons.forEach(btn => {
            const btnText = btn.textContent.trim().toLowerCase();
            if ((currentSort === 'top' && (btnText.includes('top') || btnText.includes('топ'))) ||
                (currentSort === 'hot' && (btnText.includes('hot') || btnText.includes('гор'))) ||
                (currentSort === 'newest' && (btnText.includes('new') || btnText.includes('нов')))) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
    };

    const setupPostForm = () => {
        // Post form now submits normally without AJAX
        // Just handle media preview and character count
        if (!els.postForm) return;
        
        // Character count is already handled in setupCharCounter
    };

    const createPostElement = (post, csrfToken) => {
        const postElement = document.createElement('article');
        postElement.className = 'post-card';
        postElement.id = `post-${post.id}`;
        postElement.dataset.postId = post.id;
        postElement.innerHTML = `
            <div class="post-top">
                <div class="avatar">
                    <img src="${post.user.avatar ? '/storage/' + post.user.avatar : '/storage/logo/defaultPhoto.jpg'}" alt="${post.user.name}'s Avatar">
                </div>
                <div class="meta">
                    <a href="${post.user.profile_url}" class="name">${post.user.name}</a>
                    <div class="username">@${post.user.username}</div>
                    <div class="time">${post.created_at_diff || 'just now'}</div>
                </div>
            </div>
            <div class="post-body" id="post-body-${post.id}">
                <p>${post.content}</p>
                ${post.media_path ? (post.media_type === 'image' ? 
                    `<img src="/storage/${post.media_path}" alt="Post image" class="post-img" loading="lazy" />` : 
                    `<video src="/storage/${post.media_path}" controls class="post-video" style="max-height: 200px; border-radius: var(--radius);"></video>`) : ''}
            </div>
            <form id="edit-post-form-${post.id}" action="/posts/${post.id}" method="POST" enctype="multipart/form-data" style="display: none;">
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="PUT">
                <textarea name="content" rows="3" maxlength="1000">${post.content}</textarea>
                <div class="preview-container" style="position: relative;">
                    ${post.media_path && post.media_type === 'image' ? 
                        `<img id="edit-image-preview-${post.id}" src="/storage/${post.media_path}" alt="Image preview" />` : 
                        `<img id="edit-image-preview-${post.id}" alt="Image preview" style="display: none;" />`}
                    ${post.media_path && post.media_type === 'video' ? 
                        `<video id="edit-video-preview-${post.id}" src="/storage/${post.media_path}" controls style="max-height: 200px; border-radius: var(--radius);"></video>` : 
                        `<video id="edit-video-preview-${post.id}" controls style="display: none; max-height: 200px; border-radius: var(--radius);" alt="Video preview"></video>`}
                    <button type="button" class="remove-media" data-post-id="${post.id}" style="display: ${post.media_path ? 'block' : 'none'};">×</button>
                </div>
                <label class="file-label" title="Attach photo">
                    <input type="file" name="photo" accept="image/*" class="edit-post-photo">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="#3b82f6">
                        <path d="M160-160q-33 0-56.5-23.5T80-240v-400q0-33 23.5-56.5T160-720h240l80-80h320q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm73-280h207v-207L233-440Zm-73-40 160-160H160v160Zm0 120v120h640v-480H520v280q0 33-23.5 56.5T440-360H160Zm280-160Z"/>
                    </svg>
                </label>
                <label class="file-label" title="Attach video">
                    <input type="file" name="video" accept="video/mp4,video/mpeg,video/ogg,video/webm" class="edit-post-video">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="#3b82f6">
                        <path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm0-80h640v-480H160v480Zm160-80 240-160-240-160v320Zm-160 80v-480 480Z"/>
                    </svg>
                </label>
                <button type="submit" class="btn">Save</button>
                <button type="button" class="btn cancel-edit" data-post-id="${post.id}">Cancel</button>
            </form>
            <div class="post-actions">
                <button class="action-btn like-btn ${post.user_liked ? 'active' : ''}" data-post-id="${post.id}">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="${post.user_liked ? '#ef4444' : 'currentColor'}">
                        <path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/>
                    </svg>
                    <span class="count-like">${post.like_count || 0}</span>
                </button>
                <button class="action-btn dislike-btn ${post.user_disliked ? 'active' : ''}" data-post-id="${post.id}">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="${post.user_disliked ? '#ffffffff' : 'currentColor'}">
                        <path d="M240-840h440v520L400-40l-50-50q-7-7-11.5-19t-4.5-23v-14l44-174H120q-32 0-56-24t-24-56v-80q0-7 2-15t4-15l120-282q9-20 30-34t44-14Zm360 80H240L120-480v80h360l-54 220 174-174v-406Zm0 406v-406 406Zm80 34v-80h120v-360H680v-80h200v520H680Z"/>
                    </svg>
                    <span class="count-dislike">${post.dislike_count || 0}</span>
                </button>
                <button class="action-btn comment-toggle" data-post-id="${post.id}" data-count="${post.comment_count || 0}">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="#3b82f6">
                        <path d="M240-400h480v-80H240v80Zm0-120h480v-80H240v80Zm0-120h480v-80H240v80ZM880-80 720-240H160q-33 0-56.5-23.5T80-320v-480q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v720ZM160-320h594l46 45v-525H160v480Zm0-480 0 480-0 0Z"/>
                    </svg>
                    <span class="comment-count">${post.comment_count || 0}</span> Comments
                </button>
                <span class="view-count">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="#6b7280">
                        <path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z"/>
                    </svg>
                    <span class="count-view">${post.views || 0}</span>
                </span>
                ${post.can_update ? `<button type="button" class="action-btn edit-post-btn" data-post-id="${post.id}">Edit</button>` : ''}
                ${post.can_delete ? `<form action="/posts/${post.id}" method="POST" class="inline-form delete-post-form"><input type="hidden" name="_token" value="${csrfToken}"><input type="hidden" name="_method" value="DELETE"><button type="submit" class="action-btn delete-btn">Delete</button></form>` : ''}
            </div>
            <div class="comments" id="comments-${post.id}" style="display:none">
                <form action="/posts/${post.id}/comment" method="POST" class="comment-form" data-post-id="${post.id}">
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <textarea name="content" placeholder="Write a comment..." rows="1" maxlength="500"></textarea>
                    <button type="submit" class="btn"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#006400"><path d="M120-160v-640l760 320-760 320Zm80-120 474-200-474-200v140l240 60-240 60v140Zm0 0v-400 400Z"/></svg></button>
                </form>
            </div>
        `;
        return postElement;
    };

    const createCommentElement = (comment, postId, csrfToken) => {
        const commentElement = document.createElement('div');
        commentElement.className = `comment-item ${comment.parent_id ? 'is-reply' : ''}`;
        commentElement.id = `comment-${comment.id}`;
        commentElement.dataset.commentId = comment.id;
        const avatarUrl = comment.user_avatar
            ? `/storage/${comment.user_avatar}`
            : '/storage/logo/defaultPhoto.jpg';
        const escapedContent = comment.content.replace(/</g, '&lt;').replace(/>/g, '&gt;');
        commentElement.innerHTML = `
            <img src="${avatarUrl}" alt="${comment.user_name}" class="comment-avatar">
            <div class="comment-content">
                <div class="comment-header">
                    <a href="/profile/${comment.user_id}" class="comment-author">${comment.user_name}</a>
                    <span class="comment-username">@${comment.user?.username || ''}</span>
                    ${comment.parent_id ? `<span class="comment-reply-to">→ @${comment.parent_username || ''}</span>` : ''}
                    <span class="comment-time">${comment.created_at_diff || 'just now'}</span>
                </div>
                <div class="comment-text" id="comment-text-${comment.id}">
                    <p>${escapedContent}</p>
                </div>
                <form id="edit-comment-form-${comment.id}" class="comment-edit-form" action="/comments/${comment.id}" method="POST" style="display: none;">
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="_method" value="PUT">
                    <textarea name="content" maxlength="500">${escapedContent}</textarea>
                    <div class="comment-edit-btns">
                        <button type="submit" class="btn-save">Save</button>
                        <button type="button" class="btn-cancel cancel-edit-comment" data-comment-id="${comment.id}">Cancel</button>
                    </div>
                </form>
                <div class="comment-actions">
                    <button class="comment-btn like ${comment.user_liked ? 'active' : ''}" data-comment-id="${comment.id}">
                        <svg viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                        <span>${comment.like_count || 0}</span>
                    </button>
                    <button class="comment-btn dislike ${comment.user_disliked ? 'active' : ''}" data-comment-id="${comment.id}">
                        <svg viewBox="0 0 24 24"><path d="M15 3H6c-.83 0-1.54.5-1.84 1.22l-3.02 7.05c-.09.23-.14.47-.14.73v2c0 1.1.9 2 2 2h6.31l-.95 4.57-.03.32c0 .41.17.79.44 1.06L9.83 23l6.59-6.59c.36-.36.58-.86.58-1.41V5c0-1.1-.9-2-2-2zm4 0v12h4V3h-4z"/></svg>
                        <span>${comment.dislike_count || 0}</span>
                    </button>
                    <button class="comment-btn reply reply-btn" data-comment-id="${comment.id}" data-post-id="${postId}">
                        <svg viewBox="0 0 24 24"><path d="M10 9V5l-7 7 7 7v-4.1c5 0 8.5 1.6 11 5.1-1-5-4-10-11-11z"/></svg>
                        <span>Reply</span>
                    </button>
                    ${comment.can_update ? `
                    <button class="comment-btn edit edit-comment-btn" data-comment-id="${comment.id}">
                        <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34a.9959.9959 0 00-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                        <span>Edit</span>
                    </button>` : ''}
                    ${comment.can_delete ? `
                    <form action="/comments/${comment.id}" method="POST" class="inline-delete">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="comment-btn delete">
                            <svg viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                            <span>Delete</span>
                        </button>
                    </form>` : ''}
                    ${comment.reply_count > 0 ? `
                    <button class="comment-btn show-replies show-replies-btn" data-comment-id="${comment.id}">
                        <svg viewBox="0 0 24 24"><path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z"/></svg>
                        <span>Show ${comment.reply_count} ${comment.reply_count === 1 ? 'Reply' : 'Replies'}</span>
                    </button>` : ''}
                </div>
                <form action="/posts/${postId}/comment" method="POST" class="comment-reply-form" data-post-id="${postId}" data-parent-id="${comment.id}" style="display: none;">
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="parent_id" value="${comment.id}">
                    <textarea name="content" placeholder="Write a reply..." maxlength="500"></textarea>
                    <div class="comment-reply-btns">
                        <button type="submit" class="btn-reply">Reply</button>
                        <button type="button" class="btn-cancel cancel-reply" data-comment-id="${comment.id}">Cancel</button>
                    </div>
                </form>
                <div class="comment-replies" id="replies-${comment.id}" style="display: none;"></div>
            </div>
        `;
        return commentElement;
    };

    const restoreReplyDrafts = (scope = main) => {
        // Support both old (.reply-form) and new (.comment-reply-form) selectors
        scope.querySelectorAll('.reply-form, .comment-reply-form').forEach(form => {
            const postId = form.dataset.postId;
            const parentId = form.dataset.parentId;
            const draftKey = `replyDraft_${postId}_${parentId}`;
            const draft = localStorage.getItem(draftKey);
            if (draft) {
                form.querySelector('textarea').value = draft;
                form.style.display = 'block';
            }
            form.querySelector('textarea').addEventListener('input', () => {
                localStorage.setItem(draftKey, form.querySelector('textarea').value);
            });
        });
    };

    const setupReplyButtons = (scope = main) => {
        scope.querySelectorAll('.reply-btn').forEach(btn => {
            btn.removeEventListener('click', toggleReplyForm);
            btn.addEventListener('click', toggleReplyForm);
        });
    };

    const toggleReplyForm = (e) => {
        const btn = e.target.closest('.reply-btn');
        const commentId = btn.dataset.commentId;
        const postId = btn.dataset.postId;
        // Support both old (.reply-form) and new (.comment-reply-form) selectors
        const form = document.querySelector(`.reply-form[data-parent-id="${commentId}"]`) || 
                     document.querySelector(`.comment-reply-form[data-parent-id="${commentId}"]`);
        if (form) {
            const isVisible = form.style.display === 'block';
            // Hide all reply forms for this post
            document.querySelectorAll(`.reply-form[data-post-id="${postId}"], .comment-reply-form[data-post-id="${postId}"]`).forEach(f => {
                f.style.display = 'none';
            });
            form.style.display = isVisible ? 'none' : 'block';
            if (!isVisible) {
                form.querySelector('textarea').focus();
            }
        } else {
            console.error(`Reply form for comment ${commentId} not found`);
        }
    };

    const initializeReplies = (scope = main) => {
        // Support both old (.comment) and new (.comment-item) selectors
        scope.querySelectorAll('.comment, .comment-item').forEach(comment => {
            const commentId = comment.dataset.commentId;
            const repliesContainer = comment.querySelector(`#replies-${commentId}`) || 
                                     comment.querySelector('.comment-replies');
            if (repliesContainer && repliesContainer.children.length > 0) {
                let showRepliesBtn = comment.querySelector(`.show-replies-btn[data-comment-id="${commentId}"]`);
                // Support both old (.reply) and new (.is-reply) selectors
                const replyCount = repliesContainer.querySelectorAll('.reply, .is-reply').length;
                if (!showRepliesBtn && replyCount > 0) {
                    showRepliesBtn = document.createElement('button');
                    showRepliesBtn.className = 'comment-btn show-replies show-replies-btn';
                    showRepliesBtn.dataset.commentId = commentId;
                    showRepliesBtn.innerHTML = `
                        <svg viewBox="0 0 24 24"><path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z"/></svg>
                        <span>Show ${replyCount} ${replyCount === 1 ? 'Reply' : 'Replies'}</span>
                    `;
                    comment.querySelector('.comment-actions').appendChild(showRepliesBtn);
                    setupShowRepliesButtons(comment);
                }
            }
        });
    };

    const attachCommentFormListeners = (scope = main) => {
        // Support both old (.comment-form) and new (.comment-reply-form) selectors
        scope.querySelectorAll('.comment-form, .comment-reply-form').forEach(form => {
            form.removeEventListener('submit', submitCommentForm);
            form.addEventListener('submit', submitCommentForm);

            const cancelReply = form.querySelector('.cancel-reply');
            if (cancelReply) {
                cancelReply.removeEventListener('click', cancelReplyForm);
                cancelReply.addEventListener('click', cancelReplyForm);
            }
        });
    };

    const submitCommentForm = async (e) => {
        e.preventDefault();
        const form = e.target;
        const postId = form.dataset.postId;
        const parentId = form.dataset.parentId;
        const textarea = form.querySelector('textarea');
        if (!textarea.value.trim()) {
            showAlert(t('comment_empty', 'Comment cannot be empty'), 'error');
            return;
        }

        form.querySelector('button[type="submit"]').disabled = true;
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: new FormData(form)
            });
            const data = await response.json();
            if (data.success) {
                showAlert(t('comment_added', 'Comment added'), 'success');
                textarea.value = '';
                if (parentId) {
                    localStorage.removeItem(`replyDraft_${postId}_${parentId}`);
                    form.style.display = 'none';
                }
                const comments = document.getElementById(`comments-${postId}`);
                const commentsListEl = comments ? comments.querySelector('.comments-list') : null;
                if (!comments) {
                    console.error(`Comments container for post ${postId} not found`);
                    showAlert(t('comment_add_error', 'Failed to add comment'), 'error');
                    return;
                }
                const existingComment = document.getElementById(`comment-${data.comment.id}`);
                    if (!existingComment) {
                    const comment = createCommentElement(data.comment, postId, csrfToken);
                    let parent;
                    if (data.comment.parent_id) {
                        parent = document.getElementById(`replies-${data.comment.parent_id}`);
                        if (!parent) {
                            parent = document.createElement('div');
                            parent.className = 'comment-replies';
                            parent.id = `replies-${data.comment.parent_id}`;
                            parent.style.display = 'none';
                            const parentComment = document.getElementById(`comment-${data.comment.parent_id}`);
                            if (parentComment) {
                                const contentDiv = parentComment.querySelector('.comment-content') || parentComment;
                                contentDiv.appendChild(parent);
                            } else {
                                console.error(`Parent comment ${data.comment.parent_id} not found`);
                                showAlert(t('comment_add_error', 'Failed to add reply'), 'error');
                                return;
                            }
                        }
                    } else {
                        // Append to the server-rendered comments list if present, otherwise to the comments container
                        parent = commentsListEl || comments;
                    }
                    parent.appendChild(comment);
                    attachCommentEventListeners(comment);
                    attachCommentFormListeners(comment);
                    setupReplyButtons(comment);
                    setupShowRepliesButtons(comment);
                    restoreReplyDrafts(comment);

                    if (data.comment.parent_id) {
                        let showRepliesBtn = document.querySelector(`.show-replies-btn[data-comment-id="${data.comment.parent_id}"]`);
                        const parentComment = document.getElementById(`comment-${data.comment.parent_id}`);
                        if (showRepliesBtn) {
                            const replyCount = parseInt(showRepliesBtn.querySelector('span').textContent.match(/\d+/)[0]) + 1;
                            showRepliesBtn.querySelector('span').textContent = `Show ${replyCount} ${replyCount === 1 ? 'Reply' : 'Replies'}`;
                            parent.querySelector(`#replies-${data.comment.parent_id}`).style.display = 'block';
                            showRepliesBtn.querySelector('svg').style.transform = 'rotate(180deg)';
                        } else {
                            showRepliesBtn = document.createElement('button');
                            showRepliesBtn.className = 'comment-btn show-replies show-replies-btn';
                            showRepliesBtn.dataset.commentId = data.comment.parent_id;
                            showRepliesBtn.innerHTML = `
                                <svg viewBox="0 0 24 24"><path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z"/></svg>
                                <span>Show 1 Reply</span>
                            `;
                            if (parentComment) {
                                parentComment.querySelector('.comment-actions').appendChild(showRepliesBtn);
                                setupShowRepliesButtons(parentComment);
                                parent.querySelector(`#replies-${data.comment.parent_id}`).style.display = 'block';
                                showRepliesBtn.querySelector('span').textContent = `Hide 1 Reply`;
                                showRepliesBtn.querySelector('svg').style.transform = 'rotate(180deg)';
                            }
                        }
                    }

                    const toggle = document.querySelector(`.comment-toggle[data-post-id="${postId}"]`);
                    toggle.querySelector('.comment-count').textContent = +toggle.dataset.count + 1;
                    toggle.dataset.count = +toggle.dataset.count + 1;
                    comments.style.display = 'block';
                }
            } else {
                showAlert(data.message || t('comment_add_error', 'Failed to add comment'), 'error');
            }
        } catch (error) {
            console.error('Comment submission error:', error);
            //showAlert('Failed to add comment: ' + error.message, 'error');
        } finally {
            form.querySelector('button[type="submit"]').disabled = false;
        }
    };

    const cancelReplyForm = (e) => {
        // Support both old (.reply-form) and new (.comment-reply-form) selectors
        const form = e.target.closest('.reply-form') || e.target.closest('.comment-reply-form');
        form.querySelector('textarea').value = '';
        localStorage.removeItem(`replyDraft_${form.dataset.postId}_${form.dataset.parentId}`);
        form.style.display = 'none';
    };

    const attachCommentEventListeners = (scope = main) => {
        // Support both old (.comment) and new (.comment-item) selectors
        scope.querySelectorAll('.comment, .comment-item').forEach(comment => {
            const commentId = comment.dataset.commentId;
            const editBtn = comment.querySelector('.edit-comment-btn');
            const editForm = comment.querySelector(`#edit-comment-form-${commentId}`);
            // Support both old (.comment-body) and new (.comment-text) selectors
            const commentBody = comment.querySelector('.comment-body') || comment.querySelector('.comment-text');
            const cancelEdit = comment.querySelector(`.cancel-edit-comment[data-comment-id="${commentId}"]`);
            const deleteForm = comment.querySelector('.delete-comment-form') || comment.querySelector('.inline-delete');

            if (editBtn && editForm && commentBody && cancelEdit) {
                editBtn.removeEventListener('click', toggleEditForm);
                editBtn.addEventListener('click', toggleEditForm);

                cancelEdit.removeEventListener('click', cancelEditForm);
                cancelEdit.addEventListener('click', cancelEditForm);

                editForm.removeEventListener('submit', submitEditForm);
                editForm.addEventListener('submit', submitEditForm);
            }

            if (deleteForm) {
                deleteForm.removeEventListener('submit', submitDeleteForm);
                deleteForm.addEventListener('submit', submitDeleteForm);
            }
        });
    };

    const toggleEditForm = (e) => {
        // Support both old (.comment) and new (.comment-item) selectors
        const comment = e.target.closest('.comment') || e.target.closest('.comment-item');
        const commentId = comment.dataset.commentId;
        // Support both old (.comment-body) and new (.comment-text) selectors
        const commentBody = comment.querySelector('.comment-body') || comment.querySelector('.comment-text');
        const editForm = comment.querySelector(`#edit-comment-form-${commentId}`);
        commentBody.style.display = 'none';
        editForm.style.display = 'block';
    };

    const cancelEditForm = (e) => {
        // Support both old (.comment) and new (.comment-item) selectors
        const comment = e.target.closest('.comment') || e.target.closest('.comment-item');
        const commentId = comment.dataset.commentId;
        // Support both old (.comment-body) and new (.comment-text) selectors
        const commentBody = comment.querySelector('.comment-body') || comment.querySelector('.comment-text');
        const editForm = comment.querySelector(`#edit-comment-form-${commentId}`);
        commentBody.style.display = 'block';
        editForm.style.display = 'none';
    };

    const submitEditForm = async (e) => {
        e.preventDefault();
        const form = e.target;
        const commentId = form.id.replace('edit-comment-form-', '');
        const comment = document.querySelector(`#comment-${commentId}`);
        // Support both old (.comment-body) and new (.comment-text) selectors
        const commentBody = comment.querySelector('.comment-body') || comment.querySelector('.comment-text');
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: new FormData(form)
            });
            const data = await response.json();
            if (data.success) {
                showAlert(t('comment_updated', 'Comment updated'), 'success');
                commentBody.innerHTML = `<p>${data.comment.content}</p>`;
                commentBody.style.display = 'block';
                form.style.display = 'none';
            } else {
                showAlert(data.message || t('comment_update_error', 'Failed to update comment'), 'error');
            }
        } catch (error) {
            console.error('Comment update error:', error);
            showAlert(t('comment_update_error', 'Failed to update comment'), 'error');
        }
    };

    const submitDeleteForm = async (e) => {
        e.preventDefault();
        const form = e.target;
        // Support both old (.comment) and new (.comment-item) selectors
        const comment = form.closest('.comment') || form.closest('.comment-item');
        const postCard = comment.closest('.post-card');
        const postId = postCard ? postCard.dataset.postId : null;
        if (!confirm('Are you sure you want to delete this comment?')) return;
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: new FormData(form)
            });
            const data = await response.json();
            if (data.success) {
                showAlert(t('comment_deleted', 'Comment deleted'), 'success');
                comment.remove();
                if (postId) {
                    const toggle = document.querySelector(`.comment-toggle[data-post-id="${postId}"]`);
                    if (toggle) {
                        toggle.querySelector('.comment-count').textContent = +toggle.dataset.count - 1;
                        toggle.dataset.count = +toggle.dataset.count - 1;
                    }
                }
                if (data.parent_id) {
                    const showRepliesBtn = document.querySelector(`.show-replies-btn[data-comment-id="${data.parent_id}"]`);
                    if (showRepliesBtn) {
                        const replyCount = parseInt(showRepliesBtn.querySelector('span').textContent.match(/\d+/)[0]) - 1;
                        if (replyCount > 0) {
                            showRepliesBtn.querySelector('span').textContent = `Show ${replyCount} ${replyCount === 1 ? 'Reply' : 'Replies'}`;
                        } else {
                            showRepliesBtn.remove();
                            document.getElementById(`replies-${data.parent_id}`).remove();
                        }
                    }
                }
            } else {
                showAlert(data.message || t('comment_delete_error', 'Failed to delete comment'), 'error');
            }
        } catch (error) {
            console.error('Comment deletion error:', error);
            showAlert(t('comment_delete_error', 'Failed to delete comment'), 'error');
        }
    };

    const attachPostEventListeners = (post) => {
        const postId = post.dataset.postId;
        const editBtn = post.querySelector('.edit-post-btn');
        const editForm = post.querySelector(`#edit-post-form-${postId}`);
        const postBody = post.querySelector(`#post-body-${postId}`);
        const cancelEdit = post.querySelector(`.cancel-edit[data-post-id="${postId}"]`);
        const editPhotoInput = post.querySelector('.edit-post-photo');
        const editVideoInput = post.querySelector('.edit-post-video');
        const editImagePreview = post.querySelector(`#edit-image-preview-${postId}`);
        const editVideoPreview = post.querySelector(`#edit-video-preview-${postId}`);
        const removeEditMedia = post.querySelector(`.remove-media[data-post-id="${postId}"]`);
        const deleteForm = post.querySelector('.delete-post-form');

        if (editBtn) {
            editBtn.addEventListener('click', () => {
                postBody.style.display = 'none';
                editForm.style.display = 'block';
                if (editImagePreview.src && editImagePreview.src !== window.location.origin + '/storage/logo/defaultPhoto.jpg') {
                    editImagePreview.style.display = 'block';
                    editVideoPreview.style.display = 'none';
                    removeEditMedia.style.display = 'block';
                } else if (editVideoPreview.src) {
                    editVideoPreview.style.display = 'block';
                    editImagePreview.style.display = 'none';
                    removeEditMedia.style.display = 'block';
                }
            });
        }

        if (cancelEdit) {
            cancelEdit.addEventListener('click', () => {
                postBody.style.display = 'block';
                editForm.style.display = 'none';
                editImagePreview.src = post.querySelector('.post-img')?.src || '';
                editVideoPreview.src = post.querySelector('.post-video')?.src || '';
                editImagePreview.style.display = post.querySelector('.post-img') ? 'block' : 'none';
                editVideoPreview.style.display = post.querySelector('.post-video') ? 'block' : 'none';
                removeEditMedia.style.display = (editImagePreview.src || editVideoPreview.src) ? 'block' : 'none';
                editPhotoInput.value = '';
                editVideoInput.value = '';
            });
        }

        if (editPhotoInput) {
            editPhotoInput.addEventListener('change', () => {
                const file = editPhotoInput.files[0];
                console.log('Edit photo input changed:', file);
                if (file) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        editImagePreview.src = e.target.result;
                        editImagePreview.style.display = 'block';
                        editVideoPreview.style.display = 'none';
                        removeEditMedia.style.display = 'block';
                        editVideoInput.value = '';
                    };
                    reader.onerror = () => console.error('FileReader error');
                    reader.readAsDataURL(file);
                }
            });
        }

        if (editVideoInput) {
            editVideoInput.addEventListener('change', () => {
                const file = editVideoInput.files[0];
                console.log('Edit video input changed:', file);
                if (file) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        editVideoPreview.src = e.target.result;
                        editVideoPreview.style.display = 'block';
                        editImagePreview.style.display = 'none';
                        removeEditMedia.style.display = 'block';
                        editPhotoInput.value = '';
                    };
                    reader.onerror = () => console.error('FileReader error');
                    reader.readAsDataURL(file);
                }
            });
        }

        if (removeEditMedia) {
            removeEditMedia.addEventListener('click', () => {
                editPhotoInput.value = '';
                editVideoInput.value = '';
                editImagePreview.src = '';
                editVideoPreview.src = '';
                editImagePreview.style.display = 'none';
                editVideoPreview.style.display = 'none';
                removeEditMedia.style.display = 'none';
                const removeMediaInput = document.createElement('input');
                removeMediaInput.type = 'hidden';
                removeMediaInput.name = 'remove_media';
                removeMediaInput.value = '1';
                editForm.appendChild(removeMediaInput);
            });
        }

        if (editForm) {
            editForm.addEventListener('submit', async e => {
                e.preventDefault();
                try {
                    const response = await fetch(editForm.action, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                        body: new FormData(editForm)
                    });
                    const data = await response.json();
                    if (data.success) {
                        showAlert(t('post_updated', 'Post updated'), 'success');
                        postBody.innerHTML = `
                            <p>${data.post.content}</p>
                            ${data.post.media_path ? (data.post.media_type === 'image' ? 
                                `<img src="/storage/${data.post.media_path}" alt="Post image" class="post-img" loading="lazy" />` : 
                                `<video src="/storage/${data.post.media_path}" controls class="post-video" style="max-height: 200px; border-radius: var(--radius);"></video>`) : ''}
                        `;
                        postBody.style.display = 'block';
                        editForm.style.display = 'none';
                    } else {
                        showAlert(data.message || t('post_update_error', 'Failed to update post'), 'error');
                    }
                } catch (error) {
                    console.error('Post update error:', error);
                    showAlert(t('post_update_error', 'Failed to update post'), 'error');
                }
            });
        }

        if (deleteForm) {
            deleteForm.addEventListener('submit', async e => {
                e.preventDefault();
                if (!confirm('Are you sure you want to delete this post?')) return;
                try {
                    const response = await fetch(deleteForm.action, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                        body: new FormData(deleteForm)
                    });
                    const data = await response.json();
                    if (data.success) {
                        showAlert(t('post_deleted', 'Post deleted'), 'success');
                        post.remove();
                    } else {
                        showAlert(data.message || t('post_delete_error', 'Failed to delete post'), 'error');
                    }
                } catch (error) {
                    console.error('Post deletion error:', error);
                    showAlert(t('post_delete_error', 'Failed to delete post'), 'error');
                }
            });
        }
    };

    // Share button handler
    const setupShareButtons = (scope = main) => {
        scope.querySelectorAll('.post-action.share').forEach(btn => {
            btn.removeEventListener('click', handleShare);
            btn.addEventListener('click', handleShare);
        });
    };

    const handleShare = async (e) => {
        const btn = e.currentTarget;
        const postId = btn.dataset.postId;
        const url = `${window.location.origin}/posts#post-${postId}`;
        try {
            await navigator.clipboard.writeText(url);
            showAlert(t('link_copied', 'Link copied to clipboard!'), 'success');
        } catch {
            // Fallback for older browsers
            const input = document.createElement('input');
            input.value = url;
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
            showAlert(t('link_copied', 'Link copied to clipboard!'), 'success');
        }
    };

    // Initialize all event listeners and replies
    setupMobileMenu();
    setupEmojiPicker();
    setupMediaPreview();
    setupCharCounter();
    setupSortButtons();
    setupCommentToggle();
    setupReactionButtons();
    setupShareButtons();
    setupViewCounter();
    setupRealTimePolling();
    setupPostForm();
    attachCommentFormListeners();
    attachCommentEventListeners();
    setupReplyButtons();
    setupShowRepliesButtons();
    restoreReplyDrafts();
    initializeReplies();

    main.querySelectorAll('.post-card').forEach(post => {
        attachPostEventListeners(post);
    });
});