@extends('layouts.layout')
@section('title', 'Patient Dashboard | MediCare')

@section('content')
@include('includes.patientNavbar')

<div class="container-fluid">
  <div class="row">
    @include('includes.patientsidebar')

    <!-- ✅ Main Content -->
    <div class="col-12 col-md-9 col-lg-10 p-4 p-md-5">
      <h2 class="fw-bold mb-2">💬 Chat / Video Call</h2>
      <p class="text-muted mb-4">Join your scheduled video consultations with your doctor.</p>

      <div class="card shadow-sm border-0 mb-4 p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
          <h3 class="fw-bold mb-2 mb-md-0">My Approved Appointments</h3>
        </div>

        <!-- ✅ Responsive Table Wrapper -->
        <div class="table-responsive rounded-3 shadow-sm">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-dark text-center">
              <tr>
                <th>Doctor</th>
                <th>Date</th>
                <th>Time</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @forelse($appointments as $appt)
                <tr>
                  <td class="fw-semibold">
                    Dr. {{ $appt->doctor?->firstname }} {{ $appt->doctor?->lastname }}
                  </td>
                  <td>
                    {{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y') }}
                  </td>
                  <td>
                    @if($appt->slot)
                      {{ \Carbon\Carbon::parse($appt->slot->start_time)->format('h:i A') }}
                      -
                      {{ \Carbon\Carbon::parse($appt->slot->end_time)->format('h:i A') }}
                    @else
                      <em class="text-muted">No slot assigned</em>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="text-muted py-4">
                    No approved meetings yet.
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
