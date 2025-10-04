@extends('layouts.layout')
@section('title', 'Patient Dashboard | MediCare')

@section('content')
@include('includes.patientNavbar')


<div class="container-fluid">
  <div class="row">

    @include('includes.patientsidebar')

    <!-- ✅ Main Content -->
    <div class="col-12 col-md-9 col-lg-10 p-4 p-md-5">
       <h2 class="fw-bold mb-4">My Profile</h2>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <form action="{{ route('patient.update-profile') }}" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
    @csrf

    <div class="text-center mb-4">
      <img src="{{ asset(Auth::user()->profile_picture ?? 'img/default.png') }}" 
           alt="Profile Picture" 
           class="rounded-circle mb-2"
           style="width:100px; height:100px; object-fit:cover;">
      <input type="file" name="profile_picture" class="form-control mt-2">
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">First Name</label>
      <input type="text" name="firstname" value="{{ old('firstname', Auth::user()->firstname) }}" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">Last Name</label>
      <input type="text" name="lastname" value="{{ old('lastname', Auth::user()->lastname) }}" class="form-control" required>
    </div>

      <div class="mb-3">
      <label class="form-label fw-bold">Contact Number</label>
      <input type="text" name="contact_no" value="{{ old('contact_no', Auth::user()->contact_no) }}" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">Email</label>
      <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="form-control" required>
    </div>

    <hr>

    <div class="mb-3">
      <label class="form-label fw-bold">New Password (optional)</label>
      <input type="password" name="password" class="form-control">
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">Confirm New Password</label>
      <input type="password" name="password_confirmation" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Update Profile</button>
  </form>
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
