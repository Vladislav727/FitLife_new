@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/events.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="events-content">
        <header class="events-header">
            <h1 class="events-title">{{ __('admin.events_management') }}</h1>
            <a href="{{ route('admin.dashboard') }}" class="events-back-btn">{{ __('admin.back_to_dashboard') }}</a>
        </header>

        <div class="events-search">
            <input type="text" id="event-search" placeholder="{{ __('admin.search_events') }}" class="events-search-input">
            <select id="type-filter" class="events-search-select">
                <option value="">{{ __('admin.all_types') }}</option>
                <option value="yoga">{{ __('admin.yoga') }}</option>
                <option value="gym">{{ __('admin.gym') }}</option>
                <option value="rest">{{ __('admin.rest') }}</option>
            </select>
        </div>

        <div class="events-section">
            <div class="events-table">
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('admin.id') }}</th>
                            <th>{{ __('admin.user') }}</th>
                            <th>{{ __('admin.type') }}</th>
                            <th>{{ __('admin.date') }}</th>
                            <th>{{ __('admin.description') }}</th>
                            <th>{{ __('admin.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $event)
                            <tr>
                                <td>{{ $event->id }}</td>
                                <td>{{ $event->user->name }}</td>
                                <td>{{ ucfirst($event->type) }}</td>
                                <td>{{ $event->date }}</td>
                                <td>{{ Str::limit($event->description, 30) }}</td>
                                <td>
                                    <form action="{{ route('admin.events.delete', $event) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="events-btn events-btn-danger" onclick="return confirm('{{ __('admin.confirm_delete_event') }}')">{{ __('admin.delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">{{ __('admin.no_events_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="events-pagination">
                {{ $events->links() }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('event-search').addEventListener('input', function() {
            let search = this.value.toLowerCase();
            document.querySelectorAll('.events-table tr').forEach(row => {
                let user = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase();
                let description = row.querySelector('td:nth-child(5)')?.textContent.toLowerCase();
                if (user && description && (user.includes(search) || description.includes(search))) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        document.getElementById('type-filter').addEventListener('change', function() {
            let type = this.value;
            document.querySelectorAll('.events-table tr').forEach(row => {
                let typeCell = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase();
                if (!type || (typeCell && typeCell === type)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
@endsection