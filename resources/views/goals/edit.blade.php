@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/create.css') }}" rel="stylesheet">
@endsection

@section('content')
<div id="fitlife-container" role="application" aria-label="{{ __('goals.fitlife_edit_goal') }}">
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
        <h1><span>FitLife</span> {{ __('goals.edit') }}</h1>
        <p class="muted">{{ __('goals.update_goal_subtitle') }}</p>
      </div>
    </header>

    <!-- Goal Form Section -->
    <section aria-labelledby="goal-form-heading">
      <h3 id="goal-form-heading">{{ __('goals.edit') }}</h3>
      <div class="goal-card">
        <form action="{{ route('goals.update', $goal) }}" method="POST" class="goal-form">
          @csrf
          @method('PATCH')
          <div class="form-group">
            <label for="type">{{ __('goals.goal_type') }}</label>
            <select id="type" name="type" required>
              <option value="">{{ __('goals.select_goal') }}</option>
              <option value="steps" {{ $goal->type === 'steps' ? 'selected' : '' }}>{{ __('goals.type_steps') }}</option>
              <option value="calories" {{ $goal->type === 'calories' ? 'selected' : '' }}>{{ __('goals.type_calories') }}</option>
              <option value="sleep" {{ $goal->type === 'sleep' ? 'selected' : '' }}>{{ __('goals.type_sleep') }}</option>
              <option value="weight" {{ $goal->type === 'weight' ? 'selected' : '' }}>{{ __('goals.type_weight') }}</option>
            </select>
          </div>

          <div class="form-group">
            <label for="target_value">{{ __('goals.target_value') }}</label>
            <input type="number" id="target_value" name="target_value" step="0.01" value="{{ old('target_value', $goal->target_value) }}" placeholder="{{ __('goals.enter_target_value') }}" required>
          </div>

          <div class="form-group">
            <label for="end_date">{{ __('goals.end_date') }}</label>
            <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $goal->end_date?->format('Y-m-d')) }}" required>
          </div>

          <div class="form-group">
            <label for="description">{{ __('goals.description_optional') }}</label>
            <textarea id="description" name="description" rows="3" placeholder="{{ __('goals.add_description') }}">{{ old('description', $goal->description) }}</textarea>
          </div>

          <div class="form-actions" style="display: flex; gap: 10px;">
            <button type="submit" class="calculate-btn">{{ __('goals.update_goal') }}</button>
            <a href="{{ route('goals.index') }}" class="calculate-btn" style="background: #666; text-decoration: none; text-align: center;">{{ __('goals.cancel') }}</a>
          </div>
        </form>

        <!-- Delete Form -->
        <form action="{{ route('goals.destroy', $goal) }}" method="POST" style="margin-top: 20px;" onsubmit="return confirm('{{ __('goals.delete_confirm') }}');">
          @csrf
          @method('DELETE')
          <button type="submit" class="calculate-btn" style="background: #dc3545; width: 100%;">{{ __('goals.delete') }}</button>
        </form>
      </div>
    </section>
  </main>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/create.js') }}"></script>
@endsection
