@extends('layouts.app')

@section('title', $user->name)

@section('content')
    @vite([        'resources/css/admin/admin.css',
        'resources/css/admin/users.css',
    ])

    @php
        $currentUser = auth()->user();
        $isSuperAdmin = $currentUser?->isSuperAdmin();
        $isUserSuperAdmin = ($user->role ?? 'user') === 'super_admin';
        $postsCollection = $posts ?? collect();
        $subscriptionsCollection = $subscriptions ?? collect();
        $lastSeenValue = data_get($user, 'last_seen_at') ?? data_get($user, 'last_seen') ?? data_get($user, 'last_activity_at') ?? data_get($user, 'last_activity');
        $lastSeenLabel = $lastSeenValue ? \Illuminate\Support\Carbon::parse($lastSeenValue)->diffForHumans() : 'Never';
    @endphp

    <div class="admin-layout admin-layout--users">
        <section class="admin-hero">
            <div class="admin-hero__content">
                <span class="admin-hero__eyebrow">User profile</span>

                <div class="admin-user-profile__header">
                    <div class="admin-user-profile__summary">
                        <h1 class="admin-hero__title">{{ $user->name }}</h1>
                        <p class="admin-hero__description">{{ $user->email }}</p>
                        <div class="admin-user-profile__role">
                            @if ($isUserSuperAdmin)
                                <span class="admin-badge admin-badge--super">Super admin</span>
                            @elseif (($user->role ?? 'user') === 'admin')
                                <span class="admin-badge admin-badge--primary">Admin</span>
                            @else
                                <span class="admin-badge admin-badge--muted">User</span>
                            @endif
                            <span class="admin-badge admin-badge--info">Member since {{ optional($user->created_at)->format('M d, Y') ?? '—' }}</span>
                        </div>
                    </div>

                    <div class="admin-hero__actions">
                        <a href="{{ route('admin.users') }}" class="admin-button admin-button--ghost">Back to users</a>
                        @if ($isSuperAdmin || ! $isUserSuperAdmin)
                            <a href="{{ route('admin.users.edit', $user) }}" class="admin-button admin-button--secondary">Edit profile</a>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <section class="admin-grid admin-grid--stats">
            <article class="admin-stat">
                <span class="admin-stat__label">Role</span>
                <strong class="admin-stat__value">{{ ucfirst(str_replace('_', ' ', $user->role ?? 'user')) }}</strong>
                <span class="admin-stat__hint">{{ $isUserSuperAdmin ? 'This account is locked to super admin oversight.' : 'Role can be managed from the edit screen.' }}</span>
            </article>

            <article class="admin-stat">
                <span class="admin-stat__label">Last seen</span>
                <strong class="admin-stat__value">{{ $lastSeenLabel }}</strong>
                <span class="admin-stat__hint">Most recent known platform activity.</span>
            </article>

            <article class="admin-stat">
                <span class="admin-stat__label">Posts</span>
                <strong class="admin-stat__value">{{ number_format((int) ($postsCollection->count() ?? 0)) }}</strong>
                <span class="admin-stat__hint">Visible posts currently associated with this user.</span>
            </article>

            <article class="admin-stat">
                <span class="admin-stat__label">Subscriptions</span>
                <strong class="admin-stat__value">{{ number_format((int) ($subscriptionsCollection->count() ?? 0)) }}</strong>
                <span class="admin-stat__hint">Tracked membership or plan subscriptions.</span>
            </article>
        </section>

        <section class="admin-grid admin-grid--content">
            <article class="admin-card">
                <div class="admin-card__header">
                    <div class="admin-card__title-group">
                        <h2 class="admin-card__title">Account details</h2>
                        <p class="admin-card__description">Core profile information and moderation-relevant status.</p>
                    </div>
                </div>

                <div class="admin-card__body admin-user-profile">
                    <div class="admin-user-profile__details">
                        <div class="admin-user-profile__detail">
                            <span class="admin-user-profile__detail-label">Name</span>
                            <div class="admin-user-profile__detail-value">{{ $user->name }}</div>
                        </div>
                        <div class="admin-user-profile__detail">
                            <span class="admin-user-profile__detail-label">Email</span>
                            <div class="admin-user-profile__detail-value">{{ $user->email }}</div>
                        </div>
                        <div class="admin-user-profile__detail">
                            <span class="admin-user-profile__detail-label">Joined</span>
                            <div class="admin-user-profile__detail-value">{{ optional($user->created_at)->format('M d, Y') ?? '—' }}</div>
                        </div>
                        <div class="admin-user-profile__detail">
                            <span class="admin-user-profile__detail-label">Last seen</span>
                            <div class="admin-user-profile__detail-value">{{ $lastSeenLabel }}</div>
                        </div>
                    </div>

                    @if ($isUserSuperAdmin)
                        <div class="admin-note admin-note--warning">
                            This account has super admin privileges and is subject to elevated protection rules.
                        </div>
                    @endif
                </div>
            </article>

            <article class="admin-card">
                <div class="admin-card__header">
                    <div class="admin-card__title-group">
                        <h2 class="admin-card__title">Recent posts</h2>
                        <p class="admin-card__description">Posts created by this user.</p>
                    </div>
                </div>

                <div class="admin-card__body">
                    @forelse ($postsCollection as $post)
                        <div class="admin-activity__item">
                            <p class="admin-activity__title">{{ data_get($post, 'title', 'Untitled post') }}</p>
                            <div class="admin-activity__meta">
                                <span>{{ optional(data_get($post, 'created_at'))->format('M d, Y') ?? '—' }}</span>
                                <span>{{ optional(data_get($post, 'created_at'))->diffForHumans() ?? 'Recently' }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="admin-empty-state">
                            <p class="admin-empty-state__title">No posts found</p>
                            <p class="admin-empty-state__description">This user has not published any posts yet.</p>
                        </div>
                    @endforelse
                </div>
            </article>

            <article class="admin-card">
                <div class="admin-card__header">
                    <div class="admin-card__title-group">
                        <h2 class="admin-card__title">Subscriptions</h2>
                        <p class="admin-card__description">Current subscription records associated with the account.</p>
                    </div>
                </div>

                <div class="admin-card__body">
                    @forelse ($subscriptionsCollection as $subscription)
                        <div class="admin-activity__item">
                            <p class="admin-activity__title">{{ data_get($subscription, 'name', data_get($subscription, 'plan_name', 'Subscription')) }}</p>
                            <div class="admin-activity__meta">
                                <span>{{ data_get($subscription, 'status', 'Active') }}</span>
                                <span>{{ optional(data_get($subscription, 'created_at'))->format('M d, Y') ?? '—' }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="admin-empty-state">
                            <p class="admin-empty-state__title">No subscriptions found</p>
                            <p class="admin-empty-state__description">There are no tracked subscriptions for this user.</p>
                        </div>
                    @endforelse
                </div>
            </article>
        </section>
    </div>
@endsection