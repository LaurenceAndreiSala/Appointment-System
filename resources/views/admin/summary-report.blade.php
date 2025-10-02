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
            <div class="card mt-4">
  <h3>📊 Summary Report</h3>
  <div class="row text-center p-3">
    <div class="col-md-3">
      <div class="stat-card bg-success text-white p-3 rounded shadow-sm">
        <h4>{{ $completedAppointments }}</h4>
        <p>Completed Appointments</p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="stat-card bg-warning text-dark p-3 rounded shadow-sm">
        <h4>{{ $pendingAppointments }}</h4>
        <p>Pending Appointments</p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="stat-card bg-danger text-white p-3 rounded shadow-sm">
        <h4>{{ $cancelledAppointments }}</h4>
        <p>Cancelled Appointments</p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="stat-card bg-primary text-white p-3 rounded shadow-sm">
        <h4>₱{{ number_format($totalPayments, 2) }}</h4>
        <p>Total Revenue</p>
      </div>
    </div>
  </div>
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

<div class="col-md-12 mt-4">
    <div class="mycard">
        <div class="mycard-header">
            <h3>Appointments Per Day</h3>
        </div>
        <div class="mycard-content" style="height: 350px;">
            <canvas id="appointmentsPerDay"></canvas>
        </div>
    </div>
</div>

<!-- Row for two centered side-by-side charts -->
<div class="row mt-4 justify-content-center text-center">
    <!-- Financial Overview -->
    <div class="col-md-5">
        <div class="mycard">
            <div class="mycard-header">
                <h3>Financial Overview</h3>
            </div>
            <div class="mycard-content d-flex justify-content-center" style="height: 280px;">
                <canvas id="financialOverview"></canvas>
            </div>
        </div>
    </div>

    <!-- Appointments Status -->
    <div class="col-md-5">
        <div class="mycard">
            <div class="mycard-header">
                <h3>Appointments Status</h3>
            </div>
            <div class="mycard-content d-flex justify-content-center" style="height: 280px;">
                <canvas id="appointmentSummary"></canvas>
            </div>
        </div>
    </div>
</div>



<div class="text-center mt-3">
  <label class="form-label">Download Summary Report</label>
  <select name="report" class="form-select" id="reportSelect" required>
    <option value="">-- Select Download --</option>
    <option value="pdf">Download PDF</option>
    <!-- You can add more formats here if needed -->
  </select>
  <div id="downloadIcon" style="display: none;">
    <a href="{{ route('admin.report.pdf') }}" class="btn btn-danger mt-2">
      <i class="fas fa-file-pdf"></i> Download PDF
    </a>
  </div>
</div>

<!-- Sidebar (mobile offcanvas) -->
<div class="offcanvas offcanvas-start bg-primary text-white custom-offcanvas" tabindex="-1" id="adminSidebar">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">Admin Menu</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column">
    
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
/* ---------- Total Payment (Monthly) ---------- */
const monthLabels = ["January","February","March","April","May","June","July","August","September","October","November","December"];
const monthlyData = @json($monthlyTotals ?? array_fill(0,12,0));

new Chart(document.getElementById("totalpayment"), {
    type: 'line',
    data: {
        labels: monthLabels,
        datasets: [{
            label: "Total Payment (₱)",
            data: monthlyData,
            backgroundColor: "rgba(92,184,92,0.15)",
            borderColor: "#5cb85c",
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: { y: { beginAtZero: true } }
    }
});

/* ---------- Appointments Status (Pie) ---------- */
const completed = @json($completedAppointments ?? 0);
const pendingApp = @json($pendingAppointments ?? 0);
const cancelled = @json($cancelledAppointments ?? 0);

new Chart(document.getElementById("appointmentSummary"), {
    type: 'pie',
    data: {
        labels: ["Completed", "Pending", "Cancelled"],
        datasets: [{
            data: [completed, pendingApp, cancelled],
            backgroundColor: ["#5cb85c", "#f0ad4e", "#d9534f"]
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});

/* ---------- Appointments Per Day (Bar) ---------- */
const apptLabels = @json($appointmentsPerDayLabels ?? []);
const apptCounts = @json($appointmentsPerDayData ?? []);

new Chart(document.getElementById("appointmentsPerDay"), {
    type: 'bar',
    data: {
        labels: apptLabels,
        datasets: [{
            label: "Appointments",
            data: apptCounts,
            backgroundColor: "#0275d8"
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: { y: { beginAtZero: true } }
    }
});

/* ---------- Financial Overview (Pie) ---------- */
const revenue = @json($revenue ?? 0);
const pendingPayments = @json($pendingPayments ?? 0);
const refundPayments = @json($refundPayments ?? 0);
const avgPerPatient = @json($avgPerPatient ?? 0);

new Chart(document.getElementById("financialOverview"), {
    type: 'pie',
    data: {
        labels: ["Revenue", "Pending", "Refund", "Avg Per Patient"],
        datasets: [{
            data: [revenue, pendingPayments, refundPayments, avgPerPatient],
            backgroundColor: ["#5cb85c", "#f0ad4e", "#d9534f", "#5bc0de"]
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});

  document.getElementById('reportSelect').addEventListener('change', function() {
    const selectedValue = this.value;
    const downloadIcon = document.getElementById('downloadIcon');
    
    if (selectedValue === 'pdf') {
      // Show the download button and icon when 'Download PDF' is selected
      downloadIcon.style.display = 'block';
    } else {
      // Hide it if any other option is selected
      downloadIcon.style.display = 'none';
    }
  });

    // Auto refresh every 30 seconds (adjust as needed)
  setInterval(function() {
    location.reload();
  }, 30000); 
 </script>
<!-- Custom CSS -->
<style>
  /* Half-width sidebar */
  .custom-offcanvas {
    width: 50% !important; /* 👈 half of the screen */
    max-width: 100px; /* optional: limit sa dako nga screen */
  }
</style>

@endsection
