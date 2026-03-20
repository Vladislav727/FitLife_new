@extends('layouts.app')

@section('title', $user->name . ' — FitLife')

@section('content')
<div class="sp-page">

    {{-- Profile Header Card --}}
    <div class="sp-header-card">
        <div class="sp-banner">
            @if($user->banner)
                <img src="{{ asset('storage/' . $user->banner) }}" alt="" class="sp-banner__img">
            @endif
            <div class="sp-banner__overlay"></div>
        </div>

        <div class="sp-header-body">
            <div class="sp-avatar">
                <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('storage/logo/defaultPhoto.jpg') }}"
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

                {{-- Stats Row --}}
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

                {{-- Bio --}}
                @if($user->bio)
                    <p class="sp-bio">{{ $user->bio }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Content Tabs --}}
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

    {{-- Posts Tab --}}
    <div class="sp-tab-content active" id="sptab-posts">
        @forelse($user->posts()->latest()->get() as $post)
            <article class="post-card" id="post-{{ $post->id }}">
                <div class="post-content">
                    <div class="post-meta">
                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('storage/logo/defaultPhoto.jpg') }}"
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

    {{-- About Tab --}}
    <div class="sp-tab-content" id="sptab-about">
        <div class="sp-about-grid">
            <div class="sp-card">
                <div class="sp-card__header">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <h3>{{ __('profile.profile_details') }}</h3>
                </div>
                <div class="sp-detail-list">
                    <div class="sp-detail"><span class="sp-detail__label">{{ __('profile.full_name') }}</span><span class="sp-detail__value">{{ $user->biography?->full_name ?? __('profile.not_set') }}</span></div>
                    <div class="sp-detail"><span class="sp-detail__label">{{ __('profile.age') }}</span><span class="sp-detail__value">{{ $user->biography?->age ?? __('profile.not_set') }}</span></div>
                    <div class="sp-detail"><span class="sp-detail__label">{{ __('profile.height') }}</span><span class="sp-detail__value">{{ $user->biography?->height ? $user->biography->height . ' ' . __('profile.cm') : __('profile.not_set') }}</span></div>
                    <div class="sp-detail"><span class="sp-detail__label">{{ __('profile.weight') }}</span><span class="sp-detail__value">{{ $user->biography?->weight ? $user->biography->weight . ' ' . __('profile.kg') : __('profile.not_set') }}</span></div>
                    <div class="sp-detail"><span class="sp-detail__label">{{ __('profile.gender') }}</span><span class="sp-detail__value">{{ $user->biography?->gender ?? __('profile.not_set') }}</span></div>
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
                        <span class="sp-mini-stat__value">{{ $user->friends->count() }}</span>
                        <span class="sp-mini-stat__label">{{ __('profile.friends_count') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.sp-page { max-width: 680px; margin: 0 auto; }
.sp-header-card {
    background: var(--bg-surface); border: 1px solid var(--border-subtle);
    border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 0;
}
.sp-banner { height: 200px; position: relative; background: linear-gradient(135deg, var(--bg-elevated) 0%, var(--bg-surface) 100%); }
.sp-banner__img { width: 100%; height: 100%; object-fit: cover; }
.sp-banner__overlay { position: absolute; inset: 0; background: linear-gradient(to bottom, transparent 50%, rgba(0,0,0,0.6) 100%); }
.sp-header-body { padding: 0 24px 24px; position: relative; }
.sp-avatar {
    width: 120px; height: 120px; border-radius: 50%;
    border: 4px solid var(--bg-surface); overflow: hidden;
    margin-top: -60px; margin-bottom: 12px; background: var(--bg-elevated);
}
.sp-avatar img { width: 100%; height: 100%; object-fit: cover; }
.sp-name-row { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
.sp-name { font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin: 0; }
.sp-username { font-size: 0.9375rem; color: var(--text-muted); display: block; margin: 2px 0 14px; }
.sp-bio { color: var(--text-secondary); font-size: 0.9375rem; line-height: 1.5; margin-top: 8px; }
.sp-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 20px; border-radius: 10px; font-size: 14px; font-weight: 600;
    font-family: inherit; cursor: pointer; transition: all 0.2s; border: none;
}
.sp-btn--primary {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    color: #000; box-shadow: 0 4px 16px rgba(34,197,94,0.25);
}
.sp-btn--primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(34,197,94,0.35); }
.sp-btn--outline { background: transparent; color: var(--text-primary); border: 1px solid var(--border-default); }
.sp-btn--outline:hover { border-color: var(--primary); color: var(--primary); }
.sp-stats-row { display: flex; gap: 24px; }
.sp-stat { display: flex; flex-direction: column; align-items: center; text-decoration: none; }
.sp-stat--link:hover .sp-stat__value { color: var(--primary); }
.sp-stat__value { font-size: 1.125rem; font-weight: 700; color: var(--text-primary); }
.sp-stat__label { font-size: 0.8125rem; color: var(--text-muted); }
.sp-tabs { display: flex; border-bottom: 1px solid var(--border-subtle); margin-top: 16px; }
.sp-tab {
    flex: 1; display: flex; align-items: center; justify-content: center; gap: 8px;
    padding: 14px; font-size: 14px; font-weight: 600; font-family: inherit;
    color: var(--text-muted); background: none; border: none;
    border-bottom: 2px solid transparent; cursor: pointer; transition: all 0.2s;
}
.sp-tab:hover { color: var(--text-secondary); }
.sp-tab.active { color: var(--primary); border-bottom-color: var(--primary); }
.sp-tab svg { opacity: 0.7; } .sp-tab.active svg { opacity: 1; stroke: var(--primary); }
.sp-tab-content { display: none; padding-top: 16px; }
.sp-tab-content.active { display: block; }
.sp-empty { text-align: center; padding: 48px 24px; color: var(--text-muted); }
.sp-empty svg { margin: 0 auto 12px; opacity: 0.4; }
.sp-about-grid { display: grid; gap: 16px; }
.sp-card { background: var(--bg-surface); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); overflow: hidden; }
.sp-card__header { display: flex; align-items: center; gap: 10px; padding: 16px 20px; border-bottom: 1px solid var(--border-subtle); color: var(--text-primary); }
.sp-card__header h3 { font-size: 0.9375rem; font-weight: 600; margin: 0; }
.sp-detail-list { padding: 8px 0; }
.sp-detail { display: flex; justify-content: space-between; padding: 10px 20px; }
.sp-detail__label { font-size: 14px; color: var(--text-muted); }
.sp-detail__value { font-size: 14px; font-weight: 500; color: var(--text-primary); }
.sp-mini-stats { display: grid; grid-template-columns: repeat(3,1fr); padding: 20px 0; }
.sp-mini-stat { text-align: center; }
.sp-mini-stat__value { display: block; font-size: 1.25rem; font-weight: 700; color: var(--primary); }
.sp-mini-stat__label { font-size: 0.8125rem; color: var(--text-muted); }
@media (max-width: 640px) {
    .sp-page { padding: 0; }
    .sp-banner { height: 140px; }
    .sp-avatar { width: 90px; height: 90px; margin-top: -45px; }
    .sp-name { font-size: 1.25rem; }
    .sp-header-body { padding: 0 16px 16px; }
    .sp-stats-row { gap: 16px; }
}
[data-theme="light"] .sp-header-card,
[data-theme="light"] .sp-card { background: #fff; border-color: rgba(0,0,0,0.08); }
[data-theme="light"] .sp-tabs { border-bottom-color: rgba(0,0,0,0.08); }
[data-theme="light"] .sp-card__header { border-bottom-color: rgba(0,0,0,0.06); }
</style>

@section('scripts')
<script>
document.querySelectorAll('.sp-tab').forEach(function(tab) {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.sp-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.sp-tab-content').forEach(c => c.classList.remove('active'));
        this.classList.add('active');
        document.getElementById('sptab-' + this.dataset.spTab).classList.add('active');
    });
});
</script>
@endsection
@endsection