<!-- Sidebar (desktop version) -->
<aside class="d-none d-lg-block bg-primary mt-15 p-3 text-white vh-100 position-fixed  start-0 col-lg-2">
  <ul class="nav flex-column gap-2">
    <li class="nav-item">
        <a href="{{ route('admin.admin-dashboard') }}" 
           class="nav-link d-flex align-items-center {{ request()->routeIs('admin.admin-dashboard') ? 'active bg-white text-primary fw-bold rounded shadow-sm' : 'text-white' }}">
          <i data-feather="activity" class="me-2"></i> Dashboard Overview
        </a>
      </li>

      <li class="nav-item">
        <a href="{{ route('admin.set-available-slots') }}" 
           class="nav-link d-flex align-items-center {{ request()->routeIs('admin.set-available-slots') ? 'active bg-white text-primary fw-bold rounded shadow-sm' : 'text-white' }}">
          <i data-feather="clock" class="me-2"></i> Set Available Slot
        </a>
      </li>

      <li class="nav-item">
        <a href="{{ route('admin.view-appointment') }}" 
           class="nav-link  d-flex align-items-center {{ request()->routeIs('admin.view-appointment') ? 'active bg-white text-primary fw-bold rounded shadow-sm' : 'text-white' }}">
          <i data-feather="list" class="me-2"></i> View Appointments
        </a>
      </li>

      <li class="nav-item">
        <a href="{{ route('admin.create-doctors') }}" 
           class="nav-link d-flex align-items-center {{ request()->routeIs('admin.create-doctors') ? 'active bg-white text-primary fw-bold rounded shadow-sm' : 'text-white' }}">
          <i data-feather="users" class="me-2"></i> Manage Users
        </a>
      </li>

       <li class="nav-item">
       <a href="{{ route('admin.manage-notifications') }}" 
         class="nav-link d-flex align-items-center {{ request()->routeIs('admin.manage-notifications') ? 'active bg-white text-primary fw-bold rounded shadow-sm' : 'text-white' }}">
        <i data-feather="bell" class="me-2"></i> Manage Notifications
      </a>
      </li>

      <li class="nav-item">
        <a href="{{ route('admin.summary-report') }}" 
         class="nav-link  d-flex align-items-center {{ request()->routeIs('admin.summary-report') ? 'active bg-white text-primary fw-bold rounded shadow-sm' : 'text-white' }}">
        <i data-feather="bar-chart-2" class="me-2"></i> Summary Reports
      </a>
      </li>

      <!-- Logout -->
      <li class="nav-item mt-3">
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-light d-flex align-items-center w-50">
            <i data-feather="log-out" class="me-2"></i> Logout
          </button>
        </form>
    </li>
  </ul>
</aside>

<!-- ✅ Sidebar (mobile offcanvas, below navbar) -->
<div class="offcanvas offcanvas-start bg-primary text-white custom-offcanvas" tabindex="-1" id="adminSidebar" data-bs-scroll="true" data-bs-backdrop="false">
  <div class="offcanvas-header border-bottom border-light">
    <h5 class="offcanvas-title fw-bold" style="margin-top: 50px;">Admin Menu</h5>
  </div>

  <div class="offcanvas-body d-flex flex-column">
    <ul class="nav flex-column gap-2 flex-grow-1">
      <li class="nav-item">
        <a href="{{ route('admin.admin-dashboard') }}" 
           class="nav-link d-flex align-items-center {{ request()->routeIs('admin.admin-dashboard') ? 'active bg-white text-primary fw-bold rounded shadow-sm' : 'text-white' }}">
          <i data-feather="activity" class="me-2"></i> Dashboard Overview
        </a>
      </li>

      <li class="nav-item">
        <a href="{{ route('admin.set-available-slots') }}" 
           class="nav-link d-flex align-items-center {{ request()->routeIs('admin.set-available-slots') ? 'active bg-white text-primary fw-bold rounded shadow-sm' : 'text-white' }}">
          <i data-feather="clock" class="me-2"></i> Set Available Slot
        </a>
      </li>

      <li class="nav-item">
        <a href="{{ route('admin.view-appointment') }}" 
           class="nav-link  d-flex align-items-center {{ request()->routeIs('admin.view-appointment') ? 'active bg-white text-primary fw-bold rounded shadow-sm' : 'text-white' }}">
          <i data-feather="list" class="me-2"></i> View Appointments
        </a>
      </li>

      <li class="nav-item">
        <a href="{{ route('admin.create-doctors') }}" 
           class="nav-link d-flex align-items-center {{ request()->routeIs('admin.create-doctors') ? 'active bg-white text-primary fw-bold rounded shadow-sm' : 'text-white' }}">
          <i data-feather="users" class="me-2"></i> Manage Users
        </a>
      </li>

       <li class="nav-item">
       <a href="{{ route('admin.manage-notifications') }}" 
         class="nav-link d-flex align-items-center {{ request()->routeIs('admin.manage-notifications') ? 'active bg-white text-primary fw-bold rounded shadow-sm' : 'text-white' }}">
        <i data-feather="bell" class="me-2"></i> Manage Notifications
      </a>
      </li>

      <li class="nav-item">
        <a href="{{ route('admin.summary-report') }}" 
         class="nav-link  d-flex align-items-center {{ request()->routeIs('admin.summary-report') ? 'active bg-white text-primary fw-bold rounded shadow-sm' : 'text-white' }}">
        <i data-feather="bar-chart-2" class="me-2"></i> Summary Reports
      </a>
      </li>

      <!-- Logout -->
      <li class="nav-item mt-3">
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-light d-flex align-items-center w-50">
            <i data-feather="log-out" class="me-2"></i> Logout
          </button>
        </form>
      </li>
    </ul>
  </div>
</div>

<style>
  @media (max-width: 576px) {
    #adminSidebar {
      width: 70%;
    }
  }
</style>