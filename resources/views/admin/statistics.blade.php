@extends('layouts.app')

@section('content')
<div class="admin-container">
    <header class="admin-header">
        <h1 class="admin-title">{{ __('admin.statistics') }}</h1>
        <a href="{{ route('admin.dashboard') }}" class="admin-back-btn">{{ __('admin.back_to_dashboard') }}</a>
    </header>

    <div class="admin-section">
        <h2 class="admin-section-title">{{ __('admin.user_registrations') }}</h2>
        <div class="admin-chart">
            <canvas id="userChart" data-user-stats='@json($userStats)'></canvas>
        </div>
    </div>

    <div class="admin-section">
        <h2 class="admin-section-title">{{ __('admin.post_creations') }}</h2>
        <div class="admin-chart">
            <canvas id="postChart" data-post-stats='@json($postStats)'></canvas>
        </div>
    </div>
</div>

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/admin-statistics.js') }}"></script>
@endsection
