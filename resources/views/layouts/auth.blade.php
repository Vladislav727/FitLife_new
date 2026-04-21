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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        :root {
            --auth-shell-bg: #0a0a0a;
            --auth-shell-card: #111111;
            --auth-shell-elevated: #1a1a1a;
            --auth-shell-primary: #22c55e;
            --auth-shell-primary-glow: rgba(34, 197, 94, 0.4);
            --auth-shell-text: #ffffff;
            --auth-shell-text-muted: #a1a1aa;
            --auth-shell-border: #27272a;
            --auth-shell-error: #ef4444;
            --auth-shell-gradient: linear-gradient(135deg, #22c55e 0%, #06b6d4 100%);
        }

        .auth-shell-page {
            font-family: 'Space Grotesk', sans-serif;
            background: var(--auth-shell-bg);
            background-image:
                radial-gradient(circle at top left, rgba(34, 197, 94, 0.14), transparent 28%),
                radial-gradient(circle at 80% 15%, rgba(6, 182, 212, 0.12), transparent 22%),
                linear-gradient(180deg, #0a0a0a 0%, #090d0c 100%);
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
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(10, 10, 10, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--auth-shell-border);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.18);
        }

        .auth-shell-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .auth-shell-logo-icon {
            width: 40px;
            height: 40px;
            background: var(--auth-shell-gradient);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
            color: #08110d;
            flex-shrink: 0;
        }

        .auth-shell-logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            background: var(--auth-shell-gradient);
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
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.3s;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-sizing: border-box;
        }

        .auth-shell-nav-button--primary {
            background: var(--auth-shell-gradient);
            color: #08110d;
        }

        .auth-shell-nav-button--ghost {
            background: transparent;
            color: var(--auth-shell-text);
            border: 1px solid var(--auth-shell-border);
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
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 30% 30%, var(--auth-shell-primary-glow) 0%, transparent 50%),
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

        .auth-shell--wide {
            max-width: 540px;
        }

        .auth-shell-card {
            width: 100%;
            background: var(--auth-shell-card);
            border: 1px solid var(--auth-shell-border);
            border-radius: 24px;
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.35);
            box-sizing: border-box;
        }

        .auth-shell-card::before {
            content: '';
            position: absolute;
            inset: 0 0 auto 0;
            height: 120px;
            background: linear-gradient(180deg, rgba(34, 197, 94, 0.12), transparent);
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
            padding: 0.35rem 0.75rem;
            margin: 0 auto 1rem;
            border-radius: 999px;
            border: 1px solid rgba(34, 197, 94, 0.24);
            background: rgba(34, 197, 94, 0.08);
            color: var(--auth-shell-primary);
            font-size: 0.78rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-weight: 700;
        }

        .auth-shell-icon {
            width: 60px;
            height: 60px;
            background: var(--auth-shell-gradient);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: #08110d;
            font-weight: 700;
            font-size: 1.35rem;
            flex-shrink: 0;
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
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .auth-shell-title-gradient {
            background: var(--auth-shell-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
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
            background: var(--auth-shell-elevated);
            border: 1px solid var(--auth-shell-border);
            border-radius: 12px;
            color: var(--auth-shell-text);
            font-size: 0.95rem;
            font-family: inherit;
            transition: all 0.3s;
            box-sizing: border-box;
        }

        .auth-shell-input:focus {
            outline: none;
            border-color: var(--auth-shell-primary);
            box-shadow: 0 0 0 3px var(--auth-shell-primary-glow);
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
            border-radius: 12px;
            color: #08110d;
            font-size: 1rem;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 18px 34px rgba(34, 197, 94, 0.18);
            box-sizing: border-box;
        }

        .auth-shell-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--auth-shell-border);
            font-size: 0.9rem;
            color: var(--auth-shell-text-muted);
        }

        .auth-shell-reset-link {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 1px solid rgba(34, 197, 94, 0.25);
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.03);
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
                padding: 1rem;
            }

            .auth-shell-nav-text {
                display: none;
            }

            .auth-shell-container {
                padding: 5rem 1rem 1rem;
            }

            .auth-shell-card {
                padding: 1.5rem;
                border-radius: 18px;
            }

            .auth-shell-form-grid {
                grid-template-columns: 1fr;
            }

            .auth-shell-title {
                font-size: 1.5rem;
            }
        }
    </style>
    @yield('styles')
</head>
<body class="auth-shell-page @yield('auth-body-class')">
    <nav class="auth-shell-nav" id="authShellNav">
        <a href="{{ url('/') }}" class="auth-shell-logo">
            <div class="auth-shell-logo-icon">F</div>
            <span class="auth-shell-logo-text">FitLife</span>
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