@extends('layouts.app')

@section('title', 'Events')

@section('content')
    @vite([        'resources/css/admin/admin.css',
        'resources/css/admin/events.css',
        'resources/css/admin/adminposts.css',
    ])

    @php
        $eventCollection = $events instanceof \Illuminate\Pagination\AbstractPaginator ? $events->getCollection() : collect($events);
        $eventTypes = $eventCollection->pluck('type')->filter()->unique()->values();
    @endphp

    <div class="admin-layout admin-layout--events">
        <section class="admin-hero">
            <div class="admin-hero__content">
                <span class="admin-hero__eyebrow">Event moderation</span>

                <div>
                    <h1 class="admin-hero__title">Review events</h1>
                    <p class="admin-hero__description">
                        Filter events by type, review scheduling details, and remove items that no longer meet platform standards.
                    </p>
                </div>

                <div class="admin-hero__meta">
                    <span class="admin-badge admin-badge--primary">{{ number_format((int) ($events->total() ?? $events->count() ?? 0)) }} events</span>
                    <span class="admin-badge admin-badge--muted">Type-aware filtering</span>
                </div>

                <div class="admin-hero__actions">
                    <a href="{{ route('admin.dashboard') }}" class="admin-button admin-button--ghost">Back to dashboard</a>
                    <a href="{{ route('admin.comments') }}" class="admin-button admin-button--secondary">Comments</a>
                </div>
            </div>
        </section>

        <section class="admin-toolbar">
            <div class="admin-toolbar__group">
                <label class="admin-toolbar__label" for="event-search">Search</label>
                <input id="event-search" type="search" class="admin-toolbar__search" placeholder="Search events by title or location" autocomplete="off">
            </div>

            <div class="admin-toolbar__group">
                <label class="admin-toolbar__label" for="type-filter">Type</label>
                <select id="type-filter" class="admin-toolbar__select">
                    <option value="all">All types</option>
                    @forelse ($eventTypes as $type)
                        <option value="{{ strtolower((string) $type) }}">{{ ucfirst(str_replace(['_', '-'], ' ', (string) $type)) }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </section>

        <section class="admin-card">
            <div class="admin-card__header">
                <div class="admin-card__title-group">
                    <h2 class="admin-card__title">All events</h2>
                    <p class="admin-card__description">Use the filters above to browse the event catalogue.</p>
                </div>
            </div>

            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($events as $event)
                            @php
                                $eventType = strtolower((string) ($event->type ?? 'general'));
                                $eventLocation = data_get($event, 'location', data_get($event, 'venue', 'No location provided'));
                                $startDate = data_get($event, 'start_at', data_get($event, 'date', data_get($event, 'starts_at')));
                                $searchText = strtolower(($event->title ?? '') . ' ' . $eventLocation . ' ' . $eventType);
                            @endphp
                            <tr data-event-row data-type="{{ $eventType }}" data-search-text="{{ $searchText }}">
                                <td data-label="Event">
                                    <div class="admin-table__stack">
                                        <strong class="admin-table__title">{{ $event->title ?? 'Untitled event' }}</strong>
                                        <span class="admin-table__subtitle">{{ \Illuminate\Support\Str::limit(strip_tags(data_get($event, 'description', data_get($event, 'body', ''))), 110) }}</span>
                                    </div>
                                </td>
                                <td data-label="Type">
                                    <div class="admin-event__type">
                                        <span class="admin-badge admin-badge--info">{{ ucfirst(str_replace(['_', '-'], ' ', $eventType)) }}</span>
                                    </div>
                                </td>
                                <td data-label="Date">
                                    <div class="admin-event__time">
                                        <strong>{{ optional($startDate)->format('M d, Y') ?? (is_string($startDate) ? $startDate : '—') }}</strong>
                                        <span>{{ optional($startDate)->diffForHumans() ?? 'Upcoming or recently scheduled' }}</span>
                                    </div>
                                    <div class="admin-event__location">{{ $eventLocation }}</div>
                                </td>
                                <td data-label="Actions">
                                    <div class="admin-table__actions">
                                        <form action="{{ route('admin.events.delete', $event) }}" method="POST" onsubmit="return confirm('Delete this event?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-button admin-button--sm admin-button--danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="admin-empty-state">
                                        <p class="admin-empty-state__title">No events found</p>
                                        <p class="admin-empty-state__description">There are no events available for moderation right now.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="admin-card__footer">
                <div class="admin-card__meta">
                    <span class="admin-badge admin-badge--muted">Total: {{ number_format((int) ($events->total() ?? $events->count() ?? 0)) }}</span>
                </div>
                {{ $events->links() }}
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/admin-events-index.js') }}" defer></script>
@endsection