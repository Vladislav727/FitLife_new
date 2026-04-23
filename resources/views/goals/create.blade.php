@extends('layouts.app')

@section('hide-mobile-nav', '1')
@section('flush-mobile-content', '1')

@section('content')
<div id="fitlife-container" class="goals-create-page" role="application" aria-label="{{ __('goals.fitlife_create_goal') }}">
  <main>
    <button id="mobile-toggle" aria-controls="sidebar" aria-expanded="false">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
        <path d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>

    <header>
      <div class="header-left">
        <h1><span>FitLife</span> {{ __('goals.create') }}</h1>
        <p class="muted">{{ __('goals.set_new_goal_subtitle') }}</p>
      </div>
    </header>

    <a href="{{ route('goals.index') }}" class="back-link">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      {{ __('goals.back_to_goals') }}
    </a>

    <section aria-labelledby="goal-form-heading">
      <h3 id="goal-form-heading">{{ __('goals.create_new_goal') }}</h3>
      <div class="goal-form-card">
        <form action="{{ route('goals.store') }}" method="POST" class="goal-form">
          @csrf

          <div class="form-group">
            <label>{{ __('goals.goal_type') }}</label>
            <div class="goal-type-grid">
              <label class="goal-type-option">
                <input type="radio" name="type" value="steps" required>
                <div class="goal-type-option-inner">
                  <div class="goal-type-option-icon goal-type-option-icon--steps">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                  </div>
                  <span>{{ __('goals.type_steps') }}</span>
                </div>
              </label>
              <label class="goal-type-option">
                <input type="radio" name="type" value="calories">
                <div class="goal-type-option-inner">
                  <div class="goal-type-option-icon goal-type-option-icon--calories">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 12c-2-2.67-6-2.67-6 2 0 4 6 7 6 7s6-3 6-7c0-4.67-4-4.67-6-2z"/></svg>
                  </div>
                  <span>{{ __('goals.type_calories') }}</span>
                </div>
              </label>
              <label class="goal-type-option">
                <input type="radio" name="type" value="sleep">
                <div class="goal-type-option-inner">
                  <div class="goal-type-option-icon goal-type-option-icon--sleep">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                  </div>
                  <span>{{ __('goals.type_sleep') }}</span>
                </div>
              </label>
              <label class="goal-type-option">
                <input type="radio" name="type" value="weight">
                <div class="goal-type-option-inner">
                  <div class="goal-type-option-icon goal-type-option-icon--weight">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6.5 7h11M4 7l2 14h12l2-14M9 7V5a3 3 0 016 0v2"/></svg>
                  </div>
                  <span>{{ __('goals.type_weight') }}</span>
                </div>
              </label>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="target_value">{{ __('goals.target_value') }}</label>
              <input type="number" id="target_value" name="target_value" step="0.01" placeholder="{{ __('goals.enter_target_value') }}" required>
            </div>
            <div class="form-group">
              <label for="end_date">{{ __('goals.end_date') }}</label>
              <input type="date" id="end_date" name="end_date" required>
            </div>
          </div>

          <div class="form-group">
            <label for="description">{{ __('goals.description_optional') }}</label>
            <textarea id="description" name="description" rows="3" placeholder="{{ __('goals.add_description') }}">{{ old('description') }}</textarea>
          </div>

          <button type="submit" class="calculate-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 5v14M5 12h14"/></svg>
            {{ __('goals.create') }}
          </button>
        </form>
      </div>
    </section>
  </main>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/create.js') }}"></script>
@endsection
