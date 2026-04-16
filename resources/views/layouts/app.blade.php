<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ Auth::id() ?? 'guest' }}">
    <title>@yield('title', 'FitLife')</title>
    <link rel="icon" href="{{ asset('favicon.PNG') }}" type="image/png">
    <script>
        (function() {
            var t = localStorage.getItem('fitlife-theme') || 'dark';
            document.documentElement.setAttribute('data-theme', t);
        })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
    @media (max-width: 900px) {
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="search"],
        input[type="number"],
        input[type="tel"],
        input[type="url"],
        textarea,
        select {
            font-size: 16px !important;
        }
    }
    </style>
    @yield('styles')
</head>
<body>
    @auth

    <header class="main-header" id="mainHeader">
        <div class="header-container">

            <a href="{{ route('dashboard') }}" class="header-logo">
                <div class="header-logo-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="5" r="2"/><path d="M7 21l1.5-6.5M16 21l-1-5"/><path d="M9.5 14.5L8 10l4-2 4 2-1.5 4.5"/><path d="M12 8v4"/><path d="M8 10c-1.5 1-3 3.5-1.5 5"/><path d="M16 10c1.5 1 3 3.5 1.5 5"/></svg>
                </div>
                <span class="header-logo-text">FitLife</span>
            </a>

            <nav class="header-nav">
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1m-2 0h2"/></svg>
                    <span>{{ __('nav.dashboard') }}</span>
                </a>

                <a href="{{ route('posts.index') }}" class="nav-item {{ request()->routeIs('posts.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                    <span>{{ __('nav.community') }}</span>
                </a>

                <a href="{{ route('activity-calendar') }}" class="nav-item {{ request()->routeIs('activity-calendar') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01M16 18h.01"/></svg>
                    <span>{{ __('nav.calendar') }}</span>
                </a>

                <a href="{{ route('foods.index') }}" class="nav-item {{ request()->routeIs('foods.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 2l1 14c0 2.21 3.58 4 8 4s8-1.79 8-4l1-14"/><path d="M3 6c0 2.21 3.58 4 8 4s8-1.79 8-4"/><path d="M12 12v4"/></svg>
                    <span>{{ __('nav.meals') }}</span>
                </a>

                <a href="{{ route('water.index') }}" class="nav-item {{ request()->routeIs('water.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/><path d="M8 14a4.001 4.001 0 004 4"/></svg>
                    <span>{{ __('nav.water') }}</span>
                </a>

                <a href="{{ route('sleep.index') }}" class="nav-item {{ request()->routeIs('sleep.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/><path d="M14.5 2.5l2 2-2 2"/><path d="M17 5.5l3 3-3 3"/></svg>
                    <span>{{ __('nav.sleep') }}</span>
                </a>

                <a href="{{ route('goals.index') }}" class="nav-item {{ request()->routeIs('goals.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/><path d="M22 2L12 12"/><path d="M16 2h6v6"/></svg>
                    <span>{{ __('nav.goals') }}</span>
                </a>

                <a href="{{ route('progress.index') }}" class="nav-item {{ request()->routeIs('progress.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                    <span>{{ __('nav.progress') }}</span>
                </a>

                <a href="{{ route('chats.index') }}" class="nav-item {{ request()->routeIs('chats.*') || request()->routeIs('conversations.*') || request()->routeIs('groups.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/><path d="M8 10h.01M12 10h.01M16 10h.01"/></svg>
                    <span>{{ __('nav.chats') }}</span>
                </a>

                @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg>
                    <span>{{ __('nav.admin') }}</span>
                </a>
                @endif
            </nav>

            <div class="header-actions">

                <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Menu">
                    <svg viewBox="0 0 24 24"><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg>
                </button>

                @php
                    $notifCount = Auth::user()->groupInvites()->count()
                        + \App\Models\Notification::where('user_id', Auth::id())->whereNull('read_at')->count();
                    $unreadMsgCount = \App\Models\ConversationMessage::whereHas('conversation', function($q) {
                        $q->where('user_one_id', Auth::id())->orWhere('user_two_id', Auth::id());
                    })->where('user_id', '!=', Auth::id())->whereNull('read_at')->count();
                    $totalBadge = $notifCount + $unreadMsgCount;
                @endphp
                <div class="user-menu" id="userMenu">
                    <button class="user-menu-trigger" id="userMenuTrigger">
                        <div class="user-menu-avatar-wrap">
                            <img
                                src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('storage/logo/defaultPhoto.jpg') }}"
                                alt="{{ Auth::user()->name }}"
                                class="user-menu-avatar"
                            >
                            @if($totalBadge > 0)
                                <span class="user-menu-badge">{{ $totalBadge > 99 ? '99+' : $totalBadge }}</span>
                            @endif
                        </div>
                        <div class="user-menu-info">
                            <div class="user-menu-name">{{ Auth::user()->name }}</div>
                        </div>
                        <svg class="user-menu-arrow" viewBox="0 0 24 24"><path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z"/></svg>
                    </button>

                    <div class="user-dropdown" id="userDropdown">
                        <div class="user-dropdown-header">
                            <img
                                src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('storage/logo/defaultPhoto.jpg') }}"
                                alt="{{ Auth::user()->name }}"
                                class="user-dropdown-avatar"
                            >
                            <div class="user-dropdown-info">
                                <div class="user-dropdown-name">{{ Auth::user()->name }}</div>
                                <div class="user-dropdown-email">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                        <div class="user-dropdown-body">
                            <a href="{{ route('profile.edit') }}" class="user-dropdown-item">
                                <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                <span>{{ __('nav.my_profile') }}</span>
                            </a>
                            <a href="{{ route('biography.edit') }}" class="user-dropdown-item">
                                <svg viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm-1 7V3.5L18.5 9H13zM6 20V4h5v7h7v9H6z"/></svg>
                                <span>{{ __('nav.biography') }}</span>
                            </a>
                            <a href="{{ route('settings.index') }}" class="user-dropdown-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="21" x2="4" y2="14"/><line x1="4" y1="10" x2="4" y2="3"/><line x1="12" y1="21" x2="12" y2="12"/><line x1="12" y1="8" x2="12" y2="3"/><line x1="20" y1="21" x2="20" y2="16"/><line x1="20" y1="12" x2="20" y2="3"/><line x1="1" y1="14" x2="7" y2="14"/><line x1="9" y1="8" x2="15" y2="8"/><line x1="17" y1="16" x2="23" y2="16"/></svg>
                                <span>{{ __('nav.settings') }}</span>
                            </a>
                            <a href="{{ route('notifications.index') }}" class="user-dropdown-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                                <span>{{ __('nav.notifications') }}</span>
                                @if($notifCount > 0)
                                    <span class="user-dropdown-badge">{{ $notifCount }}</span>
                                @endif
                            </a>
                            <div class="user-dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                                @csrf
                                <button type="submit" class="user-dropdown-item danger">
                                    <svg viewBox="0 0 24 24"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/></svg>
                                    <span>{{ __('nav.logout') }}</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>

    <aside class="mobile-menu-panel" id="mobileMenuPanel">
        <div class="mobile-menu-header">
            <a href="{{ route('dashboard') }}" class="mobile-menu-logo">
                <div class="mobile-menu-logo-icon">
                    <svg viewBox="0 0 24 24"><path d="M13.5 5.5c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zM9.8 8.9L7 23h2.1l1.8-8 2.1 2v6h2v-7.5l-2.1-2 .6-3C14.8 12 16.8 13 19 13v-2c-1.9 0-3.5-1-4.3-2.4l-1-1.6c-.4-.6-1-1-1.7-1-.3 0-.5.1-.8.1L6 8.3V13h2V9.6l1.8-.7"/></svg>
                </div>
                <span class="mobile-menu-logo-text">FitLife</span>
            </a>
            <button class="mobile-menu-close" id="mobileMenuClose">
                <svg viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
            </button>
        </div>

        <div class="mobile-menu-body">
            <div class="mobile-menu-section">
                <div class="mobile-menu-section-title">{{ __('nav.main') }}</div>
                <a href="{{ route('dashboard') }}" class="mobile-menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                    <span>{{ __('nav.dashboard') }}</span>
                </a>
                <a href="{{ route('posts.index') }}" class="mobile-menu-link {{ request()->routeIs('posts.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/></svg>
                    <span>{{ __('nav.community') }}</span>
                </a>
            </div>

            <div class="mobile-menu-section">
                <div class="mobile-menu-section-title">{{ __('nav.trackers') }}</div>
                <a href="{{ route('activity-calendar') }}" class="mobile-menu-link {{ request()->routeIs('activity-calendar*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM9 10H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2z"/></svg>
                    <span>{{ __('nav.calendar') }}</span>
                </a>
                <a href="{{ route('foods.index') }}" class="mobile-menu-link {{ request()->routeIs('foods.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M8.1 13.34l2.83-2.83L3.91 3.5a4.008 4.008 0 0 0 0 5.66l4.19 4.18zm6.78-1.81c1.53.71 3.68.21 5.27-1.38 1.91-1.91 2.28-4.65.81-6.12-1.46-1.46-4.2-1.1-6.12.81-1.59 1.59-2.09 3.74-1.38 5.27L3.7 19.87l1.41 1.41L12 14.41l6.88 6.88 1.41-1.41L13.41 13l1.47-1.47z"/></svg>
                    <span>{{ __('nav.meal_tracker') }}</span>
                </a>
                <a href="{{ route('sleep.index') }}" class="mobile-menu-link {{ request()->routeIs('sleep.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M12.34 2.02C6.59 1.82 2 6.42 2 12c0 5.52 4.48 10 10 10 3.71 0 6.93-2.02 8.66-5.02-7.51-.25-12.09-8.43-8.32-14.96z"/></svg>
                    <span>{{ __('nav.sleep_tracker') }}</span>
                </a>
                <a href="{{ route('water.index') }}" class="mobile-menu-link {{ request()->routeIs('water.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M12 2c-5.33 4.55-8 8.48-8 11.8 0 4.98 3.8 8.2 8 8.2s8-3.22 8-8.2c0-3.32-2.67-7.25-8-11.8zm0 18c-3.35 0-6-2.57-6-6.2 0-2.34 1.95-5.44 6-9.14 4.05 3.7 6 6.79 6 9.14 0 3.63-2.65 6.2-6 6.2z"/></svg>
                    <span>{{ __('nav.water_tracker') }}</span>
                </a>
            </div>

            <div class="mobile-menu-section">
                <div class="mobile-menu-section-title">{{ __('nav.progress') }}</div>
                <a href="{{ route('progress.index') }}" class="mobile-menu-link {{ request()->routeIs('progress.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                    <span>{{ __('nav.progress_photos') }}</span>
                </a>
                <a href="{{ route('goals.index') }}" class="mobile-menu-link {{ request()->routeIs('goals.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm0-14c-3.31 0-6 2.69-6 6s2.69 6 6 6 6-2.69 6-6-2.69-6-6-6zm0 10c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm0-6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                    <span>{{ __('nav.goals') }}</span>
                </a>
                <a href="{{ route('chats.index') }}" class="mobile-menu-link {{ request()->routeIs('chats.*') || request()->routeIs('conversations.*') || request()->routeIs('groups.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12zM7 9h2v2H7zm4 0h2v2h-2zm4 0h2v2h-2z"/></svg>
                    <span>{{ __('nav.chats') }}</span>
                </a>
                <a href="{{ route('calories.index') }}" class="mobile-menu-link {{ request()->routeIs('calories.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/></svg>
                    <span>{{ __('nav.calculator') }}</span>
                </a>
            </div>

            @if(Auth::user()->isAdmin())
            <div class="mobile-menu-section">
                <div class="mobile-menu-section-title">{{ __('nav.admin') }}</div>
                <a href="{{ route('admin.dashboard') }}" class="mobile-menu-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/></svg>
                    <span>{{ __('nav.admin_panel') }}</span>
                </a>
            </div>
            @endif
        </div>

        <div class="mobile-menu-footer">
            <a href="{{ route('profile.edit') }}" class="mobile-user-card">
                <img
                    src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('storage/logo/defaultPhoto.jpg') }}"
                    alt="{{ Auth::user()->name }}"
                    class="mobile-user-avatar"
                >
                <div class="mobile-user-info">
                    <div class="mobile-user-name">{{ Auth::user()->name }}</div>
                    <div class="mobile-user-email">{{ Auth::user()->email }}</div>
                </div>
            </a>
        </div>
    </aside>

    <nav class="mobile-bottom-nav">
        <div class="mobile-nav-items">
            <a href="{{ route('dashboard') }}" class="mobile-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                <span>{{ __('nav.home') }}</span>
            </a>
            <a href="{{ route('posts.index') }}" class="mobile-nav-item {{ request()->routeIs('posts.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/></svg>
                <span>{{ __('nav.social') }}</span>
            </a>
            <a href="{{ route('foods.index') }}" class="mobile-nav-item {{ request()->routeIs('foods.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M8.1 13.34l2.83-2.83L3.91 3.5a4.008 4.008 0 0 0 0 5.66l4.19 4.18zm6.78-1.81c1.53.71 3.68.21 5.27-1.38 1.91-1.91 2.28-4.65.81-6.12-1.46-1.46-4.2-1.1-6.12.81-1.59 1.59-2.09 3.74-1.38 5.27L3.7 19.87l1.41 1.41L12 14.41l6.88 6.88 1.41-1.41L13.41 13l1.47-1.47z"/></svg>
                <span>{{ __('nav.food') }}</span>
            </a>
            <a href="{{ route('goals.index') }}" class="mobile-nav-item {{ request()->routeIs('goals.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm0-14c-3.31 0-6 2.69-6 6s2.69 6 6 6 6-2.69 6-6-2.69-6-6-6zm0 10c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm0-6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                <span>{{ __('nav.goals') }}</span>
            </a>
            <a href="{{ route('profile.edit') }}" class="mobile-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                <span>{{ __('nav.profile') }}</span>
            </a>
        </div>
    </nav>

    <main class="main-content">
        <div class="content-wrapper">
            @yield('content')
        </div>
    </main>

    @else

    <main>
        @yield('content')
    </main>
    @endauth

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Header scroll effect
            const header = document.getElementById('mainHeader');
            if (header) {
                window.addEventListener('scroll', () => {
                    header.classList.toggle('scrolled', window.scrollY > 20);
                });
            }

            // User menu toggle
            const userMenu = document.getElementById('userMenu');
            const userMenuTrigger = document.getElementById('userMenuTrigger');

            if (userMenu && userMenuTrigger) {
                userMenuTrigger.addEventListener('click', (e) => {
                    e.stopPropagation();
                    userMenu.classList.toggle('open');
                });

                document.addEventListener('click', (e) => {
                    if (!userMenu.contains(e.target)) {
                        userMenu.classList.remove('open');
                    }
                });
            }

            // Mobile menu
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const mobileMenuPanel = document.getElementById('mobileMenuPanel');
            const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
            const mobileMenuClose = document.getElementById('mobileMenuClose');

            function openMobileMenu() {
                mobileMenuPanel?.classList.add('open');
                mobileMenuOverlay?.classList.add('open');
                document.body.style.overflow = 'hidden';
            }

            function closeMobileMenu() {
                mobileMenuPanel?.classList.remove('open');
                mobileMenuOverlay?.classList.remove('open');
                document.body.style.overflow = '';
            }

            mobileMenuBtn?.addEventListener('click', openMobileMenu);
            mobileMenuClose?.addEventListener('click', closeMobileMenu);
            mobileMenuOverlay?.addEventListener('click', closeMobileMenu);

            // Toast notification system
            window.toast = {
                container: null,
                init() {
                    if (!this.container) {
                        this.container = document.createElement('div');
                        this.container.className = 'toast-container';
                        document.body.appendChild(this.container);
                    }
                },
                show(message, type = 'info', duration = 3000) {
                    this.init();
                    const toast = document.createElement('div');
                    toast.className = `toast toast-${type}`;

                    const icons = {
                        success: '<svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>',
                        error: '<svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>',
                        warning: '<svg viewBox="0 0 24 24"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-8h2v8z"/></svg>',
                        info: '<svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>'
                    };

                    toast.innerHTML = `
                        <div class="toast-icon">${icons[type] || icons.info}</div>
                        <div class="toast-message">${message}</div>
                        <button class="toast-close" onclick="this.parentElement.remove()">
                            <svg viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                        </button>
                    `;

                    this.container.appendChild(toast);

                    // Trigger animation
                    requestAnimationFrame(() => {
                        toast.classList.add('show');
                    });

                    // Auto remove
                    setTimeout(() => {
                        toast.classList.remove('show');
                        setTimeout(() => toast.remove(), 300);
                    }, duration);
                },
                success(message, duration) { this.show(message, 'success', duration); },
                error(message, duration) { this.show(message, 'error', duration); },
                warning(message, duration) { this.show(message, 'warning', duration); },
                info(message, duration) { this.show(message, 'info', duration); }
            };

            // Toast translations
            window.toastMessages = {
                // Posts
                post_created: @json(__('toast.post_created')),
                post_updated: @json(__('toast.post_updated')),
                post_deleted: @json(__('toast.post_deleted')),
                post_create_error: @json(__('toast.post_create_error')),
                post_update_error: @json(__('toast.post_update_error')),
                post_delete_error: @json(__('toast.post_delete_error')),
                // Comments
                comment_added: @json(__('toast.comment_added')),
                comment_updated: @json(__('toast.comment_updated')),
                comment_deleted: @json(__('toast.comment_deleted')),
                comment_add_error: @json(__('toast.comment_add_error')),
                comment_update_error: @json(__('toast.comment_update_error')),
                comment_delete_error: @json(__('toast.comment_delete_error')),
                comment_empty: @json(__('toast.comment_empty')),
                // Reactions
                reaction_toggled: @json(__('toast.reaction_toggled')),
                reaction_error: @json(__('toast.reaction_error')),
                // Share
                link_copied: @json(__('toast.link_copied')),
                link_copy_error: @json(__('toast.link_copy_error')),
                // General
                saved: @json(__('toast.saved')),
                deleted: @json(__('toast.deleted')),
                updated: @json(__('toast.updated')),
                created: @json(__('toast.created')),
                error: @json(__('toast.error')),
                unauthorized: @json(__('toast.unauthorized')),
            };
        });
    </script>

    @yield('scripts')
</body>
</html>
