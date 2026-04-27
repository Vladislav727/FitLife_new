@extends('layouts.auth')

@section('title', __('auth.login') . ' - FitLife')

@section('content')
    <div class="auth-shell-header">
        <div class="auth-shell-badge">FitLife</div>
        <div class="auth-shell-icon">F</div>
        <h1 class="auth-shell-title"><span class="auth-shell-title-gradient">{{ __('auth.welcome_back') }}</span></h1>
        <p class="auth-shell-subtitle">{{ __('auth.login_subtitle') }}</p>
    </div>

    @if (session('status'))
        <div class="auth-shell-alert auth-shell-alert--success">
            {{ session('status') }}
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

    <form method="POST" action="{{ route('login') }}" class="auth-shell-form">
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

        <div class="auth-shell-form-group">
            <div class="auth-shell-label-row">
                <label for="password" class="auth-shell-label" style="margin-bottom:0;">{{ __('auth.password') }}</label>
                @if (Route::has('password.request'))
                    <a class="auth-shell-link" href="{{ route('password.request') }}">{{ __('auth.forgot_password') }}</a>
                @endif
            </div>
            <div class="auth-shell-input-wrap">
                <svg class="auth-shell-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="auth-shell-input @error('password') is-error @enderror"
                    placeholder="{{ __('auth.password') }}"
                    required
                >
                <button type="button" class="auth-shell-password-toggle" onclick="togglePassword('password')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
        </div>

        <label class="auth-shell-checkbox">
            <input type="checkbox" name="remember" class="auth-shell-checkbox-input">
            <span>{{ __('auth.remember_me') }}</span>
        </label>

        <button type="submit" class="auth-shell-submit">{{ __('auth.login_cta') }}</button>
    </form>

    <div class="auth-shell-footer">
        <span>{{ __('auth.no_account') }}</span>
        <a href="{{ route('register') }}">{{ __('auth.register') }}</a>
    </div>
@endsection