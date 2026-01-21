@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/foods.css') }}" rel="stylesheet">
@endsection

@section('content')
<div id="fitlife-container" role="application" aria-label="{{ __('food.app_label') }}">
    <main>
        <button id="mobile-toggle" aria-controls="sidebar" aria-expanded="false">{{ __('food.menu') }}</button>

        <header>
            <div class="header-left">
                <h1>{{ __('food.meal_tracker') }}</h1>
                <p class="muted">{{ __('food.log_nutrition') }}</p>
            </div>
        </header>

        <div id="notification" class="notification" role="alert"></div>

        <section aria-labelledby="meal-form-heading">
            <h3 id="meal-form-heading">{{ __('food.log_meals') }}</h3>
            <form action="{{ route('foods.calculate') }}" method="POST" class="meal-grid-form" id="meal-form">
                @csrf
                @php $meals = ['Breakfast', 'Lunch', 'Dinner', 'Snack']; @endphp
                <div class="meals-grid">
                    @foreach($meals as $meal)
                        <div class="meal-block">
                            <div class="meal-card" data-meal-block="{{ $meal }}">
                                <h4>{{ $meal }}</h4>

                                <div class="meal-items" data-meal="{{ $meal }}">
                                    <div class="meal-item">
                                        <select class="food-select" name="meals[{{ $meal }}][0][food]" aria-label="{{ __('food.select_food_for', ['meal' => $meal]) }}">
                                            <option value="">{{ __('food.select_food') }}</option>
                                            @foreach($foods as $food => $cal)
                                                <option value="{{ $food }}" data-calories="{{ $cal }}">{{ $food }} ({{ $cal }} {{ __('food.kcal') }})</option>
                                            @endforeach
                                        </select>
                                        <input type="number" class="quantity-input" name="meals[{{ $meal }}][0][quantity]" placeholder="{{ __('food.g_ml') }}" style="display:none;" min="0" step="1" aria-label="{{ __('food.quantity_for', ['meal' => $meal]) }}">
                                        <div class="calorie-preview" data-calories="0">0 {{ __('food.kcal') }}</div>
                                        <button type="button" class="remove-food-btn" style="display:none;" aria-label="{{ __('food.remove_item') }}">×</button>
                                    </div>
                                </div>

                                <button type="button" class="add-food-btn" data-meal="{{ $meal }}">{{ __('food.add_item') }}</button>
                                <div class="total-calories" data-total-calories="0">{{ __('food.total') }}: 0 {{ __('food.kcal') }}</div>

                                @error("meals.{$meal}.*")
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="submit" class="calculate-btn" id="calculate-btn">{{ __('food.calculate_calories') }}</button>
            </form>
        </section>

        <section id="history-section" aria-labelledby="history-heading">
            <h3 id="history-heading">{{ __('food.history') }}</h3>

            @php
                $logs = $mealLogs ?? session('mealLogs') ?? collect();
            @endphp

            @include('profile.partials.meal_table', ['mealLogs' => $logs])
        </section>

        @if(session('result'))
            <section aria-labelledby="result-heading">
                <h3 id="result-heading">{{ __('food.meal_summary') }}</h3>
                <div class="result-card">
                    <div class="result-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                            <path d="M4 21c4-4 6-11 6-17"/>
                            <path d="M20 7a4 4 0 11-8 0"/>
                        </svg>
                    </div>
                    <div class="result-body">
                        <h4>{{ __('food.total_calories') }}</h4>
                        <div class="value">{{ session('result')['calories'] }} {{ __('food.kcal') }}</div>
                        <div class="muted">{{ session('result')['comment'] }}</div>
                    </div>
                </div>
            </section>
        @endif
    </main>
</div>

<script>
    window.foodCalories = @json($foods);
    window.foodOptionsHTML = `<option value="">{{ __('food.select_food') }}</option>
        @foreach($foods as $food => $cal)
            <option value="{{ $food }}" data-calories="{{ $cal }}">{{ $food }} ({{ $cal }} {{ __('food.kcal') }})</option>
        @endforeach`;
    window.translations = {
        kcal: '{{ __('food.kcal') }}',
        total: '{{ __('food.total') }}',
        selectFood: '{{ __('food.select_food') }}',
        gMl: '{{ __('food.g_ml') }}',
        removeItem: '{{ __('food.remove_item') }}'
    };
</script>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('js/foods.js') }}"></script>
@endsection
