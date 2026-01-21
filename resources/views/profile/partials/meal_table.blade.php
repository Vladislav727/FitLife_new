<table class="history-table">
  <thead>
    <tr>
      <th>{{ __('profile.date') }}</th>
      <th>{{ __('profile.meal') }}</th>
      <th>{{ __('profile.food') }}</th>
      <th>{{ __('profile.quantity') }}</th>
      <th>{{ __('profile.calories') }}</th>
    </tr>
  </thead>
  <tbody>
    @if($mealLogs->isEmpty())
      <tr>
        <td colspan="5" class="no-data">{{ __('profile.no_meal_history') }}</td>
      </tr>
    @else
      @foreach($mealLogs as $log)
        <tr>
          <td>{{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y H:i') }}</td>
          <td>{{ $log->meal }}</td>
          <td>{{ $log->food }}</td>
          <td>{{ $log->quantity }} g/ml</td>
          <td>{{ $log->calories }} kcal</td>
        </tr>
      @endforeach
    @endif
  </tbody>
</table>
@if(!$mealLogs->isEmpty())
  <div class="pagination" data-current-page="{{ $mealLogs->currentPage() }}" data-last-page="{{ $mealLogs->lastPage() }}">
    <a href="{{ route('foods.index', ['page' => max(1, $mealLogs->currentPage() - 1)]) }}" class="{{ $mealLogs->onFirstPage() ? 'disabled' : '' }}">{{ __('profile.previous') }}</a>
    @for($i = 1; $i <= $mealLogs->lastPage(); $i++)
      <a href="{{ route('foods.index', ['page' => $i]) }}" class="{{ $mealLogs->currentPage() == $i ? 'current' : '' }}">{{ $i }}</a>
    @endfor
    <a href="{{ route('foods.index', ['page' => min($mealLogs->lastPage(), $mealLogs->currentPage() + 1)]) }}" class="{{ $mealLogs->onLastPage() ? 'disabled' : '' }}">{{ __('profile.next') }}</a>
  </div>
@endif