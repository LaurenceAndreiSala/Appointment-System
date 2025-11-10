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

<!-- Desktop Sidebar -->
<div id="sidebar" class="col-md-3 col-lg-2 border-end min-vh-100 p-3 bg-primary d-none d-lg-block position-fixed">
  <div class="text-center mb-4">
    <img src="{{ Auth::user()->profile_picture ? asset(Auth::user()->profile_picture) : asset('img/default.png') }}"
         alt="Doctor Profile"
         class="rounded-circle img-fluid mb-2"
         style="width:80px; height:80px; object-fit:cover;">
    <h6 class="text-white mb-0">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</h6>
    <small class="text-light">Doctor</small>
  </div>

  <ul class="nav flex-column gap-2">
    <li class="nav-item">
            <a href="{{ route('doctor.doctor-dashboard') }}" 
               class="nav-link d-flex align-items-center {{ request()->routeIs('doctor.doctor-dashboard') ? 'active bg-white text-primary fw-bold rounded shadow-sm' : 'text-white' }}">
              <i data-feather="activity" class="me-2"></i> Dashboard Overview
            </a>
          </li>
             <li class="nav-item">
            <a href="{{ route('doctor.write-prescriptions') }}" 
               class="nav-link d-flex align-items-center {{ request()->routeIs('doctor.write-prescriptions') ? 'active bg-white text-primary fw-bold rounded shadow-sm' : 'text-white' }}">
               <i data-feather="calendar" class="me-2"></i> View Appointment
            </a>
          </li>
          <!-- <li class="nav-item">
            <a href="{{ route('doctor.view-appointment') }}" 
               class="nav-link d-flex align-items-center {{ request()->routeIs('doctor.view-appointment') ? 'active bg-white text-primary fw-bold rounded shadow-sm' : 'text-white' }}">
              <i data-feather="calendar" class="me-2"></i> View Appointments
            </a>
          </li> -->
          <li class="nav-item">
            <a href="{{ route('doctor.view-patients') }}" 
               class="nav-link  d-flex align-items-center {{ request()->routeIs('doctor.view-patients') ? 'active bg-white text-primary fw-bold rounded shadow-sm' : 'text-white' }}">
              <i data-feather="users" class="me-2"></i> View Patients
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('doctor.chat-call') }}" 
               class="nav-link d-flex align-items-center {{ request()->routeIs('doctor.chat-call') ? 'active bg-white text-primary fw-bold rounded shadow-sm' : 'text-white' }}">
              <i data-feather="message-circle" class="me-2"></i> Chat / Video Call
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('doctor.my-profile') }}" 
               class="nav-link d-flex align-items-center {{ request()->routeIs('doctor.my-profile') ? 'active bg-white text-primary fw-bold rounded shadow-sm' : 'text-white' }}">
              <i data-feather="user" class="me-2"></i> My Profile
            </a>
          </li>
         <!-- Logout -->
      <li class="nav-item mt-3">
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="nav-link d-flex align-items-center 'active bg-white text-primary fw-bold rounded shadow-sm' : 'text-white' }}">
            <i data-feather="log-out" class="me-2"></i> Logout
          </button>
        </form>
      </li>
  </ul>
</div>

<!-- ✅ Offcanvas Sidebar (mobile only) -->
<div class="offcanvas offcanvas-start bg-primary text-white"
     tabindex="-1"
     id="doctorSidebar"
     data-bs-scroll="true"
     data-bs-backdrop="false"
     style="top: 57px; height: calc(100vh - 30px);">
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
               class="nav-link d-flex align-items-center {{ request()->routeIs('doctor.doctor-dashboard') ? 'active bg-white text-primary fw-bold rounded shadow-sm' : 'text-white' }}">
              <i data-feather="activity" class="me-2"></i> Dashboard Overview
            </a>
          </li>
           <li class="nav-item">
            <a href="{{ route('doctor.write-prescriptions') }}" 
               class="nav-link d-flex align-items-center {{ request()->routeIs('doctor.write-prescriptions') ? 'active bg-white text-primary fw-bold rounded shadow-sm' : 'text-white' }}">
               <i data-feather="calendar" class="me-2"></i> View Appointment
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('doctor.view-patients') }}" 
               class="nav-link  d-flex align-items-center {{ request()->routeIs('doctor.view-patients') ? 'active bg-white text-primary fw-bold rounded shadow-sm' : 'text-white' }}">
              <i data-feather="users" class="me-2"></i> View Patients
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('doctor.chat-call') }}" 
               class="nav-link d-flex align-items-center {{ request()->routeIs('doctor.chat-call') ? 'active bg-white text-primary fw-bold rounded shadow-sm' : 'text-white' }}">
              <i data-feather="message-circle" class="me-2"></i> Chat / Video Call
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('doctor.my-profile') }}" 
               class="nav-link d-flex align-items-center {{ request()->routeIs('doctor.my-profile') ? 'active bg-white text-primary fw-bold rounded shadow-sm' : 'text-white' }}">
              <i data-feather="user" class="me-2"></i> My Profile
            </a>
          </li>
           <li class="nav-item mt-3">
            <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="nav-link d-flex align-items-center 'active bg-white text-primary fw-bold rounded shadow-sm' : 'text-white' }}">
            <i data-feather="log-out" class="me-2"></i> Logout
          </button>
        </form>
        </ul>
      </div>
    </div>


<style>
  @media (max-width: 576px) {
    #doctorSidebar {
      width: 70%;
    }
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const offcanvasEl = document.getElementById('doctorSidebar');
    const bsOffcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl);

    // 1️⃣ Close offcanvas if window is resized to desktop
    function handleResize() {
      if (window.innerWidth >= 992) { // lg breakpoint
        bsOffcanvas.hide();
      }
    }
    window.addEventListener('resize', handleResize);

    // 2️⃣ Close offcanvas when clicking outside (mobile only)
    document.addEventListener('click', function (e) {
      if (window.innerWidth < 992) {
        if (!offcanvasEl.contains(e.target) && !e.target.closest('[data-bs-toggle="offcanvas"]')) {
          bsOffcanvas.hide();
        }
      }
    });

    // Optional: close offcanvas when a link inside is clicked
    offcanvasEl.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        if (window.innerWidth < 992) bsOffcanvas.hide();
      });
    });
  });
</script>
