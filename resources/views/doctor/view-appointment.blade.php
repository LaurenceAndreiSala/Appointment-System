@extends('layouts.layout')
@section('title', 'Manage Appointments | MediCare')

@section('content')
@include('includes.doctornavbar')
@include('includes.doctorsidebar')

<div class="container-fluid">
  <div class="row">

  <!-- ✅ Main Content -->
<div class="col-12 col-md-9 col-lg-10 offset-lg-2  p-4 p-md-2">
  <div class="bg-light rounded-4 shadow-sm p-4 mb-4 d-flex align-items-center">
    <i class="fas fa-calendar-check text-primary fa-2x me-3"></i>
    <h3 class="fw-bold mb-0 text-dark">Manage Appointments</h3>
  </div>

  <p class="text-muted mb-4">Review, approve, or deny appointments.</p>

      <!-- ✅ Filter & Search Controls -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
  <div class="d-flex gap-2">
      <input type="text" id="searchInput" class="form-control" placeholder="🔍 Search...">
    <select id="statusFilter" class="form-select">
      <option value="">All Status</option>
      <option value="pending">Pending</option>
      <option value="complete">Complete</option>
      <option value="denied">Denied</option>
      <option value="cancelled">Cancelled</option>
    </select>
  </div>
</div>

      <!-- ✅ Appointments Table -->
      <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="bg-primary text-white text-center">
                <tr>
                  <th>Profile</th>
                  <th>Patient</th>
                  <th>Doctor</th>
                  <th>Date</th>
                  <th>Time</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="appointmentsTable" class="text-center">
  @forelse($appointments as $appt)
    <tr class="align-middle">
      <!-- Profile -->
      <td>
        <img src="{{ $appt->patient?->profile_picture ? asset($appt->patient->profile_picture) : asset('img/default-avatar.png') }}"
             alt="Profile"
             class="rounded-circle shadow-sm border"
             style="width:50px; height:50px; object-fit:cover;">
      </td>

      <!-- Patient -->
      <td class="fw-semibold patient-name">
        {{ $appt->patient?->firstname }} {{ $appt->patient?->lastname }}
      </td>

      <!-- Doctor -->
      <td>{{ $appt->doctor?->firstname }} {{ $appt->doctor?->lastname }}</td>

      <!-- Date -->
      <td>{{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y') }}</td>

      <!-- Time -->
      <td>
        @if($appt->slot)
          {{ \Carbon\Carbon::parse($appt->slot->start_time)->format('h:i A') }} -
          {{ \Carbon\Carbon::parse($appt->slot->end_time)->format('h:i A') }}
        @else
          <em class="text-muted">No slot</em>
        @endif
      </td>

      <!-- Status -->
      <td class="status-cell">
        <span class="badge px-3 py-2 rounded-pill text-capitalize 
          @if($appt->status == 'pending') bg-warning text-dark 
          @elseif($appt->status == 'complete') bg-success 
          @elseif($appt->status == 'denied') bg-danger 
          @elseif($appt->status == 'cancelled') bg-secondary 
          @else bg-info text-dark @endif">
          {{ $appt->status }}
        </span>
      </td>

      <!-- Actions -->
      <td>
  <div class="d-flex justify-content-center gap-2 flex-wrap">
      <!-- ✅ Enable Approve if prescription written -->
         <form action="{{ route('doctor.appointments.approve', $appt->id') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3 shadow-sm">
          <i class="fas fa-check me-1"></i> Approve
        </button>
      </form>

    <!-- ❌ Deny button still always available -->
    <form action="{{ route('doctor.view-appointment.deny', $appt->id') }}" method="POST">
      @csrf
      <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 shadow-sm">
        <i class="fas fa-times me-1"></i> Deny
      </button>
    </form>
  </div>
</td>
    </tr>
  @empty
    <tr>
      <td colspan="7" class="text-muted py-5">
        <i class="fas fa-inbox fa-2x mb-2"></i><br>
        No appointments found.
      </td>
    </tr>
  @endforelse
</tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById("searchInput");
    const statusFilter = document.getElementById("statusFilter");
    const tableBody = document.getElementById("appointmentsTable");

    function filterTable() {
        const searchValue = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value.toLowerCase();
        const rows = Array.from(tableBody.querySelectorAll("tr"));
        let visibleCount = 0;

        rows.forEach(row => {
            // Skip "no appointments" row
            if (row.classList.contains("no-data-row")) return;

            const patientCell = row.cells[1]; // 2nd column: Patient
            const dateCell = row.cells[3];    // 4th column: Date & Time
            const statusCell = row.cells[4];  // 5th column: Status

            if (!patientCell || !dateCell || !statusCell) return;

            const patientName = patientCell.textContent.toLowerCase();
            const appointmentDate = dateCell.textContent.toLowerCase();
            const statusText = statusCell.textContent.toLowerCase();

            // ✅ Match if search includes patient name or appointment date
            const matchesSearch = 
                patientName.includes(searchValue) ||
                appointmentDate.includes(searchValue);

            // ✅ Match if selected status matches or "All"
            const matchesStatus = 
                statusValue === "" || statusText.includes(statusValue);

            const isVisible = matchesSearch && matchesStatus;
            row.style.display = isVisible ? "" : "none";

            if (isVisible) visibleCount++;
        });

        // ✅ Show "No appointments found" only when nothing is visible
        const noDataRow = tableBody.querySelector(".no-data-row");
        if (noDataRow) {
            noDataRow.style.display = visibleCount === 0 ? "" : "none";
        }
    }

    searchInput.addEventListener("input", filterTable);
    statusFilter.addEventListener("change", filterTable);
});
</script>


<script>
  document.addEventListener("DOMContentLoaded", function() {
    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("sidebarToggle");
    const closeBtn = document.getElementById("sidebarClose");

    toggleBtn?.addEventListener("click", () => sidebar.classList.add("active"));
    closeBtn?.addEventListener("click", () => sidebar.classList.remove("active"));
  });
</script>

<!-- ✅ Notifications Script -->
<script>
  const notifUrl = "{{ route('doctor.notifications.fetch') }}";
</script>
<script src="{{ asset('js/notification.js') }}"></script>

<style>
  .table-hover tbody tr:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
    transition: all 0.2s ease;
  }

  .card {
    border-radius: 1rem;
  }

  .badge {
    font-size: 0.85rem;
  }

  .btn {
    transition: 0.2s ease-in-out;
  }

  .btn:hover {
    transform: translateY(-2px);
  }
</style>
@endsection
