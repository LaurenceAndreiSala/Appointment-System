<!-- ✅ Top Navbar -->
<nav class="navbar navbar-light bg-white shadow-sm sticky-top">
  <div class="container-fluid d-flex align-items-center">
    <!-- Sidebar Toggle (mobile only) + Brand -->
    <div class="d-flex align-items-center">
      <!-- Hamburger (mobile only) -->
      <button class="btn d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#doctorSidebar">
        <i data-feather="menu"></i>
      </button>
        <a class="navbar-brand d-flex align-items-center" style="margin-left: 15px;" href="#">
      <i class="fas fa-heartbeat text-primary me-2"></i>
      <span class="fw-bold fs-4">MediCAL</span>
    </a>
    </div>

    <!-- Notifications -->
    <ul class="navbar-nav ms-auto align-items-lg-center">
  <li class="nav-item me-3 position-relative">
    <a href="javascript:void(0)" class="nav-link" id="notifBell">
      <i class="fas fa-bell"></i>
      <span id="notifCount" class="badge bg-danger position-absolute top-0 start-100 translate-middle"></span>
    </a>
  </li>
</ul>
  </div>
</nav>

<!-- ✅ Notification Dropdown -->
<div id="notifDropdown" 
     style="display:none; position:absolute; top:60px; right:20px; background:#fff; border:1px solid #ccc; 
            border-radius:5px; padding:10px; width:280px; z-index:1000; box-shadow:0 2px 5px rgba(0,0,0,0.15);">
  <h5 style="margin:0 0 10px 0;">Notifications</h5>
  <ul id="notifList" 
      style="list-style:none; padding:0; margin:0; max-height:250px; overflow-y:auto;">
    <li class="list-group-item text-muted small">Loading...</li>
  </ul>
</div>