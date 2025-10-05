@extends('layouts.layout')
@section('title', 'Doctor Appointment')

@section('content')

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" style="margin-left: 10px;" href="#">
      <i class="fas fa-heartbeat text-primary me-2"></i>
      <span class="fw-bold fs-4">MediCAL</span>
    </a>

    <!-- Desktop: Login Button inside menu -->
    <div class="collapse navbar-collapse d-none d-lg-block" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <li class="nav-item">
          <a href="{{ route('login')}}" class="btn btn-primary px-4">Log In</a>
        </li>
      </ul>
    </div>

    <!-- Mobile: Direct Login Button -->
    <div class="d-lg-none ms-auto">
      <a href="{{ route('login')}}" class="btn btn-primary px-3">Log In</a>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<section class="gradient-bg text-white py-5" style="min-height: 90vh;">
  <div class="container">
    <div class="row align-items-center text-center text-md-start">
      <!-- Left Content -->
      <div class="col-md-6 mb-4" data-aos="fade-right" style="margin-top: -25px;">
        <h1 class="display-4 fw-bold">
          Find & Book Appointment With Your Trusted Doctor
        </h1>
        <p class="lead mb-4">
          Instantly connect with top doctors 24/7 and get the care you deserve — anywhere, anytime.
        </p>
      </div>

      <!-- Right Image -->
      <div class="col" data-aos="fade-left">
        <img src="{{ asset('/img/doctor.png') }}"
             class="img-fluid rounded-4 shadow-lg border"
             alt="Doctor" style="width: 70%; height: 25%; margin-top: -30px">
      </div>
    </div>
  </div>
</section>

<!-- ✅ Footer -->
<footer class="bg-dark text-white py-3 mt-auto">
  <div class="container text-center small">
    &copy; 2025 MediCAL. All rights reserved.
  </div>
</footer>

<!-- Custom Responsive Styling -->
<style>
  .gradient-bg {
    background: linear-gradient(135deg, #007bff, #00c6ff);
  }
</style>

@endsection
