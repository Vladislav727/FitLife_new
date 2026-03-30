<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('auth.verify_email') }} - FitLife</title>
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
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="auth-navbar-link" style="background: none; border: none; cursor: pointer;">
                    {{ __('auth.logout') }}
                </button>
            </form>
        </div>
    </header>

    <div class="auth-container">
        <div class="auth-card">

            <div class="auth-header">
                <div class="auth-logo">
                    <div class="auth-logo-icon auth-logo-icon--info">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                    </div>
                </div>
                <h1 class="auth-title">{{ __('auth.verify_email') }}</h1>
                <p class="auth-subtitle">{{ __('auth.verify_email_text') }}</p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="auth-status auth-status--success">
                    {{ __('auth.verification_link_sent') }}
                </div>
            @endif

            <div class="auth-info">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="16" x2="12" y2="12"/>
                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                </svg>
                <p>{{ __('auth.verify_email_info') }}</p>
            </div>

            <div class="auth-actions">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="auth-submit">
                        {{ __('auth.resend_verification') }}
                    </button>
                </form>
            </div>

            <div class="auth-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="auth-link-button">
                        {{ __('auth.logout') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
