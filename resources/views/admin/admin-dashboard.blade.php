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
   <main class="col-lg-10 offset-lg-2 p-5">
  <div class="card shadow-sm border-0 mb-4 p-4">
    <div class="card-body">
          <!-- Stats -->
          <div class="row g-4">
            <div class="col-md-3">
              <div class="card text-center shadow-sm border-0 bg-primary text-white">
                <div class="card-body">
                  <i class="fas fa-user-md fa-2x mb-2"></i>
                  <h2 class="fw-bold">{{ $doctorCount }}</h2>
                  <p class="mb-0">Registered Doctors</p>
                </div>
              </div>
            </div>

            <div class="col-md-3">
              <div class="card text-center shadow-sm border-0 bg-success text-white">
                <div class="card-body">
                  <i class="fas fa-user-injured fa-2x mb-2"></i>
                  <h2 class="fw-bold">{{ $patientCount }}</h2>
                  <p class="mb-0">Appointment Patients</p>
                </div>
              </div>
            </div>

            <div class="col-md-3">
              <div class="card text-center shadow-sm border-0 bg-warning text-dark">
                <div class="card-body">
                  <i class="fas fa-users fa-2x mb-2"></i>
                  <h2 class="fw-bold">{{ $totaluserCount }}</h2>
                  <p class="mb-0">All Users</p>
                </div>
              </div>
            </div>

     <div class="col-md-3">
  <div class="card text-center shadow-sm border-0 bg-danger text-white">
    <div class="card-body">
      <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
      <h2 class="fw-bold">
       ₱ {{ $totalPayments }}
      </h2>
      <p class="mb-0">Total Payment</p>
    </div>
  </div>
</div>

        <!-- Payment Chart -->
<div class="card shadow-sm border-2 mt-4">
  <div class="card-body">
    <h3 class="fw-bold mb-3">Total Payment (Monthly)</h3>
    <div style="height:300px;">
      <canvas id="totalpayment"></canvas>
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


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
   // <!-- Total Payment Count ================================== -->
        const jan = '{{ $janCount ?? 0 }}';
        const feb = '{{ $febCount ?? 0 }}';
        const mar = '{{ $marchCount ?? 0 }}';
        const apr = '{{ $aprilCount ?? 0 }}';
        const may = '{{ $mayCount ?? 0 }}';
        const jun = '{{ $juneCount ?? 0 }}';
        const jul = '{{ $julyCount ?? 0 }}';
        const aug = '{{ $augustCount ?? 0 }}';
        const sep = '{{ $sepCount ?? 0 }}';
        const oct = '{{ $octCount ?? 0 }}';
        const nov = '{{ $novCount ?? 0 }}';
        const dec = '{{ $decCount ?? 0 }}';

       new Chart(document.getElementById("totalpayment"), {
    type: 'line', // 👈 Line Chart (can change to 'bar' if needed)
    data: {
        labels: [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ],
        datasets: [{
            label: "Total Payment (₱)",
            backgroundColor: "#5cb85c",
            borderColor: "#5cb85c",
            fill: true,
            tension: 0.3,
            data: [
                jan, feb, mar, apr, may, jun,
                jul, aug, sep, oct, nov, dec
            ]
        }]
    },
    options: {
  responsive: true,
  maintainAspectRatio: false, // 👈 ensures it stretches inside parent div
  scales: {
    y: {
      beginAtZero: true
    }
  }
}

});

</script>
@endsection
