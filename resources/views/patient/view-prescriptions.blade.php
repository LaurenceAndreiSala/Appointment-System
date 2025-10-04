@extends('layouts.layout')
@section('title', 'View Prescriptions | MediCare')

@section('content')
@include('includes.patientNavbar')


<div class="container-fluid">
  <div class="row">

      @include('includes.patientsidebar')

<!-- ✅ Main Content -->
<div class="col-12 col-md-9 col-lg-10 p-4 p-md-5">
  <h2 class="fw-bold">My Prescriptions</h2>
  <p class="text-muted">Below are the prescriptions given by your doctors.</p>

  <div class="card shadow-sm border-0 p-4">
    @if($prescriptions->isEmpty())
      <p class="text-center text-muted">No prescriptions found.</p>
    @else
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle text-center mb-0">
          <thead class="table-dark">
            <tr>
              <th>Date</th>
              <th>Doctor</th>
              <th>Medication</th>
              <th>Dosage</th>
              <th>Notes</th>
            </tr>
          </thead>
          <tbody>
            @foreach($prescriptions as $prescription)
              <tr>
                <td data-label="Date">{{ $prescription->created_at->format('M d, Y h:i A') }}</td>
                <td data-label="Doctor">
                  {{ $prescription->appointment->doctor->firstname ?? '' }}
                  {{ $prescription->appointment->doctor->lastname ?? '' }}
                </td>
                <td data-label="Medication">{{ $prescription->medication }}</td>
                <td data-label="Dosage">{{ $prescription->dosage }}</td>
                <td data-label="Notes">{{ $prescription->notes ?? '-' }}</td>
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

</script>
@endsection
