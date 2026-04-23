@extends('layouts.app')
@section('title', __('messages.notifications'))

@section('content')
<div class="msg-page">
    <div class="msg-header">
        <h1 class="msg-title">{{ __('messages.notifications') }}</h1>
        @if($notifications->where('read_at', null)->count() > 0)
            <form action="{{ route('notifications.read') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="notif-btn notif-btn--read-all">{{ __('messages.mark_all_read') }}</button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="msg-alert msg-alert--success">{{ session('success') }}</div>
    @endif

    {{-- Group invites --}}
    @foreach($invites as $invite)
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
    @endforeach

    {{-- Notifications (like/comment/mention) --}}
    <div class="msg-list">
        @forelse($notifications as $notif)
            @php
                $isUnread = is_null($notif->read_at);
                $post = null;
                if ($notif->notifiable_type === \App\Models\Post::class) {
                    $post = $notif->notifiable;
                } elseif ($notif->notifiable_type === \App\Models\Comment::class && $notif->notifiable) {
                    $post = $notif->notifiable->post;
                }
            @endphp
            <a href="{{ $post ? route('posts.index') . '#post-' . $post->id : '#' }}" class="msg-item msg-item--static notif-item {{ $isUnread ? 'notif-item--unread' : '' }}">
                <div class="notif-item__avatar">
                    <img src="{{ $notif->sender->avatar ? asset('storage/' . $notif->sender->avatar) : asset('storage/default-avatar/default-avatar.avif') }}" alt="" class="notif-avatar-img">
                    <span class="notif-type-icon notif-type-icon--{{ $notif->type }}">
                        @if($notif->type === 'like')
                            <svg viewBox="0 0 24 24" fill="currentColor" width="14" height="14"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                        @elseif($notif->type === 'comment')
                            <svg viewBox="0 0 24 24" fill="currentColor" width="14" height="14"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        @elseif($notif->type === 'mention')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"/></svg>
                        @endif
                    </span>
                </div>
                <div class="msg-item__content">
                    <p class="notif-item__text">
                        <strong>{{ $notif->sender->name }}</strong>
                        @if($notif->type === 'like')
                            {{ __('messages.notif_liked_post') }}
                        @elseif($notif->type === 'comment')
                            {{ __('messages.notif_commented_post') }}
                        @elseif($notif->type === 'mention')
                            {{ __('messages.notif_mentioned_you') }}
                        @endif
                    </p>
                    @if($post && $post->content)
                        <p class="notif-item__preview">{{ Str::limit($post->content, 80) }}</p>
                    @endif
                    <span class="msg-item__time">{{ $notif->created_at->diffForHumans() }}</span>
                </div>
                @if($isUnread)
                    <span class="notif-item__dot"></span>
                @endif
            </a>
        @empty
            @if($invites->isEmpty())
                <div class="msg-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="48" height="48"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    <p>{{ __('messages.no_notifications') }}</p>
                </div>
            @endif
        @endforelse

        {{ $notifications->links() }}
    </div>
</div>

@endsection
