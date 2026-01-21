<div class="table-wrapper">
    @if($sleepLogs->isEmpty())
        <p style="text-align:center; padding:1rem; color:#475569;">{{ __('profile.no_sleep_records') }}</p>
    @else
    <table>
        <thead>
            <tr>
                <th>{{ __('profile.date') }}</th>
                <th>{{ __('profile.start_time') }}</th>
                <th>{{ __('profile.end_time') }}</th>
                <th>{{ __('profile.duration_hours') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sleepLogs as $sleep)
            <tr>
                <td>{{ $sleep->date }}</td>
                <td>{{ $sleep->start_time }}</td>
                <td>{{ $sleep->end_time }}</td>
                <td>{{ round($sleep->duration, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination-wrapper">
        {{ $sleepLogs->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
