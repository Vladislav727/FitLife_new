@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    @vite([
        'resources/css/admin/admin.css',
        'resources/css/admin/admindashboard.css',
    ])

    @php
        $currentUser = auth()->user();
        $isSuperAdmin = $currentUser?->isSuperAdmin();
        $recentPosts = $recentPosts ?? collect();
        $recentComments = $recentComments ?? collect();
    @endphp

    <div class="admin-layout admin-layout--dashboard">
        <section class="admin-hero">
            <div class="admin-hero__content">
                <span class="admin-hero__eyebrow">Admin overview</span>

                <div>
                    <h1 class="admin-hero__title">Welcome back, {{ $currentUser?->name ?? 'Administrator' }}</h1>
                    <p class="admin-hero__description">
                        Track platform growth, moderation activity, and the latest content at a glance. Use the shortcuts below to move quickly between content management, member oversight, and reporting.
                    </p>
                </div>

                <div class="admin-hero__meta">
                    <span class="admin-badge admin-badge--primary">Users: {{ number_format((int) ($totalUsers ?? 0)) }}</span>
                    <span class="admin-badge admin-badge--success">Active: {{ number_format((int) ($activeUsers ?? 0)) }}</span>
                    <span class="admin-badge admin-badge--info">Posts: {{ number_format((int) ($totalPosts ?? 0)) }}</span>
                    <span class="admin-badge admin-badge--warning">Events: {{ number_format((int) ($totalEvents ?? 0)) }}</span>
                </div>

                <div class="admin-hero__actions">
                    <a href="{{ route('admin.users') }}" class="admin-button admin-button--primary">Manage users</a>
                    <a href="{{ route('admin.posts') }}" class="admin-button admin-button--ghost">Review posts</a>
                    <a href="{{ route('admin.events') }}" class="admin-button admin-button--ghost">Review events</a>
                    <a href="{{ route('admin.comments') }}" class="admin-button admin-button--ghost">Moderate comments</a>
                    <a href="{{ route('admin.statistics') }}" class="admin-button admin-button--secondary">View statistics</a>
                    @if ($isSuperAdmin)
                        <a href="{{ route('admin.administrators') }}" class="admin-button admin-button--danger">Administrators</a>
                    @endif
                </div>
            </div>
        </section>

        <section class="admin-grid admin-grid--stats">
            <article class="admin-stat">
                <span class="admin-stat__label">Total users</span>
                <strong class="admin-stat__value">{{ number_format((int) ($totalUsers ?? 0)) }}</strong>
                <span class="admin-stat__hint">Registered accounts across the platform.</span>
            </article>

            <article class="admin-stat">
                <span class="admin-stat__label">Active users</span>
                <strong class="admin-stat__value">{{ number_format((int) ($activeUsers ?? 0)) }}</strong>
                <span class="admin-stat__hint">Accounts that have recently engaged with the platform.</span>
            </article>

            <article class="admin-stat">
                <span class="admin-stat__label">Total posts</span>
                <strong class="admin-stat__value">{{ number_format((int) ($totalPosts ?? 0)) }}</strong>
                <span class="admin-stat__hint">Published content pieces available in the feed.</span>
            </article>

            <article class="admin-stat">
                <span class="admin-stat__label">Total events</span>
                <strong class="admin-stat__value">{{ number_format((int) ($totalEvents ?? 0)) }}</strong>
                <span class="admin-stat__hint">Upcoming and archived events currently tracked.</span>
            </article>

            <article class="admin-stat">
                <span class="admin-stat__label">Total comments</span>
                <strong class="admin-stat__value">{{ number_format((int) ($totalComments ?? 0)) }}</strong>
                <span class="admin-stat__hint">Community comments available for moderation.</span>
            </article>

            <article class="admin-stat">
                <span class="admin-stat__label">Admins</span>
                <strong class="admin-stat__value">{{ number_format((int) ($totalAdmins ?? 0)) }}</strong>
                <span class="admin-stat__hint">Accounts with admin-level privileges.</span>
            </article>

            <article class="admin-stat">
                <span class="admin-stat__label">Super admins</span>
                <strong class="admin-stat__value">{{ number_format((int) ($totalSuperAdmins ?? 0)) }}</strong>
                <span class="admin-stat__hint">Locked system accounts with full oversight.</span>
            </article>
        </section>

        <section class="admin-dashboard__layout">
            <article class="admin-card" id="recent-posts">
                <div class="admin-card__header">
                    <div class="admin-card__title-group">
                        <h2 class="admin-card__title">Recent posts</h2>
                        <p class="admin-card__description">The latest published posts from the content team.</p>
                    </div>
                    <div class="admin-card__actions">
                        <a href="{{ route('admin.posts') }}" class="admin-button admin-button--sm admin-button--ghost">View all</a>
                    </div>
                </div>

                <div class="admin-card__body">
                    <div class="admin-activity">
                        @forelse ($recentPosts as $post)
                            @php
                                $postContent = trim((string) data_get($post, 'content', ''));
                                $postHeadline = $postContent !== ''
                                    ? \Illuminate\Support\Str::limit(strip_tags($postContent), 72)
                                    : 'Published post';
                            @endphp
                            <div class="admin-activity__item">
                                <p class="admin-activity__title">{{ $postHeadline }}</p>
                                <div class="admin-activity__meta">
                                    <span>By {{ data_get($post, 'user.name', data_get($post, 'author.name', 'Unknown author')) }}</span>
                                    <span>{{ optional(data_get($post, 'created_at'))->diffForHumans() ?? 'Recently published' }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="admin-empty-state">
                                <p class="admin-empty-state__title">No recent posts</p>
                                <p class="admin-empty-state__description">New posts will appear here once they are published.</p>
                            </div>
                        @endforelse
                    </div>

                    @if (method_exists($recentPosts, 'hasPages') && $recentPosts->hasPages())
                        <div class="pagination admin-pagination" data-current-page="{{ $recentPosts->currentPage() }}" data-last-page="{{ $recentPosts->lastPage() }}">
                            <a href="{{ $recentPosts->previousPageUrl() ? $recentPosts->previousPageUrl() . '#recent-posts' : '#' }}" class="{{ $recentPosts->onFirstPage() ? 'disabled' : '' }}">{{ __('profile.previous') }}</a>
                            @for ($i = 1; $i <= $recentPosts->lastPage(); $i++)
                                <a href="{{ $recentPosts->url($i) . '#recent-posts' }}" class="{{ $recentPosts->currentPage() == $i ? 'current' : '' }}">{{ $i }}</a>
                            @endfor
                            <a href="{{ $recentPosts->nextPageUrl() ? $recentPosts->nextPageUrl() . '#recent-posts' : '#' }}" class="{{ $recentPosts->onLastPage() ? 'disabled' : '' }}">{{ __('profile.next') }}</a>
                        </div>
                    @endif
                </div>
            </article>

            <article class="admin-card" id="recent-comments">
                <div class="admin-card__header">
                    <div class="admin-card__title-group">
                        <h2 class="admin-card__title">Recent comments</h2>
                        <p class="admin-card__description">A live moderation queue for the latest community discussion.</p>
                    </div>
                    <div class="admin-card__actions">
                        <a href="{{ route('admin.comments') }}" class="admin-button admin-button--sm admin-button--ghost">Moderate</a>
                    </div>
                </div>

                <div class="admin-card__body">
                    <div class="admin-activity">
                        @forelse ($recentComments as $comment)
                            @php
                                $commentText = data_get($comment, 'content')
                                    ?? data_get($comment, 'body')
                                    ?? data_get($comment, 'message')
                                    ?? '';
                            @endphp
                            <div class="admin-activity__item">
                                <p class="admin-activity__title">{{ \Illuminate\Support\Str::limit(strip_tags($commentText), 120) ?: 'Comment removed or empty' }}</p>
                                <div class="admin-activity__meta">
                                    <span>By {{ data_get($comment, 'user.name', 'Unknown user') }}</span>
                                    <span>On {{ data_get($comment, 'post.title', 'Unknown post') }}</span>
                                    <span>{{ optional(data_get($comment, 'created_at'))->diffForHumans() ?? 'Recently posted' }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="admin-empty-state">
                                <p class="admin-empty-state__title">No recent comments</p>
                                <p class="admin-empty-state__description">Moderation activity will appear here when new comments are submitted.</p>
                            </div>
                        @endforelse
                    </div>

                    @if (method_exists($recentComments, 'hasPages') && $recentComments->hasPages())
                        <div class="pagination admin-pagination" data-current-page="{{ $recentComments->currentPage() }}" data-last-page="{{ $recentComments->lastPage() }}">
                            <a href="{{ $recentComments->previousPageUrl() ? $recentComments->previousPageUrl() . '#recent-comments' : '#' }}" class="{{ $recentComments->onFirstPage() ? 'disabled' : '' }}">{{ __('profile.previous') }}</a>
                            @for ($i = 1; $i <= $recentComments->lastPage(); $i++)
                                <a href="{{ $recentComments->url($i) . '#recent-comments' }}" class="{{ $recentComments->currentPage() == $i ? 'current' : '' }}">{{ $i }}</a>
                            @endfor
                            <a href="{{ $recentComments->nextPageUrl() ? $recentComments->nextPageUrl() . '#recent-comments' : '#' }}" class="{{ $recentComments->onLastPage() ? 'disabled' : '' }}">{{ __('profile.next') }}</a>
                        </div>
                    @endif
                </div>
            </article>
        </section>
    </div>
@endsection
