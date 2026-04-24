@extends('layouts.app')

@section('title', 'Administrators')

@section('content')
    @vite([        'resources/css/admin/admin.css',
        'resources/css/admin/administrators.css',
        'resources/css/admin/adminposts.css',
    ])

    @php
        $currentUser = auth()->user();
        $isSuperAdmin = $currentUser?->isSuperAdmin();
    @endphp

    <div class="admin-layout admin-layout--administrators">
        <section class="admin-hero admin-hero--premium">
            <div class="admin-hero__content">
                <span class="admin-hero__eyebrow">Super admin only</span>

                <div>
                    <h1 class="admin-hero__title">Administrators</h1>
                    <p class="admin-hero__description">
                        This area lists every admin and super admin account so you can manage privileged access, review account activity, and quickly open user profiles.
                    </p>
                </div>

                <div class="admin-hero__meta">
                    <span class="admin-badge admin-badge--super">{{ number_format((int) ($administrators->total() ?? $administrators->count() ?? 0)) }} privileged accounts</span>
                    <span class="admin-badge admin-badge--muted">Role locked</span>
                </div>

                <div class="admin-hero__actions">
                    <a href="{{ route('admin.dashboard') }}" class="admin-button admin-button--ghost">Back to dashboard</a>
                    <a href="{{ route('admin.users') }}" class="admin-button admin-button--secondary">All users</a>
                </div>
            </div>
        </section>

        <section class="admin-note admin-note--warning">
            This page is reserved for super admins. Use it to oversee trusted accounts and confirm that elevated permissions remain correct.
        </section>

        <section class="admin-card">
            <div class="admin-card__header">
                <div class="admin-card__title-group">
                    <h2 class="admin-card__title">Privileged accounts</h2>
                    <p class="admin-card__description">Open profiles or edit roles directly from the quick actions column.</p>
                </div>
            </div>

            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Administrator</th>
                            <th>Role</th>
                            <th>Email</th>
                            <th>Last seen</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($administrators as $administrator)
                            @php
                                $role = $administrator->role ?? 'admin';
                                $lastSeenValue = data_get($administrator, 'last_seen_at') ?? data_get($administrator, 'last_seen') ?? data_get($administrator, 'last_activity_at') ?? data_get($administrator, 'last_activity');
                                $lastSeenLabel = $lastSeenValue ? \Illuminate\Support\Carbon::parse($lastSeenValue)->diffForHumans() : 'Never';
                            @endphp
                            <tr class="admin-table__row {{ $role === 'super_admin' ? 'admin-table__row--super' : '' }}">
                                <td data-label="Administrator">
                                    <div class="admin-table__stack">
                                        <strong class="admin-table__title">{{ $administrator->name }}</strong>
                                        <span class="admin-table__subtitle">ID #{{ $administrator->id }}</span>
                                    </div>
                                </td>
                                <td data-label="Role">
                                    <div class="admin-admin-card__role">
                                        @if ($role === 'super_admin')
                                            <span class="admin-badge admin-badge--super">Super admin</span>
                                        @else
                                            <span class="admin-badge admin-badge--primary">Admin</span>
                                        @endif
                                    </div>
                                </td>
                                <td data-label="Email">
                                    <div class="admin-table__stack">
                                        <span class="admin-table__meta">{{ $administrator->email }}</span>
                                        @if ($role === 'super_admin')
                                            <span class="admin-table__footnote">Highest privilege tier</span>
                                        @endif
                                    </div>
                                </td>
                                <td data-label="Last seen">
                                    <div class="admin-table__stack">
                                        <span class="admin-table__meta">{{ $lastSeenLabel }}</span>
                                        <span class="admin-table__footnote">{{ optional($lastSeenValue ? \Illuminate\Support\Carbon::parse($lastSeenValue) : null)->toDayDateTimeString() ?? 'No recent activity' }}</span>
                                    </div>
                                </td>
                                <td data-label="Actions">
                                    <div class="admin-admin-card__actions">
                                        <a href="{{ route('admin.users.show', $administrator) }}" class="admin-button admin-button--sm admin-button--ghost">View</a>
                                        <a href="{{ route('admin.users.edit', $administrator) }}" class="admin-button admin-button--sm admin-button--secondary">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="admin-empty-state">
                                        <p class="admin-empty-state__title">No administrators found</p>
                                        <p class="admin-empty-state__description">Only users with admin or super admin roles appear on this page.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="admin-card__footer">
                <div class="admin-card__meta">
                    <span class="admin-badge admin-badge--muted">Page total: {{ number_format((int) ($administrators->count() ?? 0)) }}</span>
                </div>
                {{ $administrators->links() }}
            </div>
        </section>
    </div>
@endsection