@extends('layouts.app')
@section('title', __('messages.groups'))

@section('content')
<div class="msg-page">
    <div class="msg-header">
        <h1 class="msg-title">{{ __('messages.groups') }}</h1>
        <a href="{{ route('groups.create') }}" class="msg-btn-create">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            {{ __('messages.create_group') }}
        </a>
    </div>

    <div class="msg-list">
        @forelse($groups as $group)
            <a href="{{ route('groups.show', $group) }}" class="msg-item">
                @if($group->avatar)
                    <img src="{{ asset('storage/' . $group->avatar) }}" alt="{{ $group->name }}" class="msg-item__avatar">
                @else
                    <div class="msg-item__group-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="24" height="24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                @endif
                <div class="msg-item__content">
                    <div class="msg-item__top">
                        <span class="msg-item__name">{{ $group->name }}</span>
                        <span class="msg-item__meta">{{ $group->members_count }} {{ __('messages.members') }}</span>
                    </div>
                    <p class="msg-item__preview">
                        @if($group->latestMessage)
                            <strong>{{ $group->latestMessage->user->name }}:</strong> {{ Str::limit($group->latestMessage->body, 50) }}
                        @else
                            {{ __('messages.no_messages_yet') }}
                        @endif
                    </p>
                </div>
            </a>
        @empty
            <div class="msg-empty">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="48" height="48"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <p>{{ __('messages.no_groups') }}</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
