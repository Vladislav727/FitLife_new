<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@extends('layouts.auth')

@section('title', __('auth.login') . ' - FitLife')

@section('nav-text', __('auth.no_account'))

@section('nav-action')
    <a href="{{ route('register') }}" class="auth-shell-nav-button auth-shell-nav-button--primary">{{ __('auth.register') }}</a>
@endsection

@section('content')
            <div class="auth-shell-header">
                <div class="auth-shell-badge">FitLife</div>
                <div class="auth-shell-icon">F</div>
                <h1 class="auth-shell-title"><span class="auth-shell-title-gradient">{{ __('auth.welcome_back') }}</span></h1>
                <p class="auth-shell-subtitle">{{ __('auth.login_subtitle') }}</p>
            </div>
                radial-gradient(circle at 80% 15%, rgba(6, 182, 212, 0.12), transparent 22%),
                linear-gradient(180deg, #0a0a0a 0%, #090d0c 100%);
            color: var(--text);
            line-height: 1.6;
            min-height: 100vh;
        }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-dark); }
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 4px; }

        /* Navigation */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(10, 10, 10, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.18);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: var(--gradient-1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
        }

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

        .nav-buttons span {
            color: var(--text-muted);
            font-size: 0.9rem;
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

        .btn-primary {
            background: var(--gradient-1);
            color: var(--bg-dark);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px var(--primary-glow);
        }

        /* Main Container */
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6rem 2rem 2rem;
            position: relative;
        }

        .auth-shell {
            width: 100%;
            max-width: 460px;
            position: relative;
            z-index: 1;
            margin: 0 auto;
        }

        .auth-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 30% 30%, var(--primary-glow) 0%, transparent 50%),
                radial-gradient(circle at 70% 70%, rgba(6, 182, 212, 0.2) 0%, transparent 50%);
            pointer-events: none;
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
            font-size: 1.5rem;
            font-weight: 700;
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
            max-width: 28ch;
            margin: 0 auto;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        /* Alerts */
        .alert {
            padding: 1rem;
            border-radius: 14px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: var(--primary);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: var(--error);
        }

        .alert-error ul {
            margin: 0;
            padding-left: 1.25rem;
        }

        .alert-error li {
            margin-bottom: 0.25rem;
        }

        /* Form */
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

        .form-label-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .form-link {
            font-size: 0.85rem;
            color: var(--primary);
            text-decoration: none;
            transition: opacity 0.3s;
        }

        .form-link:hover {
            opacity: 0.8;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper svg {
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

        .form-input::placeholder {
            color: var(--text-muted);
        }

        .form-input.is-error {
            border-color: var(--error);
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .password-toggle svg {
            position: static;
            transform: none;
            width: 18px;
            height: 18px;
        }

        .password-toggle:hover {
            color: var(--text);
        }

        /* Checkbox */
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .checkbox-input {
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            padding: 1rem;
            background: var(--gradient-1);
            border: none;
            border-radius: 12px;
            color: var(--bg-dark);
            font-size: 1rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 0.5rem;
            box-shadow: 0 18px 34px rgba(34, 197, 94, 0.18);
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px var(--primary-glow);
        }

        /* Footer */
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

        .auth-footer a:hover {
            text-decoration: underline;
        }

        /* Mobile */
        @media (max-width: 480px) {
            nav {
                padding: 1rem;
            }

            .nav-buttons span {
                display: none;
            }

            .auth-card {
                padding: 1.5rem;
                border-radius: 18px;
            }

            form {
                gap: 1rem;
            }

            .auth-header h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

    <nav>
        <a href="/" class="logo">
            <div class="logo-icon">F</div>
            <span class="logo-text">FitLife</span>
        </a>

        <div class="nav-buttons">
            <span>{{ __('auth.no_account') }}</span>
            <a href="{{ route('register') }}" class="btn btn-primary">{{ __('auth.register') }}</a>
        </div>
    </nav>

    <div class="auth-container">
        <div class="auth-shell">
            <div class="auth-card">

            <div class="auth-header">
                <div class="auth-badge">FitLife</div>
                <div class="auth-icon">F</div>
                <h1><span class="gradient">{{ __('auth.welcome_back') }}</span></h1>
                <p>{{ __('auth.login_subtitle') }}</p>
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
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
                            <a href="{{ route('password.request') }}" class="auth-shell-link">{{ __('auth.forgot_password') }}</a>
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

                <div class="auth-shell-form-group">
                    <label class="auth-shell-checkbox">
                        <input type="checkbox" name="remember">
                        <span>{{ __('auth.remember_me') }}</span>
                    </label>
                </div>

                <button type="submit" class="auth-shell-submit">
                    {{ __('auth.login') }}
                </button>
            </form>

            <div class="auth-shell-footer">
                <span>{{ __('auth.no_account') }}</span>
                <a href="{{ route('register') }}">{{ __('auth.register') }}</a>
            </div>
@endsection
