@extends('layouts.app')

@section('hide-mobile-nav', '1')
@section('flush-mobile-content', '1')

@section('styles')
<style>
    @media (max-width: 768px) {
    }
</style>
@endsection

@section('content')
<div class="mt-page">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="mt-header">
        <div class="mt-header__icon">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/>
                <line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/>
            </svg>
        </div>
        <div class="mt-header__text">
            <h1>{{ __('food.meal_tracker') }}</h1>
            <p>{{ __('food.log_nutrition') }}</p>
        </div>
    </div>

    <div class="mt-notification" id="notification" role="alert"></div>

    <section class="mt-card mt-summary-section">
        <div class="mt-card__header">
            <div class="mt-card__header-icon mt-card__header-icon--warning">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 21c4-4 6-11 6-17"/><path d="M20 7a4 4 0 11-8 0"/>
                </svg>
            </div>
            <div>
                <h2>{{ __('food.today_summary') }}</h2>
                <p>{{ __('food.today_summary_desc') }}</p>
            </div>
        </div>
        <div class="mt-summary-grid" id="today-summary">
            <div class="mt-summary-item">
                <span class="mt-summary-item__value" id="sum-calories">{{ $todaySummary['calories'] }}</span>
                <span class="mt-summary-item__label">{{ __('food.kcal') }}</span>
            </div>
            <div class="mt-summary-item mt-summary-item--protein">
                <span class="mt-summary-item__value" id="sum-protein">{{ $todaySummary['protein'] }}{{ __('food.g') }}</span>
                <span class="mt-summary-item__label">{{ __('food.protein') }}</span>
            </div>
            <div class="mt-summary-item mt-summary-item--fat">
                <span class="mt-summary-item__value" id="sum-fats">{{ $todaySummary['fats'] }}{{ __('food.g') }}</span>
                <span class="mt-summary-item__label">{{ __('food.fats') }}</span>
            </div>
            <div class="mt-summary-item mt-summary-item--carbs">
                <span class="mt-summary-item__value" id="sum-carbs">{{ $todaySummary['carbs'] }}{{ __('food.g') }}</span>
                <span class="mt-summary-item__label">{{ __('food.carbs') }}</span>
            </div>
        </div>
    </section>

    <section class="mt-card" id="add-food-section">
        <div class="mt-card__header">
            <div class="mt-card__header-icon mt-card__header-icon--warning">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
            </div>
            <div>
                <h2>{{ __('food.log_meals') }}</h2>
                <p>{{ __('food.log_meals_desc') }}</p>
            </div>
        </div>

        <div class="mt-food-form">

            <div class="mt-meal-selector">
                @php $meals = ['Breakfast', 'Lunch', 'Dinner', 'Snack']; @endphp
                @foreach($meals as $meal)
                <button type="button" class="mt-meal-tab {{ $loop->first ? 'active' : '' }}" data-meal="{{ $meal }}">
                    <span class="mt-meal-tab__icon mt-meal-tab__icon--{{ strtolower($meal) }}">
                        @switch(strtolower($meal))
                            @case('breakfast')
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/></svg>
                                @break
                            @case('lunch')
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 21c4-4 6-11 6-17"/><path d="M20 7a4 4 0 11-8 0"/></svg>
                                @break
                            @case('dinner')
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></svg>
                                @break
                            @default
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>
                        @endswitch
                    </span>
                    {{ __('food.meal_' . strtolower($meal)) }}
                </button>
                @endforeach
            </div>

            <div class="mt-items-list" id="food-items">
                <div class="mt-food-item" data-index="0">
                    <div class="mt-food-item__row">
                        <div class="mt-food-item__input-group">
                            <input type="text" class="mt-input mt-input--food" placeholder="{{ __('food.food_placeholder') }}" autocomplete="off">
                            <div class="mt-suggestions" style="display:none;"></div>
                        </div>
                        <input type="number" class="mt-input mt-input--qty" placeholder="{{ __('food.g_ml') }}" min="1" max="10000">
                        <button type="button" class="mt-lookup-btn" title="{{ __('food.search') }}">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        </button>
                        <button type="button" class="mt-remove-btn" style="display:none;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="mt-food-item__info" style="display:none;">
                        <span class="mt-info-tag mt-info-tag--cal"><span class="val">0</span> {{ __('food.kcal') }}</span>
                        <span class="mt-info-tag mt-info-tag--protein">P: <span class="val">0</span>{{ __('food.g') }}</span>
                        <span class="mt-info-tag mt-info-tag--fat">F: <span class="val">0</span>{{ __('food.g') }}</span>
                        <span class="mt-info-tag mt-info-tag--carbs">C: <span class="val">0</span>{{ __('food.g') }}</span>
                    </div>
                </div>
            </div>

            <button type="button" class="mt-add-btn" id="add-item-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                {{ __('food.add_item') }}
            </button>

            <div class="mt-form__actions">
                <button type="button" class="mt-btn mt-btn--primary" id="save-btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    {{ __('food.save_meal') }}
                </button>
            </div>
        </div>
    </section>

    <section class="mt-card" id="history-section">
        <div class="mt-card__header">
            <div class="mt-card__header-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
            <div>
                <h2 id="history-heading">{{ __('food.history') }}</h2>
                <p>{{ __('food.history_desc') }}</p>
            </div>
        </div>
        @include('profile.partials.meal_table', ['mealLogs' => $mealLogs])
    </section>
</div>

<script>
    window.mealTrackerConfig = {
        lookupUrl: '{{ route('foods.lookup') }}',
        calculateUrl: '{{ route('foods.calculate') }}',
        destroyUrl: '{{ url('tracker/foods/log') }}',
        csrfToken: '{{ csrf_token() }}',
        translations: {
            kcal: '{{ __('food.kcal') }}',
            g: '{{ __('food.g') }}',
            total: '{{ __('food.total') }}',
            searching: '{{ __('food.searching') }}',
            no_results: '{{ __('food.no_results') }}',
            per_serving: '{{ __('food.per_serving') }}',
            food_placeholder: '{{ __('food.food_placeholder') }}',
            g_ml: '{{ __('food.g_ml') }}',
            search: '{{ __('food.search') }}',
            saving: '{{ __('food.saving') }}',
            saved: '{{ __('food.meal_added') }}',
            error: '{{ __('food.error') }}',
            empty_items: '{{ __('food.empty_items') }}',
            confirm_delete: '{{ __('food.confirm_delete') }}',
            deleted: '{{ __('food.deleted') }}',
        }
    };
</script>
@endsection

@section('scripts')
    <script src="{{ asset('js/foods.js') }}?v={{ time() }}"></script>
@endsection
