@extends('layouts.layout')
@section('title', 'View Prescriptions | MediCare')

@section('content')
@include('includes.patientNavbar')


<div class="container-fluid">
  <div class="row">

      @include('includes.patientsidebar')

 <!-- ✅ Main Content -->
<div class="col-12 col-md-9 col-lg-10 offset-lg-2  p-4 p-md-2">
      <div class="bg-light rounded-4 shadow-sm p-4 mb-4 d-flex align-items-center">
    <i class="fas fa-capsules text-primary fa-2x me-3"></i>
        <h3 class="fw-bold mb-0 text-dark">My Prescriptions</h3>
      </div>

      <p class="text-muted mb-4">Below are the prescriptions given by your doctors.</p>

  <!-- ✅ Filter & Search Controls -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
  <div class="d-flex gap-2">
      <input type="text" id="searchInput" class="form-control" placeholder="🔍 Search...">
  </div>
</div>

  <div class="card shadow-sm border-0 p-4">
    @if($prescriptions->isEmpty())
      <p class="text-center text-muted">No prescriptions found.</p>
    @else
      <div class="table-responsive">
        <table id="callsTable" class="table table-bordered table-striped align-middle text-center mb-0">
          <thead class="table-dark">
            <tr>
              <th>Date</th>
              <th>Doctor</th>
              <th>License No.</th>
              <th>Specialization</th>
              <th>Medication</th>
              <th>Dosage</th>
              <th>Notes</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="appointmentsTable">
            @foreach($prescriptions as $prescription)
              <tr>
                <td data-label="Date">{{ now()->setTimezone('Asia/Manila')->format('M d, Y h:i A') }}</td>
                <td data-label="Doctor">
                  {{ $prescription->appointment->doctor->firstname ?? '' }}
                  {{ $prescription->appointment->doctor->lastname ?? '' }}
                </td>
                <td data-label="Licence #">{{ $prescription->appointment->doctor->license_no ?? '' }}</td>
                <td data-label="Specialization">{{ $prescription->appointment->doctor->specialization ?? '' }}</td>
                <td data-label="Medication">{{ $prescription->medication }} ({{ $prescription->quantity }})</td>
                <td data-label="Dosage">{{ $prescription->dosage }}</td>
                <td data-label="Notes">{{ $prescription->notes ?? '-' }}</td>
                <td>  
<a href="{{ route('patient.prescriptions.download', $prescription->id) }}"
     class="btn btn-outline-primary btn-sm">
     <i class="fas fa-download me-1"></i> Download
  </a>
</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
</div>

<!-- ✅ Call Popup Modal -->
<div class="modal fade" id="incomingCallModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-3 border-0 shadow-lg">
      <h5 id="callerName" class="fw-bold mt-2"></h5>
      <div class="d-flex justify-content-center mt-3 mb-2">
        <button id="acceptCall" class="btn btn-success me-3 px-4">
          <i class="fas fa-phone-alt me-1"></i> Accept
        </button>
        <button id="rejectCall" class="btn btn-danger px-4">
          <i class="fas fa-phone-slash me-1"></i> Reject
        </button>
      </div>
    </div>
  </div>
</div>

<style>
@media (max-width: 768px) {
    table thead {
        display: none;
    }
    table tr {
        display: block;
        margin-bottom: 1rem;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 0.5rem;
        background: #fff;
    }
    table td {
        display: block;
        text-align: right;
        font-size: 0.9rem;
        border: none !important;
        border-bottom: 1px solid #f0f0f0;
    }
    table td:last-child {
        border-bottom: none;
    }
    table td::before {
        content: attr(data-label);
        float: left;
        font-weight: bold;
        text-transform: uppercase;
        color: #495057;
    }
}
</style>

<script>
   const fetchNotificationsUrl = "{{ route('patient.notifications.fetch') }}";
</script>
<script src="{{ asset('js/notificationcall.js') }}"></script>

{{-- ✅ Ringing Popup JS --}}
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>
const userId = "{{ Auth::id() }}";

const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
  cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
  forceTLS: true
});

const channel = pusher.subscribe("appointments." + userId);

let ringtone;

channel.bind("App\\Events\\CallStarted", function(data) {
  // create ringtone only once
  if (!ringtone) {
    ringtone = new Audio("{{ asset('sounds/ringtone.mp3') }}");
    ringtone.loop = true;
  }

  // show modal
  document.getElementById("callerName").innerText =
    `📞 Dr. ${data.appointment.doctor.firstname} is calling...`;

  const callModal = new bootstrap.Modal(document.getElementById("incomingCallModal"));
  callModal.show();

  // play ringtone when modal buttons are clicked
  ringtone.play().catch(err => console.warn("Autoplay blocked until user interacts"));

  document.getElementById("acceptCall").onclick = () => {
    ringtone.pause();
    callModal.hide();
    window.open(data.appointment.meeting_url, "_blank");
  };

  document.getElementById("rejectCall").onclick = () => {
    ringtone.pause();
    callModal.hide();
    alert("❌ You rejected the call.");
  };
});

document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById("searchInput");
    const tableBody = document.querySelector("#callsTable tbody");

    function filterTable() {
        const searchValue = searchInput.value.toLowerCase();
        const rows = Array.from(tableBody.querySelectorAll("tr"));
        let visibleCount = 0;

        rows.forEach(row => {
            // Skip "No meetings found" row
            if (row.classList.contains("no-data-row")) return;

            const patientCell = row.cells[0]; // 1st column: Patient
            const dateCell = row.cells[1];    // 2nd column: Date & Time (adjust if different)

            if (!patientCell || !dateCell) return;

            const patientName = patientCell.textContent.toLowerCase();
            const meetingDate = dateCell.textContent.toLowerCase();

            const matchesSearch =
                patientName.includes(searchValue) ||
                meetingDate.includes(searchValue);

            row.style.display = matchesSearch ? "" : "none";

            if (matchesSearch) visibleCount++;
        });

        // Show/hide "No meetings found" row
        const noDataRow = tableBody.querySelector(".no-data-row");
        if (noDataRow) {
            noDataRow.style.display = visibleCount === 0 ? "" : "none";
        }
    }

    searchInput.addEventListener("input", filterTable);
});
</script>
@endsection
