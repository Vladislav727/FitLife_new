@extends('layouts.app')
@section('title', __('messages.conversations'))

@section('content')
<div class="msg-page">
    <div class="msg-header">
        <h1 class="msg-title">{{ __('messages.conversations') }}</h1>
    </div>

    <div class="msg-list">
        @forelse($conversations as $conversation)
            @php $other = $conversation->otherUser(Auth::user()); @endphp
            <a href="{{ route('profile.show', $other) }}" class="msg-item">
                <img src="{{ $other->avatar ? asset('storage/' . $other->avatar) : asset('storage/default-avatar/default-avatar.avif') }}" alt="{{ $other->name }}" class="msg-item__avatar">
                <div class="msg-item__content">
                    <div class="msg-item__top">
                        <span class="msg-item__name">{{ $other->name }}</span>
                        @if($conversation->latestMessage)
                            <span class="msg-item__time">{{ $conversation->latestMessage->created_at->diffForHumans(null, true) }}</span>
                        @endif
                    </div>
                    <p class="msg-item__preview">
                        @if($conversation->latestMessage)
                            {{ Str::limit($conversation->latestMessage->body, 60) }}
                        @else
                            {{ __('messages.no_messages_yet') }}
                        @endif
                    </p>
                </div>
                @php
                    $unread = $conversation->messages()->where('user_id', '!=', Auth::id())->whereNull('read_at')->count();
                @endphp
                @if($unread > 0)
                    <span class="msg-item__badge">{{ $unread }}</span>
                @endif
            </a>
        @empty
            <div class="msg-empty">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="48" height="48"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                <p>{{ __('messages.no_conversations') }}</p>
                <p class="msg-empty__hint">{{ __('messages.start_from_profile') }}</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
