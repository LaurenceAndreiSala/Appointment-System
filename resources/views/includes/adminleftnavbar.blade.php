    <!-- Sidebar (desktop version) -->
<aside class="col-md-3 col-lg-2 d-none d-lg-block bg-primary min-vh-100 p-3">
  <ul class="nav flex-column gap-2">
    <li class="nav-item">
      <a href="{{ route('admin.admin-dashboard') }}" 
         class="nav-link text-white d-flex align-items-center  {{ request()->routeIs('admin.admin-dashboard') ? 'active bg-info text-primary rounded' : '' }}">
        <i data-feather="activity" class="me-2  text-white"></i> Dashboard Overview
      </a>
    </li>
    <li class="nav-item">
      <a href="{{ route('admin.set-available-slots') }}" 
         class="nav-link text-white d-flex align-items-center  {{ request()->routeIs('admin.set-available-slots') ? 'active bg-info text-primary rounded' : '' }}">
        <i data-feather="activity" class="me-2  text-white"></i> Set Available Slot of the Doctor
      </a>
    </li>
    <li class="nav-item">
          <a href="{{ route('admin.view-appointment') }}" 
         class="nav-link text-white d-flex align-items-center  {{ request()->routeIs('admin.view-appointment') ? 'active bg-info text-primary rounded' : '' }}">
        <i data-feather="activity" class="me-2  text-white"></i> View all Appointments
      </a>
    </li>
    <li class="nav-item">
      <a href="{{ route('admin.create-doctors') }}" 
         class="nav-link text-white d-flex align-items-center  {{ request()->routeIs('admin.create-doctors') ? 'active bg-info text-primary rounded' : '' }}">
        <i data-feather="activity" class="me-2  text-white"></i> Manage Users
      </a>
    </li>
    <li class="nav-item">
      <a href="{{ route('admin.manage-notifications') }}"
               class="nav-link text-white d-flex align-items-center  {{ request()->routeIs('admin.manage-notifications') ? 'active bg-info text-primary rounded' : '' }}">
        <i data-feather="bell" class="me-2"></i> Manage Notifications
      </a>
    </li>
    <li class="nav-item">
      <a href="{{ route('admin.summary-report') }}" 
               class="nav-link text-white d-flex align-items-center  {{ request()->routeIs('admin.summary-report') ? 'active bg-info text-primary rounded' : '' }}">
        <i data-feather="bar-chart-2" class="me-2"></i> Summary Reports
      </a>
    </li>

    <!-- Logout (desktop sidebar) -->
    <li class="nav-item mt-3">
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-light d-flex align-items-center">
          <i data-feather="log-out" class="me-2"></i> Logout
        </button>
      </form>
    </li>
  </ul>
</aside>