@extends('layouts.app')

@section('title', $user->name . ' — FitLife')

@section('hide-mobile-nav', '1')

@section('content')
<div class="sp-page">

    <div class="sp-header-card">
        <div class="sp-banner">
            @if($user->banner)
                <img src="{{ asset('storage/' . $user->banner) }}" alt="" class="sp-banner__img">
            @endif
            <div class="sp-banner__overlay"></div>
        </div>

        <div class="sp-header-body">
            <div class="sp-avatar">
                <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('storage/default-avatar/default-avatar.avif') }}"
                     alt="{{ $user->name }}">
            </div>

            <div class="sp-header-info">
                <div class="sp-name-row">
                    <h1 class="sp-name">{{ $user->name }}</h1>
                    @if(Auth::id() === $user->id)
                        <a href="{{ route('profile.edit') }}" class="sp-btn sp-btn--outline">{{ __('profile.edit_profile') }}</a>
                    @else
                        <form action="{{ route('follow.toggle', $user) }}" method="POST" style="margin:0">
                            @csrf
                            @if(Auth::user()->isFollowing($user))
                                <button class="sp-btn sp-btn--outline">{{ __('profile.following') }}</button>
                            @else
                                <button class="sp-btn sp-btn--primary">{{ __('profile.follow') }}</button>
                            @endif
                        </form>
                        @if(Auth::user()->isMutualFollow($user))
                            <a href="{{ route('conversations.start', $user) }}" class="sp-btn sp-btn--outline" onclick="event.preventDefault(); document.getElementById('start-dm-{{ $user->id }}').submit();">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" style="vertical-align:middle;margin-right:4px"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                {{ __('messages.send_message') }}
                            </a>
                            <form id="start-dm-{{ $user->id }}" action="{{ route('conversations.start', $user) }}" method="POST" style="display:none">@csrf</form>
                        @endif
                    @endif
                </div>
                <span class="sp-username">{{ '@' . $user->username }}</span>

                <div class="sp-stats-row">
                    <div class="sp-stat">
                        <span class="sp-stat__value">{{ $user->posts_count ?? $user->posts->count() }}</span>
                        <span class="sp-stat__label">{{ __('profile.posts_count') }}</span>
                    </div>
                    <a href="{{ route('follow.followers', $user) }}" class="sp-stat sp-stat--link">
                        <span class="sp-stat__value">{{ $user->followers_count ?? $user->followers->count() }}</span>
                        <span class="sp-stat__label">{{ __('profile.followers') }}</span>
                    </a>
                    <a href="{{ route('follow.following', $user) }}" class="sp-stat sp-stat--link">
                        <span class="sp-stat__value">{{ $user->followings_count ?? $user->followings->count() }}</span>
                        <span class="sp-stat__label">{{ __('profile.following_label') }}</span>
                    </a>
                </div>

                @if($user->bio)
                    <p class="sp-bio">{{ $user->bio }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="sp-tabs">
        <button class="sp-tab active" data-sp-tab="posts">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            {{ __('profile.posts_count') }}
        </button>
        <button class="sp-tab" data-sp-tab="about">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            {{ __('profile.about') }}
        </button>
    </div>

    <div class="sp-tab-content active" id="sptab-posts">
        @forelse($user->posts()->latest()->get() as $post)
            <article class="post-card" id="post-{{ $post->id }}">
                <div class="post-content">
                    <div class="post-meta">
                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('storage/default-avatar/default-avatar.avif') }}"
                             alt="{{ $user->name }}" class="post-author-avatar">
                        <a href="{{ route('profile.show', $user) }}" class="post-author">{{ $user->name }}</a>
                        <span class="post-dot">&middot;</span>
                        <span class="post-username">{{ '@' . $user->username }}</span>
                        <span class="post-dot">&middot;</span>
                        <span class="post-time">{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="post-text"><p>{{ $post->content }}</p></div>
                    @if($post->media_path)
                        <div class="post-media">
                            @if($post->media_type === 'image')
                                <img src="{{ asset('storage/' . $post->media_path) }}" alt="" loading="lazy" />
                            @elseif($post->media_type === 'video')
                                <video src="{{ asset('storage/' . $post->media_path) }}" controls></video>
                            @endif
                        </div>
                    @elseif($post->photo_path)
                        <div class="post-media">
                            <img src="{{ asset('storage/' . $post->photo_path) }}" alt="" loading="lazy" />
                        </div>
                    @endif
                    <div class="post-actions">
                        <span class="post-action" style="cursor:default">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                            <span>{{ $post->postViews()->count() }}</span>
                        </span>
                    </div>
                </div>
            </article>
        @empty
            <div class="sp-empty">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                <p>{{ __('profile.no_posts_yet') }}</p>
            </div>
        @endforelse
    </div>

    <div class="sp-tab-content" id="sptab-about">
        <div class="sp-about-grid">
            <div class="sp-card">
                <div class="sp-card__header">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <h3>{{ __('profile.profile_details') }}</h3>
                </div>
                <div class="sp-detail-list">
                    <div class="sp-detail"><span class="sp-detail__label">{{ __('profile.full_name') }}</span><span class="sp-detail__value">{{ $user->full_name ?? __('profile.not_set') }}</span></div>
                    <div class="sp-detail"><span class="sp-detail__label">{{ __('profile.age') }}</span><span class="sp-detail__value">{{ $user->age ?? __('profile.not_set') }}</span></div>
                    <div class="sp-detail"><span class="sp-detail__label">{{ __('profile.height') }}</span><span class="sp-detail__value">{{ $user->height ? $user->height . ' ' . __('profile.cm') : __('profile.not_set') }}</span></div>
                    <div class="sp-detail"><span class="sp-detail__label">{{ __('profile.weight') }}</span><span class="sp-detail__value">{{ $user->weight ? $user->weight . ' ' . __('profile.kg') : __('profile.not_set') }}</span></div>
                    <div class="sp-detail"><span class="sp-detail__label">{{ __('profile.gender') }}</span><span class="sp-detail__value">{{ $user->gender ?? __('profile.not_set') }}</span></div>
                </div>
            </div>

            <div class="sp-card">
                <div class="sp-card__header">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/></svg>
                    <h3>{{ __('profile.fitness_stats') }}</h3>
                </div>
                <div class="sp-mini-stats">
                    <div class="sp-mini-stat">
                        <span class="sp-mini-stat__value">{{ $user->goals()->count() }}</span>
                        <span class="sp-mini-stat__label">{{ __('profile.goals') }}</span>
                    </div>
                    <div class="sp-mini-stat">
                        <span class="sp-mini-stat__value">{{ $user->progress()->count() }}</span>
                        <span class="sp-mini-stat__label">{{ __('profile.photos') }}</span>
                    </div>
                    <div class="sp-mini-stat">
                        <span class="sp-mini-stat__value">{{ $subscriptionsCount }}</span>
                        <span class="sp-mini-stat__label">{{ __('profile.subscriptions_count') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script src="{{ asset('js/profile.js') }}"></script>
@endsection
@endsection
