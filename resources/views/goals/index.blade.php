@extends('layouts.app')

@section('hide-mobile-nav', '1')
@section('flush-mobile-content', '1')

@php
  $shouldOpenCreateModal = $errors->hasAny(['type', 'target_value', 'end_date', 'description']) && old('_modal') === 'create';
  $shouldOpenLogModal = $errors->has('value') && old('_modal') === 'log' && old('_goal_id');
  $selectedLogGoal = $shouldOpenLogModal ? $goals->firstWhere('id', (int) old('_goal_id')) : null;
  $logCurrentValue = $selectedLogGoal ? (float) $selectedLogGoal->current_value : 0.0;
  $logPendingValue = is_numeric(old('value')) ? (float) old('value') : 0.0;
  $logProjectedValue = $logCurrentValue + $logPendingValue;
  $logCurrentDisplay = floor($logCurrentValue) == $logCurrentValue ? number_format($logCurrentValue, 0) : number_format($logCurrentValue, 2);
  $logProjectedDisplay = floor($logProjectedValue) == $logProjectedValue ? number_format($logProjectedValue, 0) : number_format($logProjectedValue, 2);
@endphp

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

    <section class="goals-header-section" aria-labelledby="create-goal-heading">
      <h3 id="create-goal-heading">{{ __('goals.your_goals') }}</h3>
      <button type="button" class="create-btn" id="openCreateGoal">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <path d="M12 5v14M5 12h14" />
        </svg>
        {{ __('goals.new_goal') }}
      </button>
    </section>

    <section aria-labelledby="goals-heading">
      <div class="goals-grid">
        @forelse($goals as $goal)
        @php
          $pct = $goal->target_value > 0 ? min(round($goal->current_value / $goal->target_value * 100), 100) : 0;
          $isDone = $pct >= 100;
          $currentValue = (float) $goal->current_value;
          $targetValue = (float) $goal->target_value;
          $currentDisplay = floor($currentValue) == $currentValue ? number_format($currentValue, 0) : number_format($currentValue, 2);
          $targetDisplay = floor($targetValue) == $targetValue ? number_format($targetValue, 0) : number_format($targetValue, 2);
          $endDateDisplay = \Carbon\Carbon::parse($goal->end_date)->format('d M Y');
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
                <span class="goal-badge goal-badge--done">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                  {{ __('goals.completed') }}
                </span>
              @else
                <span class="goal-badge goal-badge--active">{{ __('goals.in_progress') }}</span>
              @endif
            </div>
          </div>

          @if($goal->description)
            <div class="goal-description-block">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
              <p>{{ $goal->description }}</p>
            </div>
          @endif

          <div class="goal-progress-header">
            <span class="goal-progress-label-text">{{ __('goals.progress') }}</span>
            <span class="goal-progress-pct {{ $isDone ? 'goal-progress-pct--done' : '' }}">{{ $pct }}%</span>
          </div>
          <div class="goal-progress-bar">
            <div class="goal-progress-fill" style="width: {{ $pct }}%"></div>
          </div>

          <div class="goal-metrics">
            <div class="goal-metric">
              <span class="goal-metric-label">{{ __('goals.current') }}</span>
              <span class="goal-metric-value">{{ $currentDisplay }}</span>
            </div>
            <div class="goal-metric-divider" aria-hidden="true"></div>
            <div class="goal-metric">
              <span class="goal-metric-label">{{ __('goals.target') }}</span>
              <span class="goal-metric-value">{{ $targetDisplay }}</span>
            </div>
            <div class="goal-metric-divider" aria-hidden="true"></div>
            <div class="goal-metric">
              <span class="goal-metric-label">{{ __('goals.end_date') }}</span>
              <span class="goal-metric-value">{{ $endDateDisplay }}</span>
            </div>
          </div>

          @if(!$isDone)
            <button
              type="button"
              class="log-btn open-log-modal"
              data-goal-id="{{ $goal->id }}"
              data-goal-type="{{ ucfirst($goal->type) }}"
              data-goal-subtitle="{{ __('goals.update_progress_for', ['type' => ucfirst($goal->type)]) }}"
              data-goal-current="{{ $currentValue }}"
              data-log-url="{{ route('goals.storeLog', $goal) }}"
            >
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 5v14M5 12h14"/></svg>
              {{ __('goals.log_progress') }}
            </button>
          @else
            <div class="goal-done-label">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              {{ __('goals.completed') }}!
            </div>
          @endif
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

<div class="gm-backdrop {{ $shouldOpenCreateModal ? 'gm-open' : '' }}" id="createGoalBackdrop" aria-hidden="{{ $shouldOpenCreateModal ? 'false' : 'true' }}">
  <div class="gm-modal" role="dialog" aria-modal="true" aria-labelledby="createGoalTitle">
    <div class="gm-header">
      <div class="gm-header-info">
        <div class="gm-header-icon gm-header-icon--create">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 5v14M5 12h14"/></svg>
        </div>
        <div>
          <h3 id="createGoalTitle">{{ __('goals.create_new_goal') }}</h3>
          <p>{{ __('goals.set_new_goal_subtitle') }}</p>
        </div>
      </div>
      <button type="button" class="gm-close" id="closeCreateGoal" aria-label="Close">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="gm-body">
      <form action="{{ route('goals.store') }}" method="POST" class="gm-form">
        @csrf
        <input type="hidden" name="_modal" value="create">

        <div class="gm-field">
          <label class="gm-label">{{ __('goals.goal_type') }}</label>
          <div class="gm-type-grid">
            <label class="gm-type-tile">
              <input type="radio" name="type" value="steps" {{ old('type') === 'steps' ? 'checked' : '' }} required>
              <div class="gm-type-tile-inner">
                <div class="gm-type-icon gm-type-icon--steps">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                </div>
                <span>{{ __('goals.type_steps') }}</span>
              </div>
            </label>
            <label class="gm-type-tile">
              <input type="radio" name="type" value="calories" {{ old('type') === 'calories' ? 'checked' : '' }}>
              <div class="gm-type-tile-inner">
                <div class="gm-type-icon gm-type-icon--calories">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 12c-2-2.67-6-2.67-6 2 0 4 6 7 6 7s6-3 6-7c0-4.67-4-4.67-6-2z"/></svg>
                </div>
                <span>{{ __('goals.type_calories') }}</span>
              </div>
            </label>
            <label class="gm-type-tile">
              <input type="radio" name="type" value="sleep" {{ old('type') === 'sleep' ? 'checked' : '' }}>
              <div class="gm-type-tile-inner">
                <div class="gm-type-icon gm-type-icon--sleep">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                </div>
                <span>{{ __('goals.type_sleep') }}</span>
              </div>
            </label>
            <label class="gm-type-tile">
              <input type="radio" name="type" value="weight" {{ old('type') === 'weight' ? 'checked' : '' }}>
              <div class="gm-type-tile-inner">
                <div class="gm-type-icon gm-type-icon--weight">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6.5 7h11M4 7l2 14h12l2-14M9 7V5a3 3 0 016 0v2"/></svg>
                </div>
                <span>{{ __('goals.type_weight') }}</span>
              </div>
            </label>
          </div>
          @error('type')
            <span class="gm-error">{{ $message }}</span>
          @enderror
        </div>

        <div class="gm-row">
          <div class="gm-field">
            <label for="gm_target_value" class="gm-label">{{ __('goals.target_value') }}</label>
            <input type="number" id="gm_target_value" name="target_value" step="0.01" min="0.01" value="{{ old('target_value') }}" placeholder="{{ __('goals.enter_target_value') }}" class="gm-input @error('target_value') gm-input--error @enderror" required>
            @error('target_value')
              <span class="gm-error">{{ $message }}</span>
            @enderror
          </div>
          <div class="gm-field">
            <label for="gm_end_date" class="gm-label">{{ __('goals.end_date') }}</label>
            <input type="date" id="gm_end_date" name="end_date" value="{{ old('end_date') }}" class="gm-input @error('end_date') gm-input--error @enderror" required>
            @error('end_date')
              <span class="gm-error">{{ $message }}</span>
            @enderror
          </div>
        </div>

        <div class="gm-field">
          <label for="gm_description" class="gm-label">{{ __('goals.description_optional') }}</label>
          <textarea id="gm_description" name="description" rows="3" placeholder="{{ __('goals.add_description') }}" class="gm-input gm-textarea @error('description') gm-input--error @enderror">{{ old('description') }}</textarea>
          @error('description')
            <span class="gm-error">{{ $message }}</span>
          @enderror
        </div>

        <button type="submit" class="gm-submit-btn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 5v14M5 12h14"/></svg>
          {{ __('goals.create') }}
        </button>
      </form>
    </div>
  </div>
</div>

<div class="gm-backdrop {{ $shouldOpenLogModal ? 'gm-open' : '' }}" id="logProgressBackdrop" aria-hidden="{{ $shouldOpenLogModal ? 'false' : 'true' }}">
  <div class="gm-modal gm-modal--sm" role="dialog" aria-modal="true" aria-labelledby="logProgressTitle">
    <div class="gm-header">
      <div class="gm-header-info">
        <div class="gm-header-icon gm-header-icon--log">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <div>
          <h3 id="logProgressTitle">{{ __('goals.log_progress') }}</h3>
          <p id="logProgressSubtitle" class="gm-subtitle">
            {{ old('_goal_type') ? __('goals.update_progress_for', ['type' => old('_goal_type')]) : '' }}
          </p>
        </div>
      </div>
      <button type="button" class="gm-close" id="closeLogProgress" aria-label="Close">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="gm-body">
      <form id="logProgressForm" action="{{ old('_goal_id') ? route('goals.storeLog', old('_goal_id')) : '' }}" method="POST" class="gm-form">
        @csrf
        <input type="hidden" name="_modal" value="log">
        <input type="hidden" name="_goal_id" id="logGoalId" value="{{ old('_goal_id') }}">
        <input type="hidden" name="_goal_type" id="logGoalType" value="{{ old('_goal_type') }}">
        <input type="hidden" name="_goal_current" id="logGoalCurrent" value="{{ old('_goal_current', $logCurrentValue) }}">

        <div class="gm-log-summary">
          <div class="gm-log-stat">
            <span class="gm-log-stat-label">{{ __('goals.current') }}</span>
            <strong class="gm-log-stat-value" id="logCurrentTotal">{{ $logCurrentDisplay }}</strong>
          </div>
          <div class="gm-log-stat gm-log-stat--accent">
            <span class="gm-log-stat-label">{{ __('goals.new_total') }}</span>
            <strong class="gm-log-stat-value" id="logProjectedTotal">{{ $logProjectedDisplay }}</strong>
          </div>
        </div>

        <div class="gm-log-note">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12 8v4l3 3"/><path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <span>{{ __('goals.log_progress_hint') }}</span>
        </div>

        <div class="gm-field">
          <label for="log_value" class="gm-label">{{ __('goals.todays_value') }}</label>
          <div class="gm-log-input-shell @error('value') gm-log-input-shell--error @enderror">
            <span class="gm-log-input-prefix">+</span>
            <input type="number" id="log_value" name="value" step="0.01" min="0" value="{{ old('value') }}" placeholder="{{ __('goals.enter_todays_value') }}" class="gm-input gm-input--lg gm-log-input @error('value') gm-input--error @enderror" required>
          </div>
          @error('value')
            <span class="gm-error">{{ $message }}</span>
          @enderror
        </div>
        <button type="submit" class="gm-submit-btn gm-submit-btn--log" id="logProgressSubmit" {{ old('_goal_id') ? '' : 'disabled' }}>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          {{ __('goals.submit') }}
        </button>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/goalsindex.js') }}"></script>
@endsection
