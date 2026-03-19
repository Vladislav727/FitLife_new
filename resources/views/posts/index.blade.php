@extends('layouts.app')

@section('content')
<div class="community-page">
    <!-- Main Content -->
    <div class="community-main">
        @if (session('success'))
            <script>document.addEventListener('DOMContentLoaded', () => window.toast?.success('{{ session('success') }}'));</script>
        @endif
        @if (session('error'))
            <script>document.addEventListener('DOMContentLoaded', () => window.toast?.error('{{ session('error') }}'));</script>
        @endif

        <!-- Create Post Section -->
        <section class="create-post">
            <form id="post-form" action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @error('content') <script>document.addEventListener('DOMContentLoaded', () => window.toast?.error('{{ $message }}'));</script> @enderror
                @error('photo') <script>document.addEventListener('DOMContentLoaded', () => window.toast?.error('{{ $message }}'));</script> @enderror
                @error('video') <script>document.addEventListener('DOMContentLoaded', () => window.toast?.error('{{ $message }}'));</script> @enderror
                
                <div class="create-post-header">
                    <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('storage/logo/defaultPhoto.jpg') }}" 
                         alt="{{ Auth::user()->name }}" class="create-post-avatar">
                    <textarea name="content" placeholder="{{ __('posts.whats_on_your_mind') }}" rows="3" maxlength="1000"></textarea>
                </div>
                
                <div class="preview-container" style="display: none;">
                    <img id="image-preview" alt="{{ __('posts.image_preview') }}" style="display: none;" />
                    <video id="video-preview" controls style="display: none;"></video>
                    <button id="remove-media" type="button" style="display: none;">×</button>
                </div>
                
                <div class="create-footer">
                    <div class="left-controls">
                        <label class="file-label" title="{{ __('posts.attach_photo') }}">
                            <input type="file" name="photo" accept="image/*" id="post-photo">
                            <svg viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                        </label>
                        <label class="file-label" title="{{ __('posts.attach_video') }}">
                            <input type="file" name="video" accept="video/mp4,video/mpeg,video/ogg,video/webm" id="post-video">
                            <svg viewBox="0 0 24 24"><path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/></svg>
                        </label>
                        <div class="emoji-picker-wrapper">
                            <button type="button" class="file-label emoji-trigger" title="{{ __('posts.add_emoji') ?? 'Add emoji' }}">
                                <svg viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/></svg>
                            </button>
                            <div class="emoji-panel" style="display: none;">
                                <div class="emoji-grid">
                                    <button type="button" class="emoji-btn">💪</button>
                                    <button type="button" class="emoji-btn">🏃</button>
                                    <button type="button" class="emoji-btn">🏋️</button>
                                    <button type="button" class="emoji-btn">🥗</button>
                                    <button type="button" class="emoji-btn">🔥</button>
                                    <button type="button" class="emoji-btn">💯</button>
                                    <button type="button" class="emoji-btn">🎯</button>
                                    <button type="button" class="emoji-btn">⭐</button>
                                    <button type="button" class="emoji-btn">🏆</button>
                                    <button type="button" class="emoji-btn">💥</button>
                                    <button type="button" class="emoji-btn">❤️</button>
                                    <button type="button" class="emoji-btn">👏</button>
                                    <button type="button" class="emoji-btn">🙌</button>
                                    <button type="button" class="emoji-btn">✨</button>
                                    <button type="button" class="emoji-btn">🚀</button>
                                    <button type="button" class="emoji-btn">😊</button>
                                    <button type="button" class="emoji-btn">😎</button>
                                    <button type="button" class="emoji-btn">🤝</button>
                                    <button type="button" class="emoji-btn">👍</button>
                                    <button type="button" class="emoji-btn">🎉</button>
                                </div>
                            </div>
                        </div>
                        <span class="char-count" id="post-char-count">0/1000</span>
                    </div>
                    <button type="submit" class="btn">
                        <svg viewBox="0 0 24 24" width="18" height="18"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" fill="currentColor"/></svg>
                        {{ __('posts.post_button') }}
                    </button>
                </div>
            </form>
        </section>

        <!-- Sort Bar -->
        <div class="sort-bar">
            <button class="sort-btn active">
                <svg viewBox="0 0 24 24"><path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/></svg>
                {{ __('posts.newest') ?? 'New' }}
            </button>
            <button class="sort-btn">
                <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                {{ __('posts.top') ?? 'Top' }}
            </button>
            <button class="sort-btn">
                <svg viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                {{ __('posts.hot') ?? 'Hot' }}
            </button>
        </div>

        <!-- Posts Feed -->
        <section class="posts-feed">
            @forelse($posts as $post)
            <article class="post-card" id="post-{{ $post->id }}" data-post-id="{{ $post->id }}">
                <!-- Vote Column -->
                <div class="post-votes">
                    <button class="vote-btn upvote {{ Auth::check() && $post->isLikedBy(Auth::id()) ? 'active' : '' }}" data-post-id="{{ $post->id }}">
                        <svg viewBox="0 0 24 24"><path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z"/></svg>
                    </button>
                    <span class="vote-count">{{ $post->likes()->where('type', 'post')->where('is_like', true)->count() - $post->likes()->where('type', 'post')->where('is_like', false)->count() }}</span>
                    <button class="vote-btn downvote {{ Auth::check() && $post->isDislikedBy(Auth::id()) ? 'active' : '' }}" data-post-id="{{ $post->id }}">
                        <svg viewBox="0 0 24 24"><path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6z"/></svg>
                    </button>
                </div>

                <!-- Post Content -->
                <div class="post-content">
                    <div class="post-meta">
                        <img src="{{ $post->user->avatar ? asset('storage/' . $post->user->avatar) : asset('storage/logo/defaultPhoto.jpg') }}" 
                             alt="{{ $post->user->name }}" class="post-author-avatar">
                        <a href="{{ route('profile.show', $post->user->id) }}" class="post-author">{{ $post->user->name }}</a>
                        <span class="post-dot">•</span>
                        <span class="post-username">{{ '@' . $post->user->username }}</span>
                        <span class="post-dot">•</span>
                        <span class="post-time">{{ $post->created_at->diffForHumans() }}</span>
                    </div>

                    <div class="post-text" id="post-body-{{ $post->id }}">
                        <p data-updated-at="{{ $post->updated_at->toISOString() }}">{{ $post->content }}</p>
                    </div>

                    @if($post->media_path)
                        <div class="post-media">
                            @if($post->media_type === 'image')
                                <img src="{{ asset('storage/' . $post->media_path) }}" alt="Post image" loading="lazy" />
                            @elseif($post->media_type === 'video')
                                <video src="{{ asset('storage/' . $post->media_path) }}" controls></video>
                            @endif
                        </div>
                    @endif

                    <!-- Post Actions -->
                    <div class="post-actions">
                        <button class="post-action comments comment-toggle" data-post-id="{{ $post->id }}" data-count="{{ $post->allComments()->count() }}">
                            <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            <span class="comment-count">{{ $post->allComments()->count() }}</span> {{ __('posts.comments') }}
                        </button>
                        <button class="post-action share" data-post-id="{{ $post->id }}">
                            <svg viewBox="0 0 24 24"><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92s2.92-1.31 2.92-2.92-1.31-2.92-2.92-2.92z"/></svg>
                            <span>{{ __('posts.share') ?? 'Share' }}</span>
                        </button>
                        <span class="post-action" style="cursor: default;">
                            <svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                            <span>{{ $post->postViews()->count() }}</span>
                        </span>
                        @can('update', $post)
                            <button class="post-action edit edit-post-btn" data-post-id="{{ $post->id }}">
                                <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34a.9959.9959 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                                <span>{{ __('posts.edit') }}</span>
                            </button>
                        @endcan
                        @can('delete', $post)
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display: inline;" class="delete-post-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="post-action delete">
                                    <svg viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                                    <span>{{ __('posts.delete') }}</span>
                                </button>
                            </form>
                        @endcan
                    </div>

                    <!-- Edit Post Form (hidden) -->
                    <form id="edit-post-form-{{ $post->id }}" action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data" class="edit-post-form">
                        @csrf
                        @method('PUT')
                        <textarea name="content" maxlength="1000">{{ $post->content }}</textarea>
                        <div class="preview-container" style="position: relative;">
                            @if($post->media_path && $post->media_type === 'image')
                                <img id="edit-image-preview-{{ $post->id }}" src="{{ asset('storage/' . $post->media_path) }}" alt="{{ __('posts.image_preview') }}" />
                            @else
                                <img id="edit-image-preview-{{ $post->id }}" alt="{{ __('posts.image_preview') }}" style="display: none;" />
                            @endif
                            @if($post->media_path && $post->media_type === 'video')
                                <video id="edit-video-preview-{{ $post->id }}" src="{{ asset('storage/' . $post->media_path) }}" controls></video>
                            @else
                                <video id="edit-video-preview-{{ $post->id }}" controls style="display: none;"></video>
                            @endif
                            <button type="button" class="remove-media" data-post-id="{{ $post->id }}" style="display: {{ $post->media_path ? 'flex' : 'none' }};">×</button>
                        </div>
                        <div class="edit-post-tools">
                            <label class="file-label">
                                <input type="file" name="photo" accept="image/*" class="edit-post-photo">
                                <svg viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                            </label>
                            <label class="file-label">
                                <input type="file" name="video" accept="video/mp4,video/mpeg,video/ogg,video/webm" class="edit-post-video">
                                <svg viewBox="0 0 24 24"><path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/></svg>
                            </label>
                        </div>
                        <div class="edit-post-btns">
                            <button type="submit" class="edit-save-btn">{{ __('posts.save') }}</button>
                            <button type="button" class="edit-cancel-btn cancel-edit" data-post-id="{{ $post->id }}">{{ __('posts.cancel') }}</button>
                        </div>
                    </form>

                    <!-- Comments Section -->
                    <div class="comments-section" id="comments-{{ $post->id }}" style="display: none;">
                        <form action="{{ route('posts.comment', $post) }}" method="POST" class="comment-form" data-post-id="{{ $post->id }}">
                            @csrf
                            <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('storage/logo/defaultPhoto.jpg') }}" 
                                 alt="{{ Auth::user()->name }}" class="comment-form-avatar">
                            <div class="comment-form-input">
                                <textarea name="content" placeholder="{{ __('posts.write_comment') }}" maxlength="500"></textarea>
                                <div class="comment-form-actions">
                                    <button type="submit" class="comment-submit">{{ __('posts.comment_btn') ?? 'Comment' }}</button>
                                </div>
                            </div>
                        </form>
                        <div class="comments-list">
                            @foreach($post->comments as $comment)
                                @include('posts.partials.comment', ['comment' => $comment, 'post' => $post])
                            @endforeach
                        </div>
                    </div>
                </div>
            </article>
            @empty
                <div class="empty-posts">
                    <div class="empty-posts-icon">
                        <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
                    </div>
                    <h3 class="empty-posts-title">{{ __('posts.no_posts_available') }}</h3>
                    <p class="empty-posts-text">{{ __('posts.be_first') ?? 'Be the first to share something!' }}</p>
                </div>
            @endforelse
        </section>

        <!-- Pagination -->
        <div class="pagination-wrapper">
            {{ $posts->links() }}
        </div>
    </div>

    <!-- Sidebar -->
    <aside class="community-sidebar">
        <!-- About Community -->
        <div class="sidebar-card">
            <div class="sidebar-card-header">About Community</div>
            <div class="sidebar-card-body">
                <h3 class="sidebar-card-title">FitLife Community</h3>
                <p class="sidebar-card-desc">Share your fitness journey, get motivated, and connect with others who share your passion for health and wellness.</p>
                
                <div class="sidebar-stats">
                    <div class="sidebar-stat">
                        <div class="sidebar-stat-value">{{ \App\Models\User::count() }}</div>
                        <div class="sidebar-stat-label">Members</div>
                    </div>
                    <div class="sidebar-stat">
                        <div class="sidebar-stat-value">{{ \App\Models\Post::count() }}</div>
                        <div class="sidebar-stat-label">Posts</div>
                    </div>
                </div>
                
                <div class="sidebar-created">
                    <svg viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 002 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11zM9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm-8 4H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2z"/></svg>
                    <span>Created Jan 2024</span>
                </div>
            </div>
        </div>

        <!-- Community Rules -->
        <div class="sidebar-card">
            <div class="sidebar-card-header">Community Rules</div>
            <div class="sidebar-card-body">
                <ul class="community-rules">
                    <li class="community-rule">
                        <span class="rule-number">1</span>
                        <span class="rule-text">Be respectful and supportive of others</span>
                    </li>
                    <li class="community-rule">
                        <span class="rule-number">2</span>
                        <span class="rule-text">No spam or self-promotion</span>
                    </li>
                    <li class="community-rule">
                        <span class="rule-number">3</span>
                        <span class="rule-text">Share accurate fitness information</span>
                    </li>
                    <li class="community-rule">
                        <span class="rule-number">4</span>
                        <span class="rule-text">Use appropriate content warnings</span>
                    </li>
                    <li class="community-rule">
                        <span class="rule-number">5</span>
                        <span class="rule-text">Keep posts fitness-related</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Top Contributors -->
        <div class="sidebar-card">
            <div class="sidebar-card-header">Top Contributors</div>
            <div class="sidebar-card-body">
                <div class="top-users">
                    @php
                        $topUsers = \App\Models\User::withCount('posts')
                            ->orderByDesc('posts_count')
                            ->take(5)
                            ->get();
                    @endphp
                    @foreach($topUsers as $index => $user)
                        <a href="{{ route('profile.show', $user->id) }}" class="top-user">
                            <span class="top-user-rank {{ $index === 0 ? 'gold' : ($index === 1 ? 'silver' : ($index === 2 ? 'bronze' : '')) }}">{{ $index + 1 }}</span>
                            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('storage/logo/defaultPhoto.jpg') }}" 
                                 alt="{{ $user->name }}" class="top-user-avatar">
                            <div class="top-user-info">
                                <div class="top-user-name">{{ $user->name }}</div>
                                <div class="top-user-posts">{{ $user->posts_count }} posts</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </aside>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/posts.js') }}?v={{ time() }}"></script>
@endsection
