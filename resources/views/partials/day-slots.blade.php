<h5 class="fw-bold">Available Times for {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</h5>

@if($slots->isEmpty())
  <p class="text-danger">No slots for this date.</p>
@else
  <div class="list-group">
    @foreach($slots->groupBy('start_time') as $time => $slotGroup)
      @php
        $availableSlot  = $slotGroup->where('is_taken', false)->first();
        $anySlot        = $slotGroup->first(); // ✅ always have a slot to check doctor
        $availableCount = $slotGroup->where('is_taken', false)->count();

        // ✅ Decide which doctor handles this slot (main or sub)
        $doctor = $anySlot && $anySlot->doctor && $anySlot->doctor->is_absent
                    ? $anySlot->subDoctor
                    : ($anySlot->doctor ?? null);
      @endphp

      <label class="list-group-item d-flex justify-content-between align-items-center">
        <input type="radio" 
               name="slot_id" 
               value="{{ $availableSlot->id ?? '' }}" 
               data-doctor-id="{{ $doctor?->id ?? '' }}"
               @if(!$availableSlot) disabled @endif required>

        <!-- ✅ Show time -->
        {{ \Carbon\Carbon::parse($time)->format('g:i A') }} -
        {{ \Carbon\Carbon::parse($slotGroup->first()->end_time)->format('g:i A') }}

        <!-- ✅ Always show doctor (main or sub) -->
        <small class="text-muted ms-2">
          @if($doctor)
            Dr. {{ $doctor->firstname }} {{ $doctor->lastname }}
            @if($anySlot->doctor && $anySlot->doctor->is_absent)
              (Substitute)
            @endif
          @else
            N/A
          @endif
        </small>

        <!-- ✅ Availability -->
        <span class="{{ $availableCount > 0 ? 'text-success' : 'text-danger' }}">
          {{ $availableCount > 0 ? "Available Slots: $availableCount" : "Fully Booked" }}
        </span>
      </label>
    @endforeach
  </div>
@endif
