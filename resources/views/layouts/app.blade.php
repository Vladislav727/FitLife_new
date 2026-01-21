<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ Auth::id() ?? 'guest' }}">
    <title>@yield('title', 'FitLife')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('styles')
</head>
<body>
    <div id="app">
        @auth
        <!-- Sidebar Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Sidebar Navigation -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <div class="logo-icon">
                        <svg viewBox="0 0 24 24"><path d="M13.5 5.5c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zM9.8 8.9L7 23h2.1l1.8-8 2.1 2v6h2v-7.5l-2.1-2 .6-3C14.8 12 16.8 13 19 13v-2c-1.9 0-3.5-1-4.3-2.4l-1-1.6c-.4-.6-1-1-1.7-1-.3 0-.5.1-.8.1L6 8.3V13h2V9.6l1.8-.7"/></svg>
                    </div>
                    <span class="logo-text">FitLife</span>
                </div>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">{{ __('nav.main') }}</div>
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg viewBox="0 -960 960 960"><path d="M520-600v-240h320v240H520ZM120-440v-400h320v400H120Zm400 320v-400h320v400H520Zm-400 0v-240h320v240H120Z"/></svg>
                        <span>{{ __('nav.dashboard') }}</span>
                    </a>
                    <a href="{{ route('posts.index') }}" class="nav-link {{ request()->routeIs('posts.*') ? 'active' : '' }}">
                        <svg viewBox="0 -960 960 960"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"/></svg>
                        <span>{{ __('nav.community') }}</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">{{ __('nav.trackers') }}</div>
                    <a href="{{ route('activity-calendar') }}" class="nav-link {{ request()->routeIs('activity-calendar*') ? 'active' : '' }}">
                        <svg viewBox="0 -960 960 960"><path d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Z"/></svg>
                        <span>{{ __('nav.calendar') }}</span>
                    </a>
                    <a href="{{ route('foods.index') }}" class="nav-link {{ request()->routeIs('foods.*') ? 'active' : '' }}">
                        <svg viewBox="0 -960 960 960"><path d="M280-80v-366q-51-14-85.5-56T160-600v-280h80v280h40v-280h80v280h40v-280h80v280q0 56-34.5 98T360-446v366h-80Zm400 0v-320H560v-280q0-83 58.5-141.5T760-880v800h-80Z"/></svg>
                        <span>{{ __('nav.meal_tracker') }}</span>
                    </a>
                    <a href="{{ route('sleep.index') }}" class="nav-link {{ request()->routeIs('sleep.*') ? 'active' : '' }}">
                        <svg viewBox="0 -960 960 960"><path d="M480-120q-150 0-255-105T120-480q0-150 105-255t255-105q14 0 27.5 1t26.5 3q-41 29-65.5 75.5T444-660q0 90 63 153t153 63q55 0 101-24.5t75-65.5q2 13 3 26.5t1 27.5q0 150-105 255T480-120Z"/></svg>
                        <span>{{ __('nav.sleep_tracker') }}</span>
                    </a>
                    <a href="{{ route('water.index') }}" class="nav-link {{ request()->routeIs('water.*') ? 'active' : '' }}">
                        <svg viewBox="0 -960 960 960"><path d="M480-80q-137 0-228.5-94T160-408q0-100 79.5-217.5T480-880q161 137 240.5 254.5T800-408q0 140-91.5 234T480-80Z"/></svg>
                        <span>{{ __('nav.water_tracker') }}</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">{{ __('nav.progress') }}</div>
                    <a href="{{ route('progress.index') }}" class="nav-link {{ request()->routeIs('progress.*') ? 'active' : '' }}">
                        <svg viewBox="0 -960 960 960"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm40-80h480L570-480 450-320l-90-120-120 160Z"/></svg>
                        <span>{{ __('nav.progress_photos') }}</span>
                    </a>
                    <a href="{{ route('goals.index') }}" class="nav-link {{ request()->routeIs('goals.*') ? 'active' : '' }}">
                        <svg viewBox="0 -960 960 960"><path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-160q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47Z"/></svg>
                        <span>{{ __('nav.goals') }}</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">{{ __('nav.tools') }}</div>
                    <a href="{{ route('calories.index') }}" class="nav-link {{ request()->routeIs('calories.*') ? 'active' : '' }}">
                        <svg viewBox="0 -960 960 960"><path d="M320-240h60v-80h80v-60h-80v-80h-60v80h-80v60h80v80Zm200-30h200v-60H520v60Zm0-100h200v-60H520v60Zm44-152 56-56 56 56 42-42-56-58 56-56-42-42-56 56-56-56-42 42 56 56-56 58 42 42Zm-314-70h200v-60H250v60ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Z"/></svg>
                        <span>{{ __('nav.calculator') }}</span>
                    </a>
                    <a href="{{ route('biography.edit') }}" class="nav-link {{ request()->routeIs('biography.*') ? 'active' : '' }}">
                        <svg viewBox="0 -960 960 960"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"/></svg>
                        <span>{{ __('nav.biography') }}</span>
                    </a>
                </div>

                @if(Auth::user()->role === 'admin')
                <div class="nav-section">
                    <div class="nav-section-title">{{ __('nav.admin') }}</div>
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                        <svg viewBox="0 -960 960 960"><path d="M680-280q25 0 42.5-17.5T740-340q0-25-17.5-42.5T680-400q-25 0-42.5 17.5T620-340q0 25 17.5 42.5T680-280Zm0 120q31 0 57-14.5t42-38.5q-22-13-47-20t-52-7q-27 0-52 7t-47 20q16 24 42 38.5t57 14.5ZM480-80q-139-35-229.5-159.5T160-516v-244l320-120 320 120v227q-19-8-39-14.5t-41-9.5v-147l-240-90-240 90v188q0 47 12.5 94t35 89.5Q310-290 342-254t71 60q11 32 29 61t41 52q-1 0-1.5.5t-1.5.5Zm200 0q-83 0-141.5-58.5T480-280q0-83 58.5-141.5T680-480q83 0 141.5 58.5T880-280q0 83-58.5 141.5T680-80Z"/></svg>
                        <span>{{ __('nav.admin_panel') }}</span>
                    </a>
                </div>
                @endif
            </nav>

            <div class="sidebar-footer">
                <div class="user-card" id="userCard" aria-expanded="false">
                    <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('storage/logo/defaultPhoto.jpg') }}" alt="Avatar" class="user-avatar">
                    <div class="user-info">
                        <div class="user-name">{{ Auth::user()->name }}</div>
                        <div class="user-role">{{ ucfirst(Auth::user()->role ?? 'Member') }}</div>
                    </div>
                    <svg class="user-menu-icon" viewBox="0 -960 960 960"><path d="M480-344 240-584l56-56 184 184 184-184 56 56-240 240Z"/></svg>

                    <div class="user-dropdown" id="userDropdown">
                        <a href="{{ route('profile.edit') }}" class="dropdown-item">
                            <svg viewBox="0 -960 960 960"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"/></svg>
                            <span>{{ __('nav.profile_settings') }}</span>
                        </a>
                        <a href="{{ route('settings.index') }}" class="dropdown-item">
                            <svg viewBox="0 -960 960 960"><path d="m370-80-16-128q-13-5-24.5-12T307-235l-119 50L78-375l103-78q-1-7-1-13.5v-27q0-6.5 1-13.5L78-585l110-190 119 50q11-8 23-15t24-12l16-128h220l16 128q13 5 24.5 12t22.5 15l119-50 110 190-103 78q1 7 1 13.5v27q0 6.5-2 13.5l103 78-110 190-118-50q-11 8-23 15t-24 12L590-80H370Zm70-80h79l14-106q31-8 57.5-23.5T639-327l99 41 39-68-86-65q5-14 7-29.5t2-31.5q0-16-2-31.5t-7-29.5l86-65-39-68-99 42q-22-23-48.5-38.5T533-694l-13-106h-79l-14 106q-31 8-57.5 23.5T321-633l-99-41-39 68 86 64q-5 15-7 30t-2 32q0 16 2 31t7 30l-86 65 39 68 99-42q22 23 48.5 38.5T427-266l13 106Zm42-180q58 0 99-41t41-99q0-58-41-99t-99-41q-59 0-99.5 41T342-480q0 58 40.5 99t99.5 41Zm-2-140Z"/></svg>
                            <span>{{ __('nav.settings') }}</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                            @csrf
                            <button type="submit" class="dropdown-item danger">
                                <svg viewBox="0 -960 960 960"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>
                                <span>{{ __('nav.logout') }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Mobile Menu Toggle -->
        <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle menu">
            <svg viewBox="0 0 24 24"><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg>
        </button>

        <!-- Mobile User Avatar -->
        <a href="{{ route('profile.edit') }}" class="mobile-user-avatar">
            <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('storage/logo/defaultPhoto.jpg') }}" alt="Avatar">
        </a>

        <!-- Mobile Bottom Navigation -->
        <nav class="mobile-nav">
            <div class="mobile-nav-inner">
                <a href="{{ route('dashboard') }}" class="mobile-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 -960 960 960"><path d="M520-600v-240h320v240H520ZM120-440v-400h320v400H120Zm400 320v-400h320v400H520Zm-400 0v-240h320v240H120Z"/></svg>
                    <span>{{ __('nav.home') }}</span>
                </a>
                <a href="{{ route('posts.index') }}" class="mobile-nav-link {{ request()->routeIs('posts.*') ? 'active' : '' }}">
                    <svg viewBox="0 -960 960 960"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"/></svg>
                    <span>{{ __('nav.social') }}</span>
                </a>
                <a href="{{ route('activity-calendar') }}" class="mobile-nav-link {{ request()->routeIs('activity-calendar*') ? 'active' : '' }}">
                    <svg viewBox="0 -960 960 960"><path d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Z"/></svg>
                    <span>{{ __('nav.calendar') }}</span>
                </a>
                <a href="{{ route('goals.index') }}" class="mobile-nav-link {{ request()->routeIs('goals.*') ? 'active' : '' }}">
                    <svg viewBox="0 -960 960 960"><path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"/></svg>
                    <span>{{ __('nav.goals') }}</span>
                </a>
                <a href="{{ route('profile.edit') }}" class="mobile-nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <svg viewBox="0 -960 960 960"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47Z"/></svg>
                    <span>{{ __('nav.profile') }}</span>
                </a>
            </div>
        </nav>
        @endauth

        <!-- Main Content -->
        <div class="main-wrapper">
            <main class="main-content">
                @if(session('success'))
                    <div class="alert alert-success animate-slide-up">
                        <svg width="20" height="20" viewBox="0 -960 960 960" fill="currentColor"><path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error animate-slide-up">
                        <svg width="20" height="20" viewBox="0 -960 960 960" fill="currentColor"><path d="M480-280q17 0 28.5-11.5T520-320q0-17-11.5-28.5T480-360q-17 0-28.5 11.5T440-320q0 17 11.5 28.5T480-280Zm-40-160h80v-240h-80v240Zm40 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Mobile menu toggle
            const mobileToggle = document.getElementById('mobileMenuToggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            function toggleSidebar() {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
                document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
            }

            mobileToggle?.addEventListener('click', toggleSidebar);
            overlay?.addEventListener('click', toggleSidebar);

            // User dropdown
            const userCard = document.getElementById('userCard');
            const userDropdown = document.getElementById('userDropdown');

            userCard?.addEventListener('click', (e) => {
                e.stopPropagation();
                const isExpanded = userCard.getAttribute('aria-expanded') === 'true';
                userCard.setAttribute('aria-expanded', !isExpanded);
                userDropdown.classList.toggle('active');
            });

            document.addEventListener('click', (e) => {
                if (!userCard?.contains(e.target)) {
                    userCard?.setAttribute('aria-expanded', 'false');
                    userDropdown?.classList.remove('active');
                }
            });

            // Close sidebar on escape
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    sidebar?.classList.remove('active');
                    overlay?.classList.remove('active');
                    document.body.style.overflow = '';
                    userDropdown?.classList.remove('active');
                    userCard?.setAttribute('aria-expanded', 'false');
                }
            });
        });
    </script>

    @yield('scripts')
</body>
</html>
