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

<!-- ✅ Main Content -->
    <div class="col-md-9 col-lg-10 p-4">
      <!-- Stats Cards -->
      <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-lg-4">
          <div class="card text-center shadow-sm border-0 bg-primary text-white">
            <div class="card-body">
              <i class="fas fa-calendar-check fa-2x mb-2"></i>
              <h2 class="fw-bold p-4">{{ $appointmentCount }}</h2>
              <p class="mb-0">Today’s Appointments</p>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card text-center shadow-sm border-0 bg-success text-white">
            <div class="card-body">
              <i class="fas fa-procedures fa-2x mb-2"></i>
              <h2 class="fw-bold p-4">{{ $patientCount }}</h2>
              <p class="mb-0">Total Patients</p>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card text-center shadow-sm border-0 bg-warning text-white">
            <div class="card-body">
              <i class="fas fa-prescription-bottle-alt fa-2x mb-2"></i>
              <h2 class="fw-bold p-4">{{ $prescriptionsCount ?? 0 }}</h2>
              <p class="mb-0">Prescriptions Written</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Appointments Table -->
      <div class="card shadow-sm border-0 mb-4 p-4">
        <h3 class="fw-bold mb-3">View All Appointments</h3>
        <div class="table-responsive">
          <table class="table table-bordered table-striped align-middle text-center">
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
</td>
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
                    <div class="d-flex justify-content-center gap-2">
                      <form action="{{ route('doctor.appointments.approve', $appt->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                      </form>
                      <form action="{{ route('doctor.view-appointment.deny', $appt->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger">Deny</button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6">No appointments found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
  const notifUrl = "{{ route('doctor.notifications.fetch') }}";
</script>
<script src="{{ asset('js/notification.js') }}"></script>

<!-- Sidebar Slide CSS -->
<style>
  #sidebar {
    transition: transform 0.3s ease-in-out;
  }
  @media (max-width: 991.98px) { /* Bootstrap lg breakpoint */
    #sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: 75%;
      height: 100%;
      z-index: 1050;
      transform: translateX(-100%);
    }
    #sidebar.active {
      transform: translateX(0);
    }
  }
</style>

<!-- JS -->
<script>
const sidebar = document.getElementById('sidebar');
const mainContent = document.getElementById('mainContent');
const toggleBtn = document.getElementById('sidebarToggle');
const sidebarClose = document.getElementById('sidebarClose');

// Hamburger toggle (mobile)
toggleBtn.addEventListener('click', () => {
  sidebar.style.transform = 'translateX(0)';
});

// Close button (mobile)
sidebarClose.addEventListener('click', () => {
  sidebar.style.transform = 'translateX(-100%)';
});

// Click outside to close sidebar (mobile)
document.addEventListener('click', function(e){
  if(window.innerWidth < 992){
    if(!sidebar.contains(e.target) && !toggleBtn.contains(e.target)){
      sidebar.style.transform = 'translateX(-100%)';
    }
  }
});

// Adjust main content margin based on sidebar (desktop)
function handleResize() {
  if(window.innerWidth >= 992){
    sidebar.style.transform = 'translateX(0)';
    mainContent.style.marginLeft = '250px'; // match sidebar width
  } else {
    sidebar.style.transform = 'translateX(-100%)';
    mainContent.style.marginLeft = '0';
  }
}

window.addEventListener('resize', handleResize);
window.addEventListener('load', handleResize);
</script>

@endsection

