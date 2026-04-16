@extends('layouts.app')

@section('content')
<div class="feed-page">
    @if (session('success'))
        <script>document.addEventListener('DOMContentLoaded', () => window.toast?.success('{{ session('success') }}'));</script>
    @endif
    @if (session('error'))
        <script>document.addEventListener('DOMContentLoaded', () => window.toast?.error('{{ session('error') }}'));</script>
    @endif
    @error('content') <script>document.addEventListener('DOMContentLoaded', () => window.toast?.error('{{ $message }}'));</script> @enderror
    @error('photo') <script>document.addEventListener('DOMContentLoaded', () => window.toast?.error('{{ $message }}'));</script> @enderror
    @error('video') <script>document.addEventListener('DOMContentLoaded', () => window.toast?.error('{{ $message }}'));</script> @enderror

    <div class="feed-grid">
        {{-- Left Sidebar: Search Users --}}
        <aside class="sidebar sidebar-left">
            <div class="sidebar-card">
                <h3 class="sidebar-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    {{ __('posts.search_users') }}
                </h3>
                <div class="search-box">
                    <input type="text" id="user-search-input" placeholder="{{ __('posts.search_placeholder') }}" autocomplete="off" maxlength="50">
                </div>
                <div id="user-search-results" class="search-results"></div>
            </div>

        </aside>

        {{-- Center: Posts Feed --}}
        <main class="feed-main">
            <header class="feed-header">
                <h1>{{ __('posts.community') }}</h1>
                <div class="feed-tabs">
                    <button class="feed-tab active" data-sort="new">{{ __('posts.newest') }}</button>
                    <button class="feed-tab" data-sort="top">{{ __('posts.top') }}</button>
                    <button class="feed-tab" data-sort="hot">{{ __('posts.hot') }}</button>
                </div>
            </header>

        <section class="feed-posts">
            @forelse($posts as $post)
            <article class="post" id="post-{{ $post->id }}" data-post-id="{{ $post->id }}">
                <div class="post-header">
                    <a href="{{ route('profile.show', $post->user->id) }}" class="post-author">
                        <img src="{{ $post->user->avatar ? asset('storage/' . $post->user->avatar) : asset('storage/logo/defaultPhoto.jpg') }}" alt="" class="post-avatar">
                        <div class="post-author-info">
                            <span class="post-author-name">{{ $post->user->name }}</span>
                            <span class="post-meta">{{ '@' . $post->user->username }} · {{ $post->created_at->diffForHumans() }}</span>
                        </div>
                    </a>
                    @can('update', $post)
                    <div class="post-menu">
                        <button class="post-menu-btn">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="5" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="12" cy="19" r="2"/></svg>
                        </button>
                        <div class="post-menu-dropdown">
                            <button class="post-menu-item edit-post-btn" data-post-id="{{ $post->id }}">{{ __('posts.edit') }}</button>
                            @can('delete', $post)
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" class="delete-post-form">
                                @csrf @method('DELETE')
                                <button type="submit" class="post-menu-item danger">{{ __('posts.delete') }}</button>
                            </form>
                            @endcan
                        </div>
                    </div>
                    @endcan
                </div>

                <div class="post-text" id="post-body-{{ $post->id }}">
                    <p data-updated-at="{{ $post->updated_at->toISOString() }}">{{ $post->content }}</p>
                </div>

                @if($post->media_path)
                <div class="post-media">
                    @if($post->media_type === 'image')
                        <img src="{{ asset('storage/' . $post->media_path) }}" alt="" loading="lazy">
                    @elseif($post->media_type === 'video')
                        <video src="{{ asset('storage/' . $post->media_path) }}" controls></video>
                    @endif
                </div>
                @endif

                <div class="post-footer">
                    <div class="post-reactions">
                        <button class="reaction-btn like {{ Auth::check() && $post->isLikedBy(Auth::id()) ? 'active' : '' }}" data-post-id="{{ $post->id }}">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="{{ Auth::check() && $post->isLikedBy(Auth::id()) ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                            <span>{{ $post->likes()->where('type', 'post')->where('is_like', true)->count() }}</span>
                        </button>
                        <button class="reaction-btn dislike {{ Auth::check() && $post->isDislikedBy(Auth::id()) ? 'active' : '' }}" data-post-id="{{ $post->id }}">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="{{ Auth::check() && $post->isDislikedBy(Auth::id()) ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2"><path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zm7-13h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"/></svg>
                            <span>{{ $post->likes()->where('type', 'post')->where('is_like', false)->count() }}</span>
                        </button>
                    </div>
                    <div class="post-actions">
                        <button class="action-btn comment-toggle" data-post-id="{{ $post->id }}">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            <span>{{ $post->allComments()->count() }}</span>
                        </button>
                        <button class="action-btn share" data-post-id="{{ $post->id }}">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                        </button>
                        <span class="action-btn views">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <span>{{ $post->postViews()->count() }}</span>
                        </span>
                    </div>
                </div>

                {{-- Edit form --}}
                <form id="edit-post-form-{{ $post->id }}" action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data" class="edit-form" style="display: none;">
                    @csrf @method('PUT')
                    <textarea name="content" maxlength="1000">{{ $post->content }}</textarea>
                    <div class="edit-media-preview">
                        @if($post->media_path && $post->media_type === 'image')
                            <img id="edit-image-preview-{{ $post->id }}" src="{{ asset('storage/' . $post->media_path) }}" alt="">
                        @else
                            <img id="edit-image-preview-{{ $post->id }}" style="display: none;">
                        @endif
                        @if($post->media_path && $post->media_type === 'video')
                            <video id="edit-video-preview-{{ $post->id }}" src="{{ asset('storage/' . $post->media_path) }}" controls></video>
                        @else
                            <video id="edit-video-preview-{{ $post->id }}" controls style="display: none;"></video>
                        @endif
                        <button type="button" class="remove-media" data-post-id="{{ $post->id }}" style="display: {{ $post->media_path ? 'flex' : 'none' }};">×</button>
                    </div>
                    <div class="edit-bar">
                        <div class="edit-tools">
                            <label><input type="file" name="photo" accept="image/*" class="edit-post-photo"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></label>
                            <label><input type="file" name="video" accept="video/*" class="edit-post-video"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg></label>
                        </div>
                        <div class="edit-btns">
                            <button type="button" class="btn-cancel cancel-edit" data-post-id="{{ $post->id }}">{{ __('posts.cancel') }}</button>
                            <button type="submit" class="btn-save">{{ __('posts.save') }}</button>
                        </div>
                    </div>
                </form>

                {{-- Comments --}}
                <div class="comments-section" id="comments-{{ $post->id }}" style="display: none;">
                    <div class="comments-list">
                        @forelse($post->comments as $comment)
                            @include('posts.partials.comment', ['comment' => $comment, 'post' => $post])
                            @if($comment->replies->count() > 0)
                                <div class="replies-toggle-wrap">
                                    <button class="replies-toggle" data-comment-id="{{ $comment->id }}">
                                        <span class="replies-toggle-line"></span>
                                        <span class="replies-toggle-text">{{ __('posts.view_replies', ['count' => $comment->replies->count()]) }}</span>
                                    </button>
                                </div>
                                <div class="comment-replies" id="replies-{{ $comment->id }}" style="display: none;">
                                    @foreach($comment->replies as $reply)
                                        @include('posts.partials.comment', ['comment' => $reply, 'post' => $post])
                                    @endforeach
                                </div>
                            @endif
                        @empty
                        <div class="comments-empty" id="comments-empty-{{ $post->id }}">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" opacity="0.4"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            <p>{{ __('posts.no_comments_yet') }}</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </article>
            @empty
            <div class="feed-empty">
                <span class="feed-empty-icon">📝</span>
                <h3>{{ __('posts.no_posts_available') }}</h3>
                <p>{{ __('posts.be_first') }}</p>
            </div>
            @endforelse
        </section>

        <div class="feed-pagination">{{ $posts->links() }}</div>
        </main>

        {{-- Right Sidebar: Trending --}}
        <aside class="sidebar sidebar-right">
            <div class="sidebar-card">
                <h3 class="sidebar-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                    {{ __('posts.trending') }}
                </h3>
                <div class="trending-list">
                    @forelse($trendingPosts as $trend)
                    <a href="#post-{{ $trend->id }}" class="trending-item" data-post-id="{{ $trend->id }}">
                        <div class="trending-info">
                            <span class="trending-author">{{ $trend->user->name }}</span>
                            <p class="trending-text">{{ Str::limit($trend->content, 60) }}</p>
                        </div>
                        <span class="trending-likes">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                            {{ $trend->like_count }}
                        </span>
                    </a>
                    @empty
                    <p class="sidebar-empty">{{ __('posts.no_trending') }}</p>
                    @endforelse
                </div>
            </div>

            <div class="sidebar-card sidebar-stats">
                <h3 class="sidebar-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 20V10"/><path d="M12 20V4"/><path d="M6 20v-6"/></svg>
                    {{ __('posts.community_stats') }}
                </h3>
                <div class="stats-grid">
                    <div class="stat-item">
                        <span class="stat-label">{{ __('posts.total_posts') }}</span>
                        <span class="stat-value" style="color: #ffffff !important; -webkit-text-fill-color: #ffffff !important;">{{ $posts->total() }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">{{ __('posts.online_now') }}</span>
                        <span class="stat-value" style="color: #ffffff !important; -webkit-text-fill-color: #ffffff !important;">{{ $activeUsers->count() }}</span>
                    </div>
                </div>
            </div>
        </aside>
    </div>

    {{-- Floating Composer Island --}}
    <div class="composer-island" id="composer-island">
        <div class="composer-context" id="composer-context" style="display: none;">
            <span class="composer-context-text" id="composer-context-text"></span>
            <button type="button" class="composer-context-close" id="composer-context-close">&times;</button>
        </div>
        <form id="post-form" action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="parent_id" id="composer-parent-id" value="">
            <input type="hidden" name="reply_to_id" id="composer-reply-to-id" value="">
            <input type="hidden" id="composer-post-id" value="">
            <input type="hidden" id="composer-mode" value="post">
            <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('storage/logo/defaultPhoto.jpg') }}" alt="" class="composer-avatar">
            <div class="composer-body">
                <textarea name="content" id="composer-text" placeholder="{{ __('posts.whats_on_your_mind') }}" rows="1" maxlength="1000"></textarea>
                <div class="composer-media" id="composer-preview" style="display: none;">
                    <img id="image-preview" style="display: none;">
                    <video id="video-preview" controls style="display: none;"></video>
                    <button type="button" id="remove-media">&times;</button>
                </div>
            </div>
            <div class="composer-tools" id="composer-tools">
                <label title="{{ __('posts.attach_photo') }}">
                    <input type="file" name="photo" accept="image/*" id="post-photo">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                </label>
                <label title="{{ __('posts.attach_video') }}">
                    <input type="file" name="video" accept="video/*" id="post-video">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>
                </label>
                <div class="emoji-picker">
                    <button type="button" class="emoji-trigger">😊</button>
                    <div class="emoji-dropdown">
                        @foreach(['💪', '🏃', '🏋️', '🥗', '🔥', '💯', '🎯', '⭐', '🏆', '💥', '❤️', '👏', '🙌', '✨', '🚀', '😊', '😎', '🤝', '👍', '🎉'] as $emoji)
                        <button type="button" class="emoji-btn">{{ $emoji }}</button>
                        @endforeach
                    </div>
                </div>
            </div>
            <button type="submit" class="composer-submit">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
            </button>
        </form>
    </div>
</div>

<style>
/* 3-Column Grid Layout */
.feed-page {
    min-height: 100vh;
    padding-bottom: 120px;
    background: var(--bg-base);
}

.feed-grid {
    display: grid;
    grid-template-columns: 240px 1fr 240px;
    gap: 24px;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 16px;
}

/* Sidebars */
.sidebar {
    position: sticky;
    top: 80px;
    height: fit-content;
    max-height: calc(100vh - 100px);
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 16px;
    z-index: 1;
    min-width: 0;
    max-width: 240px;
}

.sidebar::-webkit-scrollbar { width: 0; }

.sidebar-card {
    background: var(--bg-surface);
    border-radius: 16px;
    padding: 16px;
}

.sidebar-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0 0 12px;
}

.sidebar-empty {
    color: var(--text-muted);
    font-size: 0.8125rem;
    margin: 0;
    text-align: center;
    padding: 8px 0;
}

/* Search Box */
.search-box {
    position: relative;
}

.search-box input {
    width: 100%;
    padding: 8px 12px;
    background: var(--bg-elevated);
    border: none;
    border-radius: 10px;
    color: var(--text-primary);
    font-size: 0.8125rem;
    outline: none;
    transition: box-shadow 0.2s;
}

.search-box input:focus {
    box-shadow: 0 0 0 2px var(--primary);
}

.search-results {
    margin-top: 8px;
}

.search-result-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px;
    border-radius: 10px;
    text-decoration: none;
    transition: background 0.15s;
}

.search-result-item:hover {
    background: var(--bg-elevated);
}

.search-result-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}

.search-result-info {
    flex: 1;
    min-width: 0;
}

.search-result-name {
    display: block;
    font-size: 0.8125rem;
    font-weight: 600;
    color: var(--text-primary);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.search-result-username {
    display: block;
    font-size: 0.75rem;
    color: var(--text-muted);
}

.search-result-online {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #22c55e;
    flex-shrink: 0;
}

.search-loading {
    text-align: center;
    color: var(--text-muted);
    font-size: 0.75rem;
    padding: 12px 0;
}

/* Active Users */
.online-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #22c55e;
    display: inline-block;
    animation: pulse-green 2s infinite;
}

@keyframes pulse-green {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.active-users-list {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.active-user-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 6px 8px;
    border-radius: 10px;
    text-decoration: none;
    transition: background 0.15s;
}

.active-user-item:hover { background: var(--bg-elevated); }

.active-user-avatar-wrap {
    position: relative;
    flex-shrink: 0;
}

.active-user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
}

.online-indicator {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #22c55e;
    border: 2px solid var(--bg-surface);
}

.active-user-info {
    flex: 1;
    min-width: 0;
}

.active-user-name {
    display: block;
    font-size: 0.8125rem;
    font-weight: 600;
    color: var(--text-primary);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.active-user-username {
    display: block;
    font-size: 0.75rem;
    color: var(--text-muted);
}

/* Trending */
.trending-list {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.trending-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    padding: 8px;
    border-radius: 10px;
    text-decoration: none;
    transition: background 0.15s;
}

.trending-item:hover { background: var(--bg-elevated); }

.trending-info { flex: 1; min-width: 0; }

.trending-author {
    display: block;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-primary);
}

.trending-text {
    margin: 2px 0 0;
    font-size: 0.75rem;
    color: var(--text-muted);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.trending-likes {
    display: flex;
    align-items: center;
    gap: 3px;
    color: #f43f5e;
    font-size: 0.75rem;
    font-weight: 600;
    flex-shrink: 0;
}

/* Stats */
.stats-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 8px;
}

.stat-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 12px;
    background: var(--bg-elevated);
    border-radius: 10px;
}

.stat-value {
    font-size: 1.125rem;
    font-weight: 700;
    color: #ffffff !important;
    -webkit-text-fill-color: #ffffff !important;
}

[data-theme="light"] .stat-value {
    color: #111111 !important;
    -webkit-text-fill-color: #111111 !important;
}

.stat-label {
    font-size: 0.75rem;
    color: var(--text-muted);
}

/* Feed Center */
.feed-main {
    min-width: 0;
    padding: 0;
}

/* Header */
.feed-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 0;
    margin-bottom: 8px;
}

.feed-header h1 {
    font-size: 1.375rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
}

.feed-tabs {
    display: flex;
    gap: 4px;
    background: var(--bg-elevated);
    padding: 4px;
    border-radius: 20px;
}

.feed-tab {
    padding: 6px 14px;
    background: transparent;
    border: none;
    border-radius: 16px;
    color: var(--text-muted);
    font-size: 0.8125rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.feed-tab:hover { color: var(--text-primary); }
.feed-tab.active { background: var(--primary); color: #fff; }

/* Posts */
.feed-posts {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.post {
    background: var(--bg-surface);
    border-radius: 16px;
    padding: 16px;
    transition: transform 0.15s;
}

.post:hover { box-shadow: 0 2px 12px rgba(0,0,0,0.08); }

/* Post Header */
.post-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}

.post-author {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
}

.post-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.post-author-name {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.9375rem;
    display: block;
}

.post-meta {
    font-size: 0.8125rem;
    color: var(--text-muted);
}

/* Post Menu */
.post-menu { position: relative; }

.post-menu-btn {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: none;
    border-radius: 50%;
    color: var(--text-muted);
    cursor: pointer;
    transition: all 0.15s;
}

.post-menu-btn:hover { background: var(--bg-elevated); color: var(--text-primary); }

.post-menu-dropdown {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    background: var(--bg-surface);
    border: 1px solid var(--border-subtle);
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    min-width: 120px;
    z-index: 100;
    overflow: hidden;
}

.post-menu:hover .post-menu-dropdown { display: block; }

.post-menu-item {
    display: block;
    width: 100%;
    padding: 10px 14px;
    background: transparent;
    border: none;
    text-align: left;
    color: var(--text-secondary);
    font-size: 0.875rem;
    cursor: pointer;
    transition: background 0.15s;
}

.post-menu-item:hover { background: var(--bg-elevated); }
.post-menu-item.danger { color: #ef4444; }

/* Post Content */
.post-text {
    margin-bottom: 12px;
}

.post-text p {
    color: var(--text-primary);
    font-size: 0.9375rem;
    line-height: 1.6;
    margin: 0;
    white-space: pre-wrap;
}

.post-media {
    margin-bottom: 12px;
    border-radius: 12px;
    overflow: hidden;
}

.post-media img, .post-media video {
    width: 100%;
    max-height: 450px;
    object-fit: contain;
    background: var(--bg-elevated);
}

/* Post Footer */
.post-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 12px;
    border-top: 1px solid var(--border-subtle);
}

.post-reactions {
    display: flex;
    gap: 12px;
}

.reaction-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: var(--bg-elevated);
    border: none;
    border-radius: 20px;
    color: var(--text-muted);
    font-size: 0.8125rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.reaction-btn:hover { transform: scale(1.05); }
.reaction-btn.like:hover, .reaction-btn.like.active { color: #f43f5e; background: rgba(244,63,94,0.1); }
.reaction-btn.dislike:hover, .reaction-btn.dislike.active { color: #6366f1; background: rgba(99,102,241,0.1); }

.post-actions {
    display: flex;
    gap: 8px;
}

.action-btn {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 6px 10px;
    background: transparent;
    border: none;
    border-radius: 8px;
    color: var(--text-secondary);
    font-size: 0.8125rem;
    cursor: pointer;
    transition: all 0.15s;
}

.action-btn svg {
    width: 18px;
    height: 18px;
    flex-shrink: 0;
    stroke-width: 2.2;
}

.action-btn:hover { background: var(--bg-elevated); color: var(--text-secondary); }
.action-btn.views { cursor: default; }

/* Edit Form */
.edit-form {
    margin-top: 16px;
    padding: 12px;
    background: var(--bg-elevated);
    border-radius: 12px;
}

.edit-form textarea {
    width: 100%;
    min-height: 80px;
    padding: 10px;
    background: var(--bg-surface);
    border: none;
    border-radius: 8px;
    color: var(--text-primary);
    font-size: 0.9375rem;
    font-family: inherit;
    resize: none;
    outline: none;
}

.edit-media-preview { position: relative; margin-top: 8px; }
.edit-media-preview img, .edit-media-preview video { max-width: 100%; max-height: 180px; border-radius: 8px; }
.edit-media-preview .remove-media {
    position: absolute;
    top: 4px;
    right: 4px;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0,0,0,0.7);
    border: none;
    border-radius: 50%;
    color: #fff;
    cursor: pointer;
}

.edit-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 10px;
}

.edit-tools {
    display: flex;
    gap: 8px;
}

.edit-tools label {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background: var(--bg-surface);
    border-radius: 8px;
    color: var(--text-muted);
    cursor: pointer;
    transition: color 0.15s;
}

.edit-tools label:hover { color: var(--primary); }
.edit-tools input { display: none; }

.edit-btns { display: flex; gap: 8px; }

.btn-cancel, .btn-save {
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 0.8125rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.15s;
}

.btn-cancel { background: transparent; border: none; color: var(--text-muted); }
.btn-cancel:hover { color: var(--text-primary); }
.btn-save { background: var(--primary); border: none; color: #fff; }

/* Comments */
.comments-section {
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid var(--border-subtle);
}

.comment-form {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
    padding: 8px 12px;
    background: var(--bg-elevated);
    border-radius: 24px;
}

.comment-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}

.comment-form input {
    flex: 1;
    padding: 6px 0;
    background: transparent;
    border: none;
    color: var(--text-primary);
    font-size: 0.875rem;
    outline: none;
}

.comment-form button {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--primary);
    border: none;
    border-radius: 50%;
    color: #fff;
    cursor: pointer;
    transition: transform 0.15s;
    flex-shrink: 0;
}

.comment-form button:hover { transform: scale(1.08); }

/* Comment Items */
.comment-item {
    display: flex;
    gap: 10px;
    padding: 10px 0;
}

.comment-item.is-reply {
    padding-left: 38px;
}

.comment-item .comment-avatar {
    width: 28px;
    height: 28px;
    flex-shrink: 0;
}

.comment-item.is-reply .comment-avatar {
    width: 24px;
    height: 24px;
}

.comment-mention {
    color: var(--primary);
    font-weight: 600;
}

.comment-quote {
    background: var(--bg-elevated, #f3f4f6);
    border-left: 3px solid var(--primary, #6366f1);
    border-radius: 0 8px 8px 0;
    padding: 6px 10px;
    margin-bottom: 6px;
    cursor: pointer;
    transition: background 0.15s;
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.comment-quote:hover {
    background: #f0f0f0;
}
.comment-quote-author {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--primary, #6366f1);
    line-height: 1.3;
}
.comment-quote-username {
    font-weight: 400;
    color: var(--text-secondary, #6b7280);
}
.comment-quote-text {
    font-size: 0.8rem;
    color: var(--text-secondary, #6b7280);
    line-height: 1.3;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.comment-content {
    flex: 1;
    min-width: 0;
}

.comment-header {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
    margin-bottom: 4px;
}

.comment-author {
    font-weight: 600;
    font-size: 0.8125rem;
    color: var(--text-primary);
    text-decoration: none;
}

.comment-author:hover { text-decoration: underline; }

.comment-username {
    font-size: 0.75rem;
    color: var(--text-muted);
}

.comment-time {
    font-size: 0.75rem;
    color: var(--text-muted);
}

.comment-text {
    margin-bottom: 6px;
}

.comment-text p {
    margin: 0;
    font-size: 0.875rem;
    line-height: 1.5;
    color: var(--text-primary);
}

.comment-actions {
    display: flex;
    align-items: center;
    gap: 4px;
    flex-wrap: wrap;
}

.comment-btn {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    background: transparent;
    border: none;
    border-radius: 6px;
    color: var(--text-muted);
    font-size: 0.75rem;
    cursor: pointer;
    transition: all 0.15s;
}

.comment-btn svg {
    width: 14px;
    height: 14px;
    fill: currentColor;
    flex-shrink: 0;
}

.comment-btn:hover { background: var(--bg-elevated); color: var(--text-secondary); }
.comment-btn.like:hover, .comment-btn.like.active { color: #f43f5e; }
.comment-btn.dislike:hover, .comment-btn.dislike.active { color: #6366f1; }
.comment-btn.delete:hover { color: #ef4444; }

.comment-edit-form textarea {
    width: 100%;
    min-height: 60px;
    padding: 8px 10px;
    background: var(--bg-elevated);
    border: none;
    border-radius: 8px;
    color: var(--text-primary);
    font-size: 0.875rem;
    font-family: inherit;
    resize: none;
    outline: none;
    margin-bottom: 8px;
}

.comment-edit-btns, .comment-reply-btns {
    display: flex;
    gap: 6px;
    justify-content: flex-end;
}

.btn-reply {
    padding: 6px 14px;
    background: var(--primary);
    border: none;
    border-radius: 8px;
    color: #fff;
    font-size: 0.8125rem;
    font-weight: 500;
    cursor: pointer;
}

.comment-reply-form textarea {
    width: 100%;
    min-height: 60px;
    padding: 8px 10px;
    background: var(--bg-elevated);
    border: none;
    border-radius: 8px;
    color: var(--text-primary);
    font-size: 0.875rem;
    font-family: inherit;
    resize: none;
    outline: none;
    margin: 8px 0;
}

.comment-reply-form { margin-top: 6px; }

.comment-replies {
    margin: 0;
}

/* Replies Toggle */
.replies-toggle-wrap {
    padding-left: 38px;
}

.replies-toggle {
    display: flex;
    align-items: center;
    gap: 8px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px 0;
    color: var(--primary);
    font-size: 0.75rem;
    font-weight: 600;
}

.replies-toggle-line {
    width: 24px;
    height: 1px;
    background: var(--text-muted);
    opacity: 0.4;
}

.replies-toggle:hover .replies-toggle-text {
    text-decoration: underline;
}

.replies-toggle.expanded .replies-toggle-text::before {
    content: '{{ __("posts.hide") }} ';
}

.inline-delete { display: inline; }

/* Empty State */
.feed-empty {
    text-align: center;
    padding: 60px 24px;
    background: var(--bg-surface);
    border-radius: 16px;
}

.feed-empty-icon { font-size: 3rem; display: block; margin-bottom: 12px; }
.feed-empty h3 { color: var(--text-secondary); font-size: 1.125rem; margin: 0 0 6px; }
.feed-empty p { color: var(--text-muted); font-size: 0.9375rem; margin: 0; }

/* Comments Empty */
.comments-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    padding: 24px 16px;
}
.comments-empty svg {
    color: var(--text-muted);
}
.comments-empty p {
    color: var(--text-muted);
    font-size: 0.8125rem;
    margin: 0;
}

/* Pagination */
.feed-pagination { margin-top: 24px; }

/* Floating Composer Island */
.composer-island {
    position: fixed;
    bottom: 24px;
    left: 50%;
    transform: translateX(-50%);
    width: 100%;
    max-width: 680px;
    padding: 0 16px;
    z-index: 100;
}

.composer-context {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 6px 16px;
    background: var(--primary);
    border-radius: 16px 16px 0 0;
    color: #fff;
    font-size: 0.8125rem;
    font-weight: 500;
    margin-bottom: -1px;
}

.composer-context-close {
    background: transparent;
    border: none;
    color: #fff;
    font-size: 1.125rem;
    cursor: pointer;
    padding: 0 0 0 8px;
    opacity: 0.8;
    transition: opacity 0.15s;
}

.composer-context-close:hover { opacity: 1; }

.composer-island.mode-comment .composer-context,
.composer-island.mode-reply .composer-context {
    display: flex;
}

.composer-island.mode-comment form,
.composer-island.mode-reply form {
    border-radius: 0 0 24px 24px;
}

.composer-island.mode-comment .composer-tools,
.composer-island.mode-reply .composer-tools {
    display: none;
}

.composer-island form {
    display: flex;
    align-items: flex-end;
    gap: 12px;
    background: var(--bg-surface);
    border-radius: 24px;
    padding: 10px 12px 10px 16px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.2), 0 0 0 1px var(--border-subtle);
    backdrop-filter: blur(10px);
}

.composer-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}

.composer-body {
    flex: 1;
    min-width: 0;
}

.composer-body textarea {
    width: 100%;
    padding: 8px 0;
    background: transparent;
    border: none;
    color: var(--text-primary);
    font-size: 0.9375rem;
    font-family: inherit;
    resize: none;
    outline: none;
    min-height: 24px;
    max-height: 120px;
}

.composer-media {
    position: relative;
    margin-top: 8px;
}

.composer-media img, .composer-media video {
    max-width: 100%;
    max-height: 120px;
    border-radius: 12px;
}

.composer-media button {
    position: absolute;
    top: 4px;
    right: 4px;
    width: 22px;
    height: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0,0,0,0.7);
    border: none;
    border-radius: 50%;
    color: #fff;
    font-size: 14px;
    cursor: pointer;
}

.composer-tools {
    display: flex;
    align-items: center;
    gap: 4px;
}

.composer-tools label, .composer-tools > button {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: none;
    border-radius: 50%;
    color: var(--text-muted);
    cursor: pointer;
    transition: all 0.15s;
}

.composer-tools label:hover, .composer-tools > button:hover {
    background: var(--bg-elevated);
    color: var(--primary);
}

.composer-tools input { display: none; }

.emoji-picker { position: relative; }

.emoji-trigger {
    font-size: 1.25rem;
    background: transparent;
    border: none;
    cursor: pointer;
}

.emoji-dropdown {
    display: none;
    position: absolute;
    bottom: 44px;
    right: 0;
    background: var(--bg-surface);
    border: 1px solid var(--border-subtle);
    border-radius: 16px;
    padding: 10px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.2);
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 4px;
    z-index: 110;
    display: none;
}

.emoji-dropdown.open { display: grid; }

.emoji-btn {
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: none;
    border-radius: 8px;
    font-size: 1.125rem;
    cursor: pointer;
    transition: background 0.15s;
}

.emoji-btn:hover { background: var(--bg-elevated); }

.composer-submit {
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--primary);
    border: none;
    border-radius: 50%;
    color: #fff;
    cursor: pointer;
    flex-shrink: 0;
    transition: all 0.2s;
}

.composer-submit:hover {
    transform: scale(1.08);
    box-shadow: 0 4px 16px rgba(var(--primary-rgb), 0.4);
}

/* Responsive */
@media (max-width: 1100px) {
    .feed-grid { grid-template-columns: 1fr 240px; }
    .sidebar-left { display: none; }
}

@media (max-width: 800px) {
    .feed-grid { grid-template-columns: 1fr; }
    .sidebar-left, .sidebar-right { display: none; }
}

@media (max-width: 600px) {
    .feed-page { padding-bottom: 100px; }
    .feed-grid { padding: 0 8px; }
    .feed-header { flex-direction: column; gap: 12px; align-items: flex-start; }
    .post { padding: 14px; }
    .post-avatar { width: 36px; height: 36px; }
    .composer-island { bottom: 16px; max-width: 100%; }
    .composer-island form { padding: 8px 10px; gap: 8px; }
    .composer-avatar { width: 32px; height: 32px; }
    .composer-tools label, .composer-tools button { width: 32px; height: 32px; }
    .composer-submit { width: 38px; height: 38px; }
}

/* Light theme */
[data-theme="light"] .composer-island form {
    background: #fff;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1), 0 0 0 1px rgba(0,0,0,0.05);
}

[data-theme="light"] .post { background: #fff; }
[data-theme="light"] .feed-empty { background: #fff; }
[data-theme="light"] .sidebar-card { background: #fff; }
</style>
@endsection

@section('scripts')
<script src="{{ asset('js/posts.js') }}?v={{ time() }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const composerIsland = document.getElementById('composer-island');
    const composerForm = document.getElementById('post-form');
    const composerText = document.getElementById('composer-text');
    const composerContext = document.getElementById('composer-context');
    const composerContextText = document.getElementById('composer-context-text');
    const composerContextClose = document.getElementById('composer-context-close');
    const composerMode = document.getElementById('composer-mode');
    const composerPostId = document.getElementById('composer-post-id');
    const composerParentId = document.getElementById('composer-parent-id');
    const composerReplyToIdInput = document.getElementById('composer-reply-to-id');
    const composerTools = document.getElementById('composer-tools');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    const originalAction = composerForm.action;
    const originalPlaceholder = composerText.placeholder;
    let composerReplyToUser = '';

    // Auto-resize textarea
    if (composerText) {
        composerText.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });
    }

    // Emoji picker
    const trigger = document.querySelector('.emoji-trigger');
    const dropdown = document.querySelector('.emoji-dropdown');
    if (trigger && dropdown) {
        trigger.addEventListener('click', e => { e.stopPropagation(); dropdown.classList.toggle('open'); });
        document.addEventListener('click', () => dropdown.classList.remove('open'));
        dropdown.querySelectorAll('.emoji-btn').forEach(btn => {
            btn.addEventListener('click', e => {
                e.stopPropagation();
                if (composerText) {
                    composerText.value += btn.textContent;
                    composerText.focus();
                    composerText.dispatchEvent(new Event('input'));
                }
            });
        });
    }

    // === Composer Mode Switching ===
    function setComposerMode(mode, postId, parentId, contextLabel) {
        composerMode.value = mode;
        composerPostId.value = postId || '';
        composerParentId.value = parentId || '';

        if (mode === 'comment' || mode === 'reply') {
            composerIsland.classList.remove('mode-post', 'mode-comment', 'mode-reply');
            composerIsland.classList.add('mode-' + mode);
            composerContext.style.display = 'flex';
            composerContextText.textContent = contextLabel;
            composerForm.action = '/posts/' + postId + '/comment';
            composerText.placeholder = mode === 'reply' ? '{{ __("posts.write_reply") }}' : '{{ __("posts.write_comment") }}';
            composerText.setAttribute('maxlength', '500');
        } else {
            composerIsland.classList.remove('mode-comment', 'mode-reply');
            composerIsland.classList.add('mode-post');
            composerContext.style.display = 'none';
            composerForm.action = originalAction;
            composerText.placeholder = originalPlaceholder;
            composerText.setAttribute('maxlength', '1000');
            composerParentId.value = '';
            composerReplyToIdInput.value = '';
            composerReplyToUser = '';
        }
        composerText.focus();
    }

    // Cancel comment/reply mode
    composerContextClose.addEventListener('click', () => {
        setComposerMode('post');
        composerText.value = '';
        composerText.dispatchEvent(new Event('input'));
    });

    // Click "comment" button on post -> switch composer to comment mode
    document.addEventListener('click', e => {
        const commentToggle = e.target.closest('.comment-toggle');
        if (commentToggle) {
            const postId = commentToggle.dataset.postId;
            const postEl = document.getElementById('post-' + postId);
            const authorName = postEl?.querySelector('.post-author-name')?.textContent || '';
            setComposerMode('comment', postId, '', '{{ __("posts.commenting_on") }} ' + authorName);
            // Also still toggle the comments section
        }

        // Click "reply" button on a comment
        const replyBtn = e.target.closest('.reply-btn');
        if (replyBtn) {
            const commentId = replyBtn.dataset.commentId; // always root comment ID
            const replyToId = replyBtn.dataset.replyToId || ''; // actual comment being replied to
            const postId = replyBtn.dataset.postId;
            const replyToUser = replyBtn.dataset.replyToUser || '';
            const commentEl = document.getElementById('comment-' + commentId);
            const authorName = replyToUser || commentEl?.querySelector('.comment-author')?.textContent || '';
            composerReplyToUser = replyToUser;
            composerReplyToIdInput.value = replyToId;
            setComposerMode('reply', postId, commentId, '{{ __("posts.replying_to") }} @' + authorName);
            // Show replies section if hidden
            const repliesEl = document.getElementById('replies-' + commentId);
            if (repliesEl) repliesEl.style.display = 'block';
            // Update toggle text
            const toggleBtn = document.querySelector(`.replies-toggle[data-comment-id="${commentId}"]`);
            if (toggleBtn) toggleBtn.classList.add('expanded');
        }

        // Click "View N replies" toggle
        const repliesToggle = e.target.closest('.replies-toggle');
        if (repliesToggle) {
            const cid = repliesToggle.dataset.commentId;
            const repliesEl = document.getElementById('replies-' + cid);
            if (repliesEl) {
                const isVisible = repliesEl.style.display !== 'none';
                repliesEl.style.display = isVisible ? 'none' : 'block';
                repliesToggle.classList.toggle('expanded', !isVisible);
            }
        }

        // Click "share" button on a post
        const shareBtn = e.target.closest('.action-btn.share');
        if (shareBtn) {
            const pid = shareBtn.dataset.postId;
            const url = window.location.origin + '/posts#post-' + pid;
            navigator.clipboard.writeText(url).then(() => {
                window.toast?.success('{{ __("posts.link_copied") }}');
            }).catch(() => {
                const inp = document.createElement('input');
                inp.value = url;
                document.body.appendChild(inp);
                inp.select();
                document.execCommand('copy');
                document.body.removeChild(inp);
                window.toast?.success('{{ __("posts.link_copied") }}');
            });
        }

        // Click "edit" button on a comment
        const editBtn = e.target.closest('.edit-comment-btn');
        if (editBtn) {
            const cid = editBtn.dataset.commentId;
            const textEl = document.getElementById('comment-text-' + cid);
            const formEl = document.getElementById('edit-comment-form-' + cid);
            if (textEl && formEl) { textEl.style.display = 'none'; formEl.style.display = 'block'; }
        }

        // Click "cancel" edit on a comment
        const cancelEdit = e.target.closest('.cancel-edit-comment');
        if (cancelEdit) {
            const cid = cancelEdit.dataset.commentId;
            const textEl = document.getElementById('comment-text-' + cid);
            const formEl = document.getElementById('edit-comment-form-' + cid);
            if (textEl && formEl) { textEl.style.display = 'block'; formEl.style.display = 'none'; }
        }
    });

    // Delegated: edit comment form submit
    document.addEventListener('submit', async function(e) {
        const editForm = e.target.closest('.comment-edit-form');
        if (editForm) {
            e.preventDefault();
            const cid = editForm.id.replace('edit-comment-form-', '');
            try {
                const res = await fetch(editForm.action, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: new FormData(editForm)
                });
                const data = await res.json();
                if (data.success) {
                    window.toast?.success('{{ __("toast.comment_updated") }}');
                    const textEl = document.getElementById('comment-text-' + cid);
                    if (textEl) textEl.innerHTML = '<p>' + data.comment.content + '</p>';
                    if (textEl) textEl.style.display = 'block';
                    editForm.style.display = 'none';
                } else {
                    window.toast?.error(data.message || 'Error');
                }
            } catch { window.toast?.error('Failed to update'); }
        }

        // Delegated: delete comment form submit
        const deleteForm = e.target.closest('.inline-delete');
        if (deleteForm && deleteForm.closest('.comment-item')) {
            e.preventDefault();
            if (!confirm('{{ __("posts.confirm_delete_comment") }}')) return;
            const commentEl = deleteForm.closest('.comment-item');
            const postEl = commentEl.closest('.post');
            const postId = postEl?.dataset?.postId;
            try {
                const res = await fetch(deleteForm.action, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: new FormData(deleteForm)
                });
                const data = await res.json();
                if (data.success) {
                    window.toast?.success('{{ __("toast.comment_deleted") }}');
                    commentEl.remove();
                    if (postId) {
                        const toggle = document.querySelector(`.comment-toggle[data-post-id="${postId}"]`);
                        if (toggle) {
                            const countSpan = toggle.querySelector('span');
                            if (countSpan) countSpan.textContent = Math.max(0, parseInt(countSpan.textContent || '0') - 1);
                        }
                    }
                } else {
                    window.toast?.error(data.message || 'Error');
                }
            } catch { window.toast?.error('Failed to delete'); }
        }
    });

    // Override form submit for comment/reply mode
    composerForm.addEventListener('submit', async function(e) {
        const mode = composerMode.value;
        if (mode === 'comment' || mode === 'reply') {
            e.preventDefault();
            let content = composerText.value.trim();
            if (!content) return;

            // Prepend @username for replies
            if (mode === 'reply' && composerReplyToUser) {
                const mention = '@' + composerReplyToUser;
                if (!content.startsWith(mention)) {
                    content = mention + ' ' + content;
                }
            }

            const postId = composerPostId.value;
            const parentId = composerParentId.value;
            const fd = new FormData();
            fd.append('content', content);
            fd.append('_token', csrfToken);
            if (parentId) fd.append('parent_id', parentId);
            const replyToId = composerReplyToIdInput.value;
            if (replyToId) fd.append('reply_to_id', replyToId);

            try {
                const res = await fetch('/posts/' + postId + '/comment', {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: fd
                });
                const data = await res.json();
                if (data.success && data.comment) {
                    window.toast?.success('{{ __("toast.comment_added") }}');
                    composerText.value = '';
                    composerText.dispatchEvent(new Event('input'));

                    // Insert comment dynamically
                    const c = data.comment;
                    const esc = s => {
                        const d = document.createElement('div');
                        d.textContent = s;
                        return d.innerHTML;
                    };
                    const avatarSrc = c.user_avatar ? '/storage/' + esc(c.user_avatar) : '/storage/logo/defaultPhoto.jpg';
                    const rootId = c.parent_id || c.id;
                    // Highlight leading @mention in content
                    let displayContent = esc(c.content);
                    if (c.parent_id) {
                        displayContent = displayContent.replace(/^(@\S+)/, '<span class="comment-mention">$1</span>');
                    }
                    // Build quote block for replies
                    let quoteHtml = '';
                    if (c.parent_id && c.quoted_name) {
                        const parentText = esc(c.quoted_content || '').substring(0, 100);
                        quoteHtml = `<div class="comment-quote" onclick="document.getElementById('comment-${c.quoted_comment_id}')?.scrollIntoView({behavior:'smooth', block:'center'})">
                            <span class="comment-quote-author">${esc(c.quoted_name)} <span class="comment-quote-username">@${esc(c.quoted_username)}</span></span>
                            <span class="comment-quote-text">${parentText}</span>
                        </div>`;
                    }
                    const commentHtml = `
                        <div class="comment-item ${c.parent_id ? 'is-reply' : ''}" id="comment-${c.id}" data-comment-id="${c.id}" data-root-id="${rootId}">
                            <img src="${avatarSrc}" alt="" class="comment-avatar">
                            <div class="comment-content">
                                <div class="comment-header">
                                    <a href="${esc(c.user.profile_url)}" class="comment-author">${esc(c.user_name)}</a>
                                    <span class="comment-username">@${esc(c.user.username)}</span>
                                    <span class="comment-time">${esc(c.created_at_diff)}</span>
                                </div>
                                <div class="comment-text" id="comment-text-${c.id}">${quoteHtml}<p>${displayContent}</p></div>
                                <form id="edit-comment-form-${c.id}" class="comment-edit-form" action="/comments/${c.id}" method="POST" style="display: none;">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="PUT">
                                    <textarea name="content" maxlength="500">${esc(c.content)}</textarea>
                                    <div class="comment-edit-btns">
                                        <button type="submit" class="btn-save">{{ __('posts.save') }}</button>
                                        <button type="button" class="btn-cancel cancel-edit-comment" data-comment-id="${c.id}">{{ __('posts.cancel') }}</button>
                                    </div>
                                </form>
                                <div class="comment-actions">
                                    <button class="comment-btn like" data-comment-id="${c.id}">
                                        <svg viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                        <span>0</span>
                                    </button>
                                    <button class="comment-btn dislike" data-comment-id="${c.id}">
                                        <svg viewBox="0 0 24 24"><path d="M15 3H6c-.83 0-1.54.5-1.84 1.22l-3.02 7.05c-.09.23-.14.47-.14.73v2c0 1.1.9 2 2 2h6.31l-.95 4.57-.03.32c0 .41.17.79.44 1.06L9.83 23l6.59-6.59c.36-.36.58-.86.58-1.41V5c0-1.1-.9-2-2-2zm4 0v12h4V3h-4z"/></svg>
                                        <span>0</span>
                                    </button>
                                    <button class="comment-btn reply reply-btn" data-comment-id="${rootId}" data-reply-to-id="${c.id}" data-reply-to-user="${esc(c.user.username)}" data-post-id="${composerPostId.value}">
                                        <svg viewBox="0 0 24 24"><path d="M10 9V5l-7 7 7 7v-4.1c5 0 8.5 1.6 11 5.1-1-5-4-10-11-11z"/></svg>
                                        <span>{{ __('posts.reply') }}</span>
                                    </button>
                                    ${c.can_update ? `<button class="comment-btn edit edit-comment-btn" data-comment-id="${c.id}">
                                        <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34a.9959.9959 0 00-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                                        <span>{{ __('posts.edit') }}</span>
                                    </button>` : ''}
                                    ${c.can_delete ? `<form action="/comments/${c.id}" method="POST" class="inline-delete">
                                        <input type="hidden" name="_token" value="${csrfToken}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="comment-btn delete">
                                            <svg viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                                            <span>{{ __('posts.delete') }}</span>
                                        </button>
                                    </form>` : ''}
                                </div>
                            </div>
                        </div>`;

                    const postId = composerPostId.value;
                    const commentsSection = document.getElementById('comments-' + postId);
                    if (commentsSection) {
                        commentsSection.style.display = 'block';
                        // Hide empty state
                        const emptyEl = document.getElementById('comments-empty-' + postId);
                        if (emptyEl) emptyEl.style.display = 'none';
                        if (c.parent_id) {
                            // Insert into flat replies container
                            let repliesEl = document.getElementById('replies-' + c.parent_id);
                            if (!repliesEl) {
                                // Create replies container + toggle button
                                const parentComment = document.getElementById('comment-' + c.parent_id);
                                if (parentComment) {
                                    const toggleWrap = document.createElement('div');
                                    toggleWrap.className = 'replies-toggle-wrap';
                                    toggleWrap.innerHTML = `<button class="replies-toggle expanded" data-comment-id="${c.parent_id}"><span class="replies-toggle-line"></span><span class="replies-toggle-text">1 {{ __('posts.reply_singular') }}</span></button>`;
                                    parentComment.after(toggleWrap);
                                    repliesEl = document.createElement('div');
                                    repliesEl.className = 'comment-replies';
                                    repliesEl.id = 'replies-' + c.parent_id;
                                    toggleWrap.after(repliesEl);
                                }
                            }
                            if (repliesEl) {
                                repliesEl.style.display = 'block';
                                repliesEl.insertAdjacentHTML('beforeend', commentHtml);
                                // Update toggle count
                                const toggleBtn = document.querySelector(`.replies-toggle[data-comment-id="${c.parent_id}"]`);
                                if (toggleBtn) {
                                    const count = repliesEl.querySelectorAll('.comment-item').length;
                                    toggleBtn.querySelector('.replies-toggle-text').textContent = count + ' ' + (count === 1 ? '{{ __("posts.reply_singular") }}' : '{{ __("posts.replies") }}');
                                    toggleBtn.classList.add('expanded');
                                }
                            }
                        } else {
                            commentsSection.querySelector('.comments-list')?.insertAdjacentHTML('beforeend', commentHtml);
                        }
                    }

                    // Update comment count
                    const toggle = document.querySelector(`.comment-toggle[data-post-id="${postId}"]`);
                    if (toggle) {
                        const countSpan = toggle.querySelector('span');
                        if (countSpan) countSpan.textContent = parseInt(countSpan.textContent || '0') + 1;
                    }

                    setComposerMode('post');
                } else {
                    window.toast?.error(data.message || 'Error');
                }
            } catch (err) {
                window.toast?.error('Failed to submit');
            }
        }
    });

    // === User Search ===
    const searchInput = document.getElementById('user-search-input');
    const searchResults = document.getElementById('user-search-results');
    let searchTimeout = null;

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const q = this.value.trim();
            if (q.length < 2) {
                searchResults.innerHTML = '';
                return;
            }
            searchResults.innerHTML = '<div class="search-loading">{{ __("posts.searching") }}...</div>';
            searchTimeout = setTimeout(async () => {
                try {
                    const res = await fetch('/posts/search-users?q=' + encodeURIComponent(q), {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const users = await res.json();
                    if (users.length === 0) {
                        searchResults.innerHTML = '<p class="sidebar-empty">{{ __("posts.no_results") }}</p>';
                        return;
                    }
                    searchResults.innerHTML = users.map(u => `
                        <a href="${u.url}" class="search-result-item">
                            <img src="${u.avatar}" alt="" class="search-result-avatar">
                            <div class="search-result-info">
                                <span class="search-result-name">${u.name}</span>
                                <span class="search-result-username">@${u.username}</span>
                            </div>
                            ${u.online ? '<span class="search-result-online"></span>' : ''}
                        </a>
                    `).join('');
                } catch {
                    searchResults.innerHTML = '';
                }
            }, 300);
        });
    }

    // Trending post click - scroll to post
    document.querySelectorAll('.trending-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const postId = this.dataset.postId;
            const postEl = document.getElementById('post-' + postId);
            if (postEl) {
                postEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                postEl.style.boxShadow = '0 0 0 2px var(--primary)';
                setTimeout(() => postEl.style.boxShadow = '', 2000);
            }
        });
    });
});
</script>
@endsection
