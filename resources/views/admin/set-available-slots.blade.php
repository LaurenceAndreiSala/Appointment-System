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

     <!-- ✅ Filter & Search Controls -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
  <div class="d-flex gap-2">
    <input type="text" id="searchInput" class="form-control" placeholder="🔍 Search by date or time...">
    <select id="statusFilter" class="form-select">
      <option value="">All Status</option>
      <option value="available">Available</option>
      <option value="booked">Booked</option>
    </select>
  </div>
</div>
   
<!-- ✅ Slots Table -->
<div class="table-responsive rounded-4 shadow-sm">
  <table class="table table-hover align-middle text-center mb-0">
    <thead class="bg-primary text-white">
      <tr>
        <th>Date</th>
        <th>Start</th>
        <th>End</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody id="slotsTable" class="text-center">
      @forelse($slots as $slot)
      <tr>
        <td>{{ \Carbon\Carbon::parse($slot->created_at)->format('M d, Y') }}</td>
        <td>{{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }}</td>
        <td>{{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}</td>
        <td>
          @if($slot->is_taken)
            <span class="badge bg-danger px-3 py-2 rounded-pill">Booked</span>
          @else
            <span class="badge bg-success px-3 py-2 rounded-pill">Available</span>
          @endif
        </td>
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
      <tr class="no-data-row">
        <td colspan="5" class="text-muted py-4">
          <i class="fas fa-inbox fa-2x mb-2"></i><br>
          No available slots found.
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
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
  document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById("searchInput");
    const statusFilter = document.getElementById("statusFilter");
    const tableBody = document.getElementById("slotsTable");

    function filterTable() {
        const searchValue = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value.toLowerCase();
        const rows = Array.from(tableBody.querySelectorAll("tr"));
        let visibleCount = 0;

        rows.forEach(row => {
            if (row.classList.contains("no-data-row")) return;

            const dateCell = row.cells[0];    // 1st column: Date
            const startCell = row.cells[1];   // 2nd column: Start
            const endCell = row.cells[2];     // 3rd column: End
            const statusCell = row.cells[3];  // 4th column: Status (added below in HTML)

            if (!dateCell || !startCell || !endCell || !statusCell) return;

            const dateText = dateCell.textContent.toLowerCase();
            const startText = startCell.textContent.toLowerCase();
            const endText = endCell.textContent.toLowerCase();
            const statusText = statusCell.textContent.toLowerCase();

            // ✅ Match search text in any time/date cell
            const matchesSearch = 
                dateText.includes(searchValue) ||
                startText.includes(searchValue) ||
                endText.includes(searchValue);

            // ✅ Match selected status or “All”
            const matchesStatus = 
                statusValue === "" || statusText.includes(statusValue);

            const isVisible = matchesSearch && matchesStatus;
            row.style.display = isVisible ? "" : "none";

            if (isVisible) visibleCount++;
        });

        // ✅ Show "No slots found" message only when nothing is visible
        const noDataRow = tableBody.querySelector(".no-data-row");
        if (noDataRow) {
            noDataRow.style.display = visibleCount === 0 ? "" : "none";
        }
    }

    searchInput.addEventListener("input", filterTable);
    statusFilter.addEventListener("change", filterTable);
});

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
