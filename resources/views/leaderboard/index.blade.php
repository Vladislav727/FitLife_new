@extends('layouts.app')

@section('title', __('leaderboard.title') . ' — FitLife')

@section('content')
<div class="lb-page">
    <h1 class="lb-heading">{{ __('leaderboard.title') }}</h1>

    <div class="lb-tabs">
        <a href="{{ route('leaderboard.index', ['tab' => 'calories']) }}"
           class="lb-tab {{ $tab === 'calories' ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
            {{ __('leaderboard.calories') }}
        </a>
        <a href="{{ route('leaderboard.index', ['tab' => 'water']) }}"
           class="lb-tab {{ $tab === 'water' ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/></svg>
            {{ __('leaderboard.water') }}
        </a>
        <a href="{{ route('leaderboard.index', ['tab' => 'progress']) }}"
           class="lb-tab {{ $tab === 'progress' ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
            {{ __('leaderboard.progress') }}
        </a>
        <a href="{{ route('leaderboard.index', ['tab' => 'activity']) }}"
           class="lb-tab {{ $tab === 'activity' ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            {{ __('leaderboard.activity') }}
        </a>
    </div>

    @php
        $items = match($tab) {
            'water' => $topWater,
            'progress' => $topProgress,
            'activity' => $topActivity,
            default => $topCalories,
        };
        $unit = match($tab) {
            'water' => __('leaderboard.ml'),
            'progress' => __('leaderboard.photos_unit'),
            'activity' => __('leaderboard.points'),
            default => __('leaderboard.kcal'),
        };
    @endphp

    <div class="lb-list">
        @forelse($items as $i => $item)
            <div class="lb-row {{ $i < 3 ? 'lb-row--top' : '' }}">
                <div class="lb-rank">
                    @if($i === 0)
                        <span class="lb-medal lb-medal--gold">🥇</span>
                    @elseif($i === 1)
                        <span class="lb-medal lb-medal--silver">🥈</span>
                    @elseif($i === 2)
                        <span class="lb-medal lb-medal--bronze">🥉</span>
                    @else
                        <span class="lb-rank__num">{{ $i + 1 }}</span>
                    @endif
                </div>
                <a href="{{ route('profile.show', $item->user) }}" class="lb-user">
                    <img src="{{ $item->user->avatar ? asset('storage/' . $item->user->avatar) : asset('storage/logo/defaultPhoto.jpg') }}"
                         alt="{{ $item->user->name }}" class="lb-avatar">
                    <div>
                        <span class="lb-name">{{ $item->user->name }}</span>
                        <span class="lb-username">{{ '@' . $item->user->username }}</span>
                    </div>
                </a>
                <div class="lb-value">
                    <span class="lb-value__num">{{ number_format($item->value) }}</span>
                    <span class="lb-value__unit">{{ $unit }}</span>
                </div>
            </div>
        @empty
            <div class="lb-empty">{{ __('leaderboard.no_data') }}</div>
        @endforelse
    </div>
</div>

<style>
.lb-page { max-width: 680px; margin: 0 auto; }
.lb-heading { font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin: 0 0 20px; }
.lb-tabs { display: flex; gap: 4px; margin-bottom: 20px; background: var(--bg-elevated); border-radius: var(--radius-md); padding: 4px; }
.lb-tab {
    flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px;
    padding: 10px 8px; font-size: 13px; font-weight: 600; font-family: inherit;
    color: var(--text-muted); text-decoration: none; border-radius: calc(var(--radius-md) - 2px);
    transition: all 0.2s;
}
.lb-tab:hover { color: var(--text-secondary); }
.lb-tab.active { background: var(--bg-surface); color: var(--primary); box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
.lb-list { display: flex; flex-direction: column; gap: 2px; background: var(--bg-surface); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); overflow: hidden; }
.lb-row { display: flex; align-items: center; padding: 12px 16px; gap: 14px; }
.lb-row--top { background: rgba(34,197,94,0.03); }
.lb-rank { width: 36px; text-align: center; flex-shrink: 0; }
.lb-rank__num { font-size: 0.9375rem; font-weight: 700; color: var(--text-muted); }
.lb-medal { font-size: 1.25rem; }
.lb-user { display: flex; align-items: center; gap: 10px; flex: 1; text-decoration: none; min-width: 0; }
.lb-avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; flex-shrink: 0; }
.lb-name { display: block; font-weight: 600; color: var(--text-primary); font-size: 0.9375rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.lb-username { display: block; font-size: 0.8125rem; color: var(--text-muted); }
.lb-value { text-align: right; flex-shrink: 0; }
.lb-value__num { font-size: 1.125rem; font-weight: 700; color: var(--primary); }
.lb-value__unit { font-size: 0.75rem; color: var(--text-muted); margin-left: 2px; }
.lb-empty { padding: 48px 24px; text-align: center; color: var(--text-muted); }
@media (max-width: 640px) {
    .lb-tab { font-size: 12px; padding: 8px 4px; }
    .lb-tab svg { display: none; }
}
[data-theme="light"] .lb-list { background: #fff; border-color: rgba(0,0,0,0.08); }
[data-theme="light"] .lb-tabs { background: #f3f4f6; }
[data-theme="light"] .lb-tab.active { background: #fff; }
[data-theme="light"] .lb-row--top { background: rgba(34,197,94,0.05); }
</style>
@endsection
