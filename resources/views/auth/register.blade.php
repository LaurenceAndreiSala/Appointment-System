@extends('layouts.layout')
@section('title', 'Register | MediCAL')

@section('content')

<!-- ✅ Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" style="margin-left: 10px;" href="#">
      <div class="logo-section">
      <img class="clinic-logo" src="{{ asset('img/clinic-logo.png') }}" style="width: 30px; height: 30px; margin-right: 5px;"></i>
      <span class="fw-bold fs-4">MediCAL</span>
</div>
    </a>
  </div>
</nav>

<!-- ✅ Register Section -->
<section class="gradient-bg text-white py-5" style="min-height: 91vh;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-10 col-lg-6">
        <div class="card shadow-lg border-0 rounded-4 card-hover transition-all" data-aos="zoom-in">
          <div class="card-body p-5">
            <h3 class="fw-bold text-center mb-4 text-primary">Create an Account</h3>

            <form action="{{ route('register') }}" method="POST">
              @csrf

              <div class="row g-3">
                <!-- Full Name -->
                <div class="col-md-12">
                  <label for="name" class="form-label fw-semibold">Full Name</label>
                  <input type="text" name="name" id="name" 
                         class="form-control rounded-3 shadow-sm" 
                         placeholder="Enter your full name" required>
                </div>

                <!-- Email -->
                <div class="col-md-6">
                  <label for="email" class="form-label fw-semibold">Email</label>
                  <input type="email" name="email" id="email" 
                         class="form-control rounded-3 shadow-sm" 
                         placeholder="Enter your email" required>
                </div>

                <!-- Phone -->
                <div class="col-md-6">
                  <label for="phone" class="form-label fw-semibold">Phone Number</label>
                  <input type="text" name="phone" id="phone" 
                         class="form-control rounded-3 shadow-sm" 
                         placeholder="Enter your phone number">
                </div>

                <!-- Password -->
                <div class="col-md-6">
                  <label for="password" class="form-label fw-semibold">Password</label>
                  <input type="password" name="password" id="password" 
                         class="form-control rounded-3 shadow-sm" 
                         placeholder="Enter your password" required>
                </div>

                <!-- Confirm Password -->
                <div class="col-md-6">
                  <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                  <input type="password" name="password_confirmation" id="password_confirmation" 
                         class="form-control rounded-3 shadow-sm" 
                         placeholder="Re-enter your password" required>
                </div>
              </div>

              <!-- Submit -->
              <button type="submit" class="btn btn-primary w-100 rounded-3 shadow-sm mt-4 fw-semibold">
                Register
              </button>
            </form>

            <!-- Login Link -->
            <p class="text-center mt-4 small">
              Already have an account? 
              <a href="{{ route('login') }}" class="fw-semibold text-primary text-decoration-none">Log In</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ✅ Styles -->
<style>
   .gradient-bg {
    background: linear-gradient(135deg, #007bff, #00c6ff);
  }
  .card-hover {
    transition: all 0.3s ease;
  }
  .card-hover:hover {
    transform: translateY(-6px);
    box-shadow: 0 15px 25px -5px rgba(0, 0, 0, 0.15);
  }
</style>

@endsection
