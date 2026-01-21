@extends('layouts.app', ['title' => 'FitLife — ' . __('dashboard.title')])

@section('styles')
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Alert Container --}}
    <div class="alert-container" id="alert-container"></div>

    {{-- Page Header --}}
    <header class="page-header animate-slide-down">
        <h1 class="page-title">{{ __('dashboard.welcome', ['name' => Auth::user()->name]) }} 👋</h1>
        <p class="page-subtitle">{{ __('dashboard.subtitle') }}</p>
    </header>

    {{-- Quick Stats Grid --}}
    <div class="grid grid-cols-4 md:grid-cols-2 sm:grid-cols-1 stagger-animation mb-8">
        {{-- Calories Card --}}
        <div class="stat-card">
            <div class="stat-icon warning">
                <svg viewBox="0 0 24 24"><path d="M11 21c0-5.5-6-9-6-13.5C5 4 8.6 2 12 2s7 2 7 5.5C19 12 13 15.5 13 21h-2zm1-2a9 9 0 004.5-5.5c.5-2.5-1.5-5-5.5-5S6 11 6.5 13.5A9 9 0 0011 19h1z"/></svg>
            </div>
            <div class="stat-value">{{ number_format($totalCalories ?? 0) }}</div>
            <div class="stat-label">{{ __('dashboard.calories_today') }}</div>
            <div class="progress-bar mt-4">
                <div class="progress-fill warning" style="width: {{ min(100, (($totalCalories ?? 0) / 2000) * 100) }}%"></div>
            </div>
            <div class="text-xs text-muted mt-2">{{ number_format($totalCalories ?? 0) }} / 2000 {{ __('dashboard.kcal') }}</div>
        </div>

        {{-- Water Card --}}
        <div class="stat-card">
            <div class="stat-icon accent">
                <svg viewBox="0 0 24 24"><path d="M12 2c-5 6-9 10-9 14a9 9 0 0018 0c0-4-4-8-9-14zm0 18a6 6 0 01-6-6c0-2.5 2.5-5.5 6-10 3.5 4.5 6 7.5 6 10a6 6 0 01-6 6z"/></svg>
            </div>
            <div class="stat-value">{{ number_format($totalWater ?? 0) }}</div>
            <div class="stat-label">{{ __('dashboard.water_ml') }}</div>
            <div class="progress-bar mt-4">
                <div class="progress-fill" style="width: {{ min(100, (($totalWater ?? 0) / 2000) * 100) }}%"></div>
            </div>
            <div class="text-xs text-muted mt-2">{{ number_format($totalWater ?? 0) }} / 2000 {{ __('dashboard.ml') }}</div>
        </div>

        {{-- Sleep Card --}}
        <div class="stat-card">
            <div class="stat-icon secondary">
                <svg viewBox="0 0 24 24"><path d="M12 3a9 9 0 109 9c0-.5 0-1-.1-1.5a5.5 5.5 0 01-7.4-7.4A9 9 0 0012 3z"/></svg>
            </div>
            <div class="stat-value">{{ number_format($totalSleep ?? 0, 1) }}{{ __('dashboard.h') }}</div>
            <div class="stat-label">{{ __('dashboard.sleep_duration') }}</div>
            <div class="progress-bar mt-4">
                <div class="progress-fill" style="width: {{ min(100, (($totalSleep ?? 0) / 8) * 100) }}%"></div>
            </div>
            <div class="text-xs text-muted mt-2">{{ number_format($totalSleep ?? 0, 1) }} / 8 {{ __('dashboard.hours') }}</div>
        </div>

        {{-- Goals Card --}}
        <div class="stat-card">
            <div class="stat-icon success">
                <svg viewBox="0 0 24 24"><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm-1 15l-5-5 1.4-1.4 3.6 3.6 7.6-7.6L20 8l-9 9z"/></svg>
            </div>
            <div class="stat-value">{{ $goals->count() }}</div>
            <div class="stat-label">{{ __('dashboard.active_goals') }}</div>
            @php
                $completedGoals = $goals->filter(fn($g) => $g->progressPercent() >= 100)->count();
            @endphp
            <div class="badge badge-success mt-4">{{ $completedGoals }} {{ __('dashboard.completed') }}</div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="dashboard-grid">
        {{-- Left Column --}}
        <div class="dashboard-main">
            {{-- Upcoming Events --}}
            <div class="card animate-slide-up mb-6">
                <div class="card-header">
                    <h2 class="card-title flex items-center gap-3">
                        <svg class="text-primary" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 3h-1V1h-2v2H8V1H6v2H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2zm0 16H5V8h14v11zM9 10H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm-8 4H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2z"/>
                        </svg>
                        {{ __('dashboard.upcoming_events') }}
                    </h2>
                    <a href="{{ route('activity-calendar') }}" class="btn btn-ghost btn-sm">{{ __('dashboard.view_all') }}</a>
                </div>
                <div class="card-body">
                    @if($upcomingEvents->isEmpty())
                        <div class="empty-state">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="var(--text-muted)">
                                <path d="M19 3h-1V1h-2v2H8V1H6v2H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2zm0 16H5V8h14v11z"/>
                            </svg>
                            <p class="text-muted mt-4">{{ __('dashboard.no_upcoming_events') }}</p>
                            <a href="{{ route('activity-calendar') }}" class="btn btn-primary btn-sm mt-4">{{ __('dashboard.add_event') }}</a>
                        </div>
                    @else
                        <div class="events-list">
                            @foreach($upcomingEvents as $event)
                                <div class="event-card event-type-{{ $event->type }}">
                                    <div class="event-date-badge">
                                        <span class="event-day">{{ \Carbon\Carbon::parse($event->date)->format('d') }}</span>
                                        <span class="event-month">{{ \Carbon\Carbon::parse($event->date)->format('M') }}</span>
                                    </div>
                                    <div class="event-details">
                                        <span class="event-type-badge">{{ ucfirst($event->type) }}</span>
                                        <p class="event-description">{{ $event->description ?? __('dashboard.no_description') }}</p>
                                        @if(\Carbon\Carbon::parse($event->date)->isToday())
                                            <span class="badge badge-primary">{{ __('dashboard.today') }}</span>
                                        @elseif(\Carbon\Carbon::parse($event->date)->isTomorrow())
                                            <span class="badge badge-warning">{{ __('dashboard.tomorrow') }}</span>
                                        @endif
                                    </div>
                                    @if($event->completed)
                                        <div class="event-status completed">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Goals Progress --}}
            <div class="card animate-slide-up mb-6">
                <div class="card-header">
                    <h2 class="card-title flex items-center gap-3">
                        <svg class="text-success" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm0 18c-4.4 0-8-3.6-8-8s3.6-8 8-8 8 3.6 8 8-3.6 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/>
                        </svg>
                        {{ __('dashboard.active_goals') }}
                    </h2>
                    <a href="{{ route('goals.index') }}" class="btn btn-ghost btn-sm">{{ __('dashboard.manage') }}</a>
                </div>
                <div class="card-body">
                    @if($goals->isEmpty())
                        <div class="empty-state">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="var(--text-muted)">
                                <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2z"/>
                            </svg>
                            <p class="text-muted mt-4">{{ __('dashboard.no_goals_set') }}</p>
                            <a href="{{ route('goals.create') }}" class="btn btn-primary btn-sm mt-4">{{ __('dashboard.create_goal') }}</a>
                        </div>
                    @else
                        <div class="goals-list">
                            @foreach($goals as $goal)
                                @php $percent = min(100, max(0, (int) $goal->progressPercent())); @endphp
                                <div class="goal-card">
                                    <div class="goal-header">
                                        <span class="goal-type">{{ ucfirst($goal->type) }}</span>
                                        <span class="goal-percent {{ $percent >= 100 ? 'completed' : '' }}">{{ $percent }}%</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill {{ $percent >= 100 ? 'success' : ($percent >= 50 ? '' : 'warning') }}" 
                                             style="width: {{ $percent }}%"></div>
                                    </div>
                                    <div class="goal-footer">
                                        <span class="goal-current">{{ number_format($goal->current_value, 1) }}</span>
                                        <span class="goal-divider">/</span>
                                        <span class="goal-target">{{ number_format($goal->target_value) }} {{ $goal->unit ?? '' }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Progress Photos --}}
            <div class="card animate-slide-up">
                <div class="card-header">
                    <h2 class="card-title flex items-center gap-3">
                        <svg class="text-secondary" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                        </svg>
                        {{ __('dashboard.progress_gallery') }}
                    </h2>
                    <a href="{{ route('progress.index') }}" class="btn btn-ghost btn-sm">{{ __('dashboard.view_all') }}</a>
                </div>
                <div class="card-body">
                    @if($photos->isEmpty())
                        <div class="empty-state">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="var(--text-muted)">
                                <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                            </svg>
                            <p class="text-muted mt-4">{{ __('dashboard.no_progress_photos') }}</p>
                            <a href="{{ route('progress.index') }}" class="btn btn-primary btn-sm mt-4">{{ __('dashboard.add_photo') }}</a>
                        </div>
                    @else
                        <div class="photos-grid">
                            @foreach($photos as $idx => $photo)
                                <div class="photo-card" 
                                     role="button" 
                                     tabindex="0" 
                                     data-idx="{{ $idx }}"
                                     data-img="{{ asset('storage/' . $photo->photo) }}" 
                                     data-desc="{{ $photo->description ?? '' }}"
                                     data-date="{{ $photo->created_at->format('M d, Y') }}"
                                     onclick="openLightbox(this)">
                                    <img src="{{ asset('storage/' . $photo->photo) }}" alt="Progress photo" loading="lazy">
                                    <div class="photo-overlay">
                                        <span class="photo-date">{{ $photo->created_at->format('M d') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Sidebar --}}
        <div class="dashboard-sidebar">
            {{-- Profile Stats Card --}}
            <div class="card animate-slide-up mb-6">
                <div class="card-header">
                    <h2 class="card-title">{{ __('dashboard.your_profile') }}</h2>
                </div>
                <div class="card-body">
                    @php $bio = Auth::user()->biography; @endphp
                    <div class="profile-stats">
                        <div class="profile-stat">
                            <span class="profile-stat-label">{{ __('dashboard.full_name') }}</span>
                            <span class="profile-stat-value">{{ $bio?->full_name ?? Auth::user()->name }}</span>
                        </div>
                        <div class="profile-stat">
                            <span class="profile-stat-label">{{ __('dashboard.age') }}</span>
                            <span class="profile-stat-value">{{ $bio?->age ?? '—' }}</span>
                        </div>
                        <div class="profile-stat">
                            <span class="profile-stat-label">{{ __('dashboard.height') }}</span>
                            <span class="profile-stat-value">{{ $bio?->height ? $bio->height . ' ' . __('dashboard.cm') : '—' }}</span>
                        </div>
                        <div class="profile-stat">
                            <span class="profile-stat-label">{{ __('dashboard.weight') }}</span>
                            <span class="profile-stat-value">{{ $bio?->weight ? $bio->weight . ' ' . __('dashboard.kg') : '—' }}</span>
                        </div>
                        <div class="profile-stat">
                            <span class="profile-stat-label">{{ __('dashboard.gender') }}</span>
                            <span class="profile-stat-value">{{ $bio?->gender ? ucfirst($bio->gender) : '—' }}</span>
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="btn btn-secondary w-full mt-4">{{ __('dashboard.edit_profile') }}</a>
                </div>
            </div>

            {{-- Friends List --}}
            <div class="card animate-slide-up">
                <div class="card-header">
                    <h2 class="card-title flex items-center gap-3">
                        <svg class="text-accent" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                        </svg>
                        {{ __('dashboard.friends') }}
                    </h2>
                    <a href="{{ route('posts.index') }}" class="btn btn-ghost btn-sm">{{ __('dashboard.find') }}</a>
                </div>
                <div class="card-body">
                    @if(Auth::user()->friends->isEmpty())
                        <div class="empty-state small">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="var(--text-muted)">
                                <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3z"/>
                            </svg>
                            <p class="text-muted mt-3">{{ __('dashboard.no_friends') }}</p>
                            <a href="{{ route('posts.index') }}" class="btn btn-secondary btn-sm mt-3">{{ __('dashboard.find_friends') }}</a>
                        </div>
                    @else
                        <div class="friends-list">
                            @foreach(Auth::user()->friends->take(5) as $friend)
                                <a href="{{ route('profile.show', $friend) }}" class="friend-card">
                                    <img src="{{ $friend->avatar ? asset('storage/' . $friend->avatar) : asset('storage/logo/defaultPhoto.jpg') }}"
                                         alt="{{ $friend->name }}'s Avatar"
                                         class="friend-avatar">
                                    <div class="friend-info">
                                        <span class="friend-name">{{ $friend->name }}</span>
                                        <span class="friend-username">{{ '@' . $friend->username }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        @if(Auth::user()->friends->count() > 5)
                            <a href="{{ route('profile.friends') }}" class="btn btn-ghost btn-sm w-full mt-4">
                                {{ __('dashboard.view_all_friends', ['count' => Auth::user()->friends->count()]) }}
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Lightbox Modal --}}
    <div id="lightbox" class="lightbox" role="dialog" aria-hidden="true">
        <div class="lightbox-backdrop" onclick="closeLightbox()"></div>
        <div class="lightbox-content">
            <button class="lightbox-close" aria-label="Close" onclick="closeLightbox()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                </svg>
            </button>
            <img id="lightbox-img" src="" alt="Lightbox image">
            <div class="lightbox-info">
                <p id="lightbox-desc"></p>
                <p id="lightbox-date"></p>
            </div>
            <button class="lightbox-nav prev" aria-label="Previous" onclick="prevPhoto()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                </svg>
            </button>
            <button class="lightbox-nav next" aria-label="Next" onclick="nextPhoto()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
                </svg>
            </button>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Lightbox functionality
        let currentPhotoIndex = 0;
        const photoCards = document.querySelectorAll('.photo-card');
        
        function openLightbox(element) {
            const lightbox = document.getElementById('lightbox');
            const img = document.getElementById('lightbox-img');
            const desc = document.getElementById('lightbox-desc');
            const date = document.getElementById('lightbox-date');
            
            currentPhotoIndex = parseInt(element.dataset.idx);
            img.src = element.dataset.img;
            desc.textContent = element.dataset.desc || 'No description';
            date.textContent = element.dataset.date;
            
            lightbox.classList.add('active');
            lightbox.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }
        
        function closeLightbox() {
            const lightbox = document.getElementById('lightbox');
            lightbox.classList.remove('active');
            lightbox.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }
        
        function prevPhoto() {
            if (photoCards.length === 0) return;
            currentPhotoIndex = (currentPhotoIndex - 1 + photoCards.length) % photoCards.length;
            updateLightboxPhoto();
        }
        
        function nextPhoto() {
            if (photoCards.length === 0) return;
            currentPhotoIndex = (currentPhotoIndex + 1) % photoCards.length;
            updateLightboxPhoto();
        }
        
        function updateLightboxPhoto() {
            const card = photoCards[currentPhotoIndex];
            const img = document.getElementById('lightbox-img');
            const desc = document.getElementById('lightbox-desc');
            const date = document.getElementById('lightbox-date');
            
            img.src = card.dataset.img;
            desc.textContent = card.dataset.desc || 'No description';
            date.textContent = card.dataset.date;
        }
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            const lightbox = document.getElementById('lightbox');
            if (!lightbox.classList.contains('active')) return;
            
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') prevPhoto();
            if (e.key === 'ArrowRight') nextPhoto();
        });
    </script>
@endsection
