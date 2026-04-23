@extends('layouts.app')

@section('content')
    <div class="users-content">
        <header class="users-header">
            <h1 class="users-title">{{ __('admin.users_management') }}</h1>
            <a href="{{ route('admin.dashboard') }}" class="users-back-btn">{{ __('admin.back_to_dashboard') }}</a>
        </header>

        <div class="users-search">
            <input type="text" id="user-search" placeholder="{{ __('admin.search_users') }}" class="users-search-input">
            <select id="role-filter" class="users-search-select">
                <option value="">{{ __('admin.all_roles') }}</option>
                <option value="user">{{ __('admin.users') }}</option>
                <option value="admin">{{ __('admin.admins') }}</option>
                <option value="super_admin">Super Admin</option>
            </select>
        </div>

        <div class="users-section">
            <div class="users-table">
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('admin.id') }}</th>
                            <th>{{ __('admin.name') }}</th>
                            <th>{{ __('admin.email') }}</th>
                            <th>{{ __('admin.role') }}</th>
                            <th>{{ __('admin.posts') }}</th>
                            <th>{{ __('admin.created') }}</th>
                            <th>{{ __('admin.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="role-badge role-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
                                </td>
                                <td>{{ $user->posts->count() }}</td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.users.show', $user) }}" class="users-btn users-btn-primary">{{ __('admin.view') }}</a>
                                    @if(!$user->isSuperAdmin() || auth()->user()->isSuperAdmin())
                                    <a href="{{ route('admin.users.edit', $user) }}" class="users-btn users-btn-secondary">{{ __('admin.edit') }}</a>
                                    @endif
                                    @if(!$user->isSuperAdmin())
                                    <form action="{{ route('admin.users.delete', $user) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="users-btn users-btn-danger" data-confirm="{{ __('admin.confirm_delete_user') }}">{{ __('admin.delete') }}</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">{{ __('admin.no_users_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="users-pagination">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/admin-users-index.js') }}"></script>
@endsection
