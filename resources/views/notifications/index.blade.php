@extends('layouts.app')
@section('title', __('messages.notifications'))

@section('content')
<div class="msg-page">
    <div class="msg-header">
        <h1 class="msg-title">{{ __('messages.notifications') }}</h1>
    </div>

    @if(session('success'))
        <div class="msg-alert msg-alert--success">{{ session('success') }}</div>
    @endif

    <div class="msg-list">
        @forelse($invites as $invite)
            <div class="msg-item msg-item--static notif-item">
                <div class="msg-item__group-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="24" height="24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div class="msg-item__content">
                    <p class="notif-item__text">
                        <strong>{{ $invite->sender->name }}</strong> {{ __('messages.invited_you_to') }} <strong>{{ $invite->group->name }}</strong>
                    </p>
                    <span class="msg-item__time">{{ $invite->created_at->diffForHumans() }}</span>
                </div>
                <div class="notif-item__actions">
                    <form action="{{ route('notifications.invite.accept', $invite) }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit" class="notif-btn notif-btn--accept">{{ __('messages.accept') }}</button>
                    </form>
                    <form action="{{ route('notifications.invite.decline', $invite) }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit" class="notif-btn notif-btn--decline">{{ __('messages.decline') }}</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="msg-empty">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="48" height="48"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                <p>{{ __('messages.no_notifications') }}</p>
            </div>
        @endforelse

        {{ $invites->links() }}
    </div>
</div>
@endsection
