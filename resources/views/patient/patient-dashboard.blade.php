@extends('layouts.layout')
@section('title', 'Patient Dashboard | MediCare')

@section('content')
@include('includes.patientNavbar')
@include('includes.patientsidebar')

<div class="container-fluid">
  <div class="row">

     <!-- ✅ Main Dashboard Content -->
    <div class="col-12 col-md-9 col-lg-10 p-4 p-md-5">
 <a class="navbar-brand d-flex align-items-center mb-3 h1 p-1 rounded shadow-sm" 
   href="{{ route('admin.admin-dashboard') }}" 
   style="background-color: #f8f9fa; padding: 5px 5px;">
  <i data-feather="user" class="text-primary me-2"></i>
  <span class="fw-bold fs-10 fs-md-10">
    Welcome Patient, {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}!
  </span>
</a>
            <div class="card shadow-sm border-0 mb-4 p-4">
        <h4 class="fw-bold mb-3">My Recent Appointments</h4>
        @if($appointments->isEmpty())
          <p class="text-muted">No appointments yet.</p>
        @else
          <div class="table-responsive">
  <table class="table table-striped align-middle">
    <thead class="table-light">
      <tr>
        <th>Date & Time</th>
        <th>Doctor</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      @foreach($appointments as $appt)
      <tr>
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
        <td>Dr. {{ $appt->doctor->firstname }} {{ $appt->doctor->lastname }}</td>
        <td>
          <span class="badge 
            @if($appt->status == 'pending') bg-warning 
            @elseif($appt->status == 'approved') bg-success 
            @elseif($appt->status == 'denied') bg-danger 
            @endif">
            {{ ucfirst($appt->status) }}
          </span>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

        @endif
      </div>

      <!-- ✅ Prescription Report -->
      <div class="card shadow-sm border-0 p-4">
        <h4 class="fw-bold mb-3">My Recent Prescriptions</h4>
        @if($prescriptions->isEmpty())
          <p class="text-muted">No prescriptions yet.</p>
        @else
          <ul class="list-group">
            @foreach($prescriptions as $prescription)
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                  <strong>{{ $prescription->appointment->appointment_date }}</strong> <br>
                  Prescribed by: Dr. {{ $prescription->appointment->doctor->firstname }} {{ $prescription->appointment->doctor->lastname }}
                </div>
                <a href="{{ route('patient.view-precription') }}" class="btn btn-sm btn-primary">View</a>
              </li>
            @endforeach
          </ul>
        @endif
      </div>
    </div>
  </div>
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
