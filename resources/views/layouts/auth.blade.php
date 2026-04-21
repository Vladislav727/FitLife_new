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