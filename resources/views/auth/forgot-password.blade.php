<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('auth.forgot_password') }} - FitLife</title>
    <link rel="icon" href="{{ asset('favicon.PNG') }}" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #0a0a0a;
            --bg-card: #111111;
            --bg-elevated: #1a1a1a;
            --primary: #22c55e;
            --primary-glow: rgba(34, 197, 94, 0.4);
            --secondary: #06b6d4;
            --text: #ffffff;
            @extends('layouts.auth')

            @section('title', __('auth.forgot_password') . ' - FitLife')

            @section('nav-action')
                <a href="{{ route('login') }}" class="auth-shell-nav-button auth-shell-nav-button--ghost">{{ __('auth.back_to_login') }}</a>
            @endsection

            @section('content')
                            <div class="auth-shell-header">
                                <div class="auth-shell-badge">Recovery</div>
                                <div class="auth-shell-icon">
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(10, 10, 10, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
                                <h1 class="auth-shell-title"><span class="auth-shell-title-gradient">{{ __('auth.forgot_password') }}</span></h1>
                                <p class="auth-shell-subtitle">{{ __('auth.forgot_password_text') }}</p>
                            </div>
        .logo {
            display: flex;
                                <div class="auth-shell-alert auth-shell-alert--success">
            gap: 0.75rem;
            text-decoration: none;
        }

        .logo-icon {
                                <div class="auth-shell-alert auth-shell-alert--success auth-shell-stack">
                                    <a href="{{ session('reset_link') }}" class="auth-shell-submit" style="text-align:center;text-decoration:none;">
            background: var(--gradient-1);
            border-radius: 10px;
                                    <div class="auth-shell-reset-link">{{ session('reset_link') }}</div>
                                    <p class="auth-shell-note">{{ __('auth.reset_link_local_hint') }}</p>
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
            color: #08110d;
                                <div class="auth-shell-alert auth-shell-alert--error">

        .logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.3s;
            cursor: pointer;
            border: none;
        }

        .btn-ghost {
            background: transparent;
            color: var(--text);
            border: 1px solid var(--border);
        }

        .btn-ghost:hover {
            background: var(--bg-elevated);
            border-color: var(--primary);
        }

        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6rem 2rem 2rem;
            position: relative;
        }

        .auth-container::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 30% 30%, var(--primary-glow) 0%, transparent 50%),
                radial-gradient(circle at 70% 70%, rgba(6, 182, 212, 0.2) 0%, transparent 50%);
            pointer-events: none;
        }

        .auth-shell {
            width: 100%;
            max-width: 460px;
            position: relative;
            z-index: 1;
            margin: 0 auto;
        }

        .auth-card {
            width: 100%;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.35);
        }

        .auth-card::before {
            content: '';
            position: absolute;
            inset: 0 0 auto 0;
            height: 120px;
            background: linear-gradient(180deg, rgba(34, 197, 94, 0.12), transparent);
            pointer-events: none;
        }

        .auth-card > * {
            position: relative;
            z-index: 1;
        }

        .auth-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.35rem 0.75rem;
            margin: 0 auto 1rem;
            border-radius: 999px;
            border: 1px solid rgba(34, 197, 94, 0.24);
            background: rgba(34, 197, 94, 0.08);
            color: var(--primary);
            font-size: 0.78rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-weight: 700;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .auth-icon {
            width: 60px;
            height: 60px;
            background: var(--gradient-1);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: #08110d;
        }

        .auth-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .auth-header h1 .gradient {
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .auth-header p {
            color: var(--text-muted);
            font-size: 0.95rem;
            max-width: 30ch;
            margin: 0 auto;
        }

        .auth-status,
        .auth-errors {
            padding: 1rem;
            border-radius: 14px;
            margin-bottom: 1.25rem;
            font-size: 0.9rem;
        }

        .auth-status--success {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: var(--primary);
        }

        .auth-errors {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: var(--error);
        }

        .auth-errors ul {
            padding-left: 1.25rem;
        }

        .auth-form {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .form-group {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text);
            margin-bottom: 0.5rem;
        }

        .auth-input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            color: var(--text-muted);
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 2.75rem;
            background: var(--bg-elevated);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: var(--text);
            font-size: 0.95rem;
            font-family: inherit;
            transition: all 0.3s;
        }

        .form-input:hover {
            border-color: rgba(34, 197, 94, 0.35);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }

        .form-input.is-error {
            border-color: var(--error);
        }

        .auth-submit {
            width: 100%;
            padding: 1rem;
            background: var(--gradient-1);
            border: none;
            border-radius: 12px;
            color: #08110d;
            font-size: 1rem;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 18px 34px rgba(34, 197, 94, 0.18);
        }

        .auth-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px var(--primary-glow);
        }

        .reset-link-box {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 1px solid rgba(34, 197, 94, 0.25);
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.03);
            overflow: auto;
            word-break: break-all;
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .auth-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .auth-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            margin-left: 0.25rem;
        }

        @media (max-width: 480px) {
            nav {
                padding: 1rem;
            }

            .auth-card {
                padding: 1.5rem;
                border-radius: 18px;
            }

            .auth-header h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <nav>
        <a href="{{ url('/') }}" class="logo">
            <div class="logo-icon">F</div>
            <span class="logo-text">FitLife</span>
        </a>

        <div class="nav-buttons">
            <a href="{{ route('login') }}" class="btn btn-ghost">{{ __('auth.back_to_login') }}</a>
        </div>
    </nav>

    <div class="auth-container">
        <div class="auth-shell">
            <div class="auth-card">
                <div class="auth-header">
                    <div class="auth-badge">Recovery</div>
                    <div class="auth-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="28" height="28">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                    </div>
                    <h1><span class="gradient">{{ __('auth.forgot_password') }}</span></h1>
                    <p>{{ __('auth.forgot_password_text') }}</p>
                </div>

                @if (session('status'))
                    <div class="auth-status auth-status--success">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('reset_link'))
                    <div class="auth-status auth-status--success" style="display:flex;flex-direction:column;gap:0.75rem;align-items:flex-start;">
                        <a href="{{ session('reset_link') }}" class="auth-submit" style="text-align:center;text-decoration:none;">
                            {{ __('auth.open_reset_form') }}
                        </a>
                        <div class="reset-link-box">{{ session('reset_link') }}</div>
                        <p style="margin:0;color:var(--text-muted);font-size:0.85rem;">{{ __('auth.reset_link_local_hint') }}</p>
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

                <form method="POST" action="{{ route('password.email') }}" class="auth-shell-form">
                    @csrf

                    <div class="auth-shell-form-group">
                        <label for="email" class="auth-shell-label">{{ __('auth.email') }}</label>
                        <div class="auth-shell-input-wrap">
                            <svg class="auth-shell-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="auth-shell-input @error('email') is-error @enderror" placeholder="{{ __('auth.email') }}" required autofocus>
                        </div>
                    </div>

                    <button type="submit" class="auth-shell-submit">{{ __('auth.generate_reset_link') }}</button>
                </form>

                <div class="auth-shell-footer">
                    <span>{{ __('auth.remember_password') }}</span>
                    <a href="{{ route('login') }}">{{ __('auth.login') }}</a>
                </div>
@endsection
