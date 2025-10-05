
  <!-- Desktop Sidebar -->
<div class="d-none d-lg-block bg-primary border-end p-3" 
     style="position: fixed; top: 10; left: 0; height: 100vh; width: 16.6667%; overflow: hidden;">
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
     id="patientSidebar"
     data-bs-scroll="true"
     data-bs-backdrop="false"
     style="top: 57px; height: calc(100vh - 30px);">
      <div class="offcanvas-body">
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
          <li class="nav-item">
            <a href="{{ route('patient.patient-dashboard') }}" 
               class="nav-link d-flex align-items-center {{ request()->routeIs('patient.patient-dashboard') ? 'active bg-white text-primary fw-bold rounded shadow-sm' : 'text-white' }}">
              <i data-feather="activity" class="me-2"></i> Dashboard Overview
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('patient.book-appointment') }}" 
               class="nav-link d-flex align-items-center {{ request()->routeIs('patient.book-appointment') ? 'active bg-white text-primary fw-bold rounded shadow-sm' : 'text-white' }}">
              <i data-feather="calendar" class="me-2"></i> Book Appointment
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('patient.view-appointment') }}" 
               class="nav-link  d-flex align-items-center {{ request()->routeIs('patient.view-appointment') ? 'active bg-white text-primary fw-bold rounded shadow-sm' : 'text-white' }}">
              <i data-feather="list" class="me-2"></i> My Appointments
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('patient.video-call') }}" 
               class="nav-link d-flex align-items-center {{ request()->routeIs('patient.video-call') ? 'active bg-white text-primary fw-bold rounded shadow-sm' : 'text-white' }}">
              <i data-feather="message-circle" class="me-2"></i> Chat / Video Call
            </a>
          </li>
           <li class="nav-item">
            <a href="{{ route('patient.view-precription') }}" 
               class="nav-link d-flex align-items-center {{ request()->routeIs('patient.view-precription') ? 'active bg-white text-primary fw-bold rounded shadow-sm' : 'text-white' }}">
              <i data-feather="edit" class="me-2"></i> Prescriptions
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('patient.my-profile') }}" 
               class="nav-link d-flex align-items-center {{ request()->routeIs('patient.my-profile') ? 'active bg-white text-primary fw-bold rounded shadow-sm' : 'text-white' }}">
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
    </div>

<style>
  @media (max-width: 576px) {
    #patientSidebar {
      width: 70%;
    }
  }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const offcanvasEls = [
    document.getElementById('doctorSidebar'),
    document.getElementById('adminSidebar'),
    document.getElementById('patientSidebar')
  ];

  offcanvasEls.forEach(offcanvasEl => {
    if (!offcanvasEl) return; // skip if not on this page
    const bsOffcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl);

    // 1️⃣ Close offcanvas if window resized to desktop
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

    // 3️⃣ Close offcanvas when a link inside is clicked (mobile)
    offcanvasEl.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        if (window.innerWidth < 992) bsOffcanvas.hide();
      });
    });
  });
});
</script>
