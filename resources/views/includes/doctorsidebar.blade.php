<!-- Navigation -->
  <div class="container">
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <!-- Notification Bell inside navbar -->
        <li class="nav-item me-3 position-relative">
          <a href="javascript:void(0)" class="nav-link" id="notifBell">
            <i class="fas fa-bell"></i>
            @if($notificationCount > 0)
              <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                {{ $notificationCount }}
              </span>
            @endif
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Sidebar -->
<div class="col-md-3 col-lg-2 border-end min-vh-100 p-3 bg-primary position-fixed">
    <!-- Profile Section -->
  <div class="text-center mb-4 b">
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
         class="nav-link text-white d-flex align-items-center  {{ request()->routeIs('doctor.doctor-dashboard') ? 'active bg-info text-primary rounded' : '' }}">
        <i data-feather="activity" class="me-2  text-white"></i> Dashboard Overview
      </a>
    </li>

    <li class="nav-item">
      <a href="{{ route('doctor.view-appointment') }}" 
       class="nav-link text-white d-flex align-items-center  {{ request()->routeIs('doctor.view-appointment') ? 'active bg-info text-primary rounded' : '' }}">
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
        class="nav-link text-white d-flex align-items-center  {{ request()->routeIs('doctor.chat-call') ? 'active bg-info text-primary rounded' : '' }}">
        <i data-feather="message-circle" class="me-2 text-success"></i> Chat / Video Call
      </a>
    </li>

    <li class="nav-item">
      <a href="write-prescriptions" 
         class="nav-link text-white d-flex align-items-center  {{ request()->routeIs('doctor.write-prescriptions') ? 'active bg-info text-primary rounded' : '' }}">
        <i data-feather="edit" class="me-2 text-secondary"></i> Manage Prescription
      </a>
    </li>

     <li class="nav-item">
          <a href="{{ route('doctor.my-profile') }}"
                  class="nav-link text-white d-flex align-items-center  {{ request()->routeIs('doctor.my-profile') ? 'active bg-info text-primary rounded' : '' }}">
            <i data-feather="user" class="me-2 text-secondary"></i> My Profile
          </a>
        </li>

      <!-- Logout (mobile offcanvas) -->
      <li class="nav-item mt-3">
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-light d-flex text-primary align-items-center">
            <i data-feather="log-out" class="me-2"></i> Logout
          </button>
        </form>
    </li>
  </ul>
</div

