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
    <div class="col-md-9 col-lg-10 offset-md-3 offset-lg-2 p-4">
       <h2 class="fw-bold mb-4">My Doctor Profile</h2>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

   <form action="{{ route('doctor.update-profile') }}" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
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

<!-- ✅ Offcanvas Sidebar (mobile only) -->
<div class="offcanvas offcanvas-start bg-primary text-white" 
     tabindex="-1" 
     id="patientSidebar" 
     style="width: 70vw;">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">Patient Menu</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
  </div>

  <div class="offcanvas-body d-flex flex-column">
    <div class="text-center mb-4">
      <img src="{{ asset('./img/loloy.png') }}" 
           alt="Patient Profile" 
           class="rounded-circle img-fluid mb-2"
           style="width:80px; height:80px; object-fit:cover;">
      <h6 class="text-white mb-0">{{ Auth::user()->lastname }}</h6>
      <small class="text-light">Patient</small>
    </div>

    <ul class="nav flex-column gap-2">
      <li class="nav-item"><a href="{{ route('patient.patient-dashboard') }}" class="nav-link text-white"><i data-feather="activity" class="me-2"></i> Dashboard Overview</a></li>
      <li class="nav-item"><a href="{{ route('patient.book-appointment') }}" class="nav-link text-white"><i data-feather="calendar" class="me-2"></i> Book Appointment</a></li>
      <li class="nav-item"><a href="{{ route('patient.view-appointment') }}" class="nav-link text-white"><i data-feather="list" class="me-2"></i> My Appointments</a></li>
      <li class="nav-item"><a href="{{ route('patient.view-precription') }}" class="nav-link text-white"><i data-feather="file-text" class="me-2"></i> Prescriptions</a></li>
      <li class="nav-item"><a href="{{ route('patient.video-call') }}" class="nav-link text-white"><i data-feather="message-circle" class="me-2"></i> Chat / Video Call</a></li>
      <li class="nav-item"><a href="#" class="nav-link text-white"><i data-feather="user" class="me-2"></i> My Profile</a></li>
    </ul>

    <!-- ✅ Logout Button (mobile under My Profile) -->
    <form action="{{ route('logout') }}" method="POST" class="mt-2">
      @csrf
      <button type="submit" class="btn btn-light d-flex align-items-center justify-content-center">
        <i data-feather="log-out" class="me-2"></i> Logout
      </button>
    </form>
  </div>
</div>
<script>
  const notifUrl = "{{ route('doctor.notifications.fetch') }}";
</script>
<script src="{{ asset('js/notification.js') }}"></script>
<style>
  @media (max-width: 768px) {
    #patientSidebar {
      width: 70%;
    }
  }

  @media (max-width: 576px) {
    #patientSidebar {
      width: 95%;
    }
  }
</style>

@endsection
