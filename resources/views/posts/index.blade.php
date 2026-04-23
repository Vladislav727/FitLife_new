@extends('layouts.app')

@section('hide-mobile-nav', '1')
@section('flush-mobile-content', '1')

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
                        <a href="{{ route('profile.show', $post->user->username) }}" class="post-author">
                            <img src="{{ $post->user->avatar ? asset('storage/' . $post->user->avatar) : asset('storage/default-avatar/default-avatar.avif') }}" alt="" class="post-avatar">
                            <div class="post-author-info">
                                <span class="post-author-name">{{ $post->user->name }}</span>
                                <span class="post-meta">{{ '@' . $post->user->username }} · {{ $post->created_at->diffForHumans() }}@if($post->updated_at->gt($post->created_at)) <span class="post-edited-badge" id="post-edited-{{ $post->id }}">· {{ __('posts.edited') }}</span>@else<span class="post-edited-badge" id="post-edited-{{ $post->id }}" style="display:none"> · {{ __('posts.edited') }}</span>@endif</span>
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
                                    <div class="comment-replies" id="replies-{{ $comment->id }}" style="display: none;" data-total="{{ $comment->replies->count() }}">
                                        @foreach($comment->replies as $reply)
                                            @include('posts.partials.comment', ['comment' => $reply, 'post' => $post, 'hidden' => $loop->index >= 5])
                                        @endforeach
                                        @if($comment->replies->count() > 5)
                                            <button class="replies-load-more" data-comment-id="{{ $comment->id }}" data-remaining="{{ $comment->replies->count() - 5 }}">
                                                {{ __('posts.load_more_replies', ['count' => $comment->replies->count() - 5]) }}
                                            </button>
                                        @endif
                                        <button class="replies-collapse-btn" data-comment-id="{{ $comment->id }}">
                                            <svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor"><path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z"/></svg>
                                            {{ __('posts.collapse_replies') }}
                                        </button>
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
        </aside>
    </div>

    {{-- Floating Composer Island --}}
    <div class="composer-island" id="composer-island">
        <div class="composer-context" id="composer-context">
            <span class="composer-context-text" id="composer-context-text"></span>
            <button type="button" class="composer-context-close" id="composer-context-close">&times;</button>
        </div>
        <form id="post-form" action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="parent_id" id="composer-parent-id" value="">
            <input type="hidden" name="reply_to_id" id="composer-reply-to-id" value="">
            <input type="hidden" id="composer-post-id" value="">
            <input type="hidden" id="composer-mode" value="post">
            <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('storage/default-avatar/default-avatar.avif') }}" alt="" class="composer-avatar">
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
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('css/posts-styles.css') }}">
@endsection

@section('scripts')
<script src="{{ asset('js/posts-composer.js') }}"></script>
@endsection