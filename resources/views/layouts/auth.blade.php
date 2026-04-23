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

    <script src="{{ asset('js/auth-shell.js') }}"></script>
    @yield('scripts')
</body>
</html>