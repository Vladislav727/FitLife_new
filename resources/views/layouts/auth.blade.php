<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FitLife')</title>
    <link rel="icon" href="{{ asset('favicon.PNG') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        html, body {
            background-color: #040506;
            overscroll-behavior-y: none;
            overscroll-behavior-x: auto;
        }

        :root {
            --auth-shell-bg: #040506;
            --auth-shell-card: rgba(14, 16, 20, 0.82);
            --auth-shell-elevated: rgba(255, 255, 255, 0.04);
            --auth-shell-primary: #d9ff61;
            --auth-shell-primary-glow: rgba(217, 255, 97, 0.18);
            --auth-shell-text: #f4f7fb;
            --auth-shell-text-muted: rgba(244, 247, 251, 0.52);
            --auth-shell-border: rgba(255, 255, 255, 0.07);
            --auth-shell-error: #ef4444;
            --auth-shell-gradient: linear-gradient(135deg, #e8ff80 0%, #d9ff61 45%, #aaed3f 100%);
        }

        .auth-shell-page {
            font-family: 'Inter', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(34, 197, 94, 0.14), transparent 32%),
                radial-gradient(circle at top right, rgba(249, 115, 22, 0.16), transparent 28%),
                linear-gradient(180deg, #040506 0%, #090b0f 42%, #050607 100%);
            color: var(--auth-shell-text);
            line-height: 1.6;
            min-height: 100vh;
            margin: 0;
        }

        .auth-shell-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 0 2.5rem;
            height: 64px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(6, 8, 11, 0.72);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .auth-shell-logo-img {
            height: 32px;
            width: auto;
            display: block;
        }

        .auth-shell-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .auth-shell-logo-icon {
            width: 38px;
            height: 38px;
            background: var(--auth-shell-gradient);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.1rem;
            color: #07080a;
            flex-shrink: 0;
            font-family: 'Inter', sans-serif;
        }

        .auth-shell-logo-text {
            font-family: 'Inter', sans-serif;
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #f4f7fb;
            -webkit-text-fill-color: unset;
            background: none;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .auth-shell-nav-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
            color: var(--auth-shell-text-muted);
            font-size: 0.95rem;
        }

        .auth-shell-nav-button {
            padding: 0.6rem 1.25rem;
            border-radius: 999px;
            font-weight: 600;
            font-size: 0.88rem;
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid transparent;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-sizing: border-box;
        }

        .auth-shell-nav-button--primary {
            background: var(--auth-shell-gradient);
            color: #07080a;
            border-color: transparent;
        }

        .auth-shell-nav-button--ghost {
            background: transparent;
            color: var(--auth-shell-text);
            border-color: rgba(255, 255, 255, 0.1);
        }

        .auth-shell-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6rem 2rem 2rem;
            position: relative;
            box-sizing: border-box;
        }

        .auth-shell-container::before {
            display: none;
        }

        .auth-shell {
            width: 100%;
            max-width: 460px;
            position: relative;
            z-index: 1;
            margin: 0 auto;
        }

        .auth-shell--wide {
            max-width: 540px;
        }

        .auth-shell-card {
            width: 100%;
            background: rgba(14, 16, 20, 0.72);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 28px;
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.45), inset 0 1px 0 rgba(255,255,255,0.04);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            box-sizing: border-box;
        }

        .auth-shell-card::before {
            content: '';
            position: absolute;
            inset: 0 0 auto 0;
            height: 100px;
            background: linear-gradient(180deg, rgba(217, 255, 97, 0.06), transparent);
            pointer-events: none;
        }

        .auth-shell-card > * {
            position: relative;
            z-index: 1;
        }

        .auth-shell-header {
            text-align: center;
            margin-bottom: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .auth-shell-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.3rem 0.8rem;
            margin: 0 auto 1rem;
            border-radius: 999px;
            border: 1px solid rgba(217, 255, 97, 0.2);
            background: rgba(217, 255, 97, 0.06);
            color: var(--auth-shell-primary);
            font-size: 0.72rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            font-weight: 700;
        }

        .auth-shell-icon {
            width: 58px;
            height: 58px;
            background: var(--auth-shell-gradient);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: #07080a;
            font-weight: 800;
            font-size: 1.5rem;
            font-family: 'Inter', sans-serif;
            flex-shrink: 0;
            overflow: hidden;
        }

        .auth-shell-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 6px;
        }

        .auth-shell-icon svg,
        .auth-shell-input-icon,
        .auth-shell-password-toggle svg {
            width: 18px;
            height: 18px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
        }

        .auth-shell-icon svg {
            width: 28px;
            height: 28px;
        }

        .auth-shell-title {
            font-family: 'Inter', sans-serif;
            font-size: 2.2rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .auth-shell-title-gradient {
            color: var(--auth-shell-primary);
            -webkit-text-fill-color: var(--auth-shell-primary);
            background: none;
        }

        .auth-shell-subtitle {
            color: var(--auth-shell-text-muted);
            font-size: 0.95rem;
            max-width: 30ch;
            margin: 0 auto;
        }

        .auth-shell-alert {
            padding: 1rem;
            border-radius: 14px;
            margin-bottom: 1.25rem;
            font-size: 0.9rem;
        }

        .auth-shell-alert--success {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: var(--auth-shell-primary);
        }

        .auth-shell-alert--error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: var(--auth-shell-error);
        }

        .auth-shell-alert ul {
            margin: 0;
            padding-left: 1.25rem;
        }

        .auth-shell-form {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .auth-shell-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .auth-shell-label,
        .auth-shell-label-row {
            display: block;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--auth-shell-text);
            margin-bottom: 0.5rem;
        }

        .auth-shell-label-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .auth-shell-link,
        .auth-shell-footer a,
        .auth-shell-checkbox a {
            color: var(--auth-shell-primary);
            text-decoration: none;
        }

        .auth-shell-input-wrap {
            position: relative;
        }

        .auth-shell-input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--auth-shell-text-muted);
            pointer-events: none;
        }

        .auth-shell-input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 2.75rem;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            color: var(--auth-shell-text);
            font-size: 0.95rem;
            font-family: inherit;
            transition: all 0.2s;
            box-sizing: border-box;
        }

        .auth-shell-input:focus {
            outline: none;
            border-color: rgba(217, 255, 97, 0.5);
            box-shadow: 0 0 0 3px rgba(217, 255, 97, 0.1);
        }

        .auth-shell-input.is-error {
            border-color: var(--auth-shell-error);
        }

        .auth-shell-password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--auth-shell-text-muted);
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-shell-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            color: var(--auth-shell-text-muted);
            font-size: 0.9rem;
        }

        .auth-shell-checkbox-input {
            margin-top: 0.15rem;
        }

        .auth-shell-submit {
            width: 100%;
            padding: 1rem;
            background: var(--auth-shell-gradient);
            border: none;
            border-radius: 999px;
            color: #07080a;
            font-size: 1rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 16px 40px rgba(217, 255, 97, 0.2);
            box-sizing: border-box;
        }

        .auth-shell-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 48px rgba(217, 255, 97, 0.28);
        }

        .auth-shell-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            font-size: 0.9rem;
            color: var(--auth-shell-text-muted);
        }

        .auth-shell-reset-link {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 1px solid rgba(217, 255, 97, 0.15);
            border-radius: 14px;
            background: rgba(217, 255, 97, 0.03);
            overflow: auto;
            word-break: break-all;
            color: var(--auth-shell-text-muted);
            font-size: 0.85rem;
            box-sizing: border-box;
        }

        .auth-shell-stack {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            align-items: flex-start;
        }

        .auth-shell-note {
            margin: 0;
            color: var(--auth-shell-text-muted);
            font-size: 0.85rem;
        }

        @media (max-width: 640px) {
            .auth-shell-nav {
                padding: 0 1rem;
            }

            .auth-shell-nav-text {
                display: none;
            }

            .auth-shell-container {
                padding: 5rem 1rem 1rem;
            }

            .auth-shell-card {
                padding: 1.75rem;
                border-radius: 22px;
            }

            .auth-shell-form-grid {
                grid-template-columns: 1fr;
            }

            .auth-shell-title {
                font-size: 1.8rem;
            }
        }
    </style>
    @yield('styles')
</head>
<body class="auth-shell-page @yield('auth-body-class')">
    <nav class="auth-shell-nav" id="authShellNav">
        <a href="{{ url('/') }}" class="auth-shell-logo">
            <img src="{{ asset('storage/logo/fitlife-logo.png') }}" alt="FitLife" class="auth-shell-logo-img">
        </a>

        <div class="auth-shell-nav-actions">
            @hasSection('nav-text')
                <span class="auth-shell-nav-text">@yield('nav-text')</span>
            @endif

            @hasSection('nav-action')
                @yield('nav-action')
            @endif
        </div>
    </nav>

    <div class="auth-shell-container">
        <div class="auth-shell @yield('auth-shell-class')">
            <div class="auth-shell-card">
                @yield('content')
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const button = input?.parentElement?.querySelector('.auth-shell-password-toggle');

            if (! input || ! button) {
                return;
            }

            if (input.type === 'password') {
                input.type = 'text';
                button.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>`;
            } else {
                input.type = 'password';
                button.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
            }
        }

        window.addEventListener('scroll', function() {
            const nav = document.getElementById('authShellNav');

            if (! nav) {
                return;
            }

            nav.style.background = window.scrollY > 50 ? 'rgba(10, 10, 10, 0.95)' : 'rgba(10, 10, 10, 0.8)';
        });
    </script>
    @yield('scripts')
</body>
</html>