
  <!-- Desktop Sidebar -->
  <div class="col-12 col-md-3 col-lg-2 bg-primary border-end min-vh-100 p-3 d-none d-lg-block">
    <div class="text-center mb-4">
      <img src="{{ Auth::user()->profile_picture 
                    ? asset(Auth::user()->profile_picture) 
                    : asset('img/default.png') }}" 
          alt="Patient Profile" 
          class="rounded-circle img-fluid mb-2"
          style="width:80px; height:80px; object-fit:cover;">
      <h6 class="text-white mb-0">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</h6>
      <small class="text-light">Patient</small>
    </div>

    <ul class="nav flex-column gap-2">
      <li class="nav-item"><a href="{{ route('patient.patient-dashboard') }}" class="nav-link {{ request()->routeIs('patient.patient-dashboard') ? 'active bg-white text-primary rounded' : 'text-white' }}"><i data-feather="activity" class="me-2"></i> Dashboard</a></li>
      <li class="nav-item"><a href="{{ route('patient.book-appointment') }}" class="nav-link {{ request()->routeIs('patient.book-appointment') ? 'active bg-white text-primary rounded' : 'text-white' }}"><i data-feather="calendar" class="me-2"></i> Book Appointment</a></li>
      <li class="nav-item"><a href="{{ route('patient.view-appointment') }}" class="nav-link {{ request()->routeIs('patient.view-appointment') ? 'active bg-white text-primary rounded' : 'text-white' }}"><i data-feather="list" class="me-2"></i> My Appointments</a></li>
      <li class="nav-item"><a href="{{ route('patient.view-precription') }}" class="nav-link {{ request()->routeIs('patient.view-precription') ? 'active bg-white text-primary rounded' : 'text-white' }}"><i data-feather="file-text" class="me-2"></i> Prescriptions</a></li>
      <li class="nav-item"><a href="{{ route('patient.video-call') }}" class="nav-link {{ request()->routeIs('patient.video-call') ? 'active bg-white text-primary rounded' : 'text-white' }}"><i data-feather="message-circle" class="me-2"></i> Chat / Video Call</a></li>
      <li class="nav-item"><a href="{{ route('patient.my-profile') }}" class="nav-link {{ request()->routeIs('patient.my-profile') ? 'active bg-white text-primary rounded' : 'text-white' }}"><i data-feather="user" class="me-2"></i> My Profile</a></li>
    </ul>

    <form action="{{ route('logout') }}" method="POST" class="mt-3">
      @csrf
      <button type="submit" class="btn btn-light w-100 d-flex align-items-center justify-content-center">
        <i data-feather="log-out" class="me-2"></i> Logout
      </button>
    </form>
  </div>

  <!-- Mobile Sidebar (Offcanvas) -->
  <div class="offcanvas offcanvas-start bg-primary text-white" id="patientSidebar" tabindex="-1">
    <div class="offcanvas-header">
    </div>

    <div class="offcanvas-body d-flex flex-column">
      <div class="text-center mb-4">
        <img src="{{ Auth::user()->profile_picture 
                      ? asset(Auth::user()->profile_picture) 
                      : asset('img/default.png') }}" 
             alt="Patient Profile" 
             class="rounded-circle img-fluid mb-2"
             style="width:80px; height:80px; object-fit:cover;">
        <h6 class="text-white mb-0">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</h6>
        <small class="text-light">Patient</small>
      </div>

      <ul class="nav flex-column gap-2">
        <li class="nav-item"><a href="{{ route('patient.patient-dashboard') }}" class="nav-link text-white"><i data-feather="activity" class="me-2"></i> Dashboard</a></li>
        <li class="nav-item"><a href="{{ route('patient.book-appointment') }}" class="nav-link text-white"><i data-feather="calendar" class="me-2"></i> Book Appointment</a></li>
        <li class="nav-item"><a href="{{ route('patient.view-appointment') }}" class="nav-link text-white"><i data-feather="list" class="me-2"></i> My Appointments</a></li>
        <li class="nav-item"><a href="{{ route('patient.view-precription') }}" class="nav-link text-white"><i data-feather="file-text" class="me-2"></i> Prescriptions</a></li>
        <li class="nav-item"><a href="{{ route('patient.video-call') }}" class="nav-link text-white"><i data-feather="message-circle" class="me-2"></i> Chat / Video Call</a></li>
        <li class="nav-item"><a href="{{ route('patient.my-profile') }}" class="nav-link text-white"><i data-feather="user" class="me-2"></i> My Profile</a></li>
      </ul>

      <form action="{{ route('logout') }}" method="POST" class="mt-3">
        @csrf
        <button type="submit" class="btn btn-light d-flex align-items-center justify-content-center">
          <i data-feather="log-out" class="me-2"></i> Logout
        </button>
      </form>
    </div>
  </div>


<style>
  @media (max-width: 768px) {
    #patientSidebar {
      width: 70%;
    }
  }
  @media (max-width: 576px) {
    #patientSidebar {
      width: 75%;
    }
  }
</style>
