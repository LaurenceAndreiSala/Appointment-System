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

    <!-- Main Content -->
    <main class="col-md-9 col-lg-10 p-5">
      <div class="card shadow-sm border-0 mb-4 p-4">
         <h3 class="fw-bold mb-3">Create Doctor Account</h3>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('doctors.store') }}">
      @csrf

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">First Name</label>
          <input type="text" name="firstname" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Last Name</label>
          <input type="text" name="lastname" class="form-control" required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Confirm Password</label>
          <input type="password" name="password_confirmation" class="form-control" required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Contact Number</label>
          <input type="text" name="contact_no" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Address</label>
          <input type="text" name="address" class="form-control" required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Birth Date</label>
          <input type="date" name="birth_date" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Gender</label>
          <select name="gender" class="form-select" required>
            <option value="">-- Select Gender --</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
          </select>
        </div>
      </div>

      <div class="row mb-3 d-none">
        <div class="col-md-6">
          <label class="form-label">Status</label>
          <select name="status" class="form-select" required>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>
      </div>

      <button type="submit" class="btn btn-primary">Create Doctor</button>
    </form>
  </div
  </div>
  <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle text-center">
      <thead class="table-dark">
        <tr>
          <th>Profile</th>
          <th>Name</th>
          <th>Email</th>
          <th>Contact</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($doctors as $doctor)
          <tr>
            <!-- Profile Picture -->
            <td>
              @if($doctor->profile_picture)
                <img src="{{ asset($doctor->profile_picture) }}" 
                     alt="Doctor Picture" 
                     class="rounded-circle"
                     style="width:50px; height:50px; object-fit:cover;">
              @else
                <img src="{{ asset('img/default-avatar.png') }}" 
                     alt="Default" 
                     class="rounded-circle"
                     style="width:50px; height:50px; object-fit:cover;">
              @endif
            </td>

            <!-- Doctor Info -->
            <td>{{ $doctor->firstname }} {{ $doctor->lastname }}</td>
            <td>{{ $doctor->email }}</td>
            <td>{{ $doctor->contact_no }}</td>
            <td>
              @if($doctor->status == 'active')
                <span class="badge bg-success">Active</span>
              @else
                <span class="badge bg-secondary">Inactive</span>
              @endif
            </td>

            <td>
  <!-- Toggle absence form -->
  <form action="{{ route('doctors.toggleAbsence', $doctor->id) }}" method="POST" style="display:inline;">
    @csrf
    @method('PATCH')
    @if($doctor->is_absent)
      <button type="submit" class="btn btn-sm btn-warning">Mark Present</button>
    @else
      <button type="submit" class="btn btn-sm btn-outline-warning">Mark Absent</button>
    @endif
  </form>
</td>

          <!-- Actions -->
</tr>
        @empty
          <tr>
            <td colspan="6">No doctors found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
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
