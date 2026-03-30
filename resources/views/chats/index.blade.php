@extends('layouts.app')
@section('title', __('messages.chats'))

@section('content')
<div class="messenger">
    @include('chats.partials.sidebar')

    <div class="messenger__main">
        <div class="messenger__empty">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="56" height="56"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            <p>{{ __('messages.select_chat') }}</p>
        </div>
    </div>
</div>
@endsection
