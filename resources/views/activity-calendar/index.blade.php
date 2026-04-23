@extends('layouts.app', ['title' => __('calendar.page_title')])

@section('hide-mobile-nav', '1')
@section('flush-mobile-content', '1')

@section('content')
<div
    class="calendar-page"
    data-calendar-translations='{!! json_encode([
        "noEventsToday" => __("calendar.no_events_today"),
        "addFirstEvent" => __("calendar.add_first_event"),
        "noUpcoming" => __("calendar.no_upcoming"),
        "months" => [
            __("calendar.january"),
            __("calendar.february"),
            __("calendar.march"),
            __("calendar.april"),
            __("calendar.may"),
            __("calendar.june"),
            __("calendar.july"),
            __("calendar.august"),
            __("calendar.september"),
            __("calendar.october"),
            __("calendar.november"),
            __("calendar.december"),
        ],
        "today" => __("calendar.today"),
        "noDescription" => __("calendar.event_description"),
    ]) !!}'
>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <header class="calendar-header">
        <div class="header-content">
            <div class="header-left">
                <h1 class="page-title">
                    <svg class="title-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    {{ __('calendar.activity_calendar') }}
                </h1>
                <p class="page-subtitle">{{ __('calendar.subtitle') }}</p>
            </div>
            <div class="header-right">
                <button class="today-btn" id="today-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12,6 12,12 16,14"></polyline>
                    </svg>
                    {{ __('calendar.today') }}
                </button>
                <button class="add-event-btn" id="open-modal-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    {{ __('calendar.add_event') }}
                </button>
            </div>
        </div>
    </header>

    <div class="calendar-layout">

        <aside class="calendar-sidebar">

            <div class="mini-calendar-card">
                <div class="mini-calendar-header">
                    <button class="mini-nav-btn prev-mini" aria-label="{{ __('calendar.previous_month') }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </button>
                    <span class="mini-month-year"></span>
                    <button class="mini-nav-btn next-mini" aria-label="{{ __('calendar.next_month') }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </button>
                </div>
                <div class="mini-calendar-grid">

                </div>
            </div>

            <div class="stats-card">
                <h3 class="card-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="20" x2="18" y2="10"></line>
                        <line x1="12" y1="20" x2="12" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="14"></line>
                    </svg>
                    {{ __('calendar.this_month_stats') }}
                </h3>
                <div class="stats-grid">
                    <div class="stat-item">
                        <span class="stat-value" id="total-events">0</span>
                        <span class="stat-label">{{ __('calendar.total_events') }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value" id="completed-events">0</span>
                        <span class="stat-label">{{ __('calendar.completed') }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value" id="workout-count">0</span>
                        <span class="stat-label">{{ __('calendar.workouts') }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value" id="streak-count">0</span>
                        <span class="stat-label">{{ __('calendar.day_streak') }}</span>
                    </div>
                </div>
            </div>

            <div class="legend-card">
                <h3 class="card-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                        <polyline points="2 17 12 22 22 17"></polyline>
                        <polyline points="2 12 12 17 22 12"></polyline>
                    </svg>
                    {{ __('calendar.event_types') }}
                </h3>
                <div class="legend-items">
                    <div class="legend-item" data-type="workout">
                        <span class="legend-color workout"></span>
                        <span>{{ __('calendar.type_workout') }}</span>
                    </div>
                    <div class="legend-item" data-type="running">
                        <span class="legend-color running"></span>
                        <span>{{ __('calendar.type_running') }}</span>
                    </div>
                    <div class="legend-item" data-type="yoga">
                        <span class="legend-color yoga"></span>
                        <span>{{ __('calendar.type_yoga') }}</span>
                    </div>
                    <div class="legend-item" data-type="cycling">
                        <span class="legend-color cycling"></span>
                        <span>{{ __('calendar.type_cycling') }}</span>
                    </div>
                    <div class="legend-item" data-type="swimming">
                        <span class="legend-color swimming"></span>
                        <span>{{ __('calendar.type_swimming') }}</span>
                    </div>
                    <div class="legend-item" data-type="weightlifting">
                        <span class="legend-color weightlifting"></span>
                        <span>{{ __('calendar.type_weightlifting') }}</span>
                    </div>
                    <div class="legend-item" data-type="rest">
                        <span class="legend-color rest"></span>
                        <span>{{ __('calendar.type_rest') }}</span>
                    </div>
                    <div class="legend-item" data-type="goal">
                        <span class="legend-color goal"></span>
                        <span>{{ __('calendar.type_goal') }}</span>
                    </div>
                </div>
            </div>

            <div class="quick-add-card">
                <h3 class="card-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                    </svg>
                    {{ __('calendar.quick_add') }}
                </h3>
                <div class="quick-actions">
                    <button class="quick-btn workout" data-type="workout">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 5v14"></path>
                            <path d="M18 5v14"></path>
                            <path d="M6 9h12"></path>
                            <path d="M6 15h12"></path>
                        </svg>
                        {{ __('calendar.type_workout') }}
                    </button>
                    <button class="quick-btn running" data-type="running">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="13" cy="4" r="2"></circle>
                            <path d="M7 21l3-4"></path>
                            <path d="M16 21l-2-4-3-3 1-6"></path>
                            <path d="M6 12l2-1 3 3"></path>
                        </svg>
                        {{ __('calendar.type_running') }}
                    </button>
                    <button class="quick-btn yoga" data-type="yoga">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="4" r="2"></circle>
                            <path d="M4 20l4-4"></path>
                            <path d="M16 20l-4-4"></path>
                            <path d="M8 16l4 4 4-4"></path>
                            <path d="M12 10v6"></path>
                            <path d="M7 10h10"></path>
                        </svg>
                        {{ __('calendar.type_yoga') }}
                    </button>
                    <button class="quick-btn rest" data-type="rest">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                        </svg>
                        {{ __('calendar.type_rest') }}
                    </button>
                </div>
            </div>
        </aside>

        <main class="calendar-main">

            <div class="calendar-nav">
                <button class="nav-btn prev-month" aria-label="{{ __('calendar.previous_month') }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </button>
                <h2 class="current-month"></h2>
                <button class="nav-btn next-month" aria-label="{{ __('calendar.next_month') }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
            </div>

            <div class="main-calendar">
                <div class="calendar-weekdays">
                    <div class="weekday">{{ __('calendar.mon') }}</div>
                    <div class="weekday">{{ __('calendar.tue') }}</div>
                    <div class="weekday">{{ __('calendar.wed') }}</div>
                    <div class="weekday">{{ __('calendar.thu') }}</div>
                    <div class="weekday">{{ __('calendar.fri') }}</div>
                    <div class="weekday weekend">{{ __('calendar.sat') }}</div>
                    <div class="weekday weekend">{{ __('calendar.sun') }}</div>
                </div>
                <div class="calendar-days" id="calendar-days">

                </div>
            </div>
        </main>

        <aside class="events-panel">
            <div class="panel-header">
                <h3 class="panel-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 8v4l3 3"></path>
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                    <span id="selected-date-title">{{ __('calendar.today') }}</span>
                </h3>
                <div class="event-filter-dropdown">
                    <select id="event-filter" class="filter-select">
                        <option value="all">{{ __('calendar.all_events') }}</option>
                        <option value="workout">{{ __('calendar.type_workout') }}</option>
                        <option value="running">{{ __('calendar.type_running') }}</option>
                        <option value="yoga">{{ __('calendar.type_yoga') }}</option>
                        <option value="cycling">{{ __('calendar.type_cycling') }}</option>
                        <option value="swimming">{{ __('calendar.type_swimming') }}</option>
                        <option value="weightlifting">{{ __('calendar.type_weightlifting') }}</option>
                        <option value="rest">{{ __('calendar.type_rest') }}</option>
                        <option value="goal">{{ __('calendar.type_goal') }}</option>
                    </select>
                </div>
            </div>

            <div class="events-timeline" id="events-timeline">
                <div class="empty-state">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                        <line x1="8" y1="14" x2="8" y2="14"></line>
                        <line x1="12" y1="14" x2="12" y2="14"></line>
                        <line x1="16" y1="14" x2="16" y2="14"></line>
                    </svg>
                    <p>{{ __('calendar.no_events') }}</p>
                    <button class="add-first-event-btn" id="add-first-event">
                        {{ __('calendar.add_first_event') }}
                    </button>
                </div>
            </div>

            <div class="upcoming-section">
                <h4 class="section-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                        <polyline points="17 6 23 6 23 12"></polyline>
                    </svg>
                    {{ __('calendar.upcoming_events') }}
                </h4>
                <div class="upcoming-list" id="upcoming-list">

                </div>
            </div>
        </aside>
    </div>

    <div class="modal-overlay" id="event-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modal-title">{{ __('calendar.add_event') }}</h3>
                <button class="modal-close" id="close-modal">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <form id="event-form" class="event-form">
                @csrf
                <input type="hidden" id="event-id" name="event_id" value="">

                <div class="form-row">
                    <div class="form-group">
                        <label for="event-date">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            {{ __('calendar.event_date') }}
                        </label>
                        <input type="date" id="event-date" name="date" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="event-type">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                            <polyline points="2 17 12 22 22 17"></polyline>
                            <polyline points="2 12 12 17 22 12"></polyline>
                        </svg>
                        {{ __('calendar.event_type') }}
                    </label>
                    <div class="type-grid">
                        <label class="type-option workout">
                            <input type="radio" name="type" value="workout">
                            <span class="type-icon">
                                <svg viewBox="0 -960 960 960" fill="currentColor">
                                    <path d="M57-80 1-136l146-146-44-118q-7-18-3-41.5t23-42.5l132-132q12-12 26-18t31-6q17 0 31 6t26 18l80 78q27 27 66 42.5t84 15.5v80q-60 0-112-19t-90-57l-28-28-94 94 84 86v244h-80v-210l-52-48v88L57-80Zm542 0v-280l84-80-24-140q-15 18-33 32t-39 26q-33-2-62.5-14T475-568q45-8 79.5-30.5T611-656l40-64q17-27 47-36.5t59 2.5l202 86v188h-80v-136l-72-28L919-80h-84l-72-300-84 80v220h-80ZM459-620q-33 0-56.5-23.5T379-700q0-33 23.5-56.5T459-780q33 0 56.5 23.5T539-700q0 33-23.5 56.5T459-620Zm200-160q-33 0-56.5-23.5T579-860q0-33 23.5-56.5T659-940q33 0 56.5 23.5T739-860q0 33-23.5 56.5T659-780Z"/>
                                </svg>
                            </span>
                            <span class="type-name">{{ __('calendar.type_workout') }}</span>
                        </label>
                        <label class="type-option running">
                            <input type="radio" name="type" value="running">
                            <span class="type-icon">
                                <svg viewBox="0 -960 960 960" fill="currentColor">
                                    <path d="M520-40v-240l-84-80-40 176-276-56 16-80 192 40 64-324-72 28v136h-80v-188l158-68q35-15 51.5-19.5T480-720q21 0 39 11t29 29l40 64q26 42 70.5 69T760-520v80q-66 0-123.5-27.5T540-540l-24 120 84 80v300h-80Zm20-700q-33 0-56.5-23.5T460-820q0-33 23.5-56.5T540-900q33 0 56.5 23.5T620-820q0 33-23.5 56.5T540-740Z"/>
                                </svg>
                            </span>
                            <span class="type-name">{{ __('calendar.type_running') }}</span>
                        </label>
                        <label class="type-option yoga">
                            <input type="radio" name="type" value="yoga">
                            <span class="type-icon">
                                <svg viewBox="0 -960 960 960" fill="currentColor">
                                    <path d="M272-160q-30 0-51-21t-21-51q0-21 12-39.5t32-26.5l156-62v-90q-54 63-125.5 96.5T120-320v-80q68 0 123.5-28T344-508l54-64q12-14 28-21t34-7h40q18 0 34 7t28 21l54 64q45 52 100.5 80T840-400v80q-83 0-154.5-33.5T560-450v90l156 62q20 8 32 26.5t12 39.5q0 30-21 51t-51 21H400v-20q0-26 17-43t43-17h120q9 0 14.5-5.5T600-260q0-9-5.5-14.5T580-280H460q-42 0-71 29t-29 71v20h-88Zm208-480q-33 0-56.5-23.5T400-720q0-33 23.5-56.5T480-800q33 0 56.5 23.5T560-720q0 33-23.5 56.5T480-640Z"/>
                                </svg>
                            </span>
                            <span class="type-name">{{ __('calendar.type_yoga') }}</span>
                        </label>
                        <label class="type-option cycling">
                            <input type="radio" name="type" value="cycling">
                            <span class="type-icon">
                                <svg viewBox="0 -960 960 960" fill="currentColor">
                                    <path d="M200-280q-85 0-142.5-57.5T0-480q0-85 58.5-142.5T200-680q77 0 129.5 46T396-520h26l-72-200h-70v-80h200v80h-44l14 40h192l-58-160H480v-80h104q26 0 46.5 14t29.5 38l68 186h32q83 0 141.5 58.5T960-482q0 84-58 143t-142 59q-72 0-126.5-45T564-440H396q-14 69-68 114.5T200-280Zm0-80q41 0 70.5-22.5T312-440H200v-80h112q-12-36-41.5-58T200-600q-51 0-85.5 34.5T80-480q0 50 34.5 85t85.5 35Zm308-160h56q5-23 13.5-43t22.5-37H478l30 80Zm252 160q51 0 85.5-35t34.5-85q0-51-34.5-85.5T760-600h-4l40 106-76 28-38-106q-20 17-31 40t-11 52q0 50 34.5 85t85.5 35ZM520-40 280-160h160v-80l240 120H520v80ZM196-480Zm564 0Z"/>
                                </svg>
                            </span>
                            <span class="type-name">{{ __('calendar.type_cycling') }}</span>
                        </label>
                        <label class="type-option swimming">
                            <input type="radio" name="type" value="swimming">
                            <span class="type-icon">
                                <svg viewBox="0 -960 960 960" fill="currentColor">
                                    <path d="M80-120v-80q38 0 57-20t75-20q56 0 77 20t57 20q36 0 57-20t77-20q56 0 77 20t57 20q36 0 57-20t77-20q56 0 75 20t57 20v80q-59 0-77.5-20T748-160q-36 0-57 20t-77 20q-56 0-77-20t-57-20q-36 0-57 20t-77 20q-56 0-77-20t-57-20q-36 0-54.5 20T80-120Zm0-180v-80q38 0 57-20t75-20q56 0 77.5 20t56.5 20q36 0 57-20t77-20q56 0 77 20t57 20q36 0 57-20t77-20q56 0 75 20t57 20v80q-59 0-77.5-20T748-340q-36 0-55.5 20T614-300q-57 0-77.5-20T480-340q-38 0-56.5 20T346-300q-59 0-78.5-20T212-340q-36 0-54.5 20T80-300Zm196-204 133-133-40-40q-33-33-70-48t-91-15v-100q75 0 124 16.5t96 63.5l256 256q-17 11-33 17.5t-37 6.5q-36 0-57-20t-77-20q-56 0-77 20t-57 20q-21 0-37-6.5T276-504Zm392-336q42 0 71 29.5t29 70.5q0 42-29 71t-71 29q-42 0-71-29t-29-71q0-41 29-70.5t71-29.5Z"/>
                                </svg>
                            </span>
                            <span class="type-name">{{ __('calendar.type_swimming') }}</span>
                        </label>
                        <label class="type-option weightlifting">
                            <input type="radio" name="type" value="weightlifting">
                            <span class="type-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 12h1"></path>
                                    <path d="M5 8h2v8h-2z"></path>
                                    <path d="M3 10v4"></path>
                                    <path d="M7 12h10"></path>
                                    <path d="M17 8h2v8h-2z"></path>
                                    <path d="M21 10v4"></path>
                                    <path d="M21 12h1"></path>
                                </svg>
                            </span>
                            <span class="type-name">{{ __('calendar.type_weightlifting') }}</span>
                        </label>
                        <label class="type-option hiking">
                            <input type="radio" name="type" value="hiking">
                            <span class="type-icon">
                                <svg viewBox="0 -960 960 960" fill="currentColor">
                                    <path d="m280-40 123-622q6-29 27-43.5t44-14.5q23 0 42.5 10t31.5 30l40 64q18 29 46.5 52.5T700-529v-71h60v560h-60v-406q-48-11-89-35t-71-59l-24 120 84 80v300h-80v-240l-84-80-72 320h-84Zm17-395-85-16q-16-3-25-16.5t-6-30.5l30-157q6-32 34-50.5t60-12.5l46 9-54 274Zm243-305q-33 0-56.5-23.5T460-820q0-33 23.5-56.5T540-900q33 0 56.5 23.5T620-820q0 33-23.5 56.5T540-740Z"/>
                                </svg>
                            </span>
                            <span class="type-name">{{ __('calendar.type_hiking') }}</span>
                        </label>
                        <label class="type-option boxing">
                            <input type="radio" name="type" value="boxing">
                            <span class="type-icon">
                                <svg viewBox="0 -960 960 960" fill="currentColor">
                                    <path d="M320-120q-17 0-28.5-11.5T280-160v-120h400v120q0 17-11.5 28.5T640-120H320Zm440-520v120q0 3-2 8l-30 152q-3 18-16.5 29T680-320H280q-18 0-31.5-11T232-360l-30-152q-2-5-2-8v-240q0-33 23.5-56.5T280-840h320q33 0 56.5 23.5T680-760v120q0-17 11.5-28.5T720-680q17 0 28.5 11.5T760-640ZM306-400h348l26-136v-24h-80v-200H280v224l26 136Zm14-160h240v-120H320v120Zm160-20Z"/>
                                </svg>
                            </span>
                            <span class="type-name">{{ __('calendar.type_boxing') }}</span>
                        </label>
                        <label class="type-option dance">
                            <input type="radio" name="type" value="dance">
                            <span class="type-icon">
                                <svg viewBox="0 -960 960 960" fill="currentColor">
                                    <path d="M400-120q-66 0-113-47t-47-113q0-66 47-113t113-47q23 0 42.5 5.5T480-418v-422h240v160H560v400q0 66-47 113t-113 47Z"/>
                                </svg>
                            </span>
                            <span class="type-name">{{ __('calendar.type_dance') }}</span>
                        </label>
                        <label class="type-option crossfit">
                            <input type="radio" name="type" value="crossfit">
                            <span class="type-icon">
                                <svg viewBox="0 -960 960 960" fill="currentColor">
                                    <path d="m536-84-56-56 142-142-340-340-142 142-56-56 56-58-56-56 84-84-56-58 56-56 58 56 84-84 56 56 58-56 56 56-142 142 340 340 142-142 56 56-56 58 56 56-84 84 56 58-56 56-58-56-84 84-56-56-58 56Z"/>
                                </svg>
                            </span>
                            <span class="type-name">{{ __('calendar.type_crossfit') }}</span>
                        </label>
                        <label class="type-option meditation">
                            <input type="radio" name="type" value="meditation">
                            <span class="type-icon">
                                <svg viewBox="0 -960 960 960" fill="currentColor">
                                    <path d="M480-120q-18 0-34.5-6.5T416-146L163-400h113l197 197q2 2 3.5 2.5t3.5.5q2 0 3.5-.5t3.5-2.5l267-268q23-23 35-54.5t12-64.5q-2-69-46-118.5T645-758q-31 0-59.5 12T536-711l-27 29q-5 6-13 9.5t-16 3.5q-8 0-16-3.5t-14-9.5l-27-29q-21-23-49-36t-60-13q-54 0-93.5 34.5T167-640H85q17-85 79.5-142.5T314-840q48 0 90.5 19t75.5 53q32-34 74.5-53t90.5-19q100 0 167.5 74T880-590q0 49-17 94t-51 80L543-146q-13 13-29 19.5t-34 6.5Zm-5-360H80v-80h555q17 0 28.5-11.5T675-600q0-17-11.5-28.5T635-640q-14 0-25 7.5T596-611l-77-21q11-39 43-63.5t73-24.5q50 0 85 35t35 85q0 50-35 85t-85 35h-47q3 10 5 19.5t2 20.5q0 50-35 85t-85 35q-41 0-73-24.5T359-408l77-21q3 14 14 21.5t25 7.5q17 0 28.5-11.5T515-440q0-17-11.5-28.5T475-480Zm9 0Z"/>
                                </svg>
                            </span>
                            <span class="type-name">{{ __('calendar.type_meditation') }}</span>
                        </label>
                        <label class="type-option rest">
                            <input type="radio" name="type" value="rest">
                            <span class="type-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z"></path>
                                    <path d="M17 4a2 2 0 0 0 2 2a2 2 0 0 0 -2 2a2 2 0 0 0 -2 -2a2 2 0 0 0 2 -2"></path>
                                    <path d="M19 11h2m-1 -1v2"></path>
                                </svg>
                            </span>
                            <span class="type-name">{{ __('calendar.type_rest') }}</span>
                        </label>
                        <label class="type-option goal">
                            <input type="radio" name="type" value="goal">
                            <span class="type-icon">
                                <svg viewBox="0 -960 960 960" fill="currentColor">
                                    <path d="M200-120v-680h360l16 80h224v400H520l-16-80H280v280h-80Zm300-440Zm86 160h134v-240H510l-16-80H280v240h290l16 80Z"/>
                                </svg>
                            </span>
                            <span class="type-name">{{ __('calendar.type_goal') }}</span>
                        </label>
                        <label class="type-option walking">
                            <input type="radio" name="type" value="walking">
                            <span class="type-icon">
                                <svg viewBox="0 -960 960 960" fill="currentColor">
                                    <path d="m280-40 112-564-72 28v136h-80v-188l202-86q14-6 29.5-7t29.5 4q14 5 26.5 14t20.5 23l40 64q26 42 70.5 69T760-520v80q-70 0-125-29t-94-74l-25 123 84 80v300h-80v-260l-84-64-72 324h-84Zm260-700q-33 0-56.5-23.5T460-820q0-33 23.5-56.5T540-900q33 0 56.5 23.5T620-820q0 33-23.5 56.5T540-740Z"/>
                                </svg>
                            </span>
                            <span class="type-name">{{ __('calendar.type_walking') }}</span>
                        </label>
                        <label class="type-option custom" id="custom-type-trigger">
                            <input type="radio" name="type" value="custom" id="custom-type-radio">
                            <span class="type-icon">
                                <svg viewBox="0 -960 960 960" fill="currentColor">
                                    <path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z"/>
                                </svg>
                            </span>
                            <span class="type-name">{{ __('calendar.type_custom') }}</span>
                        </label>
                    </div>

                    <div class="custom-type-input" id="custom-type-input" style="display: none;">
                        <label for="custom-type-name">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                            {{ __('calendar.custom_type_name') }}
                        </label>
                        <input type="text" id="custom-type-name" name="custom_type" placeholder="{{ __('calendar.custom_type_placeholder') }}" maxlength="30">
                    </div>
                </div>

                <div class="form-group">
                    <label for="event-description">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="17" y1="10" x2="3" y2="10"></line>
                            <line x1="21" y1="6" x2="3" y2="6"></line>
                            <line x1="21" y1="14" x2="3" y2="14"></line>
                            <line x1="17" y1="18" x2="3" y2="18"></line>
                        </svg>
                        {{ __('calendar.event_description') }}
                    </label>
                    <textarea id="event-description" name="description" rows="3" placeholder="{{ __('calendar.description_placeholder') }}"></textarea>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" id="cancel-btn">{{ __('calendar.cancel') }}</button>
                    <button type="submit" class="btn-save">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        {{ __('calendar.save_event') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="delete-modal">
        <div class="modal-content modal-small">
            <div class="modal-header">
                <h3 class="modal-title danger">{{ __('calendar.delete_event') }}</h3>
                <button class="modal-close" id="close-delete-modal">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="delete-content">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <p>{{ __('calendar.delete_confirm') }}</p>
                <input type="hidden" id="delete-event-id">
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" id="cancel-delete-btn">{{ __('calendar.cancel') }}</button>
                <button type="button" class="btn-delete" id="confirm-delete-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    {{ __('calendar.delete') }}
                </button>
            </div>
        </div>
    </div>

    <div class="toast-container" id="toast-container"></div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/activity-calendar-new.js') }}"></script>
@endsection
