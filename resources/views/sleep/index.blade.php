@extends('layouts.app')

@section('content')
<div id="fitlife-container" class="sleep-page" role="application" aria-label="{{ __('sleep.title') }}">
    <main>
        <button id="mobile-toggle" aria-controls="sidebar" aria-expanded="false">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <header>
            <div class="header-left">
                <h1>{{ __('sleep.title') }}</h1>
                <p class="muted">{{ __('sleep.track_patterns') }}</p>
            </div>
        </header>

        <div class="sleep-top-grid">

            <section class="sleep-progress-card" aria-labelledby="kpi-heading">
                <h3 id="kpi-heading" class="sr-only">{{ __('sleep.todays_sleep') }}</h3>
                @php
                    $pct = $recommendedHours > 0 ? min($todayDuration / $recommendedHours, 1) : 0;
                    $circumference = 2 * 3.14159 * 70;
                @endphp
                <div class="sleep-ring-wrap">
                    <svg class="sleep-ring" viewBox="0 0 160 160">
                        <circle class="sleep-ring__bg" cx="80" cy="80" r="70" />
                        <circle class="sleep-ring__fill" cx="80" cy="80" r="70"
                            stroke-dasharray="{{ $circumference }}"
                            stroke-dashoffset="{{ $circumference * (1 - $pct) }}" />
                    </svg>
                    <div class="sleep-ring-label">
                        <span class="sleep-ring-value">{{ $todayDuration }}</span>
                        <span class="sleep-ring-unit">{{ __('sleep.hrs') }}</span>
                    </div>
                </div>
                <div class="sleep-progress-info">
                    <div class="sleep-progress-row">
                        <span class="sleep-progress-label">{{ __('sleep.recommended') }}</span>
                        <span class="sleep-progress-val">{{ $recommendedHours }} {{ __('sleep.hrs') }}</span>
                    </div>
                    <div class="sleep-progress-bar-wrap">
                        <div class="sleep-progress-bar__fill" style="width: {{ min(100, $pct * 100) }}%"></div>
                    </div>
                    <div class="sleep-progress-row">
                        <span class="sleep-progress-label">{{ __('sleep.remaining') }}</span>
                        <span class="sleep-progress-val {{ $todayDuration >= $recommendedHours ? 'sleep-goal-done' : '' }}">
                            @if($todayDuration >= $recommendedHours)
                                ✓ {{ __('sleep.goal_reached') }}
                            @else
                                {{ round($recommendedHours - $todayDuration, 1) }} {{ __('sleep.hrs') }}
                            @endif
                        </span>
                    </div>
                </div>
            </section>

            <section class="sleep-add-card" aria-labelledby="sleep-form-heading">
                <h3 id="sleep-form-heading">{{ __('sleep.log_your_sleep') }}</h3>
                <form action="{{ route('sleep.store') }}" method="POST" class="sleep-form">
                    @csrf
                    <div class="form-group">
                        <label for="date">{{ __('sleep.date') }}</label>
                        <input type="date" id="date" name="date" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="start_time">{{ __('sleep.start_time') }}</label>
                        <input type="time" id="start_time" name="start_time" required>
                    </div>
                    <div class="form-group">
                        <label for="end_time">{{ __('sleep.end_time') }}</label>
                        <input type="time" id="end_time" name="end_time" required>
                    </div>
                    <button type="submit" class="sleep-submit-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                        {{ __('sleep.add_sleep_record') }}
                    </button>
                </form>
            </section>
        </div>

        @if($todaySleeps->count() > 0)
        <section class="sleep-today-section" aria-labelledby="today-heading">
            <h3 id="today-heading">{{ __('sleep.todays_sleep') }}</h3>
            <div class="sleep-today-pills">
                @foreach($todaySleeps as $sleep)
                    <div class="sleep-pill">
                        <svg class="sleep-pill-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                        </svg>
                        <span class="sleep-pill-duration">{{ round($sleep->duration, 1) }} {{ __('sleep.hrs') }}</span>
                        <span class="sleep-pill-time">{{ $sleep->start_time }} — {{ $sleep->end_time }}</span>
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        <section id="history-section" aria-labelledby="history-heading">
            <h3 id="history-heading">{{ __('sleep.history') }}</h3>
            @if($sleeps->isEmpty())
                <div class="sleep-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                    </svg>
                    <p>{{ __('sleep.no_records') }}</p>
                </div>
            @else
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>{{ __('sleep.date') }}</th>
                            <th>{{ __('sleep.start') }}</th>
                            <th>{{ __('sleep.end') }}</th>
                            <th>{{ __('sleep.duration_hrs') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sleeps as $sleep)
                            <tr>
                                <td>{{ $sleep->date }}</td>
                                <td>{{ $sleep->start_time }}</td>
                                <td>{{ $sleep->end_time }}</td>
                                <td>
                                    <span class="sleep-duration-badge {{ $sleep->duration >= 7 ? 'sleep-duration-badge--good' : ($sleep->duration >= 5 ? 'sleep-duration-badge--ok' : 'sleep-duration-badge--low') }}">
                                        {{ round($sleep->duration, 1) }}h
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </section>

        @if($average)
            <section aria-labelledby="average-heading">
                <h3 id="average-heading">{{ __('sleep.summary') }}</h3>
                <div class="sleep-summary-card">
                    <div class="sleep-summary-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
                        </svg>
                    </div>
                    <div class="sleep-summary-body">
                        <span class="sleep-summary-label">{{ __('sleep.average') }}</span>
                        <span class="sleep-summary-value">{{ round($average, 1) }}</span>
                        <span class="sleep-summary-unit">{{ __('sleep.hours_per_night') }}</span>
                    </div>
                </div>
            </section>
        @endif
    </main>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/sleep.js') }}"></script>
@endsection
