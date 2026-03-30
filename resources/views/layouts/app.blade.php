<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
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
    @yield('styles')
</head>
<body>
    @auth

    <header class="main-header" id="mainHeader">
        <div class="header-container">

            <a href="{{ route('dashboard') }}" class="header-logo">
                <div class="header-logo-icon">
                    <svg viewBox="0 0 24 24"><path d="M13.5 5.5c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zM9.8 8.9L7 23h2.1l1.8-8 2.1 2v6h2v-7.5l-2.1-2 .6-3C14.8 12 16.8 13 19 13v-2c-1.9 0-3.5-1-4.3-2.4l-1-1.6c-.4-.6-1-1-1.7-1-.3 0-.5.1-.8.1L6 8.3V13h2V9.6l1.8-.7"/></svg>
                </div>
                <span class="header-logo-text">FitLife</span>
            </a>

            <nav class="header-nav">
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    <span>{{ __('nav.dashboard') }}</span>
                </a>

                <a href="{{ route('posts.index') }}" class="nav-item {{ request()->routeIs('posts.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    <span>{{ __('nav.community') }}</span>
                </a>

                <a href="{{ route('activity-calendar') }}" class="nav-item {{ request()->routeIs('activity-calendar') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <span>{{ __('nav.calendar') }}</span>
                </a>

                <a href="{{ route('foods.index') }}" class="nav-item {{ request()->routeIs('foods.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
                    <span>{{ __('nav.meals') }}</span>
                </a>

                <a href="{{ route('water.index') }}" class="nav-item {{ request()->routeIs('water.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/></svg>
                    <span>{{ __('nav.water') }}</span>
                </a>

                <a href="{{ route('sleep.index') }}" class="nav-item {{ request()->routeIs('sleep.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                    <span>{{ __('nav.sleep') }}</span>
                </a>

                <a href="{{ route('goals.index') }}" class="nav-item {{ request()->routeIs('goals.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                    <span>{{ __('nav.goals') }}</span>
                </a>

                <a href="{{ route('progress.index') }}" class="nav-item {{ request()->routeIs('progress.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/></svg>
                    <span>{{ __('nav.progress') }}</span>
                </a>

                <a href="{{ route('leaderboard.index') }}" class="nav-item {{ request()->routeIs('leaderboard.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 21h8m-4-4v4m-5-8h10l1-4H6l1 4zm1-4l1-4h6l1 4m-8 0h10"/><path d="M12 1v4"/></svg>
                    <span>{{ __('nav.leaderboard') }}</span>
                </a>

                <a href="{{ route('chats.index') }}" class="nav-item {{ request()->routeIs('chats.*') || request()->routeIs('conversations.*') || request()->routeIs('groups.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    <span>{{ __('nav.chats') }}</span>
                </a>

                @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg>
                    <span>{{ __('nav.admin') }}</span>
                </a>
                @endif
            </nav>

            <div class="header-actions">

                <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Menu">
                    <svg viewBox="0 0 24 24"><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg>
                </button>

                @php
                    $notifCount = Auth::user()->groupInvites()->count();
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
                        <svg class="user-menu-arrow" viewBox="0 -960 960 960"><path d="M480-344 240-584l56-56 184 184 184-184 56 56-240 240Z"/></svg>
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
                                <svg viewBox="0 -960 960 960"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"/></svg>
                                <span>{{ __('nav.my_profile') }}</span>
                            </a>
                            <a href="{{ route('biography.edit') }}" class="user-dropdown-item">
                                <svg viewBox="0 -960 960 960"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"/></svg>
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
                                    <svg viewBox="0 -960 960 960"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>
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
                    <svg viewBox="0 -960 960 960"><path d="M520-600v-240h320v240H520ZM120-440v-400h320v400H120Zm400 320v-400h320v400H520Zm-400 0v-240h320v240H120Z"/></svg>
                    <span>{{ __('nav.dashboard') }}</span>
                </a>
                <a href="{{ route('posts.index') }}" class="mobile-menu-link {{ request()->routeIs('posts.*') ? 'active' : '' }}">
                    <svg viewBox="0 -960 960 960"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"/></svg>
                    <span>{{ __('nav.community') }}</span>
                </a>
            </div>

            <div class="mobile-menu-section">
                <div class="mobile-menu-section-title">{{ __('nav.trackers') }}</div>
                <a href="{{ route('activity-calendar') }}" class="mobile-menu-link {{ request()->routeIs('activity-calendar*') ? 'active' : '' }}">
                    <svg viewBox="0 -960 960 960"><path d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Z"/></svg>
                    <span>{{ __('nav.calendar') }}</span>
                </a>
                <a href="{{ route('foods.index') }}" class="mobile-menu-link {{ request()->routeIs('foods.*') ? 'active' : '' }}">
                    <svg viewBox="0 -960 960 960"><path d="M280-80v-366q-51-14-85.5-56T160-600v-280h80v280h40v-280h80v280h40v-280h80v280q0 56-34.5 98T360-446v366h-80Z"/></svg>
                    <span>{{ __('nav.meal_tracker') }}</span>
                </a>
                <a href="{{ route('sleep.index') }}" class="mobile-menu-link {{ request()->routeIs('sleep.*') ? 'active' : '' }}">
                    <svg viewBox="0 -960 960 960"><path d="M480-120q-150 0-255-105T120-480q0-150 105-255t255-105q14 0 27.5 1t26.5 3q-41 29-65.5 75.5T444-660q0 90 63 153t153 63q55 0 101-24.5t75-65.5q2 13 3 26.5t1 27.5q0 150-105 255T480-120Z"/></svg>
                    <span>{{ __('nav.sleep_tracker') }}</span>
                </a>
                <a href="{{ route('water.index') }}" class="mobile-menu-link {{ request()->routeIs('water.*') ? 'active' : '' }}">
                    <svg viewBox="0 -960 960 960"><path d="M480-80q-137 0-228.5-94T160-408q0-100 79.5-217.5T480-880q161 137 240.5 254.5T800-408q0 140-91.5 234T480-80Z"/></svg>
                    <span>{{ __('nav.water_tracker') }}</span>
                </a>
            </div>

            <div class="mobile-menu-section">
                <div class="mobile-menu-section-title">{{ __('nav.progress') }}</div>
                <a href="{{ route('progress.index') }}" class="mobile-menu-link {{ request()->routeIs('progress.*') ? 'active' : '' }}">
                    <svg viewBox="0 -960 960 960"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm40-80h480L570-480 450-320l-90-120-120 160Z"/></svg>
                    <span>{{ __('nav.progress_photos') }}</span>
                </a>
                <a href="{{ route('goals.index') }}" class="mobile-menu-link {{ request()->routeIs('goals.*') ? 'active' : '' }}">
                    <svg viewBox="0 -960 960 960"><path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"/></svg>
                    <span>{{ __('nav.goals') }}</span>
                </a>
                <a href="{{ route('leaderboard.index') }}" class="mobile-menu-link {{ request()->routeIs('leaderboard.*') ? 'active' : '' }}">
                    <svg viewBox="0 -960 960 960"><path d="M160-200h160v-320H160v320Zm240 0h160v-560H400v560Zm240 0h160v-240H640v240ZM80-120v-480h240v-240h320v320h240v400H80Z"/></svg>
                    <span>{{ __('nav.leaderboard') }}</span>
                </a>
                <a href="{{ route('chats.index') }}" class="mobile-menu-link {{ request()->routeIs('chats.*') || request()->routeIs('conversations.*') || request()->routeIs('groups.*') ? 'active' : '' }}">
                    <svg viewBox="0 -960 960 960"><path d="M240-400h480v-80H240v80Zm0-120h480v-80H240v80Zm0-120h480v-80H240v80ZM80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Z"/></svg>
                    <span>{{ __('nav.chats') }}</span>
                </a>
                <a href="{{ route('calories.index') }}" class="mobile-menu-link {{ request()->routeIs('calories.*') ? 'active' : '' }}">
                    <svg viewBox="0 -960 960 960"><path d="M320-240h60v-80h80v-60h-80v-80h-60v80h-80v60h80v80Z"/></svg>
                    <span>{{ __('nav.calculator') }}</span>
                </a>
            </div>

            @if(Auth::user()->role === 'admin')
            <div class="mobile-menu-section">
                <div class="mobile-menu-section-title">{{ __('nav.admin') }}</div>
                <a href="{{ route('admin.dashboard') }}" class="mobile-menu-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                    <svg viewBox="0 -960 960 960"><path d="M680-280q25 0 42.5-17.5T740-340q0-25-17.5-42.5T680-400q-25 0-42.5 17.5T620-340q0 25 17.5 42.5T680-280Z"/></svg>
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
                <svg viewBox="0 -960 960 960"><path d="M520-600v-240h320v240H520ZM120-440v-400h320v400H120Zm400 320v-400h320v400H520Zm-400 0v-240h320v240H120Z"/></svg>
                <span>{{ __('nav.home') }}</span>
            </a>
            <a href="{{ route('posts.index') }}" class="mobile-nav-item {{ request()->routeIs('posts.*') ? 'active' : '' }}">
                <svg viewBox="0 -960 960 960"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47Z"/></svg>
                <span>{{ __('nav.social') }}</span>
            </a>
            <a href="{{ route('foods.index') }}" class="mobile-nav-item {{ request()->routeIs('foods.*') ? 'active' : '' }}">
                <svg viewBox="0 -960 960 960"><path d="M280-80v-366q-51-14-85.5-56T160-600v-280h80v280h40v-280h80v280h40v-280h80v280q0 56-34.5 98T360-446v366h-80Z"/></svg>
                <span>{{ __('nav.food') }}</span>
            </a>
            <a href="{{ route('goals.index') }}" class="mobile-nav-item {{ request()->routeIs('goals.*') ? 'active' : '' }}">
                <svg viewBox="0 -960 960 960"><path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880Z"/></svg>
                <span>{{ __('nav.goals') }}</span>
            </a>
            <a href="{{ route('profile.edit') }}" class="mobile-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <svg viewBox="0 -960 960 960"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47Z"/></svg>
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
