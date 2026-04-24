@extends('layouts.app')

@section('title', 'Edit user')

@section('content')
    @vite([
        'resources/css/admin/admin.css',
        'resources/css/admin/admindashboard.css',
        'resources/css/admin/users.css',
    ])

    @php
        $currentUser = auth()->user();
        $isSuperAdmin = $currentUser?->isSuperAdmin();
        $userRole = $user->role ?? 'user';
    @endphp

    <div class="admin-layout admin-layout--users">
        <section class="admin-hero">
            <div class="admin-hero__content">
                <span class="admin-hero__eyebrow">Edit user</span>

                <div>
                    <h1 class="admin-hero__title">Update {{ $user->name }}</h1>
                    <p class="admin-hero__description">
                        Adjust profile information and role assignments from one place. Super admin assignments remain locked behind elevated permissions.
                    </p>
                </div>

                <div class="admin-hero__meta">
                    @if ($userRole === 'super_admin')
                        <span class="admin-badge admin-badge--super">Super admin</span>
                    @elseif ($userRole === 'admin')
                        <span class="admin-badge admin-badge--primary">Admin</span>
                    @else
                        <span class="admin-badge admin-badge--muted">User</span>
                    @endif
                    <span class="admin-badge admin-badge--info">ID #{{ $user->id }}</span>
                </div>

                <div class="admin-hero__actions">
                    <a href="{{ route('admin.users.show', $user) }}" class="admin-button admin-button--ghost">View profile</a>
                    <a href="{{ route('admin.users') }}" class="admin-button admin-button--secondary">Back to users</a>
                </div>
            </div>
        </section>

        <section class="admin-card">
            <div class="admin-card__header">
                <div class="admin-card__title-group">
                    <h2 class="admin-card__title">Profile settings</h2>
                    <p class="admin-card__description">Use the fields below to update the selected account.</p>
                </div>
            </div>

            <div class="admin-card__body">
                <div class="admin-note {{ $isSuperAdmin ? '' : 'admin-note--warning' }}">
                    @if ($isSuperAdmin)
                        You have permission to assign the super admin role. Use this power carefully because it grants full oversight of the platform.
                    @else
                        Super admin role changes are locked. You can manage regular admin roles, but only a super admin may assign or revoke super admin access.
                    @endif
                </div>

                <form action="{{ route('admin.users.update', $user) }}" method="POST" class="admin-form">
                    @csrf
                    @method('PATCH')

                    <div class="admin-form__grid">
                        <div class="admin-form__group">
                            <label for="name" class="admin-form__label">Name</label>
                            <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" class="admin-form__control" required>
                            @error('name')
                                <span class="admin-form__hint text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="admin-form__group">
                            <label for="email" class="admin-form__label">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" class="admin-form__control" required>
                            @error('email')
                                <span class="admin-form__hint text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="admin-form__group admin-form__role-lock">
                        <label for="role" class="admin-form__label">Role</label>

                        @if ($isSuperAdmin)
                            <select id="role" name="role" class="admin-form__control">
                                <option value="user" @selected(old('role', $userRole) === 'user')>User</option>
                                <option value="admin" @selected(old('role', $userRole) === 'admin')>Admin</option>
                                <option value="super_admin" @selected(old('role', $userRole) === 'super_admin')>Super admin</option>
                            </select>
                        @else
                            <select id="role" name="role" class="admin-form__control">
                                <option value="user" @selected(old('role', $userRole) === 'user')>User</option>
                                <option value="admin" @selected(old('role', $userRole) === 'admin')>Admin</option>
                            </select>
                        @endif

                        <span class="admin-form__hint">
                            @if ($isSuperAdmin)
                                Only super admins can lock or unlock the super_admin role.
                            @else
                                The super_admin role is hidden here because your account does not have permission to assign it.
                            @endif
                        </span>
                    </div>

                    @if (! $isSuperAdmin && $userRole === 'super_admin')
                        <div class="admin-note admin-note--danger">
                            This account already has super admin privileges. Only a super admin can modify that role.
                        </div>
                    @endif

                    <div class="admin-form__footer">
                        <a href="{{ route('admin.users.show', $user) }}" class="admin-button admin-button--ghost">Cancel</a>
                        <button type="submit" class="admin-button admin-button--primary">Save changes</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
