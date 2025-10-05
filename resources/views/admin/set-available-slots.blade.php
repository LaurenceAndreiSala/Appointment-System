@extends('layouts.layout')
@section('title', 'Admin Dashboard | MediCare {{ Auth::user()->name }}')

@section('content')
@include('includes.adminnavbar')
@include('includes.adminleftnavbar')



<div class="container-fluid">
  <div class="row">

   <!-- Main Content -->
<div class="col-md-9 col-lg-10 offset-md-3 offset-lg-2 p-4">

  <!-- ✅ Page Header -->
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <h2 class="fw-bold mb-2 mb-md-0 text-primary">
      <i class="fas fa-calendar-alt me-2"></i> Set Available Appointment Slot
    </h2>
<small class="text-muted">Set the Available Appointment Slot for the Doctor.</small>
  </div>


    <!-- Success Alert -->
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Add Slot Form -->
    <form action="{{ route('doctor.store-slot') }}" method="POST" class="mb-4">
      @csrf

      @php
        $doctors = \App\Models\User::where('role_id', 2)->orderBy('id')->take(2)->get();
        $mainDoctor = $doctors->first();
      @endphp

      <input type="hidden" name="doctor_id" value="{{ $mainDoctor->id }}">
      <div class="mb-3">
        <label for="doctor_id" class="form-label">Main Doctor</label>
        <select name="doctor_id" id="doctor_id" class="form-select" required>
          <option value="{{ $mainDoctor->id }}" selected
                  {{ $mainDoctor->is_absent ? 'disabled' : '' }}>
            Dr. {{ $mainDoctor->firstname }} {{ $mainDoctor->lastname }}
            @if($mainDoctor->is_absent) (Absent) @endif
          </option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Date</label>
        <input type="date" name="date" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Start Time</label>
        <input type="time" name="start_time" id="start_time" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">End Time</label>
        <input type="time" name="end_time" id="end_time" class="form-control" readonly required>
      </div>

      <button type="submit" class="btn btn-primary mt-2">Save Slot</button>
    </form>

    <!-- Available Slots Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="fw-bold mb-0 text-primary">
        <i class="fas fa-clock me-2"></i>Available Slots
      </h3>
      <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#archivedModal">
        <i class="fas fa-archive"></i> Archived Slots
      </button>
    </div>

    <!-- Slots Table -->
    <div class="table-responsive rounded-4 shadow-sm">
      <table class="table table-hover align-middle text-center mb-0">
        <thead class="bg-primary text-white">
          <tr>
            <th>Date</th>
            <th>Start</th>
            <th>End</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($slots as $slot)
          <tr>
            <td>{{ $slot->date }}</td>
            <td>{{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }}</td>
            <td>{{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}</td>
            <td>
              <button type="button" class="btn btn-sm btn-secondary"
                      data-bs-toggle="modal" 
                      data-bs-target="#archiveModal"
                      data-id="{{ $slot->id }}">
                <i class="fas fa-archive"></i> Archive
              </button>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="4" class="text-muted py-4">
              <i class="fas fa-inbox fa-2x mb-2"></i><br>
              No available slots found.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- ✅ Archived Slots Modal -->
  <div class="modal fade" id="archivedModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content rounded-4 shadow-sm">
        <div class="modal-header bg-secondary text-white">
          <h5 class="modal-title">Archived Slots</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-hover align-middle text-center mb-0">
              <thead class="table-secondary">
                <tr>
                  <th>Date</th>
                  <th>Start</th>
                  <th>End</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @forelse($archivedSlots ?? [] as $slot)
                <tr>
                  <td>{{ $slot->date }}</td>
                  <td>{{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }}</td>
                  <td>{{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}</td>
                  <td>
                    <form action="{{ route('admin.slots.restore', $slot->id) }}" method="POST">
                      @csrf
                      @method('PUT')
                      <button type="submit" class="btn btn-sm btn-success">
                        <i class="fas fa-undo"></i> Restore
                      </button>
                    </form>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="4" class="text-muted py-4">No archived slots found.</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ✅ Archive Confirmation Modal -->
  <div class="modal fade" id="archiveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content rounded-4 shadow-sm">
        <div class="modal-header bg-secondary text-white">
          <h5 class="modal-title">Archive Slot</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">
          Are you sure you want to archive this slot?
        </div>
        <div class="modal-footer justify-content-center">
          <form id="archiveForm" method="POST" action="{{ route('admin.slots.archive') }}">
            @csrf
            <input type="hidden" name="slot_id" id="archiveSlotId">
            <button type="submit" class="btn btn-secondary">Yes, Archive</button>
          </form>
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>

</main>

<!-- ✅ Styles -->
<style>
.table-hover tbody tr:hover {
  background-color: #f8f9fa;
  transform: scale(1.01);
  transition: all 0.2s ease;
}
.card {
  border-radius: 1rem;
}
.btn {
  transition: 0.2s ease-in-out;
}
.btn:hover {
  transform: translateY(-2px);
}
</style>



<script>
  // Set slot ID in modal
    const archiveModal = document.getElementById("archiveModal");
    archiveModal.addEventListener("show.bs.modal", function (event) {
        let button = event.relatedTarget;
        let slotId = button.getAttribute("data-id");
        document.getElementById("archiveSlotId").value = slotId;
    });

      // Set slot ID in modal
    const archivedModal = document.getElementById("archivedModal");
    archivedModal.addEventListener("show.bs.modal", function (event) {
        let button = event.relatedTarget;
        let slotId = button.getAttribute("data-id");
    });

    document.addEventListener('DOMContentLoaded', function() {
    const startInput = document.getElementById('start_time');
    const endInput = document.getElementById('end_time');

    startInput.addEventListener('change', function() {
        if (!startInput.value) return;

        // Parse the start time
        let [hours, minutes] = startInput.value.split(':').map(Number);

        // Add 1 hour
        hours += 1;
        if (hours >= 24) hours -= 24; // wrap around midnight

        // Format as HH:mm
        const hh = hours.toString().padStart(2, '0');
        const mm = minutes.toString().padStart(2, '0');

        endInput.value = `${hh}:${mm}`;
    });
});

</script>

@endsection
