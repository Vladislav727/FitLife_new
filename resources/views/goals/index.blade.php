@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/goalsindex.css') }}" rel="stylesheet">
@endsection

@section('content')
<div id="fitlife-container" role="application" aria-label="{{ __('goals.fitlife_goals') }}">
  <!-- Main Content -->
  <main>
    <!-- Mobile Menu Toggle -->
    <button id="mobile-toggle" aria-controls="sidebar" aria-expanded="false">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
        <path d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>

    <!-- Page Header -->
    <header>
      <div class="header-left">
        <h1><span>FitLife</span> {{ __('goals.title') }}</h1>
        <p class="muted">{{ __('goals.track_progress_subtitle') }}</p>
      </div>
    </header>

    <!-- Create Goal Button -->
    <section aria-labelledby="create-goal-heading">
      <h3 id="create-goal-heading">{{ __('goals.your_goals') }}</h3>
      <a href="{{ route('goals.create') }}" class="create-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <path d="M12 5v14M5 12h14" />
        </svg>
        {{ __('goals.new_goal') }}
      </a>
    </section>

    <!-- Goals List -->
    <section aria-labelledby="goals-heading">
      <div class="goals-grid">
        @forelse($goals as $goal)
        <div class="goal-card">
          <h4>{{ ucfirst($goal->type) }} {{ __('goals.goal_suffix') }}</h4>
          <p><strong>{{ __('goals.target') }}:</strong> {{ $goal->target_value }}</p>
          <p><strong>{{ __('goals.current') }}:</strong> {{ $goal->current_value }}</p>
          <p><strong>{{ __('goals.end_date') }}:</strong> {{ $goal->end_date }}</p>
          @if($goal->description)
          <p><em>{{ $goal->description }}</em></p>
          @endif
          <div class="progress-bar">
            <div class="progress" style="width: {{ min($goal->current_value / $goal->target_value * 100, 100) }}%;"></div>
          </div>
          <a href="{{ route('goals.log', $goal) }}" class="log-btn">{{ __('goals.log_progress') }}</a>
        </div>
        @empty
        <p class="no-data">{{ __('goals.no_goals_start_creating') }}</p>
        @endforelse
      </div>
    </section>
  </main>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/goalsindex.js') }}"></script>
@endsection