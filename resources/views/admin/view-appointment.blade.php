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
      <i class="fas fa-calendar-alt me-2"></i> View All Appointments
    </h2>
<small class="text-muted">View All Appointments with their doctors.</small>
  </div>

   <!-- ✅ Filter & Search Controls -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
  <div class="d-flex gap-2">
      <input type="text" id="searchInput" class="form-control" placeholder="🔍 Search...">
    <select id="statusFilter" class="form-select">
      <option value="">All Status</option>
      <option value="pending">Pending</option>
      <option value="approved">Approved</option>
      <option value="denied">Denied</option>
      <option value="cancelled">Cancelled</option>
    </select>
  </div>
</div>

  @if(session('success'))
  <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

    <div class="table-responsive rounded-4 shadow-sm">
      <table class="table table-hover table-bordered table-striped align-middle text-center mb-0">
        <thead class="table-dark">
          <tr>
            <th>Profile</th>
            <th>Patient</th>
            <th>Email</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="appointmentsTable" class="text-center">
          @forelse($appointments as $appt)
          <tr>
            <!-- Patient Profile Picture -->
            <td>
              @if($appt->patient?->profile_picture)
                <img src="{{ asset($appt->patient->profile_picture) }}" 
                     alt="Profile Picture" 
                     class="rounded-circle"
                     style="width:50px; height:50px; object-fit:cover;">
              @else
                <img src="{{ asset('img/default-avatar.png') }}" 
                     alt="Default" 
                     class="rounded-circle"
                     style="width:50px; height:50px; object-fit:cover;">
              @endif
            </td>

            <!-- Name -->
            <td>{{ $appt->patient?->firstname }} {{ $appt->patient?->lastname }}</td>

            <!-- Email -->
            <td>{{ $appt->patient?->email }}</td>

            <!-- Appointment Date -->
            <td>{{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y h:i A') }}</td>

            <td>
        @if($appt->slot)
          {{ \Carbon\Carbon::parse($appt->slot->start_time)->format('h:i A') }} -
          {{ \Carbon\Carbon::parse($appt->slot->end_time)->format('h:i A') }}
        @else
          <em class="text-muted">No slot</em>
        @endif
      </td>

            <!-- Status -->
            <td>
              @if($appt->status == 'pending')
                <span class="badge bg-warning text-dark">Pending</span>
              @elseif($appt->status == 'approved')
                <span class="badge bg-success">Approved</span>
              @elseif($appt->status == 'denied')
                <span class="badge bg-danger">Denied</span>
              @elseif($appt->status == 'cancelled')
                <span class="badge bg-secondary">Cancelled</span>
              @else
                <span class="badge bg-info">{{ ucfirst($appt->status) }}</span>
              @endif
            </td>
            <td>
                  <div class="d-flex justify-content-center gap-2 flex-wrap">
                    <!-- Write Prescription -->
                  <!-- Write Patient Information Button -->
<button 
  type="button" 
  class="btn btn-sm btn-success rounded-pill shadow-sm px-3"
  data-bs-toggle="modal"
  data-bs-target="#editPatientInfoModal"
  data-id="{{ $appt->id }}"
  data-date="{{ $appt->appointment_date }}"
  data-start="{{ $appt->slot?->start_time }}"
  data-end="{{ $appt->slot?->end_time }}">
  <i class="fas fa-edit me-1"></i> Write
</button>


                    <!-- Archive Prescription -->
                    @if($appt->prescription)
                      <button type="button"
                              class="btn btn-sm btn-secondary rounded-pill shadow-sm px-3 btn-archive"
                              data-id="{{ $appt->prescription->id }}">
                        <i class="fas fa-archive me-1"></i> Archive
                      </button>
                    @else
                      <button type="button" class="btn btn-sm btn-secondary rounded-pill shadow-sm px-3" disabled title="No prescription to archive">
                        <i class="fas fa-archive me-1"></i> Archive
                      </button>
                    @endif

                    <!-- View Prescription -->
                    <button type="button" 
        class="btn btn-sm btn-primary rounded-pill shadow-sm px-3"
        data-bs-toggle="modal" 
        data-bs-target="#viewPatientInfoModal"
        data-name="{{ $appt->patient?->firstname }} {{ $appt->patient?->lastname }}"
        data-medication="{{ $appt->prescription->medication ?? 'N/A' }}"
        data-dosage="{{ $appt->prescription->dosage ?? 'N/A' }}"
        data-notes="{{ $appt->prescription->notes ?? 'N/A' }}"
        data-address="{{ $appt->patient?->address ?? 'N/A' }}"
        data-age="{{ $appt->patient?->age ?? 'N/A' }}"
        data-gender="{{ $appt->patient?->gender ?? 'N/A' }}"
        data-appointment-datetime="{{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y') }}{{ $appt->slot ? ' | ' . \Carbon\Carbon::parse($appt->slot->start_time)->format('h:i A') . ' - ' . \Carbon\Carbon::parse($appt->slot->end_time)->format('h:i A') : '' }}"
        data-height="{{ $appt->height ?? 'N/A' }}"
        data-weight="{{ $appt->weight ?? 'N/A' }}"
        data-bmi="{{ $appt->bmi ?? 'N/A' }}"
        data-blood="{{ $appt->blood_type ?? 'N/A' }}"
        data-advice="{{ $appt->advice ?? 'N/A' }}"
        data-doctor="{{ $appt->doctor?->firstname }} {{ $appt->doctor?->lastname }}">
  <i class="fas fa-eye me-1"></i> View
</button>
                  </div>
                </td>
          </tr>
          @empty
          <tr class="no-data-row">
  <td colspan="6" class="text-muted py-4">
              <i class="fas fa-inbox fa-2x mb-2"></i><br>
              No appointments found.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</main>

<!-- Write Prescription Modal -->
<div class="modal fade" id="editPatientInfoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-4 shadow-lg border-0">
      
      <!-- Modal Header -->
      <div class="modal-header bg-success text-white rounded-top-4">
        <h5 class="modal-title fw-bold">Write Patient Information</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <!-- Modal Body -->
       <form method="POST" id="editPatientInfoForm" action="{{ secure_url(route('admin.updatePatientInfo', [], false)) }}">
  @csrf
  <input type="hidden" name="appointment_id" id="editpatientInfoId">

  <div class="modal-body">
    <div class="mb-3">
      <label class="form-label fw-semibold">Height (cm)</label>
      <input type="text" class="form-control" name="height" required>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">Weight (kg)</label>
      <input type="text" class="form-control" name="weight" required>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">Body Mass Index (BMI)</label>
      <input type="text" class="form-control" name="bmi">
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">Blood Type</label>
      <input type="text" class="form-control" name="blood_type">
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">Advice / Consultation Notes</label>
      <textarea class="form-control" name="advice" rows="3"></textarea>
    </div>
  </div>

  <div class="modal-footer border-0 pt-0">
    <button type="submit" class="btn btn-success px-4 fw-bold">
      <i class="fas fa-save me-2"></i> Save
    </button>
    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
      Cancel
    </button>
  </div>
</form>


    </div>
  </div>
</div>

<!-- View Patient Info Modal -->
<div class="modal fade" id="viewPatientInfoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-4 shadow-lg border-0">
      
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold">View Patient Information</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body">
        
        <!-- Patient Info Section -->
        <div class="section-header">
          <h6 class="section-title text-muted">Patient Information</h6>
        </div>
        <div class="row">
          <div class="col-md-6">
            <p><strong>Name:</strong> <span id="vName">N/A</span></p>
            <p><strong>Age:</strong> <span id="vAge">N/A</span></p>
            <p><strong>Gender:</strong> <span id="vGender">N/A</span></p>
            <p><strong>Address:</strong> <span id="vAddress">N/A</span></p>
          </div>
          <div class="col-md-6">
            <p><strong>Date & Time:</strong> 
              <span id="vDate">N/A</span></p>
            <p><strong>Height:</strong> <span id="vHeight">N/A</span></p>
            <p><strong>Weight:</strong> <span id="vWeight">N/A</span></p>
            <p><strong>BMI:</strong> <span id="vBmi">N/A</span></p>
            <p><strong>Blood Type:</strong> <span id="vBlood">N/A</span></p>
          </div>
        </div>

        <!-- Consultation Section -->
        <div class="section-header">
          <h6 class="section-title text-muted">Consultation/Advice</h6>
        </div>
        <p><strong>Advice / Consultation:</strong> <span id="vAdvice">N/A</span>
        <hr class="my-4">

        <!-- Prescription Info Section -->
        <div class="section-header">
          <h6 class="section-title text-muted">Prescription (RX)</h6>
        </div>
        <p><strong>Medication:</strong> <span id="vMedication"></span></p>
        <p><strong>Dosage:</strong> <span id="vDosage"></span></p>
        <p><strong>Notes:</strong> <span id="vNotes"></span></p>

        <p><strong>Doctor Name:</strong> <span><span> <span id="vDoctor"></span> </span></p>
        
      </div>
    </div>
  </div>
</div>

<!-- Optional Hover Styles -->
<style>
.table-hover tbody tr:hover {
  background-color: #f8f9fa;
  transform: scale(1.01);
  transition: all 0.2s ease;
}
.card {
  border-radius: 1rem;
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
            if (row.classList.contains("no-data-row")) return; // skip "no data" row

            const patientCell = row.cells[1]; // Patient name
            const dateCell = row.cells[3];    // Appointment date
            const statusCell = row.cells[4];  // Status

            if (!patientCell || !dateCell || !statusCell) return;

            const patientName = patientCell.textContent.toLowerCase();
            const appointmentDate = dateCell.textContent.toLowerCase();
            const statusText = statusCell.textContent.toLowerCase();

            // Search match: either patient name OR appointment date contains search text
            const matchesSearch = 
                patientName.includes(searchValue) || 
                appointmentDate.includes(searchValue);

            // Status match
            const matchesStatus = 
                statusValue === "" || statusText.includes(statusValue);

            // Show/hide row
            const isVisible = matchesSearch && matchesStatus;
            row.style.display = isVisible ? "" : "none";

            if (isVisible) visibleCount++;
        });

        // Show/hide "No appointments found" message
        const noDataRow = tableBody.querySelector(".no-data-row");
        if (noDataRow) {
            noDataRow.style.display = visibleCount === 0 ? "" : "none";
        }
    }

    searchInput.addEventListener("input", filterTable);
    statusFilter.addEventListener("change", filterTable);
});

 document.addEventListener("DOMContentLoaded", function () {
  const editModal = document.getElementById("editPatientInfoModal");
  if (editModal) {
    editModal.addEventListener("show.bs.modal", function (event) {
      let button = event.relatedTarget;
      let appointmentId = button.getAttribute("data-id");
      document.getElementById("editpatientInfoId").value = appointmentId;
    });
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const viewModal = document.getElementById("viewPatientInfoModal");
  if (viewModal) {
    viewModal.addEventListener("show.bs.modal", function (event) {
      let button = event.relatedTarget;

      document.getElementById("vName").innerText = button.getAttribute("data-name");
      document.getElementById("vAddress").innerText = button.getAttribute("data-address");
      document.getElementById("vAge").innerText = button.getAttribute("data-age");
      document.getElementById("vGender").innerText = button.getAttribute("data-gender");
      document.getElementById("vDate").innerText = button.getAttribute("data-appointment-datetime");
      document.getElementById("vHeight").innerText = button.getAttribute("data-height");
      document.getElementById("vWeight").innerText = button.getAttribute("data-weight");
      document.getElementById("vBmi").innerText = button.getAttribute("data-bmi");
      document.getElementById("vBlood").innerText = button.getAttribute("data-blood");
      document.getElementById("vAdvice").innerText = button.getAttribute("data-advice");
      document.getElementById("vDoctor").innerText = button.getAttribute("data-doctor");
      document.getElementById("vMedication").innerText = button.getAttribute("data-medication");
      document.getElementById("vDosage").innerText = button.getAttribute("data-dosage");
      document.getElementById("vNotes").innerText = button.getAttribute("data-notes");

    });
  }
});
  </script>

<script src="{{ asset('/js/notification.js') }}"></script>


@endsection
