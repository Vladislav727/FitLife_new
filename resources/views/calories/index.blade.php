@extends('layouts.app')

@section('title', __('food.calorie_calc_label'))

@section('hide-mobile-nav', '1')

@php
    $selectedActivity = old('activity_level', $user->activity_level);
    $selectedGoal = old('goal_type', $goal ?? $user->goal_type);

    $activityLabels = [
        'sedentary' => __('food.sedentary'),
        'light' => __('food.light'),
        'moderate' => __('food.moderate'),
        'active' => __('food.active'),
    ];

    $goalLabels = [
        'lose_weight' => __('food.lose_weight'),
        'maintain' => __('food.maintain'),
        'gain_weight' => __('food.gain_weight'),
    ];

    $selectedActivityLabel = $selectedActivity ? ($activityLabels[$selectedActivity] ?? __('food.select_activity')) : __('food.select_activity');
    $selectedGoalLabel = $selectedGoal ? ($goalLabels[$selectedGoal] ?? __('food.select_goal')) : __('food.select_goal');

    $snapshotCards = [
        ['label' => __('food.weight_kg'), 'value' => old('weight', $user->weight) ?? '-'],
        ['label' => __('food.height_cm'), 'value' => old('height', $user->height) ?? '-'],
        ['label' => __('food.age'), 'value' => old('age', $user->age) ?? '-'],
        ['label' => __('food.calories_consumed'), 'value' => round($todayCalories) . ' ' . __('food.kcal')],
    ];

    $statusTone = 'neutral';
    $statusMessage = __('food.calc_daily_needs');

    if (isset($calories)) {
        if ($todayCalories < $calories) {
            $statusTone = 'under';
            $statusMessage = __('food.can_still_eat', ['amount' => round($calories - $todayCalories)]);
        } elseif ($todayCalories > $calories) {
            $statusTone = 'over';
            $statusMessage = __('food.exceeded_calories', ['amount' => round($todayCalories - $calories)]);
        } else {
            $statusTone = 'balanced';
            $statusMessage = __('food.perfect_target');
        }
    }
@endphp

@section('content')

<div id="fitlife-container" class="calorie-page" role="application" aria-label="{{ __('food.calorie_calc_label') }}">
    <main>
        <button id="mobile-toggle" type="button" aria-controls="sidebar" aria-expanded="false">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <header class="calorie-hero">
            <div class="calorie-hero__copy">
                <span class="calorie-hero__eyebrow">FitLife</span>
                <h1>{{ __('food.calorie_calc_label') }}</h1>
                <p>{{ __('food.calc_daily_needs') }}</p>
            </div>

            <div class="calorie-hero__panel">
                <div class="calorie-hero__metric">
                    <span>{{ __('food.calories_consumed') }}</span>
                    <strong>{{ round($todayCalories) }} {{ __('food.kcal') }}</strong>
                </div>

                <div class="calorie-hero__metric calorie-hero__metric--accent">
                    <span>{{ isset($calories) ? __('food.recommended_calories') : __('food.goal') }}</span>
                    <strong>
                        @if(isset($calories))
                            {{ round($calories) }} {{ __('food.kcal') }}
                        @else
                            {{ $selectedGoalLabel }}
                        @endif
                    </strong>
                </div>
            </div>
        </header>

        <section class="calorie-layout" aria-labelledby="calculator-heading">
            <section class="calorie-card calorie-card--form">
                <div class="calorie-card__header">
                    <div>
                        <h2 id="calculator-heading">{{ __('food.calorie_calculator') }}</h2>
                        <p>{{ __('food.calc_daily_needs') }}</p>
                    </div>
                    <span class="calorie-card__badge">{{ __('nav.calories') }}</span>
                </div>

                <form action="{{ route('calories.calculate') }}" method="POST" class="calorie-form">
                    @csrf

                    <div class="calorie-form__grid">
                        <div class="calorie-field">
                            <label for="weight">{{ __('food.weight_kg') }}</label>
                            <input type="number" id="weight" step="0.1" name="weight" value="{{ old('weight', $user->weight) }}" required>
                        </div>

                        <div class="calorie-field">
                            <label for="height">{{ __('food.height_cm') }}</label>
                            <input type="number" id="height" step="0.1" name="height" value="{{ old('height', $user->height) }}" required>
                        </div>

                        <div class="calorie-field">
                            <label for="age">{{ __('food.age') }}</label>
                            <input type="number" id="age" name="age" value="{{ old('age', $user->age) }}" required>
                        </div>

                        <div class="calorie-field">
                            <label for="activity_level">{{ __('food.activity_level') }}</label>
                            <select id="activity_level" name="activity_level" required>
                                <option value="">{{ __('food.select_activity') }}</option>
                                <option value="sedentary" {{ $selectedActivity === 'sedentary' ? 'selected' : '' }}>{{ __('food.sedentary') }}</option>
                                <option value="light" {{ $selectedActivity === 'light' ? 'selected' : '' }}>{{ __('food.light') }}</option>
                                <option value="moderate" {{ $selectedActivity === 'moderate' ? 'selected' : '' }}>{{ __('food.moderate') }}</option>
                                <option value="active" {{ $selectedActivity === 'active' ? 'selected' : '' }}>{{ __('food.active') }}</option>
                            </select>
                        </div>

                        <div class="calorie-field calorie-field--full">
                            <label for="goal_type">{{ __('food.goal') }}</label>
                            <select id="goal_type" name="goal_type" required>
                                <option value="">{{ __('food.select_goal') }}</option>
                                <option value="lose_weight" {{ $selectedGoal === 'lose_weight' ? 'selected' : '' }}>{{ __('food.lose_weight') }}</option>
                                <option value="maintain" {{ $selectedGoal === 'maintain' ? 'selected' : '' }}>{{ __('food.maintain') }}</option>
                                <option value="gain_weight" {{ $selectedGoal === 'gain_weight' ? 'selected' : '' }}>{{ __('food.gain_weight') }}</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="calorie-submit-btn">{{ __('food.calorie_calculator') }}</button>
                </form>
            </section>

            <aside class="calorie-card calorie-card--summary">
                <div class="calorie-card__header calorie-card__header--stacked">
                    <div>
                        <h2>{{ __('food.status') }}</h2>
                        <p>{{ $statusMessage }}</p>
                    </div>
                </div>

                <div class="calorie-snapshot-grid">
                    @foreach($snapshotCards as $card)
                        <article class="calorie-snapshot-card">
                            <span>{{ $card['label'] }}</span>
                            <strong>{{ $card['value'] }}</strong>
                        </article>
                    @endforeach
                </div>
            </aside>
        </section>

        @isset($calories)
            <section class="calorie-results" aria-labelledby="results-heading">
                <div class="calorie-results__header">
                    <div>
                        <h2 id="results-heading">{{ __('food.your_results') }}</h2>
                        <p>{{ __('food.recommended_calories') }}</p>
                    </div>
                </div>

                <div class="calorie-results__grid">
                    <article class="calorie-metric-card calorie-metric-card--primary">
                        <span>{{ __('food.recommended_calories') }}</span>
                        <strong>{{ round($calories) }}</strong>
                        <small>{{ __('food.kcal') }}</small>
                    </article>

                    <article class="calorie-metric-card calorie-metric-card--protein">
                        <span>{{ __('food.protein') }}</span>
                        <strong>{{ $protein }}</strong>
                        <small>{{ __('food.g') }}</small>
                    </article>

                    <article class="calorie-metric-card calorie-metric-card--fats">
                        <span>{{ __('food.fats') }}</span>
                        <strong>{{ $fat }}</strong>
                        <small>{{ __('food.g') }}</small>
                    </article>

                    <article class="calorie-metric-card calorie-metric-card--carbs">
                        <span>{{ __('food.carbs') }}</span>
                        <strong>{{ $carbs }}</strong>
                        <small>{{ __('food.g') }}</small>
                    </article>
                </div>

                <div class="calorie-status-card calorie-status-card--{{ $statusTone }}">
                    <span>{{ __('food.status') }}</span>
                    <p>{{ $statusMessage }}</p>
                </div>
            </section>
        @endisset
    </main>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/calories.js') }}"></script>
@endsection
