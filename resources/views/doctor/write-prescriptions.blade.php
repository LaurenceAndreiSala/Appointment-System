@extends('layouts.layout')
@section('title', 'Doctor Dashboard | MediCare')

@section('content')
@include('includes.doctornavbar')
@include('includes.doctorsidebar')

<div class="container-fluid">
  <div class="row">

  <div class="col-12 col-md-9 col-lg-10 offset-lg-2  p-4 p-md-2">

  <div class="bg-light rounded-4 shadow-sm p-4 mb-4 d-flex align-items-center">
    <i class="fas fa-prescription-bottle-alt text-primary fa-2x me-3"></i>
    <h3 class="fw-bold mb-0 text-dark">View Appointments</h3>
  </div>
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

  <button type="button" class="btn btn-dark mb-3" data-bs-toggle="modal" data-bs-target="#archivedPrescriptionsModal">
    <i class="fas fa-archive"></i> View Archived Prescriptions
  </button>

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
       <!-- Write Prescription Modal -->
<div class="modal fade" id="writePrescriptionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-4 shadow-lg border-0">
      
      <!-- Modal Header -->
      <div class="modal-header bg-success text-white rounded-top-4">
        <h5 class="modal-title fw-bold">Write Prescription</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <!-- Modal Body -->
          <form id="writePrescriptionForm" action="{{ route('doctor.prescriptions.store') }}" method="POST">
        @csrf
        <input type="hidden" name="appointment_id" id="prescriptionAppointmentId">

        <div class="modal-body">
          <!-- Medication -->
          <div class="mb-4">
            <label for="medication" class="form-label fw-semibold">Medication</label>
            <input type="text" class="form-control form-control-lg shadow-sm" name="medication" id="medication" placeholder="Enter medication name" required>
          </div>

          <!-- Dosage -->
          <div class="mb-4">
            <label for="dosage" class="form-label fw-semibold">Dosage</label>
            <input type="text" class="form-control form-control-lg shadow-sm" name="dosage" id="dosage" placeholder="e.g., 500mg, 2 times a day" required>
          </div>

          <!-- Quantity -->
        <div class="mb-4">
          <label for="quantity" class="form-label fw-semibold">Quantity</label>
          <input type="number" class="form-control form-control-lg shadow-sm" 
                name="quantity" id="quantity" placeholder="e.g., 10" required min="1">
        </div>

          <!-- Notes -->
          <div class="mb-4">
            <label for="notes" class="form-label fw-semibold">Notes</label>
            <textarea class="form-control shadow-sm" name="notes" id="notes" rows="3" placeholder="Additional instructions or notes"></textarea>
          </div>
        </div>

        <!-- Doctor's Signature Preview -->
<div class="text-center mb-4">
  <h6 class="fw-semibold text-muted mb-2">Your Electronic Signature</h6>
  @if(auth()->user()->signature)
    <img src="{{ asset('storage/' . auth()->user()->signature) }}" 
         alt="Doctor Signature"
         style="max-width: 200px; max-height: 100px; object-fit: contain;">
  @else
    <p class="text-danger">⚠️ No signature uploaded yet. Please upload in your profile.</p>
  @endif
</div>


        <!-- Modal Footer -->
        <div class="modal-footer border-0 pt-0">
          <button type="submit" class="btn btn-success btn-md px-4 fw-bold">
            <i class="fas fa-prescription-bottle-alt me-2"></i> Save Prescription
          </button>
          <button type="button" class="btn btn-outline-secondary btn-lg px-4" data-bs-dismiss="modal">
            Cancel
          </button>
        </div>
      </form>

    </div>
  </div>
</div>

<!-- ✅ View Patient Info Modal -->
<div class="modal fade" id="viewPrescriptionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content rounded-3 shadow">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold">View Patient Info</h5>
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
            <p><strong>Date & Time:</strong> <span id="viewAppointmentDateTime">N/A</span></p>
            <p><strong>Height:</strong> <span id="vHeight">N/A</span></p>
            <p><strong>Weight:</strong> <span id="vWeight">N/A</span></p>
            <p><strong>BMI:</strong> <span id="vBmi">N/A</span></p>
            <p><strong>Blood Type:</strong> <span id="vBlood">N/A</span></p>
          </div>
        </div>

        <!-- Consultation Section -->
        <div class="section-header mt-3">
          <h6 class="section-title text-muted">Consultation/Advice</h6>
        </div>
        <p><strong>Advice / Consultation:</strong> <span id="vAdvice">N/A</span></p>

        <hr class="my-4">

        <!-- Prescription Info Section -->
        <div class="section-header">
          <h6 class="section-title text-muted">Prescription (RX)</h6>
        </div>
        <p><strong>Medication:</strong> <span id="viewMedication">N/A</span></p>
        <p><strong>Dosage:</strong> <span id="viewDosage">N/A</span></p>
        <p><strong>Quantity:</strong> <span id="viewQuantity">N/A</span></p>
        <p><strong>Notes:</strong> <span id="viewNotes">N/A</span></p>
        <p><strong>Doctor:</strong> <span id="vDoctor">N/A</span></p>
      </div>
    </div>
  </div>
</div>


<!-- Archived Prescriptions Modal -->
<div class="modal fade" id="archivedPrescriptionsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content rounded-3 shadow">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title">Archived Prescriptions</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped text-center">
            <thead class="table-secondary">
              <tr>
                <th>Patient</th>
                <th>Medication</th>
                <th>Dosage</th>
                <th>Notes</th>
                <th>Archived At</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="archivedTableBody">
              @forelse($archivedPrescriptions as $arch)
                <tr data-id="{{ $arch->id }}">
                  <td>{{ $arch->appointment->patient->firstname ?? '' }} {{ $arch->appointment->patient->lastname ?? '' }}</td>
                  <td>{{ $arch->medication }}</td>
                  <td>{{ $arch->dosage }}</td>
                  <td>{{ $arch->notes ?? '-' }}</td>
                  <td>{{ $arch->updated_at->format('M d, Y h:i A') }}</td>
                  <td>
                    <button type="button" class="btn btn-sm btn-success btn-restore" data-id="{{ $arch->id }}">
                      <i class="fas fa-undo"></i> Restore
                    </button>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6">No archived prescriptions found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Edit Prescription Modal -->
<div class="modal fade" id="editPrescriptionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-4 shadow-lg border-0">
      
      <div class="modal-header bg-warning text-dark rounded-top-4">
        <h5 class="modal-title fw-bold">Edit Prescription</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
  
      <form id="editPrescriptionForm" method="POST" action="{{ route('doctor.prescriptions.update') }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="prescription_id" id="editPrescriptionId">

        <div class="modal-body">
          <div class="mb-4">
            <label class="form-label fw-semibold">Medication</label>
            <input type="text" class="form-control form-control-lg shadow-sm" name="medication" id="editMedication" required>
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold">Dosage</label>
            <input type="text" class="form-control form-control-lg shadow-sm" name="dosage" id="editDosage" required>
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold">Quantity</label>
            <input type="number" class="form-control form-control-lg shadow-sm" name="quantity" id="editQuantity" min="1" required>
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold">Notes</label>
            <textarea class="form-control shadow-sm" name="notes" id="editNotes" rows="3"></textarea>
          </div>
        </div>

        <div class="modal-footer border-0 pt-0">
          <button type="submit" class="btn btn-warning fw-bold px-4">
            <i class="fas fa-save me-1"></i> Update Prescription
          </button>
          <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Archive Confirmation Modal -->
<div class="modal fade" id="archiveModal" tabindex="-1" aria-hidden="true">
  <input type="hidden" id="archivePrescriptionId">
  <div class="modal-dialog">
    <div class="modal-content rounded-3 shadow">
      <div class="modal-header bg-secondary text-white">
        <h5 class="modal-title">Archive Prescription</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to archive this prescription?
      </div>
      <div class="modal-footer">
        <button type="button" id="confirmArchive" class="btn btn-secondary">Yes, Archive</button>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

         <!-- ✅ Appointments Table -->
  <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle text-center mb-0">
          <thead class="bg-primary text-white">
            <tr>
              <th>Profile</th>
              <th>Patient</th>
              <th>Doctor</th>
              <th>Date & Time</th>
              <th>Status</th>
              <th><i data-feather="edit" class="me-2"></i> Manage Prescriptions</th>
            </tr>
          </thead>
     <tbody id="appointmentsTable" class="text-center">
            @forelse($appointments as $appt)
              <tr class="align-middle">
                <td>
                  <img src="{{ $appt->patient?->profile_picture ? asset($appt->patient->profile_picture) : asset('img/default-avatar.png') }}"
                       alt="Profile" class="rounded-circle shadow-sm border"
                       style="width:50px; height:50px; object-fit:cover;">
                </td>
                <td>{{ $appt->patient?->firstname }} {{ $appt->patient?->lastname }}</td>
                <td>{{ $appt->doctor?->firstname }} {{ $appt->doctor?->lastname }}</td>
                <td>
                  {{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y') }}
                  <br>
                  @if($appt->slot)
                    {{ \Carbon\Carbon::parse($appt->slot->start_time)->format('h:i A') }} -
                    {{ \Carbon\Carbon::parse($appt->slot->end_time)->format('h:i A') }}
                  @else
                    <em>No slot assigned</em>
                  @endif
                </td>
                <td>
                  @if($appt->status == 'pending')
                    <span class="badge bg-warning text-dark">Pending</span>
                  @elseif($appt->status == 'complete')
                    <span class="badge bg-success">Complete</span>
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
                   <!-- Write Prescription -->
@if($appt->height || $appt->weight || $appt->bmi || $appt->blood_type || $appt->advice)
  @if($appt->prescription)
    <!-- 🟢 Already has prescription -->
    <button type="button" 
            class="btn btn-sm btn-success rounded-pill shadow-sm px-3"
            disabled
            title="Prescription already written">
      <i class="fas fa-check-circle me-1"></i> Prescription Written
    </button>
  @else
    <!-- ✏️ Write new prescription -->
    <button type="button" 
            class="btn btn-sm btn-primary rounded-pill shadow-sm px-3"
            data-bs-toggle="modal" 
            data-bs-target="#writePrescriptionModal"
            data-id="{{ $appt->id }}">
      <i class="fas fa-prescription me-1"></i> Write Prescription
    </button>
  @endif
@else
  <!-- 🚫 Disable if no patient info yet -->
  <button type="button" 
          class="btn btn-sm btn-secondary rounded-pill shadow-sm px-3" 
          disabled 
          title="Please write patient information first">
    <i class="fas fa-prescription me-1"></i> Write Prescription
  </button>
@endif

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
<!-- ✅ Fixed View Prescription -->
<button type="button" 
  class="btn btn-sm btn-primary rounded-pill shadow-sm px-3"
  data-bs-toggle="modal" 
  data-bs-target="#viewPrescriptionModal"
  data-name="{{ $appt->patient?->firstname ?? 'N/A' }} {{ $appt->patient?->lastname ?? '' }}"
  data-age="{{ $appt->patient?->age && $appt->patient->age > 0 ? $appt->patient->age : 'N/A' }}"
  data-gender="{{ $appt->patient?->gender ?? 'N/A' }}"
  data-address="{{ $appt->patient?->address && trim($appt->patient->address) != '' ? $appt->patient->address : 'N/A' }}"
  data-medication="{{ $appt->prescription?->medication ?? 'N/A' }}"
  data-dosage="{{ $appt->prescription?->dosage ?? 'N/A' }}"
  data-notes="{{ $appt->prescription?->notes ?? 'N/A' }}"
  data-quantity="{{ $appt->prescription?->quantity ?? 'N/A' }}"
  data-appointment-datetime="{{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y') }}{{ $appt->slot ? ' | ' . \Carbon\Carbon::parse($appt->slot->start_time)->format('h:i A') . ' - ' . \Carbon\Carbon::parse($appt->slot->end_time)->format('h:i A') : '' }}"
  data-height="{{ $appt->height ?? 'N/A' }}"
  data-weight="{{ $appt->weight ?? 'N/A' }}"
  data-bmi="{{ $appt->bmi ?? 'N/A' }}"
  data-blood="{{ $appt->blood_type ?? 'N/A' }}"
  data-advice="{{ $appt->advice ?? 'N/A' }}"
  data-doctor="{{ $appt->doctor?->firstname ?? 'N/A' }} {{ $appt->doctor?->lastname ?? '' }}">
  <i class="fas fa-eye me-1"></i> View
</button>

@if($appt->prescription)
  <button type="button" 
          class="btn btn-sm btn-warning rounded-pill shadow-sm px-3"
          data-bs-toggle="modal" 
          data-bs-target="#editPrescriptionModal"
          data-id="{{ $appt->prescription->id }}"
          data-medication="{{ $appt->prescription->medication }}"
          data-dosage="{{ $appt->prescription->dosage }}"
          data-quantity="{{ $appt->prescription->quantity }}"
          data-notes="{{ $appt->prescription->notes }}">
    <i class="fas fa-edit me-1"></i> Edit
  </button>
@endif

                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-muted py-5">
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
  const notifUrl = "{{ route('doctor.notifications.fetch') }}";
</script>
<script src="{{ asset('js/notification.js') }}"></script>

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

            const patientCell = row.cells[1]; // Patient name
            const emailCell = row.cells[2];   // Email
            const dateCell = row.cells[3];    // Date & Time
            const statusCell = row.cells[4];  // Status

            if (!patientCell || !emailCell || !dateCell || !statusCell) return;

            const patientName = patientCell.textContent.toLowerCase();
            const emailText = emailCell.textContent.toLowerCase();
            const appointmentDate = dateCell.textContent.toLowerCase();
            const statusText = statusCell.textContent.toLowerCase();

            // ✅ Match search: patient name, email, or appointment date
            const matchesSearch =
                patientName.includes(searchValue) ||
                emailText.includes(searchValue) ||
                appointmentDate.includes(searchValue);

            // ✅ Match status: either all or specific
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

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Write Prescription Modal (keep as you had)
    const writeModal = document.getElementById("writePrescriptionModal");
    if (writeModal) {
      writeModal.addEventListener("show.bs.modal", function (event) {
          let button = event.relatedTarget;
          let appointmentId = button.getAttribute("data-id");
          document.getElementById("prescriptionAppointmentId").value = appointmentId;
      });
    }

    // Archive modal element
    const archiveModalEl = document.getElementById("archiveModal");
    let archiveRow = null;

    // Open archive modal when .btn-archive clicked (delegated)
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-archive');
        if (!btn) return;

        const prescriptionId = btn.getAttribute('data-id');
        if (!prescriptionId) {
            alert('No prescription found to archive.');
            return;
        }

        // set hidden input and store row
        const hidden = document.getElementById('archivePrescriptionId');
        if (hidden) hidden.value = prescriptionId;
        archiveRow = btn.closest('tr');

        // show modal
        const modalInstance = new bootstrap.Modal(archiveModalEl);
        modalInstance.show();
    });

    // Confirm archive click -> AJAX
    const confirmBtn = document.getElementById('confirmArchive');
    if (confirmBtn) {
      confirmBtn.addEventListener('click', function () {
          const prescriptionId = document.getElementById('archivePrescriptionId').value;
          if (!prescriptionId) return;

          fetch("{{ route('doctor.write-prescriptions.archive') }}", {
              method: "POST",
              headers: {
                  "Content-Type": "application/json",
                  "X-CSRF-TOKEN": "{{ csrf_token() }}",
                  "X-Requested-With": "XMLHttpRequest"
              },
              body: JSON.stringify({ prescription_id: prescriptionId })
          })
          .then(res => {
              if (!res.ok) throw new Error('Network response was not ok');
              return res.json();
          })
          .then(data => {
              if (data.success) {
                  // remove row instantly
                  if (archiveRow) archiveRow.remove();

                  // hide modal
                  const inst = bootstrap.Modal.getInstance(archiveModalEl);
                  if (inst) inst.hide();
              } else {
                  alert(data.message || 'Failed to archive prescription.');
              }
          })
          .catch(err => {
              console.error(err);
              alert('An error occurred while archiving. See console for details.');
          });
      });
    }

    // View Prescription Modal (keep as you had)
// ✅ Modal dynamic fill
  const viewModal = document.getElementById("viewPrescriptionModal");
  viewModal.addEventListener("show.bs.modal", function (event) {
    const button = event.relatedTarget;

    document.getElementById("vName").innerText = button.getAttribute("data-name");
    document.getElementById("vAge").innerText = button.getAttribute("data-age");
    document.getElementById("vGender").innerText = button.getAttribute("data-gender");
    document.getElementById("vAddress").innerText = button.getAttribute("data-address");
    document.getElementById("viewMedication").innerText = button.getAttribute("data-medication");
    document.getElementById("viewDosage").innerText = button.getAttribute("data-dosage");
    document.getElementById("viewNotes").innerText = button.getAttribute("data-notes");
    document.getElementById("viewQuantity").innerText = button.getAttribute("data-quantity");
    document.getElementById("viewAppointmentDateTime").innerText = button.getAttribute("data-appointment-datetime");
    document.getElementById("vHeight").innerText = button.getAttribute("data-height");
    document.getElementById("vWeight").innerText = button.getAttribute("data-weight");
    document.getElementById("vBmi").innerText = button.getAttribute("data-bmi");
    document.getElementById("vBlood").innerText = button.getAttribute("data-blood");
    document.getElementById("vAdvice").innerText = button.getAttribute("data-advice");
    document.getElementById("vDoctor").innerText = button.getAttribute("data-doctor");
  });
});

// 🟡 Edit Prescription Modal Autofill
const editModal = document.getElementById("editPrescriptionModal");
if (editModal) {
  editModal.addEventListener("show.bs.modal", function (event) {
    const button = event.relatedTarget;
    document.getElementById("editPrescriptionId").value = button.getAttribute("data-id");
    document.getElementById("editMedication").value = button.getAttribute("data-medication");
    document.getElementById("editDosage").value = button.getAttribute("data-dosage");
    document.getElementById("editQuantity").value = button.getAttribute("data-quantity");
    document.getElementById("editNotes").value = button.getAttribute("data-notes");
  });
}


// Restore prescription
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-restore');
    if (!btn) return;

    const prescriptionId = btn.getAttribute('data-id');
    if (!prescriptionId) return;

    fetch("{{ route('doctor.write-prescriptions.restore') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "X-Requested-With": "XMLHttpRequest"
        },
        body: JSON.stringify({ prescription_id: prescriptionId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // remove row from archived table
            btn.closest('tr').remove();

            // append restored row back to main prescriptions table
            if (data.row_html) {
                document.querySelector("table tbody").insertAdjacentHTML('beforeend', data.row_html);
            }

            alert('Prescription restored successfully!');
        } else {
            alert(data.message || 'Failed to restore prescription.');
        }
    })
    .catch(err => {
        console.error(err);
        alert('An error occurred while restoring.');
    });
});

</script>


@endsection
