<!-- ✅ Top Navbar (stays visible always) -->
<nav class="navbar navbar-light bg-white shadow-sm sticky-top" style="z-index: 1055;">
  <div class="container-fluid d-flex align-items-center">
    <div class="d-flex align-items-center">
      <!-- Hamburger (mobile only) -->
      <button class="btn d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar">
        <i data-feather="menu"></i>
      </button>
         <a class="navbar-brand d-flex align-items-center" style="margin-left: 10px;" href="#">
      <img class="clinic-logo" src="{{ asset('img/clinic-logo.png') }}" style="width: 30px; height: 30px; margin-right: 5px;"></i>
      <span class="fw-bold fs-4">MediCAL</span>
    </a>
    </div>
  </div>
</nav>