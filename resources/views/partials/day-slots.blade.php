<h5 class="fw-bold mb-3 text-primary">
  <i class="fas fa-clock me-2"></i> Available Times for 
  {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}
</h5>

@if($slots->isEmpty())
  <div class="alert alert-danger text-center shadow-sm border-0">
    <i class="fas fa-exclamation-circle me-2"></i> No available slots for this date.
  </div>
@else
  <div class="list-group rounded-4 shadow-sm border-0 overflow-hidden">
    @foreach($slots->groupBy('start_time') as $time => $slotGroup)
      @php
        $availableSlot  = $slotGroup->where('is_taken', false)->first();
        $anySlot        = $slotGroup->first();
        $availableCount = $slotGroup->where('is_taken', false)->count();
        $doctor = $anySlot && $anySlot->doctor && $anySlot->doctor->is_absent
                    ? $anySlot->subDoctor
                    : ($anySlot->doctor ?? null);
      @endphp

      <label class="list-group-item d-flex justify-content-between align-items-center py-3 border-0 border-bottom bg-white hover-shadow-sm" 
             style="transition: all 0.2s ease; cursor:pointer;">
        <div class="d-flex align-items-center gap-3">
          @if($availableSlot)
<input type="radio" 
       name="slot_id" 
       value="{{ $availableSlot->id }}" 
       data-doctor-id="{{ $doctor?->id ?? '' }}" required>
@endif

          <div>
            <div class="fw-bold">
              {{ \Carbon\Carbon::parse($time)->format('g:i A') }} -
              {{ \Carbon\Carbon::parse($slotGroup->first()->end_time)->format('g:i A') }}
            </div>
            <small class="text-muted">
              @if($doctor)
                Dr. {{ $doctor->firstname }} {{ $doctor->lastname }}
                @if($anySlot->doctor && $anySlot->doctor->is_absent)
                  (Substitute)
                @endif
              @else
                <span class="text-danger">N/A</span>
              @endif
            </small>
          </div>
        </div>

        <span class="badge rounded-pill {{ $availableCount > 0 ? 'bg-success' : 'bg-danger' }}">
          {{ $availableCount > 0 ? "Available: $availableCount" : "Fully Booked" }}
        </span>
      </label>
    @endforeach
  </div>
@endif