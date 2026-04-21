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
@php
    $hideMobileNav = trim($__env->yieldContent('hide-mobile-nav')) === '1';
    $flushMobileContent = trim($__env->yieldContent('flush-mobile-content')) === '1';
    $navItems = [
        ['route' => 'dashboard', 'label' => __('nav.dashboard'), 'icon' => '<path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1m-2 0h2"/>', 'active' => request()->routeIs('dashboard')],
        ['route' => 'posts.index', 'label' => __('nav.community'), 'icon' => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>', 'active' => request()->routeIs('posts.*')],
        ['route' => 'activity-calendar', 'label' => __('nav.calendar'), 'icon' => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01M16 18h.01"/>', 'active' => request()->routeIs('activity-calendar')],
        ['route' => 'foods.index', 'label' => __('nav.meals'), 'icon' => '<path d="M3 2l1 14c0 2.21 3.58 4 8 4s8-1.79 8-4l1-14"/><path d="M3 6c0 2.21 3.58 4 8 4s8-1.79 8-4"/><path d="M12 12v4"/>', 'active' => request()->routeIs('foods.*')],
        ['route' => 'water.index', 'label' => __('nav.water'), 'icon' => '<path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/><path d="M8 14a4.001 4.001 0 004 4"/>', 'active' => request()->routeIs('water.*')],
        ['route' => 'sleep.index', 'label' => __('nav.sleep'), 'icon' => '<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/><path d="M14.5 2.5l2 2-2 2"/><path d="M17 5.5l3 3-3 3"/>', 'active' => request()->routeIs('sleep.*')],
        ['route' => 'goals.index', 'label' => __('nav.goals'), 'icon' => '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/><path d="M22 2L12 12"/><path d="M16 2h6v6"/>', 'active' => request()->routeIs('goals.*')],
        ['route' => 'progress.index', 'label' => __('nav.progress'), 'icon' => '<polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/>', 'active' => request()->routeIs('progress.*')],
        ['route' => 'chats.index', 'label' => __('nav.chats'), 'icon' => '<path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/><path d="M8 10h.01M12 10h.01M16 10h.01"/>', 'active' => request()->routeIs('chats.*') || request()->routeIs('conversations.*') || request()->routeIs('groups.*')],
    ];
    $mobileMenuSections = [
        ['title' => __('nav.main'), 'items' => [
            ['route' => 'dashboard', 'label' => __('nav.dashboard'), 'icon' => $navItems[0]['icon'], 'active' => $navItems[0]['active']],
            ['route' => 'posts.index', 'label' => __('nav.community'), 'icon' => $navItems[1]['icon'], 'active' => $navItems[1]['active']],
        ]],
        ['title' => __('nav.trackers'), 'items' => [
            ['route' => 'activity-calendar', 'label' => __('nav.calendar'), 'icon' => $navItems[2]['icon'], 'active' => $navItems[2]['active']],
            ['route' => 'foods.index', 'label' => __('nav.meal_tracker'), 'icon' => $navItems[3]['icon'], 'active' => $navItems[3]['active']],
            ['route' => 'sleep.index', 'label' => __('nav.sleep_tracker'), 'icon' => $navItems[5]['icon'], 'active' => $navItems[5]['active']],
            ['route' => 'water.index', 'label' => __('nav.water_tracker'), 'icon' => $navItems[4]['icon'], 'active' => $navItems[4]['active']],
        ]],
        ['title' => __('nav.progress'), 'items' => [
            ['route' => 'progress.index', 'label' => __('nav.progress_photos'), 'icon' => $navItems[7]['icon'], 'active' => $navItems[7]['active']],
            ['route' => 'goals.index', 'label' => __('nav.goals'), 'icon' => $navItems[6]['icon'], 'active' => $navItems[6]['active']],
            ['route' => 'chats.index', 'label' => __('nav.chats'), 'icon' => $navItems[8]['icon'], 'active' => $navItems[8]['active']],
            ['route' => 'calories.index', 'label' => __('nav.calculator'), 'icon' => $navItems[8]['icon'], 'active' => request()->routeIs('calories.*')],
        ]],
    ];
    $mobileBottomNavItems = [
        ['route' => 'dashboard', 'label' => __('nav.home'), 'icon' => $navItems[0]['icon'], 'active' => $navItems[0]['active']],
        ['route' => 'posts.index', 'label' => __('nav.social'), 'icon' => $navItems[1]['icon'], 'active' => $navItems[1]['active']],
        ['route' => 'foods.index', 'label' => __('nav.food'), 'icon' => $navItems[3]['icon'], 'active' => $navItems[3]['active']],
        ['route' => 'goals.index', 'label' => __('nav.goals'), 'icon' => $navItems[6]['icon'], 'active' => $navItems[6]['active']],
        ['route' => 'profile.edit', 'label' => __('nav.profile'), 'icon' => '<path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"/><path d="M4 20c0-2.66 5.33-4 8-4s8 1.34 8 4"/>', 'active' => request()->routeIs('profile.*')],
    ];
@endphp
<body class="{{ $hideMobileNav ? 'layout-mobile-nav-hidden' : '' }} {{ $flushMobileContent ? 'layout-mobile-content-flush' : '' }}">
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
                @foreach($navItems as $item)
                <a href="{{ route($item['route']) }}" class="nav-item {{ $item['active'] ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">{!! $item['icon'] !!}</svg>
                    <span>{{ $item['label'] }}</span>
                </a>
                @endforeach

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
                                src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('storage/logo/default-avatar.avif') }}"
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
                                src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('storage/logo/default-avatar.avif') }}"
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
                            @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="user-dropdown-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                <span>{{ __('nav.admin_panel') }}</span>
                            </a>
                            @endif
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
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="5" r="2"/><path d="M7 21l1.5-6.5M16 21l-1-5"/><path d="M9.5 14.5L8 10l4-2 4 2-1.5 4.5"/><path d="M12 8v4"/><path d="M8 10c-1.5 1-3 3.5-1.5 5"/><path d="M16 10c1.5 1 3 3.5 1.5 5"/></svg>
                </div>
                <span class="mobile-menu-logo-text">FitLife</span>
            </a>
            <button class="mobile-menu-close" id="mobileMenuClose">
                <svg viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
            </button>
        </div>

        <div class="mobile-menu-body">
            @foreach($mobileMenuSections as $section)
            <div class="mobile-menu-section">
                <div class="mobile-menu-section-title">{{ $section['title'] }}</div>
                @foreach($section['items'] as $item)
                <a href="{{ route($item['route']) }}" class="mobile-menu-link {{ $item['active'] ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">{!! $item['icon'] !!}</svg>
                    <span>{{ $item['label'] }}</span>
                </a>
                @endforeach
            </div>
            @endforeach

            @if(Auth::user()->isAdmin())
            <div class="mobile-menu-section">
                <div class="mobile-menu-section-title">{{ __('nav.admin') }}</div>
                <a href="{{ route('admin.dashboard') }}" class="mobile-menu-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg>
                    <span>{{ __('nav.admin_panel') }}</span>
                </a>
            </div>
            @endif
        </div>

        <div class="mobile-menu-footer">
            <a href="{{ route('profile.edit') }}" class="mobile-user-card">
                <img
                    src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('storage/logo/default-avatar.avif') }}"
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
            @foreach($mobileBottomNavItems as $item)
            <a href="{{ route($item['route']) }}" class="mobile-nav-item {{ $item['active'] ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">{!! $item['icon'] !!}</svg>
                <span>{{ $item['label'] }}</span>
            </a>
            @endforeach
        </div>
    </nav>

    <main class="main-content {{ $flushMobileContent ? 'main-content--mobile-flush' : '' }}">
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
