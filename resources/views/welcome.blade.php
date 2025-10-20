@extends('layouts.layout')
@section('title', 'Doctor Appointment')

@section('content')

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" style="margin-left: 10px;" href="#">
      <div class="logo-section">
      <img class="clinic-logo" src="{{ asset('img/clinic-logo.png') }}" style="width: 30px; height: 30px; margin-right: 5px;"></i>
      <span class="fw-bold fs-4">MediCAL</span>
</div>
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
<section class="gradient-bg text-white py-4" style="min-height: 80vh;">
  <div class="container">
    <div class="row align-items-center text-center text-md-start">
      <!-- Left Content -->
      <div class="col-md-6 mb-4" data-aos="fade-right" style="margin-top: 25px;">
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
             alt="Doctor" style="width: 70%; height: 590px; margin-top: 30px">
      </div>
    </div>
  </div>
</section>

<!-- Custom Responsive Styling -->
<style>
  .gradient-bg {
    background: linear-gradient(135deg, #007bff, #00c6ff);
  }
</style>

@endsection
