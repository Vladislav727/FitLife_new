@extends('layouts.app')
@section('title', __('messages.invite_to_group'))

@section('hide-mobile-nav', '1')

@section('content')
<div class="msg-page">
    <div class="msg-header">
        <a href="{{ route('groups.show', $group) }}" class="chat-back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M19 12H5m0 0l7 7m-7-7l7-7"/></svg>
        </a>
        <h1 class="msg-title">{{ __('messages.invite_to_group') }}: {{ $group->name }}</h1>
    </div>

    @if(session('success'))
        <div class="msg-alert msg-alert--success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="msg-alert msg-alert--error">{{ session('error') }}</div>
    @endif

    <div class="msg-list">
        @forelse($mutualFollowers as $user)
            <div class="msg-item msg-item--static">
                <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('storage/default-avatar/default-avatar.avif') }}" alt="{{ $user->name }}" class="msg-item__avatar">
                <div class="msg-item__content">
                    <span class="msg-item__name">{{ $user->name }}</span>
                    <span class="msg-item__meta">{{ '@' . $user->username }}</span>
                </div>
                <form action="{{ route('groups.sendInvite', $group) }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <button type="submit" class="msg-btn-invite">{{ __('messages.invite') }}</button>
                </form>
            </div>
        @empty
            <div class="msg-empty">
                <p>{{ __('messages.no_mutual_to_invite') }}</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
