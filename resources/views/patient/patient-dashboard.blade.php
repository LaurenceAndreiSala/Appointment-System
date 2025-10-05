@extends('layouts.layout')
@section('title', 'Patient Dashboard | MediCare')

@section('content')
@include('includes.patientNavbar')

<div class="container-fluid">
  <div class="row">

  @include('includes.patientsidebar')

    <!-- Main Content -->
<div class="col-12 col-md-9 col-lg-10 offset-lg-2  p-4 p-md-2">
  <!-- 👋 Welcome Banner -->
  <div class="d-flex align-items-center justify-content-between flex-wrap bg-light p-3 rounded-4 shadow-sm mb-4">
    <div class="d-flex align-items-center">
      <i class="fas fa-user text-success fa-1x me-3"></i>
      <span class="fw-bold fs-10 fs-md-10">
        Welcome Dr. {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}! 👋
      </span>
    </div>
    <small class="text-muted mt-2 mt-md-0">Patient Dashboard</small>
  </div>

  <!-- ✅ Quick Stats -->
  <div class="row g-4 mb-5">
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm text-center rounded-4 h-100 p-3 bg-primary text-white">
        <i class="fas fa-calendar-check fa-2x mb-2"></i>
        <h4 class="fw-bold mb-0">{{ $appointments->count() }}</h4>
        <small>Appointments</small>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm text-center rounded-4 h-100 p-3 bg-success text-white">
        <i class="fas fa-file-prescription fa-2x mb-2"></i>
        <h4 class="fw-bold mb-0">{{ $prescriptions->count() }}</h4>
        <small>Prescriptions</small>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm text-center rounded-4 h-100 p-3 bg-warning text-white">
        <i class="fas fa-user-md fa-2x mb-2"></i>
        <h4 class="fw-bold mb-0">{{ $doctorsCount ?? 0 }}</h4>
        <small>Doctors</small>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm text-center rounded-4 h-100 p-3 bg-info text-white">
        <i class="fas fa-bell fa-2x mb-2"></i>
        <h4 class="fw-bold mb-0">{{ $notificationsCount ?? 0 }}</h4>
        <small>Notifications</small>
      </div>
    </div>
  </div>

  <!-- ✅ My Appointments -->
  <div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body p-4">
      <h5 class="fw-bold mb-3 text-primary">
        <i class="fas fa-calendar-alt me-2"></i>My Recent Appointments
      </h5>

      @if($appointments->isEmpty())
        <p class="text-muted text-center mb-0">No appointments yet.</p>
      @else
        <div class="table-responsive">
          <table class="table table-hover align-middle text-center mb-0">
            <thead class="bg-primary text-white">
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
                    <strong>{{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y') }}</strong><br>
                    @if($appt->slot)
                      <small class="text-muted">
                        {{ \Carbon\Carbon::parse($appt->slot->start_time)->format('h:i A') }} - 
                        {{ \Carbon\Carbon::parse($appt->slot->end_time)->format('h:i A') }}
                      </small>
                    @else
                      <em>No slot assigned</em>
                    @endif
                  </td>
                  <td>Dr. {{ $appt->doctor->firstname }} {{ $appt->doctor->lastname }}</td>
                  <td>
                    <span class="badge px-3 py-2 rounded-pill
                      @if($appt->status == 'pending') bg-warning text-dark
                      @elseif($appt->status == 'approved') bg-success
                      @elseif($appt->status == 'denied') bg-danger
                      @else bg-secondary text-dark @endif">
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
  </div>

  <!-- ✅ My Prescriptions -->
  <div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body p-4">
      <h5 class="fw-bold mb-3 text-primary">
        <i class="fas fa-file-medical me-2"></i>My Recent Prescriptions
      </h5>

      @if($prescriptions->isEmpty())
        <p class="text-muted text-center mb-0">No prescriptions yet.</p>
      @else
        <div class="list-group">
          @foreach($prescriptions as $prescription)
            <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap rounded-3 shadow-sm mb-2">
              <div>
                <strong>{{ \Carbon\Carbon::parse($prescription->appointment->appointment_date)->format('M d, Y') }}</strong><br>
                <small>Prescribed by Dr. {{ $prescription->appointment->doctor->firstname }} {{ $prescription->appointment->doctor->lastname }}</small>
              </div>
              <a href="{{ route('patient.view-precription') }}" class="btn btn-sm btn-outline-primary rounded-pill shadow-sm">
                <i class="fas fa-eye me-1"></i> View
              </a>
            </div>
          @endforeach
        </div>
      @endif
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
  if (!ringtone) {
    ringtone = new Audio("{{ asset('sounds/ringtone.mp3') }}");
    ringtone.loop = true;
  }
  document.getElementById("callerName").innerText =
    `📞 Dr. ${data.appointment.doctor.firstname} is calling...`;

  const callModal = new bootstrap.Modal(document.getElementById("incomingCallModal"));
  callModal.show();
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
