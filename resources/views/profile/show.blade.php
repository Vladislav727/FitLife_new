@extends('layouts.app')

@section('title', $user->name . ' — FitLife')

@section('content')
<div class="user-profile-page">

    {{-- Back Link --}}
    <a href="{{ route('posts.index') }}" class="up-back-link">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        {{ __('profile.back_to_community') ?? __('nav.community') }}
    </a>

    {{-- Profile Header Card --}}
    <div class="up-profile-card">
        <div class="up-banner">
            @if($user->banner)
                <img src="{{ asset('storage/' . $user->banner) }}" alt="" class="up-banner__img">
            @endif
            <div class="up-banner__overlay"></div>
        </div>

        <div class="up-profile-info">
            <div class="up-avatar">
                <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('storage/logo/defaultPhoto.jpg') }}"
                     alt="{{ $user->name }}">
            </div>

            <div class="up-details">
                <div class="up-name-section">
                    <h1 class="up-name">{{ $user->name }}</h1>
                    <span class="up-username">{{ '@' . $user->username }}</span>
                </div>

                {{-- Friend Actions --}}
                @if (Auth::user() && Auth::id() != $user->id)
                    <div class="up-actions">
                        @if (Auth::user()->friends->contains($user->id))
                            <span class="up-friend-badge">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 6L9 17l-5-5"/>
                                </svg>
                                {{ __('profile.you_are_friends') }}
                            </span>
                            <form action="{{ route('friends.remove', $user->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="up-btn up-btn--danger">{{ __('profile.remove_from_friends') }}</button>
                            </form>
                        @elseif (\App\Models\Friend::where('user_id', $user->id)->where('friend_id', Auth::id())->where('status', 'pending')->exists())
                            <span class="up-friend-badge up-friend-badge--pending">
                                {{ __('profile.friend_request_received') }}
                            </span>
                            <form action="{{ route('friends.accept', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="up-btn up-btn--primary">{{ __('profile.accept_request') }}</button>
                            </form>
                        @else
                            <form action="{{ route('friends.store', $user->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="friend_id" value="{{ $user->id }}">
                                <button type="submit" class="up-btn up-btn--primary">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                        <circle cx="8.5" cy="7" r="4"/>
                                        <line x1="20" y1="8" x2="20" y2="14"/>
                                        <line x1="23" y1="11" x2="17" y2="11"/>
                                    </svg>
                                    {{ __('profile.add_friend') }}
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Content Grid --}}
    <div class="up-grid">

        {{-- Sidebar - Bio Info --}}
        <aside class="up-sidebar">
            <div class="up-card">
                <div class="up-card__header">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    <h3>{{ __('profile.profile_details') }}</h3>
                </div>
                <div class="up-bio-list">
                    <div class="up-bio-item">
                        <span class="up-bio-label">{{ __('profile.full_name') }}</span>
                        <span class="up-bio-value">{{ $user->biography?->full_name ?? __('profile.not_set') }}</span>
                    </div>
                    <div class="up-bio-item">
                        <span class="up-bio-label">{{ __('profile.age') }}</span>
                        <span class="up-bio-value">{{ $user->biography?->age ?? __('profile.not_set') }}</span>
                    </div>
                    <div class="up-bio-item">
                        <span class="up-bio-label">{{ __('profile.height') }}</span>
                        <span class="up-bio-value">{{ $user->biography?->height ? $user->biography->height . ' ' . __('profile.cm') : __('profile.not_set') }}</span>
                    </div>
                    <div class="up-bio-item">
                        <span class="up-bio-label">{{ __('profile.weight') }}</span>
                        <span class="up-bio-value">{{ $user->biography?->weight ? $user->biography->weight . ' ' . __('profile.kg') : __('profile.not_set') }}</span>
                    </div>
                    <div class="up-bio-item">
                        <span class="up-bio-label">{{ __('profile.gender') }}</span>
                        <span class="up-bio-value">{{ $user->biography?->gender ?? __('profile.not_set') }}</span>
                    </div>
                </div>
            </div>

            {{-- Stats Card --}}
            <div class="up-card">
                <div class="up-card__header">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="20" x2="12" y2="10"/>
                        <line x1="18" y1="20" x2="18" y2="4"/>
                        <line x1="6" y1="20" x2="6" y2="16"/>
                    </svg>
                    <h3>{{ __('profile.stats') ?? 'Stats' }}</h3>
                </div>
                <div class="up-stats">
                    <div class="up-stat-item">
                        <span class="up-stat-value">{{ $user->posts->count() }}</span>
                        <span class="up-stat-label">{{ __('profile.posts_count') ?? 'Posts' }}</span>
                    </div>
                    <div class="up-stat-item">
                        <span class="up-stat-value">{{ $user->friends->count() }}</span>
                        <span class="up-stat-label">{{ __('profile.friends_count') ?? 'Friends' }}</span>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main - Posts --}}
        <div class="up-main">
            <div class="up-card up-card--flat">
                <div class="up-card__header">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                    <h3>{{ __('profile.user_posts', ['name' => $user->name]) }}</h3>
                </div>
            </div>

            <div class="up-posts-feed">
                @forelse($user->posts as $post)
                    <article class="post-card" id="post-{{ $post->id }}" data-post-id="{{ $post->id }}">
                        <div class="post-content">
                            <div class="post-meta">
                                <img src="{{ $post->user->avatar ? asset('storage/' . $post->user->avatar) : asset('storage/logo/defaultPhoto.jpg') }}"
                                     alt="{{ $post->user->name }}" class="post-author-avatar">
                                <a href="{{ route('profile.show', $post->user->id) }}" class="post-author">{{ $post->user->name }}</a>
                                <span class="post-dot">&middot;</span>
                                <span class="post-username">{{ '@' . $post->user->username }}</span>
                                <span class="post-dot">&middot;</span>
                                <span class="post-time">{{ $post->created_at->diffForHumans() }}</span>
                            </div>

                            <div class="post-text">
                                <p>{{ $post->content }}</p>
                            </div>

                            @if($post->media_path)
                                <div class="post-media">
                                    @if($post->media_type === 'image')
                                        <img src="{{ asset('storage/' . $post->media_path) }}" alt="Post image" loading="lazy" />
                                    @elseif($post->media_type === 'video')
                                        <video src="{{ asset('storage/' . $post->media_path) }}" controls></video>
                                    @endif
                                </div>
                            @elseif($post->photo_path)
                                <div class="post-media">
                                    <img src="{{ asset('storage/' . $post->photo_path) }}" alt="Post image" loading="lazy" />
                                </div>
                            @endif

                            <div class="post-actions">
                                <span class="post-action" style="cursor: default;">
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                                    <span>{{ $post->postViews()->count() }}</span>
                                </span>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="up-empty-state">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                        <p>{{ __('profile.no_posts_yet') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection