@extends('layouts.app', ['title' => 'FitLife — ' . __('dashboard.title')])

@section('hide-mobile-nav', '1')
@section('flush-mobile-content', '1')

@section('content')
<div class="dashboard-page">

    <div class="dashboard-header">
        <h1 class="dashboard-greeting">{{ __('dashboard.welcome_back') }} <span>{{ Auth::user()->name }}</span> 👋</h1>
        <p class="dashboard-date">{{ now()->translatedFormat('l, d F Y') }}</p>
    </div>

    <div class="stats-row">

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon calories">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($totalCalories ?? 0) }}</div>
            <div class="stat-card-label">{{ __('dashboard.calories_today') }}</div>
            <div class="stat-card-progress">
                <div class="stat-card-progress-fill calories" style="width: {{ min(100, (($totalCalories ?? 0) / 2000) * 100) }}%"></div>
            </div>
            <div class="stat-card-footer">
                <span>{{ number_format($totalCalories ?? 0) }} / 2000 {{ __('dashboard.kcal') }}</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon water">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/></svg>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($totalWater ?? 0) }}</div>
            <div class="stat-card-label">{{ __('dashboard.water_ml') }}</div>
            <div class="stat-card-progress">
                <div class="stat-card-progress-fill water" style="width: {{ min(100, (($totalWater ?? 0) / 2000) * 100) }}%"></div>
            </div>
            <div class="stat-card-footer">
                <span>{{ number_format($totalWater ?? 0) }} / 2000 {{ __('dashboard.ml') }}</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon sleep">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($totalSleep ?? 0, 1) }}{{ __('dashboard.h') }}</div>
            <div class="stat-card-label">{{ __('dashboard.sleep_duration') }}</div>
            <div class="stat-card-progress">
                <div class="stat-card-progress-fill sleep" style="width: {{ min(100, (($totalSleep ?? 0) / 8) * 100) }}%"></div>
            </div>
            <div class="stat-card-footer">
                <span>{{ number_format($totalSleep ?? 0, 1) }} / 8 {{ __('dashboard.hours') }}</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon goals">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                </div>
            </div>
            @php $completedGoals = $goals->filter(fn($g) => $g->progressPercent() >= 100)->count(); @endphp
            <div class="stat-card-value">{{ $completedGoals }}/{{ $goals->count() }}</div>
            <div class="stat-card-label">{{ __('dashboard.active_goals') }}</div>
            <div class="stat-card-progress">
                <div class="stat-card-progress-fill goals" style="width: {{ $goals->count() > 0 ? ($completedGoals / $goals->count()) * 100 : 0 }}%"></div>
            </div>
            <div class="stat-card-footer">
                <span>{{ $completedGoals }} {{ __('dashboard.completed') }}</span>
            </div>
        </div>
    </div>

    <div class="dashboard-grid">

        <div class="dashboard-main">

            <div class="dash-card">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">
                        <svg viewBox="0 0 24 24"><path d="M19 3h-1V1h-2v2H8V1H6v2H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2zm0 16H5V8h14v11z"/></svg>
                        {{ __('dashboard.upcoming_events') }}
                    </h2>
                    <a href="{{ route('activity-calendar') }}" class="dash-card-action">{{ __('dashboard.view_all') }}</a>
                </div>
                <div class="dash-card-body">
                    @if($upcomingEvents->isEmpty())
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <svg viewBox="0 0 24 24"><path d="M19 3h-1V1h-2v2H8V1H6v2H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2zm0 16H5V8h14v11z"/></svg>
                            </div>
                            <p class="empty-state-text">{{ __('dashboard.no_upcoming_events') }}</p>
                            <a href="{{ route('activity-calendar') }}" class="empty-state-btn">{{ __('dashboard.add_event') }}</a>
                        </div>
                    @else
                        <div class="events-list">
                            @foreach($upcomingEvents as $event)
                                <div class="event-item {{ $event->type }}">
                                    <div class="event-date">
                                        <span class="event-date-day">{{ \Carbon\Carbon::parse($event->date)->format('d') }}</span>
                                        <span class="event-date-month">{{ \Carbon\Carbon::parse($event->date)->format('M') }}</span>
                                    </div>
                                    <div class="event-info">
                                        <div class="event-type">{{ ucfirst($event->type) }}</div>
                                        <p class="event-desc">{{ $event->description ?? __('dashboard.no_description') }}</p>
                                    </div>
                                    @if(\Carbon\Carbon::parse($event->date)->isToday())
                                        <span class="event-badge today">{{ __('dashboard.today') }}</span>
                                    @elseif(\Carbon\Carbon::parse($event->date)->isTomorrow())
                                        <span class="event-badge tomorrow">{{ __('dashboard.tomorrow') }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="dash-card">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">
                        <svg viewBox="0 0 24 24"><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm0 18c-4.4 0-8-3.6-8-8s3.6-8 8-8 8 3.6 8 8-3.6 8-8 8z"/></svg>
                        {{ __('dashboard.active_goals') }}
                    </h2>
                    <a href="{{ route('goals.index') }}" class="dash-card-action">{{ __('dashboard.manage') }}</a>
                </div>
                <div class="dash-card-body">
                    @if($goals->isEmpty())
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <svg viewBox="0 0 24 24"><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2z"/></svg>
                            </div>
                            <p class="empty-state-text">{{ __('dashboard.no_goals_set') }}</p>
                            <a href="{{ route('goals.create') }}" class="empty-state-btn">{{ __('dashboard.create_goal') }}</a>
                        </div>
                    @else
                        <div class="goals-list">
                            @foreach($goals as $goal)
                                @php $percent = min(100, max(0, (int) $goal->progressPercent())); @endphp
                                <div class="goal-item">
                                    <div class="goal-item-header">
                                        <span class="goal-item-type">{{ ucfirst($goal->type) }}</span>
                                        <span class="goal-item-percent {{ $percent >= 100 ? 'complete' : '' }}">{{ $percent }}%</span>
                                    </div>
                                    <div class="goal-item-progress">
                                        <div class="goal-item-progress-fill {{ $percent >= 100 ? 'complete' : ($percent < 50 ? 'warning' : '') }}" style="width: {{ $percent }}%"></div>
                                    </div>
                                    <div class="goal-item-footer">
                                        <span>{{ number_format($goal->current_value, 1) }}</span>
                                        <span>/</span>
                                        <span>{{ number_format($goal->target_value) }} {{ $goal->unit ?? '' }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="dash-card">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">
                        <svg viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                        {{ __('dashboard.progress_gallery') }}
                    </h2>
                    <a href="{{ route('progress.index') }}" class="dash-card-action">{{ __('dashboard.view_all') }}</a>
                </div>
                <div class="dash-card-body">
                    @if($photos->isEmpty())
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <svg viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                            </div>
                            <p class="empty-state-text">{{ __('dashboard.no_progress_photos') }}</p>
                            <a href="{{ route('progress.index') }}" class="empty-state-btn">{{ __('dashboard.add_photo') }}</a>
                        </div>
                    @else
                        <div class="photos-grid">
                            @foreach($photos as $idx => $photo)
                                <div class="photo-item"
                                     data-idx="{{ $idx }}"
                                     data-img="{{ asset('storage/' . $photo->photo) }}"
                                     data-desc="{{ $photo->description ?? '' }}"
                                     data-date="{{ $photo->created_at->format('M d, Y') }}"
                                     onclick="openLightbox(this)">
                                    <img src="{{ asset('storage/' . $photo->photo) }}" alt="Progress" loading="lazy">
                                    <div class="photo-item-overlay">
                                        <span class="photo-item-date">{{ $photo->created_at->format('M d') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="dashboard-sidebar">

            <div class="profile-card">
                <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('storage/logo/default-avatar.avif') }}"
                     alt="{{ Auth::user()->name }}"
                     class="profile-card-avatar">
                <div class="profile-card-name">{{ Auth::user()->name }}</div>
                <div class="profile-card-username">{{ '@' . Auth::user()->username }}</div>

                @php $bio = Auth::user()->biography; @endphp
                <div class="profile-stats-grid">
                    <div class="profile-stat-item">
                        <div class="profile-stat-value">{{ $bio?->age ?? '—' }}</div>
                        <div class="profile-stat-label">{{ __('dashboard.age') }}</div>
                    </div>
                    <div class="profile-stat-item">
                        <div class="profile-stat-value">{{ $bio?->weight ? $bio->weight . ' kg' : '—' }}</div>
                        <div class="profile-stat-label">{{ __('dashboard.weight') }}</div>
                    </div>
                    <div class="profile-stat-item">
                        <div class="profile-stat-value">{{ $bio?->height ? $bio->height . ' cm' : '—' }}</div>
                        <div class="profile-stat-label">{{ __('dashboard.height') }}</div>
                    </div>
                    <div class="profile-stat-item">
                        <div class="profile-stat-value">{{ $goals->count() }}</div>
                        <div class="profile-stat-label">{{ __('dashboard.goals') }}</div>
                    </div>
                </div>

                <a href="{{ route('profile.edit') }}" class="profile-card-btn">{{ __('dashboard.edit_profile') }}</a>
            </div>

<div id="lightbox" class="lightbox">
    <div class="lightbox-backdrop" onclick="closeLightbox()"></div>
    <div class="lightbox-content">
        <button class="lightbox-close" onclick="closeLightbox()">
            <svg viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
        </button>
        <img id="lightbox-img" src="" alt="">
        <div class="lightbox-info">
            <p id="lightbox-desc"></p>
            <p id="lightbox-date"></p>
        </div>
        <button class="lightbox-nav prev" onclick="prevPhoto()">
            <svg viewBox="0 0 24 24"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
        </button>
        <button class="lightbox-nav next" onclick="nextPhoto()">
            <svg viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
        </button>
    </div>
</div>
@endsection

@section('scripts')
<script>
let currentPhotoIndex = 0;
const photoItems = document.querySelectorAll('.photo-item');

function openLightbox(el) {
    const lightbox = document.getElementById('lightbox');
    currentPhotoIndex = parseInt(el.dataset.idx);
    document.getElementById('lightbox-img').src = el.dataset.img;
    document.getElementById('lightbox-desc').textContent = el.dataset.desc || '';
    document.getElementById('lightbox-date').textContent = el.dataset.date;
    lightbox.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    document.getElementById('lightbox').classList.remove('active');
    document.body.style.overflow = '';
}

function prevPhoto() {
    if (!photoItems.length) return;
    currentPhotoIndex = (currentPhotoIndex - 1 + photoItems.length) % photoItems.length;
    updateLightbox();
}

function nextPhoto() {
    if (!photoItems.length) return;
    currentPhotoIndex = (currentPhotoIndex + 1) % photoItems.length;
    updateLightbox();
}

function updateLightbox() {
    const el = photoItems[currentPhotoIndex];
    document.getElementById('lightbox-img').src = el.dataset.img;
    document.getElementById('lightbox-desc').textContent = el.dataset.desc || '';
    document.getElementById('lightbox-date').textContent = el.dataset.date;
}

document.addEventListener('keydown', (e) => {
    if (!document.getElementById('lightbox').classList.contains('active')) return;
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowLeft') prevPhoto();
    if (e.key === 'ArrowRight') nextPhoto();
});
</script>
@endsection
