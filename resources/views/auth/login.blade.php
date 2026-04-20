<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('auth.login') }} - FitLife</title>
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
            --text-muted: #a1a1aa;
            --border: #27272a;
            --error: #ef4444;
            --gradient-1: linear-gradient(135deg, #22c55e 0%, #06b6d4 100%);
            --glass-surface: rgba(255, 255, 255, 0.08);
            --glass-edge: rgba(255, 255, 255, 0.2);
            --glass-shadow: rgba(0, 0, 0, 0.35);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Space Grotesk', sans-serif;
            background: var(--bg-dark);
            background-image:
                radial-gradient(circle at 20% 20%, rgba(34, 197, 94, 0.18), transparent 24%),
                radial-gradient(circle at 78% 18%, rgba(255, 255, 255, 0.08), transparent 18%),
                radial-gradient(circle at 78% 72%, rgba(6, 182, 212, 0.16), transparent 26%),
                linear-gradient(160deg, #07110d 0%, #090909 45%, #03171b 100%);
            color: var(--text);
            line-height: 1.6;
            min-height: 100vh;
            overflow-x: hidden;
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
            filter: blur(12px);
        }

        .auth-container::after {
            content: '';
            position: absolute;
            width: 420px;
            height: 420px;
            top: 14%;
            left: 50%;
            transform: translateX(-50%);
            background: radial-gradient(circle, rgba(255, 255, 255, 0.14) 0%, rgba(255, 255, 255, 0.02) 48%, transparent 72%);
            filter: blur(26px);
            opacity: 0.85;
            pointer-events: none;
        }

        .auth-card {
            width: 100%;
            max-width: 420px;
            background:
                linear-gradient(145deg, rgba(255, 255, 255, 0.12), rgba(255, 255, 255, 0.04)),
                linear-gradient(180deg, rgba(8, 12, 12, 0.88), rgba(10, 14, 16, 0.72));
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 28px;
            padding: 2.5rem;
            position: relative;
            z-index: 1;
            overflow: hidden;
            backdrop-filter: blur(24px) saturate(160%);
            -webkit-backdrop-filter: blur(24px) saturate(160%);
            box-shadow:
                0 30px 80px var(--glass-shadow),
                inset 0 1px 0 rgba(255, 255, 255, 0.18),
                inset 0 -1px 0 rgba(255, 255, 255, 0.06);
        }

        .auth-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at top left, rgba(255, 255, 255, 0.26), transparent 34%),
                radial-gradient(circle at 85% 12%, rgba(34, 197, 94, 0.2), transparent 24%),
                linear-gradient(135deg, rgba(255, 255, 255, 0.12), transparent 36%);
            pointer-events: none;
        }

        .auth-card::after {
            content: '';
            position: absolute;
            inset: 1px;
            border-radius: 27px;
            border: 1px solid rgba(255, 255, 255, 0.06);
            pointer-events: none;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
            z-index: 1;
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
        }

        /* Alerts */
        .alert {
            padding: 1rem;
            border-radius: 16px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            position: relative;
            z-index: 1;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
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
            margin-bottom: 1.25rem;
            position: relative;
            z-index: 1;
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
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.04));
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 16px;
            color: var(--text);
            font-size: 0.95rem;
            font-family: inherit;
            transition: all 0.3s;
            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, 0.08),
                0 10px 24px rgba(0, 0, 0, 0.16);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
        }

        .form-input:focus {
            outline: none;
            border-color: rgba(125, 255, 184, 0.6);
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.14), rgba(255, 255, 255, 0.06));
            box-shadow:
                0 0 0 3px rgba(34, 197, 94, 0.18),
                0 18px 36px rgba(6, 182, 212, 0.12);
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
            background:
                linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.04)),
                var(--gradient-1);
            border: 1px solid rgba(255, 255, 255, 0.24);
            border-radius: 16px;
            color: var(--bg-dark);
            font-size: 1rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 0.5rem;
            box-shadow: 0 18px 40px rgba(34, 197, 94, 0.26);
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
            position: relative;
            z-index: 1;
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
        <div class="auth-card">

            <div class="auth-header">
                <div class="auth-icon">F</div>
                <h1><span class="gradient">{{ __('auth.welcome_back') }}</span></h1>
                <p>{{ __('auth.login_subtitle') }}</p>
            </div>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">{{ __('auth.email') }}</label>
                    <div class="input-wrapper">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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

                <div class="form-group">
                    <div class="form-label-row">
                        <label for="password" class="form-label">{{ __('auth.password') }}</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="form-link">{{ __('auth.forgot_password') }}</a>
                        @endif
                    </div>
                    <div class="input-wrapper">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember" class="checkbox-input">
                        <span>{{ __('auth.remember_me') }}</span>
                    </label>
                </div>

                <button type="submit" class="submit-btn">
                    {{ __('auth.login') }}
                </button>
            </form>

            <div class="auth-footer">
                <span>{{ __('auth.no_account') }}</span>
                <a href="{{ route('register') }}">{{ __('auth.register') }}</a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const btn = input.nextElementSibling;
            if (input.type === 'password') {
                input.type = 'text';
                btn.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                    <line x1="1" y1="1" x2="23" y2="23"/>
                </svg>`;
            } else {
                input.type = 'password';
                btn.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>`;
            }
        }

        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.style.background = 'rgba(10, 10, 10, 0.95)';
            } else {
                nav.style.background = 'rgba(10, 10, 10, 0.8)';
            }
        });
    </script>
</body>
</html>
