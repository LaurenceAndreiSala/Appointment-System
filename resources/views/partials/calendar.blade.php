@php
  $startOfMonth = \Carbon\Carbon::create($year, $month, 1)->startOfWeek();
  $endOfMonth = \Carbon\Carbon::create($year, $month, 1)->endOfMonth()->endOfWeek();
  $slotsByDate = $slots->groupBy('date');
@endphp

<table class="table table-bordered mb-2">
  <thead>
    <tr>
      <th>Su</th><th>Mo</th><th>Tu</th><th>We</th><th>Th</th><th>Fr</th><th>Sa</th>
    </tr>
  </thead>
  <tbody> 
    @for($date = $startOfMonth->copy(); $date <= $endOfMonth; $date->addDay())
      @if($date->dayOfWeek == 0)<tr>@endif

      @php
        $dateStr = $date->toDateString();
        $slotsForDay = $slotsByDate[$dateStr] ?? collect();
        $availableCount = $slotsForDay->where('is_taken', false)->count();
      @endphp

<td class="text-center calendar-day
  @if($slotsForDay->isEmpty()) bg-light
  @elseif($availableCount > 0) bg-success text-white
  @else bg-danger text-white
  @endif"
  data-date="{{ $date->toDateString() }}">
    {{ $date->month == $month ? $date->day : '' }}
</td>

      @if($date->dayOfWeek == 6)</tr>@endif
    @endfor
  </tbody>
</table>


