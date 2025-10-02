@extends('layouts.layout')
@section('title', 'Doctor Dashboard | MediCare')

@section('content')
@include('includes.doctornavbar')

<div class="container-fluid">
  <div class="row">
    @include('includes.doctorsidebar')

    <!-- ✅ Offcanvas Sidebar (mobile only) -->
    <div class="offcanvas offcanvas-start bg-primary text-white" tabindex="-1" id="doctorSidebar">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
      </div>
      <div class="offcanvas-body">
        <div class="text-center mb-4">
          <img src="{{ Auth::user()->profile_picture 
                        ? asset(Auth::user()->profile_picture) 
                        : asset('img/default.png') }}" 
              alt="Doctor Profile" 
              class="rounded-circle img-fluid mb-2"
              style="width:80px; height:80px; object-fit:cover;">
          <h6 class="text-white mb-0">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</h6>
          <small class="text-light">Doctor</small>
        </div>

        <ul class="nav flex-column gap-2">
          <li class="nav-item">
            <a href="{{ route('doctor.doctor-dashboard') }}" 
               class="nav-link text-white d-flex align-items-center {{ request()->routeIs('doctor.doctor-dashboard') ? 'active bg-info text-primary rounded' : '' }}">
              <i data-feather="activity" class="me-2 text-white"></i> Dashboard Overview
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('doctor.view-appointment') }}" 
               class="nav-link text-white d-flex align-items-center {{ request()->routeIs('doctor.view-appointment') ? 'active bg-info text-primary rounded' : '' }}">
              <i data-feather="calendar" class="me-2 text-success"></i> View Appointments
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('doctor.view-patients') }}" 
               class="nav-link text-white d-flex align-items-center {{ request()->routeIs('doctor.view-patients') ? 'active bg-info text-primary rounded' : '' }}">
              <i data-feather="users" class="me-2 text-danger"></i> View Patients
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('doctor.chat-call') }}" 
               class="nav-link text-white d-flex align-items-center {{ request()->routeIs('doctor.chat-call') ? 'active bg-info text-primary rounded' : '' }}">
              <i data-feather="message-circle" class="me-2 text-success"></i> Chat / Video Call
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('doctor.write-prescriptions') }}" 
               class="nav-link text-white d-flex align-items-center {{ request()->routeIs('doctor.write-prescriptions') ? 'active bg-info text-primary rounded' : '' }}">
              <i data-feather="edit" class="me-2 text-secondary"></i> Manage Prescription
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('doctor.my-profile') }}" 
               class="nav-link d-flex align-items-center {{ request()->routeIs('doctor.my-profile') ? 'active bg-white text-primary rounded' : 'text-white' }}">
              <i data-feather="user" class="me-2 text-danger"></i> My Profile
            </a>
          </li>
          <li class="nav-item mt-3">
            <form action="{{ route('logout') }}" method="POST">
              @csrf
              <button type="submit" class="btn btn-light d-flex text-primary align-items-center">
                <i data-feather="log-out" class="me-2"></i> Logout
              </button>
            </form>
          </li>
        </ul>
      </div>
    </div>

    <!-- Main Content -->
    <div class="col-12 col-md-9 col-lg-10 p-4 p-md-5">
      <h2 class="fw-bold">Chat / Video Call</h2>
      <p class="text-muted">Start or join video consultations for your approved appointments.</p>

      <div class="card shadow-sm border-0 mb-4 p-4">
        <h3 class="fw-bold mb-3">Upcoming Appointments</h3>

        <table class="table table-bordered align-middle text-center">
          <thead class="table-dark">
            <tr>
              <th>Patient</th>
              <th>Date & Time</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($appointments as $appt)
              @if($appt->status == 'approved')
                <tr>
                  <td>{{ $appt->patient?->firstname }} {{ $appt->patient?->lastname }}</td>
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
                    <button type="button"
        class="btn btn-primary start-call-btn"
        data-appointment-id="{{ $appt->id }}">
  📞 Call
</button>
                  </td>
                </tr>
              @endif
            @empty
              <tr>
                <td colspan="4">No meetings scheduled.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  const notifUrl = "{{ route('doctor.notifications.fetch') }}";
</script>
<script src="{{ asset('js/notification.js') }}"></script><!-- CSRF Token for fetch -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
document.addEventListener("click", function(e) {
  const btn = e.target.closest(".start-call-btn");
  if (!btn) return;

  const appointmentId = btn.dataset.appointmentId;

  fetch(`/doctor/start-call/${appointmentId}`, {
    method: "POST",
    headers: {
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
        "Accept": "application/json",
        "Content-Type": "application/json"
    },
    body: JSON.stringify({})
  })
  .then(res => res.json())
.then(data => {
    if (data.success) {
        console.log("✅ Meeting URL:", data.meeting_url);
        alert("Meeting started! Share link: " + data.meeting_url);
        // Optionally auto-open for doctor
        window.open(data.meeting_url, "_blank");
    } else {
        console.error("❌ Error:", data.error || "Unable to start call");
    }
})
  .catch(err => console.error("❌ Fetch error:", err));
}); // 👈 You were missing this closing
</script>
@endsection
