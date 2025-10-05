@php
  $startOfMonth = \Carbon\Carbon::create($year, $month, 1)->startOfWeek();
  $endOfMonth = \Carbon\Carbon::create($year, $month, 1)->endOfMonth()->endOfWeek();
  $slotsByDate = $slots->groupBy('date');
@endphp

<div class="mt-4 border rounded-4 overflow-hidden shadow-sm">
  <table class="table mb-0 text-center align-middle">
    <thead class="bg-primary text-white">
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

        <td class="calendar-day fw-semibold
          @if($slotsForDay->isEmpty()) bg-light
          @elseif($availableCount > 0) bg-success text-white
          @else bg-danger text-white
          @endif"
          data-date="{{ $date->toDateString() }}"
          style="cursor:pointer; transition: all 0.2s ease;">
          {{ $date->month == $month ? $date->day : '' }}
        </td>

        @if($date->dayOfWeek == 6)</tr>@endif
      @endfor
    </tbody>
  </table>
</div>

<style>
  .calendar-day:hover {
    transform: scale(1.05);
    filter: brightness(1.1);
  }

  .list-group-item:hover {
    background-color: #f8f9fa;
    box-shadow: 0 0.3rem 0.5rem rgba(0,0,0,0.08);
  }

  .list-group-item input[type="radio"]:checked + div .fw-bold {
    color: #0d6efd;
  }
</style>