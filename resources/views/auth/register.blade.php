@extends('layouts.layout')
@section('title', 'Register | MediCAL')

@section('content')

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <i class="fas fa-heartbeat text-primary me-2"></i>
      <span class="fw-bold fs-4">MediCAL</span>
    </a>
</nav>

<!-- Register Section -->
<section class="gradient-bg text-white py-5">
  <div class="container d-flex justify-content-center align-items-center" style="min-height:80vh;">
    <div class="col-md-7 col-lg-6">
      <div class="card shadow-lg border-0 rounded-4" data-aos="zoom-in">
        <div class="card-body p-5">
          <h3 class="fw-bold text-center mb-4 text-primary">Create an Account</h3>

          <form action="{{ route('register') }}" method="POST">
            @csrf

            <!-- Full Name -->
            <div class="mb-3">
              <label for="name" class="form-label fw-semibold">Full Name</label>
              <input type="text" name="name" id="name" class="form-control rounded-3 shadow-sm" placeholder="Enter your full name" required>
            </div>

            <!-- Email -->
            <div class="mb-3">
              <label for="email" class="form-label fw-semibold">Email</label>
              <input type="email" name="email" id="email" class="form-control rounded-3 shadow-sm" placeholder="Enter your email" required>
            </div>

            <!-- Phone -->
            <div class="mb-3">
              <label for="phone" class="form-label fw-semibold">Phone Number</label>
              <input type="text" name="phone" id="phone" class="form-control rounded-3 shadow-sm" placeholder="Enter your phone number">
            </div>

            <!-- Password -->
            <div class="mb-3">
              <label for="password" class="form-label fw-semibold">Password</label>
              <input type="password" name="password" id="password" class="form-control rounded-3 shadow-sm" placeholder="Enter your password" required>
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
              <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
              <input type="password" name="password_confirmation" id="password_confirmation" class="form-control rounded-3 shadow-sm" placeholder="Re-enter your password" required>
            </div>


            <!-- Submit -->
            <button type="submit" class="btn btn-primary w-100 rounded-3 shadow">Register</button>
          </form>

          <!-- Login Link -->
          <p class="text-center mt-4 small">
            Already have an account? 
            <a href="{{ route('login') }}" class="fw-semibold text-primary">Log In</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="bg-dark text-light py-4">
  <div class="container text-center small text-muted">
    &copy; 2025 MediCare. All rights reserved.
  </div>
</footer>

@endsection
