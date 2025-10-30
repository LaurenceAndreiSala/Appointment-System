@extends('layouts.layout')
@section('title', 'Admin Dashboard | MediCare {{ Auth::user()->name }}')

@section('content')
@include('includes.adminnavbar')
@include('includes.adminleftnavbar')


<div class="container-fluid">
  <div class="row">

    <!-- Main Content -->
        <div class="col-md-9 col-lg-10 offset-md-3 offset-lg-2 p-4">
      <!-- ✅ Page Header -->
 <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
  <h2 class="fw-bold mb-2 mb-md-0 text-primary">
    <i class="fas fa-chart-bar me-2"></i>Summary Report
  </h2>
</div>


<!-- ✅ Stats Cards -->
<div class="row g-4 mb-5">
  <!-- Completed Appointments -->
  <div class="col-12 col-sm-6 col-md-3">
    <div class="card shadow-sm border-0 text-center bg-success text-white rounded-4 h-100">
      <div class="card-body">
        <i class="fas fa-calendar-check fa-2x mb-2"></i>
        <h2 class="fw-bold">{{ $completedAppointments }}</h2>
        <p class="mb-0">Completed Appointments</p>
      </div>
    </div>
  </div>

  <!-- Pending Appointments -->
  <div class="col-12 col-sm-6 col-md-3">
    <div class="card shadow-sm border-0 text-center bg-warning text-white rounded-4 h-100">
      <div class="card-body">
        <i class="fas fa-clock fa-2x mb-2"></i>
        <h2 class="fw-bold">{{ $pendingAppointments }}</h2>
        <p class="mb-0">Pending Appointments</p>
      </div>
    </div>
  </div>

  <!-- Cancelled Appointments -->
  <div class="col-12 col-sm-6 col-md-3">
    <div class="card shadow-sm border-0 text-center bg-danger text-white rounded-4 h-100">
      <div class="card-body">
        <i class="fas fa-times-circle fa-2x mb-2"></i>
        <h2 class="fw-bold">{{ $cancelledAppointments }}</h2>
        <p class="mb-0">Cancelled Appointments</p>
      </div>
    </div>
  </div>

  <!-- Total Revenue -->
  <div class="col-12 col-sm-6 col-md-3">
    <div class="card shadow-sm border-0 text-center bg-primary text-white rounded-4 h-100">
      <div class="card-body">
        <i class="fas fa-coins fa-2x mb-2"></i>
        <h2 class="fw-bold">₱{{ number_format($totalPayments, 2) }}</h2>
        <p class="mb-0">Total Revenue</p>
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

<div class="card shadow-sm border-2 mt-4">
    <div class="card-body">
    <h3 class="fw-bold mb-3">Total Payment (Monthly)</h3>
    <div style="height:300px;">
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


<!-- ✅ Download PDF Button Only -->
<div class="text-center mt-4">
<a href="{{ route('admin.report.pdf') }}" class="btn btn-danger btn-lg shadow-sm">
  <i class="fas fa-file-pdf me-2"></i> Download Summary Report (PDF)
</a>

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
const fieldPayments = @json($fieldPayments ?? 0);
const avgPerPatient = @json($avgPerPatient ?? 0);

new Chart(document.getElementById("financialOverview"), {
    type: 'pie',
    data: {
        labels: ["Revenue", "Pending", "field", "Avg Per Patient"],
        datasets: [{
            data: [revenue, pendingPayments, fieldPayments, avgPerPatient],
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

@endsection
