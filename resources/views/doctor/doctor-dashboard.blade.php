@extends('layouts.layout')
@section('title', 'Doctor Dashboard | MediCare')

@section('content')
@include('includes.doctornavbar')
@include('includes.doctorsidebar')


<div class="container-fluid">
  <div class="row">

  <!-- ✅ Main Content -->
    <div class="col-md-9 col-lg-10 offset-md-3 offset-lg-2 p-4">
<!-- 👋 Welcome Banner -->
      <div class="d-flex align-items-center justify-content-between flex-wrap bg-light p-3 rounded-4 shadow-sm mb-4">
        <div class="d-flex align-items-center">
        <i class="fas fa-user-md text-primary fa-1x me-3"></i>
        <span class="fw-bold fs-10 fs-md-10">
    Welcome Dr. {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}!
  </span>
        </div>
        <small class="text-muted mt-2 mt-md-0">Doctor Dashboard</small>
      </div>

    <!-- ✅ Stats Cards -->
      <div class="row g-4 mb-5">
        <div class="col-12 col-md-4">
          <div class="card shadow-sm border-0 text-center bg-primary text-white rounded-4">
            <div class="card-body">
              <i class="fas fa-calendar-check fa-2x mb-2"></i>
              <h2 class="fw-bold">{{ $appointmentCount }}</h2>
              <p class="mb-0">Today’s Appointments</p>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="card shadow-sm border-0 text-center bg-success text-white rounded-4">
            <div class="card-body">
              <i class="fas fa-users fa-2x mb-2"></i>
              <h2 class="fw-bold">{{ $patientCount }}</h2>
              <p class="mb-0">Total Patients</p>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="card shadow-sm border-0 text-center bg-warning text-white rounded-4">
            <div class="card-body">
              <i class="fas fa-prescription-bottle-alt fa-2x mb-2"></i>
              <h2 class="fw-bold">{{ $prescriptionsCount ?? 0 }}</h2>
              <p class="mb-0">Prescriptions Written</p>
            </div>
          </div>
        </div>
      </div>


      <!-- ✅ Appointments Table -->
      <div class="card shadow-sm border-0 p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
          <h2 class="fw-bold mb-2 mb-md-0 text-primary">
          <i class="fas fa-calendar-check me-2"></i>Manage Appointments
        </h2>
      </div>

      <!-- ✅ Filter & Search Controls -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
  <div class="d-flex gap-2">
    <input type="text" id="searchInput" class="form-control" placeholder="Search by patient name...">
    <select id="statusFilter" class="form-select">
      <option value="">All Status</option>
      <option value="pending">Pending</option>
      <option value="approved">Approved</option>
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
          @elseif($appt->status == 'approved') bg-success 
          @elseif($appt->status == 'denied') bg-danger 
          @elseif($appt->status == 'cancelled') bg-secondary 
          @else bg-info text-dark @endif">
          {{ $appt->status }}
        </span>
      </td>

      <!-- Actions -->
      <td>
  <div class="d-flex justify-content-center gap-2 flex-wrap">
    @if($appt->prescriptions && $appt->prescriptions->count() > 0)
      <!-- ✅ Enable Approve if prescription written -->
      <form action="{{ route('doctor.appointments.approve', $appt->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3 shadow-sm">
          <i class="fas fa-check me-1"></i> Approve
        </button>
      </form>
    @else
      <!-- 🚫 Disable Approve if no prescription -->
      <button type="button" 
              class="btn btn-sm btn-secondary rounded-pill px-3 shadow-sm" 
              disabled 
              title="Write a prescription first before approving.">
        <i class="fas fa-check me-1"></i> Approve
      </button>
    @endif

    <!-- ❌ Deny button still always available -->
    <form action="{{ route('doctor.view-appointment.deny', $appt->id) }}" method="POST">
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
            // Skip "No appointments found" row
            if (row.classList.contains("no-data-row")) return;

            const patientCell = row.cells[1]; // 2nd column: Patient
            const emailCell = row.cells[2];   // 3rd column: Email
            const dateCell = row.cells[3];    // 4th column: Date & Time
            const statusCell = row.cells[4];  // 5th column: Status

            if (!patientCell || !emailCell || !dateCell || !statusCell) return;

            const patientName = patientCell.textContent.toLowerCase();
            const emailText = emailCell.textContent.toLowerCase();
            const appointmentDate = dateCell.textContent.toLowerCase();
            const statusText = statusCell.textContent.toLowerCase();

            // ✅ Search match (name, email, or date)
            const matchesSearch =
                patientName.includes(searchValue) ||
                emailText.includes(searchValue) ||
                appointmentDate.includes(searchValue);

            // ✅ Status filter match
            const matchesStatus =
                statusValue === "" || statusText.includes(statusValue);

            const isVisible = matchesSearch && matchesStatus;
            row.style.display = isVisible ? "" : "none";

            if (isVisible) visibleCount++;
        });

        // ✅ Toggle "No appointments found" visibility
        const noDataRow = tableBody.querySelector(".no-data-row");
        if (noDataRow) {
            noDataRow.style.display = visibleCount === 0 ? "" : "none";
        }
    }

    searchInput.addEventListener("input", filterTable);
    statusFilter.addEventListener("change", filterTable);
});
</script>

<script src="{{ asset('js/notification.js') }}"></script>

@endsection

