@extends('layouts.app')

@section('content')
<div id="fitlife-container" class="goals-page" role="application" aria-label="{{ __('goals.fitlife_goals') }}">
  <main>
    <button id="mobile-toggle" aria-controls="sidebar" aria-expanded="false">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
        <path d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>

    <header>
      <div class="header-left">
        <h1><span>FitLife</span> {{ __('goals.title') }}</h1>
        <p class="muted">{{ __('goals.track_progress_subtitle') }}</p>
      </div>
    </header>

    {{-- Stats Bar --}}
    <div class="goals-stats">
      <div class="goals-stat-card">
        <div class="goals-stat-icon goals-stat-icon--total">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
          </svg>
        </div>
        <div class="goals-stat-body">
          <span class="goals-stat-value">{{ $goals->count() }}</span>
          <span class="goals-stat-label">{{ __('goals.total_goals') }}</span>
        </div>
      </div>
      <div class="goals-stat-card">
        <div class="goals-stat-icon goals-stat-icon--active">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
          </svg>
        </div>
        <div class="goals-stat-body">
          <span class="goals-stat-value">{{ $goals->filter(fn($g) => $g->current_value < $g->target_value)->count() }}</span>
          <span class="goals-stat-label">{{ __('goals.active_goals') }}</span>
        </div>
      </div>
      <div class="goals-stat-card">
        <div class="goals-stat-icon goals-stat-icon--done">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <div class="goals-stat-body">
          <span class="goals-stat-value">{{ $goals->filter(fn($g) => $g->current_value >= $g->target_value)->count() }}</span>
          <span class="goals-stat-label">{{ __('goals.completed') }}</span>
        </div>
      </div>
    </div>

    {{-- Section Header --}}
    <section class="goals-header-section" aria-labelledby="create-goal-heading">
      <h3 id="create-goal-heading">{{ __('goals.your_goals') }}</h3>
      <a href="{{ route('goals.create') }}" class="create-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <path d="M12 5v14M5 12h14" />
        </svg>
        {{ __('goals.new_goal') }}
      </a>
    </section>

    {{-- Goals Grid --}}
    <section aria-labelledby="goals-heading">
      <div class="goals-grid">
        @forelse($goals as $goal)
        @php
          $pct = $goal->target_value > 0 ? min(round($goal->current_value / $goal->target_value * 100), 100) : 0;
          $isDone = $pct >= 100;
        @endphp
        <div class="goal-card {{ $isDone ? 'goal-card--done' : '' }}">
          <div class="goal-card-header">
            <div class="goal-type-icon goal-type-icon--{{ $goal->type }}">
              @switch($goal->type)
                @case('steps')
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                  @break
                @case('calories')
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 12c-2-2.67-6-2.67-6 2 0 4 6 7 6 7s6-3 6-7c0-4.67-4-4.67-6-2z"/></svg>
                  @break
                @case('sleep')
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                  @break
                @case('weight')
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6.5 7h11M4 7l2 14h12l2-14M9 7V5a3 3 0 016 0v2"/></svg>
                  @break
                @default
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.56 5.82 22 7 14.14l-5-4.87 6.91-1.01z"/></svg>
              @endswitch
            </div>
            <div class="goal-card-title">
              <h4>{{ ucfirst($goal->type) }} {{ __('goals.goal_suffix') }}</h4>
              @if($isDone)
                <span class="goal-badge goal-badge--done">{{ __('goals.completed') }}</span>
              @else
                <span class="goal-badge goal-badge--active">{{ __('goals.in_progress') }}</span>
              @endif
            </div>
          </div>

          @if($goal->description)
            <p class="goal-description">{{ $goal->description }}</p>
          @endif

          <div class="goal-metrics">
            <div class="goal-metric">
              <span class="goal-metric-label">{{ __('goals.current') }}</span>
              <span class="goal-metric-value">{{ $goal->current_value }}</span>
            </div>
            <div class="goal-metric">
              <span class="goal-metric-label">{{ __('goals.target') }}</span>
              <span class="goal-metric-value">{{ $goal->target_value }}</span>
            </div>
            <div class="goal-metric">
              <span class="goal-metric-label">{{ __('goals.end_date') }}</span>
              <span class="goal-metric-value">{{ $goal->end_date }}</span>
            </div>
          </div>

          <div class="goal-progress-wrap">
            <div class="goal-progress-bar">
              <div class="goal-progress-fill" style="width: {{ $pct }}%"></div>
            </div>
            <span class="goal-progress-pct">{{ $pct }}%</span>
          </div>

          <a href="{{ route('goals.log', $goal) }}" class="log-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 5v14M5 12h14"/></svg>
            {{ __('goals.log_progress') }}
          </a>
        </div>
        @empty
        <div class="goals-empty">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.56 5.82 22 7 14.14l-5-4.87 6.91-1.01z"/>
          </svg>
          <p>{{ __('goals.no_goals_start_creating') }}</p>
        </div>
        @endforelse
      </div>
    </section>
  </main>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/goalsindex.js') }}"></script>
@endsection