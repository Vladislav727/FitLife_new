<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('auth.confirm_password') }} - FitLife</title>
    <link rel="icon" href="{{ asset('favicon.PNG') }}" type="image/png">
    <style>html,body{overscroll-behavior-y:none;overscroll-behavior-x:auto}</style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="auth-layout">

    <header class="auth-navbar">
        <a href="{{ url('/') }}" class="auth-navbar-brand">
            <div class="auth-navbar-brand-icon">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M13.5 5.5c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zM9.8 8.9L7 23h2.1l1.8-8 2.1 2v6h2v-7.5l-2.1-2 .6-3C14.8 12 16.8 13 19 13v-2c-1.9 0-3.5-1-4.3-2.4l-1-1.6c-.4-.6-1-1-1.7-1-.3 0-.5.1-.8.1L6 8.3V13h2V9.6l1.8-.7"/>
                </svg>
            </div>
            <span class="auth-navbar-brand-text">FitLife</span>
        </a>
    </header>

    <div class="auth-container">
        <div class="auth-card">

            <div class="auth-header">
                <div class="auth-logo">
                    <div class="auth-logo-icon auth-logo-icon--warning">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                </div>
                <h1 class="auth-title">{{ __('auth.confirm_password') }}</h1>
                <p class="auth-subtitle">{{ __('auth.confirm_password_text') }}</p>
            </div>

            @if ($errors->any())
                <div class="auth-errors">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.confirm') }}" class="auth-form">
                @csrf

                <div class="form-group">
                    <label for="password" class="form-label">{{ __('auth.password') }}</label>
                    <div class="auth-input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input @error('password') is-error @enderror"
                            placeholder="{{ __('auth.password') }}"
                            required
                            autofocus
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="auth-submit">
                    {{ __('auth.confirm') }}
                </button>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/auth-confirm-password.js') }}"></script>
</body>
</html>
