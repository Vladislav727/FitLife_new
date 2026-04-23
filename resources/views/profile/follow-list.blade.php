@extends('layouts.app')

@section('title', $title . ' — FitLife')

@section('hide-mobile-nav', '1')

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
                    <img src="{{ $person->avatar ? asset('storage/' . $person->avatar) : asset('storage/default-avatar/default-avatar.avif') }}"
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
@endsection
