@extends('layouts.layout')
@section('title', 'Admin Dashboard | MediCare {{ Auth::user()->name }}')

@section('content')

<!-- Top Navbar -->
<nav class="navbar navbar-light bg-white shadow-sm sticky-top">
  <div class="container-fluid d-flex align-items-center">
    <!-- Sidebar Toggle (mobile only) + Brand -->
    <div class="d-flex align-items-center">
      <!-- Hamburger (mobile only) -->
      <button class="btn d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar">
        <i data-feather="menu"></i>
      </button>
      <a class="navbar-brand d-flex align-items-center mb-0 h1" href="#">
        <i data-feather="shield" class="text-primary me-2"></i>
        <span class="fw-bold fs-6 fs-md-5">Welcome {{ Auth::user()->lastname }}!</span>
      </a>
    </div>
  </div>
</nav>


<div class="container-fluid">
  <div class="row">
    @include('includes.adminleftnavbar')

    <main class="col-md-9 col-lg-10 p-5">
      <div class="card shadow-sm border-0 mb-4 p-4">
        <h3 class="fw-bold mb-3">Manage Notifications</h3>

        <div class="table-responsive">
          <table class="table table-bordered table-striped align-middle text-center">
            <thead class="table-dark">
              <tr>
                <th>ID</th>
                <th>User</th>
                <th>Message</th>
                <th>Sent At</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($notifications as $notif)
                <tr>
                  <td>{{ $notif->id }}</td>
                  <td>{{ $notif->user->lastname ?? 'N/A' }}</td>
                  <td>{{ $notif->message }}</td>
                  <td>{{ $notif->created_at ? $notif->created_at->format('M d, Y H:i') : '-' }}</td>
                  <td>
                    @if($notif->read)
                      <span class="badge bg-success">Read</span>
                    @else
                      <span class="badge bg-warning text-dark">Unread</span>
                    @endif
                  </td>
                  <td>
                    @if(!$notif->read)
                      <form action="{{ route('admin.notifications.read', $notif->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-primary">Mark as Read</button>
                      </form>
                    @endif

                    <form action="{{ route('admin.notifications.delete', $notif->id) }}" method="POST" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6">No notifications available</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="mt-3">
          {{ $notifications->links() }}
        </div>
      </div>
    </main>
  </div>
</div>

  </div>
    </div>

<!-- Sidebar (mobile offcanvas) -->
<div class="offcanvas offcanvas-start bg-primary text-white custom-offcanvas" tabindex="-1" id="adminSidebar">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">Admin Menu</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column">
    <ul class="nav flex-column gap-2 flex-grow-1">
      <li class="nav-item"><a href="{{ route('admin.admin-dashboard') }}" class="nav-link text-white"><i data-feather="activity" class="me-2"></i> Dashboard Overview</a></li>
      <li class="nav-item"><a href="{{ route('admin.set-available-slots') }}" class="nav-link text-white"><i data-feather="clock" class="me-2"></i> Set Available Slot</a></li>
      <li class="nav-item"><a href="{{ route('admin.view-appointment') }}" class="nav-link text-white"><i data-feather="list" class="me-2"></i> View Appointments</a></li>
      <li class="nav-item"><a href="{{ route('admin.create-doctors') }}" class="nav-link text-white"><i data-feather="users" class="me-2"></i> Manage Users</a></li>
      <li class="nav-item"><a href="#" class="nav-link text-white"><i data-feather="bell" class="me-2"></i> Notifications</a></li>
      <li class="nav-item"><a href="#" class="nav-link text-white"><i data-feather="bar-chart-2" class="me-2"></i> Reports</a></li>

      <!-- Logout (mobile offcanvas) -->
      <li class="nav-item mt-3">
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-light d-flex align-items-center">
            <i data-feather="log-out" class="me-2"></i> Logout
          </button>
        </form>
      </li>
    </ul>
  </div>
</div>

<!-- Custom CSS -->
<style>
  /* Half-width sidebar */
  .custom-offcanvas {
    width: 70% !important; /* 👈 half of the screen */
    max-width: 400px; /* optional: limit sa dako nga screen */
  }
</style>

@endsection
