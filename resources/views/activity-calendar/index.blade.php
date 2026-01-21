@extends('layouts.app', ['title' => __('calendar.page_title')])

@section('content')
    <div id="fitlife-container" class="particle-bg">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <header class="header fade-in">
            <h1 class="header__title fs-2xl fw-bold">{{ __('calendar.activity_calendar') }}</h1>
            <span class="header__username fs-sm text-muted">{{ __('calendar.subtitle') }}</span>
        </header>

        <section aria-labelledby="calendar-heading" class="fade-in">

            <div class="calendar-controls p-3 bg-card br-12 shadow-md">
                <button class="calendar-nav-btn prev-month" aria-label="{{ __('calendar.previous_month') }}">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <h4 class="calendar-month fs-lg fw-medium text-center"></h4>
                <button class="calendar-nav-btn next-month" aria-label="{{ __('calendar.next_month') }}">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <div class="calendar-grid role-grid p-3 bg-card br-12 shadow-md mt-3">
                <!-- Days will be added via JS -->
            </div>

            <div class="calendar-event-form bg-card p-4 mt-4 br-12 shadow-md">
                <h4 class="fs-md fw-medium mb-3">{{ __('calendar.add_event') }}</h4>
                <form id="event-form" action="{{ route('calendar.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="event-date" class="fs-sm fw-medium">{{ __('calendar.event_date') }}</label>
                        <div class="input-group">
                            <i class="fas fa-calendar-alt input-icon"></i>
                            <input type="date" id="event-date" name="date" class="fs-sm" required aria-describedby="event-date-error">
                        </div>
                        @error('date')
                            <div class="error-message" id="event-date-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="event-type" class="fs-sm fw-medium">{{ __('calendar.event_type') }}</label>
                        <div class="input-group">
                            <i class="fas fa-dumbbell input-icon"></i>
                            <select id="event-type" name="type" class="fs-sm" required aria-describedby="event-type-error">
                                <option value="" disabled selected>{{ __('calendar.select_event_type') }}</option>
                                <option value="workout">{{ __('calendar.type_workout') }}</option>
                                <option value="rest">{{ __('calendar.type_rest') }}</option>
                                <option value="goal">{{ __('calendar.type_goal') }}</option>
                                <option value="running">{{ __('calendar.type_running') }}</option>
                                <option value="yoga">{{ __('calendar.type_yoga') }}</option>
                                <option value="cycling">{{ __('calendar.type_cycling') }}</option>
                                <option value="swimming">{{ __('calendar.type_swimming') }}</option>
                                <option value="weightlifting">{{ __('calendar.type_weightlifting') }}</option>
                                <option value="hiking">{{ __('calendar.type_hiking') }}</option>
                                <option value="boxing">{{ __('calendar.type_boxing') }}</option>
                                <option value="dance">{{ __('calendar.type_dance') }}</option>
                                <option value="crossfit">{{ __('calendar.type_crossfit') }}</option>
                                <option value="walking">{{ __('calendar.type_walking') }}</option>
                                <option value="meditation">{{ __('calendar.type_meditation') }}</option>
                            </select>
                        </div>
                        @error('type')
                            <div class="error-message" id="event-type-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="event-description" class="fs-sm fw-medium">{{ __('calendar.event_description') }}</label>
                        <div class="input-group">
                            <i class="fas fa-comment input-icon"></i>
                            <input type="text" id="event-description" name="description" class="fs-sm" placeholder="{{ __('calendar.description_placeholder') }}" aria-describedby="event-description-error">
                        </div>
                        @error('description')
                            <div class="error-message" id="event-description-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="save-btn fs-sm fw-medium">
                        <i class="fas fa-save"></i> {{ __('calendar.save_event') }}
                    </button>
                </form>
                <div id="form-message" class="mt-3 fs-sm"></div>
            </div>

            <div class="calendar-events mt-4">
                <h4 class="fs-md fw-medium mb-3">{{ __('calendar.your_events') }}</h4>
                <div class="event-filter">
                    <label for="event-filter" class="fs-sm fw-medium">{{ __('calendar.filter_by_type') }}</label>
                    <select id="event-filter" class="fs-sm">
                        <option value="all">{{ __('calendar.all_events') }}</option>
                        <option value="workout">{{ __('calendar.type_workout') }}</option>
                        <option value="rest">{{ __('calendar.type_rest') }}</option>
                        <option value="goal">{{ __('calendar.type_goal') }}</option>
                        <option value="running">{{ __('calendar.type_running') }}</option>
                        <option value="yoga">{{ __('calendar.type_yoga') }}</option>
                        <option value="cycling">{{ __('calendar.type_cycling') }}</option>
                        <option value="swimming">{{ __('calendar.type_swimming') }}</option>
                        <option value="weightlifting">{{ __('calendar.type_weightlifting') }}</option>
                        <option value="hiking">{{ __('calendar.type_hiking') }}</option>
                        <option value="boxing">{{ __('calendar.type_boxing') }}</option>
                        <option value="dance">{{ __('calendar.type_dance') }}</option>
                        <option value="crossfit">{{ __('calendar.type_crossfit') }}</option>
                        <option value="walking">{{ __('calendar.type_walking') }}</option>
                        <option value="meditation">{{ __('calendar.type_meditation') }}</option>
                    </select>
                </div>
                <ul class="event-list"></ul>
            </div>
        </section>
    </div>

    <!-- SVG Icons -->
    <svg style="display: none;">
        <symbol id="chevron-left" viewBox="0 0 24 24">
            <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
        </symbol>
        <symbol id="chevron-right" viewBox="0 0 24 24">
            <path d="M8.59 16.59L10 18l6-6-6-6-1.41 1.41L13.17 12z"/>
        </symbol>
        <symbol id="save" viewBox="0 0 24 24">
            <path d="M17 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/>
        </symbol>
        <symbol id="check" viewBox="0 0 24 24">
            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
        </symbol>
        <symbol id="edit" viewBox="0 0 24 24">
            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
        </symbol>
        <symbol id="trash" viewBox="0 0 24 24">
            <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
        </symbol>
    </svg>
@endsection

@section('styles')
    <link href="{{ asset('css/activity-calendar.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
@endsection

@section('scripts')
    <script src="{{ asset('js/activity-calendar.js') }}"></script>
@endsection