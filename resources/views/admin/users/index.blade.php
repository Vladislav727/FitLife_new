@extends('layouts.app')

@section('title', 'Users')

@section('content')
    @vite([        'resources/css/admin/admin.css',
        'resources/css/admin/users.css',
    ])

    @php
        $currentUser = auth()->user();
        $isSuperAdmin = $currentUser?->isSuperAdmin();
    @endphp

    <div class="admin-layout admin-layout--users">
        <section class="admin-hero">
            <div class="admin-hero__content">
                <span class="admin-hero__eyebrow">User management</span>

                <div>
                    <h1 class="admin-hero__title">Manage members and roles</h1>
                    <p class="admin-hero__description">
                        Search the user base, review account roles, and keep the community safe. Super admin accounts are visually highlighted and protected from unsupported edits.
                    </p>
                </div>

                <div class="admin-hero__meta">
                    <span class="admin-badge admin-badge--primary">{{ number_format((int) ($users->total() ?? $users->count() ?? 0)) }} users</span>
                    <span class="admin-badge admin-badge--success">Role aware</span>
                    <span class="admin-badge admin-badge--super">Super admin protected</span>
                </div>

                <div class="admin-hero__actions">
                    <a href="{{ route('admin.dashboard') }}" class="admin-button admin-button--ghost">Back to dashboard</a>
                    <a href="{{ route('admin.statistics') }}" class="admin-button admin-button--secondary">Open statistics</a>
                </div>
            </div>
        </section>

        <section class="admin-toolbar">
            <div class="admin-toolbar__group">
                <label class="admin-toolbar__label" for="user-search">Search</label>
                <input id="user-search" type="search" class="admin-toolbar__search" placeholder="Search by name or email" autocomplete="off">
            </div>

            <div class="admin-toolbar__group">
                <label class="admin-toolbar__label" for="role-filter">Role</label>
                <select id="role-filter" class="admin-toolbar__select">
                    <option value="all">All roles</option>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                    <option value="super_admin">Super admin</option>
                </select>
            </div>
        </section>

        <section class="admin-card">
            <div class="admin-card__header">
                <div class="admin-card__title-group">
                    <h2 class="admin-card__title">Registered users</h2>
                    <p class="admin-card__description">Use the filters above to find a specific account or narrow the list by role.</p>
                </div>
            </div>

            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            @php
                                $role = $user->role ?? 'user';
                                $isProtectedSuperAdmin = $role === 'super_admin';
                            @endphp
                            <tr class="admin-table__row {{ $isProtectedSuperAdmin ? 'admin-table__row--super' : '' }}" data-role="{{ $role }}" data-search-text="{{ strtolower(($user->name ?? '') . ' ' . ($user->email ?? '') . ' ' . $role) }}">
                                <td data-label="User">
                                    <div class="admin-table__stack">
                                        <strong class="admin-table__title">{{ $user->name }}</strong>
                                        <span class="admin-table__subtitle">ID #{{ $user->id }}</span>
                                    </div>
                                </td>
                                <td data-label="Email">
                                    <span class="admin-table__meta">{{ $user->email }}</span>
                                </td>
                                <td data-label="Role">
                                    <div class="admin-table__role">
                                        @if ($role === 'super_admin')
                                            <span class="admin-badge admin-badge--super">Super admin</span>
                                        @elseif ($role === 'admin')
                                            <span class="admin-badge admin-badge--primary">Admin</span>
                                        @else
                                            <span class="admin-badge admin-badge--muted">User</span>
                                        @endif
                                    </div>
                                </td>
                                <td data-label="Joined">
                                    <div class="admin-table__stack">
                                        <span class="admin-table__meta">{{ optional($user->created_at)->format('M d, Y') ?? '—' }}</span>
                                        <span class="admin-table__footnote">{{ optional($user->created_at)->diffForHumans() ?? '' }}</span>
                                    </div>
                                </td>
                                <td data-label="Actions">
                                    <div class="admin-table__actions">
                                        <a href="{{ route('admin.users.show', $user) }}" class="admin-button admin-button--sm admin-button--ghost">View</a>
                                        @if ($isSuperAdmin || $role !== 'super_admin')
                                            <a href="{{ route('admin.users.edit', $user) }}" class="admin-button admin-button--sm admin-button--secondary">Edit</a>
                                        @endif
                                        @if ($isSuperAdmin || $role !== 'super_admin')
                                            <form action="{{ route('admin.users.delete', $user) }}" method="POST" onsubmit="return confirm('Delete this user?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="admin-button admin-button--sm admin-button--danger">Delete</button>
                                            </form>
                                        @else
                                            <span class="admin-badge admin-badge--super">Protected</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="admin-empty-state">
                                        <p class="admin-empty-state__title">No users found</p>
                                        <p class="admin-empty-state__description">There are no registered users to display at the moment.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="admin-card__footer">
                <div class="admin-card__meta">
                    <span class="admin-badge admin-badge--muted">Total: {{ number_format((int) ($users->total() ?? $users->count() ?? 0)) }}</span>
                </div>
                {{ $users->links() }}
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/admin-users-index.js') }}" defer></script>
@endsection