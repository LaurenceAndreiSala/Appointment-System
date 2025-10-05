@extends('layouts.layout')
@section('title', 'Admin Dashboard | MediCare {{ Auth::user()->name }}')

@section('content')
@include('includes.adminnavbar')
@include('includes.adminleftnavbar')


<div class="container-fluid">
  <div class="row">

   <!-- ✅ Main Content -->
    <div class="col-md-9 col-lg-10 offset-md-3 offset-lg-2 p-4">
<!-- 👋 Welcome Banner -->
      <div class="d-flex align-items-center justify-content-between flex-wrap bg-light p-3 rounded-4 shadow-sm mb-4">
        <div class="d-flex align-items-center">
        <i class="fas fa-user-tie text-primary fa-1x me-3"></i>
          <span class="fw-bold fs-10 fs-md-10">
    Welcome Dr. {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}!
  </span>
        </div>
        <small class="text-muted mt-2 mt-md-0">Secretary Dashboard</small>
      </div>

       <!-- ✅ Stats Cards -->
<div class="row g-4 mb-5">
  <div class="col-12 col-sm-6 col-md-3">
    <div class="card shadow-sm border-0 text-center bg-primary text-white rounded-4 h-100">
      <div class="card-body">
        <i class="fas fa-user-md fa-2x mb-2"></i>
        <h2 class="fw-bold">{{ $doctorCount }}</h2>
        <p class="mb-0">Registered Doctors</p>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="card shadow-sm border-0 text-center bg-success text-white rounded-4 h-100">
      <div class="card-body">
        <i class="fas fa-user-injured fa-2x mb-2"></i>
        <h2 class="fw-bold">{{ $patientCount }}</h2>
        <p class="mb-0">Appointment Patients</p>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="card shadow-sm border-0 text-center bg-warning text-white rounded-4 h-100">
      <div class="card-body">
        <i class="fas fa-users fa-2x mb-2"></i>
        <h2 class="fw-bold">{{ $totaluserCount }}</h2>
        <p class="mb-0">All Users</p>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="card shadow-sm border-0 text-center bg-danger text-white rounded-4 h-100">
      <div class="card-body">
        <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
        <h2 class="fw-bold">₱ {{ $totalPayments }}</h2>
        <p class="mb-0">Total Payment</p>
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
