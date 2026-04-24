@extends('layouts.app')

@section('title', 'Statistics')

@section('content')
    @vite([        'resources/css/admin/admin.css',
    ])

    <div class="admin-layout admin-layout--statistics">
        <section class="admin-hero">
            <div class="admin-hero__content">
                <span class="admin-hero__eyebrow">System reporting</span>

                <div>
                    <h1 class="admin-hero__title">Statistics dashboard</h1>
                    <p class="admin-hero__description">
                        Review platform trends, compare user activity, and keep an eye on content performance from one unified analytics view.
                    </p>
                </div>

                <div class="admin-hero__actions">
                    <a href="{{ route('admin.dashboard') }}" class="admin-button admin-button--ghost">Back to dashboard</a>
                    <a href="{{ route('admin.users') }}" class="admin-button admin-button--secondary">Users</a>
                    <a href="{{ route('admin.posts') }}" class="admin-button admin-button--secondary">Posts</a>
                    <a href="{{ route('admin.events') }}" class="admin-button admin-button--secondary">Events</a>
                </div>
            </div>
        </section>

        <section class="admin-grid admin-grid--stats">
            <article class="admin-stat">
                <span class="admin-stat__label">Overview</span>
                <strong class="admin-stat__value">Analytics</strong>
                <span class="admin-stat__hint">Use the cards below to inspect activity breakdowns.</span>
            </article>
            <article class="admin-stat">
                <span class="admin-stat__label">Focus</span>
                <strong class="admin-stat__value">Users</strong>
                <span class="admin-stat__hint">User activity chart is rendered in the first panel.</span>
            </article>
            <article class="admin-stat">
                <span class="admin-stat__label">Focus</span>
                <strong class="admin-stat__value">Posts</strong>
                <span class="admin-stat__hint">Content trend chart is rendered in the second panel.</span>
            </article>
        </section>

        <section class="admin-grid admin-grid--content">
            <article class="admin-card">
                <div class="admin-card__header">
                    <div class="admin-card__title-group">
                        <h2 class="admin-card__title">User activity</h2>
                        <p class="admin-card__description">A snapshot of user-related platform activity.</p>
                    </div>
                </div>

                <div class="admin-card__body">
                    <div class="admin-chart-shell">
                        <canvas id="userChart" data-user-stats='@json($userStats)' aria-label="User statistics chart" role="img"></canvas>
                    </div>
                </div>
            </article>

            <article class="admin-card">
                <div class="admin-card__header">
                    <div class="admin-card__title-group">
                        <h2 class="admin-card__title">Content performance</h2>
                        <p class="admin-card__description">A snapshot of post-related platform activity.</p>
                    </div>
                </div>

                <div class="admin-card__body">
                    <div class="admin-chart-shell">
                        <canvas id="postChart" data-post-stats='@json($postStats)' aria-label="Post statistics chart" role="img"></canvas>
                    </div>
                </div>
            </article>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/admin-statistics.js') }}" defer></script>
@endsection