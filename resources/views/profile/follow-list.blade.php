@extends('layouts.app')

@section('title', $title . ' — FitLife')

@section('styles')
<style>.mobile-bottom-nav { display: none !important; }</style>
@endsection

@section('content')
<div class="fl-page">
    <div class="fl-header">
        <a href="{{ route('profile.show', $user) }}" class="fl-back">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="fl-title">{{ $title }}</h1>
            <span class="fl-subtitle">{{ '@' . $user->username }}</span>
        </div>
    </div>

    <div class="fl-list">
        @forelse($users as $person)
            <div class="fl-item">
                <a href="{{ route('profile.show', $person) }}" class="fl-item__left">
                    <img src="{{ $person->avatar ? asset('storage/' . $person->avatar) : asset('storage/logo/default-avatar.avif') }}"
                         alt="{{ $person->name }}" class="fl-item__avatar">
                    <div>
                        <span class="fl-item__name">{{ $person->name }}</span>
                        <span class="fl-item__username">{{ '@' . $person->username }}</span>
                    </div>
                </a>
                @if(Auth::id() !== $person->id)
                    <form action="{{ route('follow.toggle', $person) }}" method="POST">
                        @csrf
                        @if(Auth::user()->isFollowing($person))
                            <button class="fl-btn fl-btn--outline">{{ __('profile.following') }}</button>
                        @else
                            <button class="fl-btn fl-btn--primary">{{ __('profile.follow') }}</button>
                        @endif
                    </form>
                @endif
            </div>
        @empty
            <div class="fl-empty">{{ __('profile.no_users') }}</div>
        @endforelse
    </div>

    <div class="fl-pagination">{{ $users->links() }}</div>
</div>

<style>
.fl-page { max-width: 560px; margin: 0 auto; }
.fl-header { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
.fl-back { color: var(--text-muted); transition: color 0.2s; display: flex; }
.fl-back:hover { color: var(--primary); }
.fl-title { font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin: 0; }
.fl-subtitle { font-size: 0.875rem; color: var(--text-muted); }
.fl-list { display: flex; flex-direction: column; gap: 2px; background: var(--bg-surface); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); overflow: hidden; }
.fl-item { display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; }
.fl-item__left { display: flex; align-items: center; gap: 12px; text-decoration: none; }
.fl-item__avatar { width: 48px; height: 48px; border-radius: 50%; object-fit: cover; }
.fl-item__name { display: block; font-weight: 600; color: var(--text-primary); font-size: 0.9375rem; }
.fl-item__username { display: block; font-size: 0.8125rem; color: var(--text-muted); }
.fl-btn { padding: 6px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; font-family: inherit; cursor: pointer; border: none; transition: all 0.2s; }
.fl-btn--primary { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: #000; }
.fl-btn--outline { background: transparent; color: var(--text-primary); border: 1px solid var(--border-default); }
.fl-btn--outline:hover { border-color: var(--primary); color: var(--primary); }
.fl-empty { padding: 48px 24px; text-align: center; color: var(--text-muted); }
.fl-pagination { margin-top: 16px; }
[data-theme="light"] .fl-list { background: #fff; border-color: rgba(0,0,0,0.08); }
@media (max-width: 768px) {
    .fl-page { padding: 0; }
    .fl-header { gap: 10px; margin-bottom: 14px; }
    .fl-title { font-size: 1.125rem; }
    .fl-subtitle { font-size: 0.8125rem; }
    .fl-item { padding: 10px 12px; }
    .fl-item__avatar { width: 40px; height: 40px; }
    .fl-item__left { gap: 10px; }
    .fl-item__name { font-size: 0.875rem; }
    .fl-item__username { font-size: 0.75rem; }
    .fl-btn { padding: 5px 14px; font-size: 12px; }
    .fl-empty { padding: 32px 16px; font-size: 0.875rem; }
}
@media (max-width: 480px) {
    .fl-header { gap: 8px; margin-bottom: 10px; }
    .fl-back svg { width: 18px; height: 18px; }
    .fl-title { font-size: 1rem; }
    .fl-subtitle { font-size: 0.75rem; }
    .fl-item { padding: 8px 10px; }
    .fl-item__avatar { width: 36px; height: 36px; }
    .fl-item__left { gap: 8px; }
    .fl-item__name { font-size: 0.8125rem; }
    .fl-item__username { font-size: 0.6875rem; }
    .fl-btn { padding: 4px 12px; font-size: 11px; border-radius: 6px; }
    .fl-empty { padding: 24px 12px; font-size: 0.8125rem; }
}
</style>
@endsection
