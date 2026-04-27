@extends('layouts.auth')

@section('title', __('auth.register') . ' - FitLife')

@section('auth-body-class', 'auth-shell-page--scrollable')

@section('auth-shell-class', 'auth-shell--wide')

@section('content')
    <div class="auth-shell-header">
        <div class="auth-shell-badge">FitLife</div>
        <div class="auth-shell-icon">F</div>
        <h1 class="auth-shell-title"><span class="auth-shell-title-gradient">{{ __('auth.create_account') }}</span></h1>
        <p class="auth-shell-subtitle">{{ __('auth.register_subtitle') }}</p>
    </div>

    @if ($errors->any())
        <div class="auth-shell-alert auth-shell-alert--error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="auth-shell-form">
        @csrf

        <div class="auth-shell-form-grid">
            <div class="auth-shell-form-group">
                <label for="name" class="auth-shell-label">{{ __('auth.name') }}</label>
                <div class="auth-shell-input-wrap">
                    <svg class="auth-shell-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        class="auth-shell-input @error('name') is-error @enderror"
                        placeholder="{{ __('auth.name') }}"
                        required
                        autofocus
                    >
                </div>
            </div>

            <div class="auth-shell-form-group">
                <label for="username" class="auth-shell-label">{{ __('auth.username') }}</label>
                <div class="auth-shell-input-wrap">
                    <svg class="auth-shell-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="4"/>
                        <path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"/>
                    </svg>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username') }}"
                        class="auth-shell-input @error('username') is-error @enderror"
                        placeholder="{{ __('auth.username') }}"
                        required
                    >
                </div>
            </div>
        </div>

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
                >
            </div>
        </div>

        <div class="auth-shell-form-grid">
            <div class="auth-shell-form-group">
                <label for="password" class="auth-shell-label">{{ __('auth.password') }}</label>
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

            <div class="auth-shell-form-group">
                <label for="password_confirmation" class="auth-shell-label">{{ __('auth.confirm_password') }}</label>
                <div class="auth-shell-input-wrap">
                    <svg class="auth-shell-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="auth-shell-input"
                        placeholder="{{ __('auth.confirm_password') }}"
                        required
                    >
                    <button type="button" class="auth-shell-password-toggle" onclick="togglePassword('password_confirmation')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <label class="auth-shell-checkbox">
            <input type="checkbox" name="terms" class="auth-shell-checkbox-input" required>
            <span>{{ __('auth.agree_terms_prefix') }} <a href="{{ route('terms-of-service') }}">{{ __('auth.terms') }}</a> {{ __('auth.and') }} <a href="{{ route('privacy-policy') }}">{{ __('auth.privacy') }}</a></span>
        </label>

        <button type="submit" class="auth-shell-submit">{{ __('auth.register_cta') }}</button>
    </form>

    <div class="auth-shell-footer">
        <span>{{ __('auth.have_account') }}</span>
        <a href="{{ route('login') }}">{{ __('auth.login') }}</a>
    </div>
@endsection