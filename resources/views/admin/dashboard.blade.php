@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/admindashboard.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="admindashboard-content">
        <header class="admindashboard-header">
            <h1 class="admindashboard-title">{{ __('admin.dashboard') }}</h1>
            <p class="admindashboard-subtitle">{{ __('admin.dashboard_subtitle') }}</p>
        </header>

        <div class="admindashboard-stats-grid">
            <div class="admindashboard-stat-card">
                <div class="admindashboard-stat-icon">👥</div>
                <h3 class="admindashboard-stat-number">{{ $totalUsers }}</h3>
                <p class="admindashboard-stat-label">{{ __('admin.total_users') }}</p>
                <a href="{{ route('admin.users') }}" class="admindashboard-btn admindashboard-btn-primary">{{ __('admin.manage_users') }}</a>
            </div>
            <div class="admindashboard-stat-card">
                <div class="admindashboard-stat-icon">📝</div>
                <h3 class="admindashboard-stat-number">{{ $totalPosts }}</h3>
                <p class="admindashboard-stat-label">{{ __('admin.total_posts') }}</p>
                <a href="{{ route('admin.posts') }}" class="admindashboard-btn admindashboard-btn-primary">{{ __('admin.manage_posts') }}</a>
            </div>
            <div class="admindashboard-stat-card">
                <div class="admindashboard-stat-icon">🟢</div>
                <h3 class="admindashboard-stat-number">{{ $activeUsers }}</h3>
                <p class="admindashboard-stat-label">{{ __('admin.active_users') }}</p>
            </div>
        </div>

        <div class="admindashboard-sections">
            <div class="admindashboard-section">
                <h2 class="admindashboard-section-title">{{ __('admin.recent_posts') }}</h2>
                <div class="admindashboard-table">
                    <table>
                        <thead>
                            <tr>
                                <th>{{ __('admin.user') }}</th>
                                <th>{{ __('admin.content') }}</th>
                                <th>{{ __('admin.date') }}</th>
                                <th>{{ __('admin.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPosts as $post)
                                <tr>
                                    <td>{{ $post->user->name }}</td>
                                    <td>{{ Str::limit($post->content, 50) }}</td>
                                    <td>{{ $post->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('profile.show', $post->user) }}" class="admindashboard-btn admindashboard-btn-primary">{{ __('admin.view_profile') }}</a>
                                        <form action="{{ route('admin.posts.delete', $post) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admindashboard-btn admindashboard-btn-danger" onclick="return confirm('{{ __('admin.confirm_delete_post') }}')">{{ __('admin.delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">{{ __('admin.no_recent_posts') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection