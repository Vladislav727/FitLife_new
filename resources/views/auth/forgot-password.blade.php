@extends('layouts.auth')

@section('title', __('auth.forgot_password') . ' - FitLife')

@section('content')
    <div class="auth-shell-header">
        <div class="auth-shell-badge">Recovery</div>
        <div class="auth-shell-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
        </div>
        <h1 class="auth-shell-title"><span class="auth-shell-title-gradient">{{ __('auth.forgot_password') }}</span></h1>
        <p class="auth-shell-subtitle">{{ __('auth.forgot_password_text') }}</p>
    </div>

    @if (session('status'))
        <div class="auth-shell-alert auth-shell-alert--success">
            {{ session('status') }}
        </div>
    @endif

    @if (session('reset_link'))
        <div class="auth-shell-alert auth-shell-alert--success auth-shell-stack">
            <a href="{{ session('reset_link') }}" class="auth-shell-submit" style="text-align:center;text-decoration:none;">{{ __('auth.open_reset_form') }}</a>
            <div class="auth-shell-reset-link">{{ session('reset_link') }}</div>
            <p class="auth-shell-note">{{ __('auth.reset_link_local_hint') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="auth-shell-alert auth-shell-alert--error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="auth-shell-form">
        @csrf

        <div class="auth-shell-form-group">
            <label for="email" class="auth-shell-label">{{ __('auth.email') }}</label>
            <div class="auth-shell-input-wrap">
                <svg class="auth-shell-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                </svg>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="auth-shell-input @error('email') is-error @enderror"
                    placeholder="{{ __('auth.email') }}"
                    required
                    autofocus
                >
            </div>
        </div>

        <button type="submit" class="auth-shell-submit">{{ __('auth.generate_reset_link') }}</button>
    </form>

    <div class="auth-shell-footer">
        <span>{{ __('auth.remember_password') }}</span>
        <a href="{{ route('login') }}">{{ __('auth.back_to_login') }}</a>
    </div>
@endsection