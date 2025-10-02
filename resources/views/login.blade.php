@extends('layouts.layout')
@section('title', 'Log In | MediCare')

@section('content')

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <i data-feather="heart" class="text-primary me-2"></i>
      <span class="fw-bold fs-4">MediCare</span>
    </a>
</nav>

<!-- Login Section -->
<section class="gradient-bg text-white py-5">
  <div class="container d-flex justify-content-center align-items-center" style="min-height:80vh;">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow-lg border-0 rounded-4" data-aos="zoom-in">
        <div class="card-body p-5">
          <h3 class="fw-bold text-center mb-4 text-primary">Log In to MediCAL</h3>
          <form action="{{ route('login') }}" method="POST">
            @csrf
            <!-- Email -->
            <div class="mb-3">
              <label for="email" class="form-label fw-semibold">Email</label>
              <input type="email" name="email" id="email" class="form-control rounded-3 shadow-sm" placeholder="Enter your email" required>
              @error('email')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
              <label for="password" class="form-label fw-semibold">Password</label>
              <input type="password" name="password" id="password" class="form-control rounded-3 shadow-sm" placeholder="Enter your password" required>
             @error('password')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="d-flex justify-content-between align-items-center mb-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label small" for="remember">Remember Me</label>
              </div>
              <a href="#" class="small text-primary">Forgot Password?</a>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-primary w-100 rounded-3 shadow">Log In</button>
          </form>
          
          <!-- Register Link -->
          <p class="text-center mt-4 small">
            Don’t have an account? 
            <a href="{{ route('register') }}" class="fw-semibold text-primary">Sign Up</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="bg-dark text-light py-4">
  <div class="container text-center small text-muted">
    &copy; 2025 MediCAL. All rights reserved.
  </div>
</footer>

@endsection
