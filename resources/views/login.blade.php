@extends('layouts.layout')
@section('title', 'Log In | MediCAL')

@section('content')

<!-- ✅ Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <i class="fas fa-heartbeat text-primary me-2"></i>
      <span class="fw-bold fs-4">MediCAL</span>
    </a>
  </div>
</nav>

<!-- ✅ Login Section -->
<section class="gradient-bg d-flex align-items-center" style="min-height: 100vh;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-8 col-lg-5">
        <div class="card shadow-lg border-0 rounded-4 card-hover transition-all" data-aos="zoom-in">
          <div class="card-body p-5">
            <h3 class="fw-bold text-center mb-4 text-primary">Log In to MediCAL</h3>

            <form action="{{ route('login') }}" method="POST">
              @csrf

              <!-- Email -->
              <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email</label>
                <input type="email" name="email" id="email" 
                       class="form-control rounded-3 shadow-sm" 
                       placeholder="Enter your email" required>
                @error('email')
                  <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
              </div>

              <!-- Password -->
              <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Password</label>
                <input type="password" name="password" id="password" 
                       class="form-control rounded-3 shadow-sm" 
                       placeholder="Enter your password" required>
                @error('password')
                  <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
              </div>

              <!-- Remember Me & Forgot Password -->
              <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="remember" id="remember">
                  <label class="form-check-label small" for="remember">Remember Me</label>
                </div>
                <a href="#" class="small text-primary text-decoration-none">Forgot Password?</a>
              </div>

              <!-- Submit Button -->
              <button type="submit" class="btn btn-primary w-100 rounded-3 shadow-sm fw-semibold">
                Log In
              </button>
            </form>

            <!-- Register Link -->
            <p class="text-center mt-4 small">
              Don’t have an account? 
              <a href="{{ route('register') }}" class="fw-semibold text-primary text-decoration-none">
                Sign Up
              </a>
            </p>
          </div>
        </div>
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

<!-- ✅ Styles -->
<style>
  .gradient-bg {
    background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
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
