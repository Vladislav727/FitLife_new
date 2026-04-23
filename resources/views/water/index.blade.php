@extends('layouts.app')

@section('hide-mobile-nav', '1')
@section('flush-mobile-content', '1')

@section('content')
<div id="fitlife-container" class="water-page" role="application" aria-label="{{ __('water.title') }}">
    <main>
        <button id="mobile-toggle" aria-controls="sidebar" aria-expanded="false">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <header>
            <div class="header-left">
                <h1>{{ __('water.title') }}</h1>
                <p class="muted">{{ __('water.subtitle') }}</p>
            </div>
        </header>

        <div class="water-top-grid">

            <section class="water-progress-card" aria-labelledby="kpi-heading">
                <h3 id="kpi-heading" class="sr-only">{{ __('water.todays_hydration') }}</h3>
                <div class="water-ring-wrap">
                    <svg class="water-ring" viewBox="0 0 160 160">
                        <circle class="water-ring__bg" cx="80" cy="80" r="70" />
                        <circle class="water-ring__fill" cx="80" cy="80" r="70"
                            stroke-dasharray="{{ 2 * 3.14159 * 70 }}"
                            stroke-dashoffset="{{ 2 * 3.14159 * 70 * (1 - min($todayTotal / max($dailyGoal, 1), 1)) }}"
                            data-goal="{{ $dailyGoal }}"
                            data-current="{{ $todayTotal }}" />
                    </svg>
                    <div class="water-ring-label">
                        <span class="water-ring-value count-up" data-target="{{ $todayTotal }}">0</span>
                        <span class="water-ring-unit">{{ __('water.ml') }}</span>
                    </div>
                </div>
                <div class="water-progress-info">
                    <div class="water-progress-row">
                        <span class="water-progress-label">{{ __('water.daily_goal') }}</span>
                        <span class="water-progress-val">{{ number_format($dailyGoal) }} {{ __('water.ml') }}</span>
                    </div>
                    <div class="water-progress-bar-wrap">
                        <div class="water-progress-bar__fill" style="width: {{ min(100, ($todayTotal / max($dailyGoal, 1)) * 100) }}%"></div>
                    </div>
                    <div class="water-progress-row">
                        <span class="water-progress-label">{{ __('water.remaining') }}</span>
                        <span class="water-progress-val {{ $todayTotal >= $dailyGoal ? 'goal-done' : '' }}">
                            @if($todayTotal >= $dailyGoal)
                                ✓ {{ __('water.goal_reached') }}
                            @else
                                {{ number_format($dailyGoal - $todayTotal) }} {{ __('water.ml') }}
                            @endif
                        </span>
                    </div>
                </div>
            </section>

            <section class="water-add-card" aria-labelledby="water-form-heading">
                <h3 id="water-form-heading">{{ __('water.add_water_intake') }}</h3>

                <div class="water-presets">
                    @foreach([150, 250, 330, 500] as $preset)
                        <form action="{{ route('water.store') }}" method="POST" class="water-preset-form">
                            @csrf
                            <input type="hidden" name="amount" value="{{ $preset }}">
                            <button type="submit" class="water-preset-btn">
                                <svg class="water-preset-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/>
                                </svg>
                                <span class="water-preset-amount">{{ $preset }}</span>
                                <span class="water-preset-unit">{{ __('water.ml') }}</span>
                            </button>
                        </form>
                    @endforeach
                </div>

                <div class="water-divider">
                    <span>{{ __('water.custom_amount') }}</span>
                </div>

                <form action="{{ route('water.store') }}" method="POST" class="water-custom-form">
                    @csrf
                    <div class="water-custom-input-wrap">
                        <input type="number" name="amount" placeholder="{{ __('water.enter_amount') }}" min="1" max="5000" required>
                        <span class="water-custom-suffix">{{ __('water.ml') }}</span>
                    </div>
                    <button type="submit" class="water-custom-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                        {{ __('water.add_amount') }}
                    </button>
                </form>
            </section>
        </div>

        @if($todayLogs->count() > 0)
        <section class="water-today-section" aria-labelledby="today-heading">
            <h3 id="today-heading">{{ __('water.today_intake') }}</h3>
            <div class="water-today-pills">
                @foreach($todayLogs as $log)
                    <div class="water-pill">
                        <svg class="water-pill-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/>
                        </svg>
                        <span class="water-pill-amount">{{ $log->amount }} {{ __('water.ml') }}</span>
                        <span class="water-pill-time">{{ $log->created_at->format('H:i') }}</span>
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        <section id="history-section" aria-labelledby="history-heading">
            <h3 id="history-heading">{{ __('water.hydration_history') }}</h3>
            @if($historyLogs->isEmpty())
                <div class="water-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/>
                    </svg>
                    <p>{{ __('water.no_logs_start') }}</p>
                </div>
            @else
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>{{ __('water.date_time') }}</th>
                            <th>{{ __('water.amount_ml') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($historyLogs as $log)
                            <tr>
                                <td>{{ $log->created_at->format('M d, Y H:i') }}</td>
                                <td><span class="water-amount-badge">{{ $log->amount }} {{ __('water.ml') }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </section>
    </main>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/water.js') }}"></script>
@endsection
