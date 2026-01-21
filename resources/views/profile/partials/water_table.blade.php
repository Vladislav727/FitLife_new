<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>{{ __('profile.date') }}</th>
                <th>{{ __('profile.amount_ml') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($waterLogs as $log)
            <tr>
                <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $log->amount }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="2">{{ __('profile.no_water_logs') }}</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $waterLogs->links() }}
</div>
