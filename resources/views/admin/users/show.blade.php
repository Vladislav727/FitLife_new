@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/users.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="users-content">
        <header class="users-header">
            <h1 class="users-title">{{ __('admin.user') }}: {{ $user->name }}</h1>
            <a href="{{ route('admin.users') }}" class="users-back-btn">{{ __('admin.back_to_users') }}</a>
        </header>

        <div class="users-section">
            <h2 class="users-section-title">{{ __('admin.user_details') }}</h2>
            <div class="users-details">
                <p><strong>{{ __('admin.id') }}:</strong> {{ $user->id }}</p>
                <p><strong>{{ __('admin.name') }}:</strong> {{ $user->name }}</p>
                <p><strong>{{ __('admin.email') }}:</strong> {{ $user->email }}</p>
                <p><strong>{{ __('admin.role') }}:</strong> <span class="role-badge role-{{ $user->role }}">{{ ucfirst($user->role) }}</span></p>
                <p><strong>{{ __('admin.username') }}:</strong> {{ $user->username ?? __('admin.not_set') }}</p>
                <p><strong>{{ __('admin.created') }}:</strong> {{ $user->created_at->format('M d, Y') }}</p>
                <p><strong>{{ __('admin.last_login') }}:</strong> {{ $user->last_login ? $user->last_login->format('M d, Y H:i') : __('admin.never') }}</p>
            </div>
        </div>

        <div class="users-section">
            <h2 class="users-section-title">{{ __('admin.biography') }}</h2>
            <div class="users-details">
                <p><strong>{{ __('admin.full_name') }}:</strong> {{ $user->biography->full_name ?? __('admin.not_set') }}</p>
                <p><strong>{{ __('admin.age') }}:</strong> {{ $user->biography->age ?? __('admin.not_set') }}</p>
                <p><strong>{{ __('admin.height') }}:</strong> {{ $user->biography->height ?? __('admin.not_set') }} cm</p>
                <p><strong>{{ __('admin.weight') }}:</strong> {{ $user->biography->weight ?? __('admin.not_set') }} kg</p>
                <p><strong>{{ __('admin.gender') }}:</strong> {{ $user->biography->gender ?? __('admin.not_set') }}</p>
            </div>
        </div>

        <div class="users-section">
            <h2 class="users-section-title">{{ __('admin.posts') }} ({{ $user->posts->count() }})</h2>
            <div class="users-table">
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('admin.content') }}</th>
                            <th>{{ __('admin.views') }}</th>
                            <th>{{ __('admin.created') }}</th>
                            <th>{{ __('admin.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->posts as $post)
                            <tr>
                                <td>{{ Str::limit($post->content, 50) }}</td>
                                <td>{{ $post->views }}</td>
                                <td>{{ $post->created_at->format('M d, Y') }}</td>
                                <td>
                                    <form action="{{ route('admin.posts.delete', $post) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="users-btn users-btn-danger" onclick="return confirm('{{ __('admin.confirm_delete_post') }}')">{{ __('admin.delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">{{ __('admin.no_posts') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="users-section">
            <h2 class="users-section-title">{{ __('admin.friends') }} ({{ $user->friends->count() }})</h2>
            <div class="users-table">
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('admin.name') }}</th>
                            <th>{{ __('admin.email') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->friends as $friend)
                            <tr>
                                <td>{{ $friend->name }}</td>
                                <td>{{ $friend->email }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">{{ __('admin.no_friends') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection