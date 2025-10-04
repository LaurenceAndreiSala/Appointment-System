@extends('layouts.layout')
@section('title', 'Doctor Dashboard | MediCare')

@section('content')
@include('includes.doctornavbar')
@include('includes.doctorsidebar')

<div class="container-fluid">
  <div class="row">

<!-- Main Content -->
    <div class="col-md-9 col-lg-10 offset-md-3 offset-lg-2 p-4">
<button type="button" class="btn btn-dark mb-3" data-bs-toggle="modal" data-bs-target="#archivedPrescriptionsModal">
  <i class="fas fa-archive"></i> View Archived Prescriptions
</button>

      <h3 class="fw-bold mb-3">Manage Prescriptions</h3>

          <!-- Write Prescription Modal -->
<div class="modal fade" id="writePrescriptionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content rounded-3 shadow">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Write Prescription</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="writePrescriptionForm" method="POST" action="{{ route('doctor.prescriptions.store') }}">
    @csrf
    <input type="hidden" name="appointment_id" id="prescriptionAppointmentId">

    <div class="mb-3">
        <label for="medication" class="form-label">Medication</label>
        <input type="text" class="form-control" name="medication" id="medication" required>
    </div>

    <div class="mb-3">
        <label for="dosage" class="form-label">Dosage</label>
        <input type="text" class="form-control" name="dosage" id="dosage" required>
    </div>

    <div class="mb-3">
        <label for="notes" class="form-label">Notes</label>
        <textarea class="form-control" name="notes" id="notes" rows="3"></textarea>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-success">Save Prescription</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
    </div>
</form>

    </div>
  </div>
</div>

<!-- View Prescription Modal -->
<div class="modal fade" id="viewPrescriptionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content rounded-3 shadow">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">View Prescription</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
    <div class="modal-body" id="viewPrescriptionContent">
    <p><strong>MediCAL</strong></p>
    <p><strong>Name:</strong> <span id=""></span></p>
    <p><strong>Address:</strong> <span id=""></span></p>
    <p><strong>Age:</strong> <span id=""></span></p>
    <p><strong>Gender:</strong> <span id=""></span></p>
    <p><strong>Date:</strong> <span id=""></span></p>
    <p><strong>RX</strong></span></p>
    <p><strong>Medication:</strong> <span id="viewMedication"></span></p>
    <p><strong>Dosage:</strong> <span id="viewDosage"></span></p>
    <p><strong>Notes:</strong> <span id="viewNotes"></span></p>
    <p><strong>Name:</strong> <span id=""></span></p>
    <p><strong>License No.</strong> <span id=""></span></p>
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

          <div class="table-responsive rounded-4 shadow-sm">
    <table class="table table-hover align-middle text-center mb-0">
      <thead class="table-dark">
          <tr>
            <th>Profile</th>
            <th>Patient</th>
            <th>Doctor</th>
            <th>Date & Time</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($appointments as $appt)
            <tr>
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
</td>              <td>
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
                <div class="d-flex justify-content-center gap-2">
              <button type="button" class="btn btn-sm btn-success"
        data-bs-toggle="modal" 
        data-bs-target="#writePrescriptionModal"
        data-id="{{ $appt->id }}">
    <i class="fas fa-edit"></i> Write Prescript
</button>

@if($appt->prescription)
  <button type="button"
          class="btn btn-sm btn-secondary btn-archive"
          data-id="{{ $appt->prescription->id }}">
    <i class="fas fa-archive"></i> Archive
  </button>
@else
  <button type="button" class="btn btn-sm btn-secondary" disabled title="No prescription to archive">
    <i class="fas fa-archive"></i> Archive
  </button>
@endif


<button type="button" class="btn btn-sm btn-primary"
        data-bs-toggle="modal" 
        data-bs-target="#viewPrescriptionModal"
        data-medication="{{ $appt->prescription->medication ?? 'N/A' }}"
        data-dosage="{{ $appt->prescription->dosage ?? 'N/A' }}"
        data-notes="{{ $appt->prescription->notes ?? 'N/A' }}">
    <i class="fas fa-eye"></i> View Prescript
</button>


                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5">No appointments found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
      <div class="mycard-content">
 </div>
  </div>
    </div>

<script>
  const notifUrl = "{{ route('doctor.notifications.fetch') }}";
</script>
<script src="{{ asset('js/notification.js') }}"></script>

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
    const viewModal = document.getElementById("viewPrescriptionModal");
    if (viewModal) {
      viewModal.addEventListener("show.bs.modal", function (event) {
          let button = event.relatedTarget;
          document.getElementById("viewMedication").innerText = button.getAttribute("data-medication");
          document.getElementById("viewDosage").innerText = button.getAttribute("data-dosage");
          document.getElementById("viewNotes").innerText = button.getAttribute("data-notes");
      });
    }
});

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
