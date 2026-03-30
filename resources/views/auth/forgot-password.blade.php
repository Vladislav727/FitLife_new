<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('auth.forgot_password') }} - FitLife</title>
    <link rel="icon" href="{{ asset('favicon.PNG') }}" type="image/png">
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
        <div class="auth-navbar-links">
            <a href="{{ route('login') }}" class="auth-navbar-link">{{ __('auth.back_to_login') }}</a>
        </div>
    </header>

    <div class="auth-container">
        <div class="auth-card">

            <div class="auth-header">
                <div class="auth-logo">
                    <div class="auth-logo-icon auth-logo-icon--warning">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                    </div>
                </div>
                <h1 class="auth-title">{{ __('auth.forgot_password') }}</h1>
                <p class="auth-subtitle">{{ __('auth.forgot_password_text') }}</p>
            </div>

            @if (session('status'))
                <div class="auth-status auth-status--success">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="auth-errors">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="auth-form">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">{{ __('auth.email') }}</label>
                    <div class="auth-input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="form-input @error('email') is-error @enderror"
                            placeholder="{{ __('auth.email') }}"
                            required
                            autofocus
                        >
                    </div>
                </div>

                <button type="submit" class="auth-submit">
                    {{ __('auth.send_reset_link') }}
                </button>
            </form>

            <div class="auth-footer">
                <span>{{ __('auth.remember_password') }}</span>
                <a href="{{ route('login') }}">{{ __('auth.login') }}</a>
            </div>
        </div>
    </div>
</body>
</html>
