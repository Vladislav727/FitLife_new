@extends('layouts.app')

@section('styles')
    <!-- Page-specific stylesheet for calorie calculator -->
    <link href="{{ asset('css/calories.css') }}" rel="stylesheet">
@endsection

@section('content')
<div id="fitlife-container" role="application" aria-label="{{ __('food.calorie_calc_label') }}">
    <main>
        <!-- Mobile menu toggle button -->
        <button id="mobile-toggle" aria-controls="sidebar" aria-expanded="false">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <header>
            <!-- Page header -->
            <div class="header-left">
                <h1><span>FitLife</span> {{ __('food.calorie_macro_calc') }}</h1>
                <p class="muted">{{ __('food.calc_daily_needs') }}</p>
            </div>
        </header>

        <section aria-labelledby="calculator-heading">
            <!-- Calorie calculator form container -->
            <div class="biography-card">
                <h3 id="calculator-heading">{{ __('food.calorie_calculator') }}</h3>
                <form action="{{ route('calories.calculate') }}" method="POST" class="form-logging">
                    @csrf

                    <!-- Weight input -->
                    <div class="form-group">
                        <label for="weight">{{ __('food.weight_kg') }}</label>
                        <input type="number" id="weight" name="weight" placeholder="{{ __('food.weight_kg') }}"
                            value="{{ old('weight', $user->weight) }}" required>
                    </div>

                    <!-- Height input -->
                    <div class="form-group">
                        <label for="height">{{ __('food.height_cm') }}</label>
                        <input type="number" id="height" name="height" placeholder="{{ __('food.height_cm') }}"
                            value="{{ old('height', $user->height) }}" required>
                    </div>

                    <!-- Age input -->
                    <div class="form-group">
                        <label for="age">{{ __('food.age') }}</label>
                        <input type="number" id="age" name="age" placeholder="{{ __('food.age') }}" value="{{ old('age', $user->age) }}" required>
                    </div>

                    <!-- Activity level select -->
                    <div class="form-group">
                        <label for="activity_level">{{ __('food.activity_level') }}</label>
                        <select id="activity_level" name="activity_level" required>
                            <option value="">{{ __('food.select_activity') }}</option>
                            <option value="sedentary" {{ old('activity_level', $user->activity_level) == 'sedentary' ? 'selected' : '' }}>{{ __('food.sedentary') }}</option>
                            <option value="light" {{ old('activity_level', $user->activity_level) == 'light' ? 'selected' : '' }}>{{ __('food.light') }}</option>
                            <option value="moderate" {{ old('activity_level', $user->activity_level) == 'moderate' ? 'selected' : '' }}>{{ __('food.moderate') }}</option>
                            <option value="active" {{ old('activity_level', $user->activity_level) == 'active' ? 'selected' : '' }}>{{ __('food.active') }}</option>
                        </select>
                    </div>

                    <!-- Goal select -->
                    <div class="form-group">
                        <label for="goal_type">{{ __('food.goal') }}</label>
                        <select id="goal_type" name="goal_type" required>
                            <option value="">{{ __('food.select_goal') }}</option>
                            <option value="lose_weight" {{ old('goal_type', $user->goal_type) == 'lose_weight' ? 'selected' : '' }}>{{ __('food.lose_weight') }}</option>
                            <option value="maintain" {{ old('goal_type', $user->goal_type) == 'maintain' ? 'selected' : '' }}>{{ __('food.maintain') }}</option>
                            <option value="gain_weight" {{ old('goal_type', $user->goal_type) == 'gain_weight' ? 'selected' : '' }}>{{ __('food.gain_weight') }}</option>
                        </select>
                    </div>

                    <!-- Calculate button -->
                    <button type="submit" class="calculate-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#ffffffff">
                            <path d="M320-240h60v-80h80v-60h-80v-80h-60v80h-80v60h80v80Zm200-30h200v-60H520v60Zm0-100h200v-60H520v60Zm44-152 56-56 56 56 42-42-56-58 56-56-42-42-56 56-56-56-42 42 56 56-56 58 42 42Zm-314-70h200v-60H250v60Zm-50 472q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0-560v560-560Z"/>
                        </svg>
                        {{ __('food.calculate') }}
                    </button>
                </form>
            </div>
        </section>

        @isset($calories)
            <section aria-labelledby="results-heading">
                <!-- Results display -->
                <h3 id="results-heading">{{ __('food.your_results') }}</h3>
                <div class="kpi-container">
                    <div class="kpi-card">
                        <h4>{{ __('food.recommended_calories') }}</h4>
                        <p>{{ round($calories) }} {{ __('food.kcal') }}</p>
                    </div>
                    <div class="kpi-card">
                        <h4>{{ __('food.protein') }}</h4>
                        <p>{{ $protein }}g</p>
                    </div>
                    <div class="kpi-card">
                        <h4>{{ __('food.fats') }}</h4>
                        <p>{{ $fat }}g</p>
                    </div>
                    <div class="kpi-card">
                        <h4>{{ __('food.carbs') }}</h4>
                        <p>{{ $carbs }}g</p>
                    </div>
                    <div class="kpi-card">
                        <h4>{{ __('food.calories_consumed') }}</h4>
                        <p>{{ $todayCalories }} {{ __('food.kcal') }}</p>
                    </div>
                    <div class="kpi-card">
                        <h4>{{ __('food.status') }}</h4>
                        <p class="status-text">
                            @if($todayCalories < $calories)
                                {{ __('food.can_still_eat', ['amount' => round($calories - $todayCalories)]) }}
                            @elseif($todayCalories > $calories)
                                {{ __('food.exceeded_calories', ['amount' => round($todayCalories - $calories)]) }}
                            @else
                                {{ __('food.perfect_target') }}
                            @endif
                        </p>
                    </div>
                </div>
            </section>
        @endisset
    </main>
</div>
@endsection

@section('scripts')
    <!-- Page-specific JavaScript for calorie calculator -->
    <script src="{{ asset('js/calories.js') }}"></script>
@endsection
