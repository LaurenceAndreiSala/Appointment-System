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

         <form action="{{ secure_url(route('register', [], false)) }}" method="POST">
              @csrf

              <div class="row g-3">
                <!-- Full Name -->
                <div class="col-md-6">
                  <label for="firstname" class="form-label fw-semibold">First Name</label>
                  <input type="text" name="firstname" id="firstname" 
                         class="form-control rounded-3 shadow-sm" 
                         placeholder="Enter your First name" required>
                </div>

                 <div class="col-md-6">
                  <label for="lastname" class="form-label fw-semibold">Last Name</label>
                  <input type="text" name="lastname" id="lastname" 
                         class="form-control rounded-3 shadow-sm" 
                         placeholder="Enter your Last name" required>
                </div>

                <!-- Email -->
                <div class="col-md-6">
                  <label for="email" class="form-label fw-semibold">Email</label>
                  <input type="email" name="email" id="email" 
                         class="form-control rounded-3 shadow-sm" 
                         placeholder="Enter your email" required>
                </div>

                <!-- Address -->
                <div class="col-md-6">
                  <label for="address" class="form-label fw-semibold">Address</label>
                  <input type="text" name="address" id="address" 
                         class="form-control rounded-3 shadow-sm" 
                         placeholder="Enter your Address" required>
                </div>

                <!-- Phone -->
                <div class="col-md-6">
                  <label for="cotact_no" class="form-label fw-semibold">Phone Number</label>
                  <input type="text" name="contact_no" id="contact_no" 
                         class="form-control rounded-3 shadow-sm" 
                         placeholder="Enter your phone number">
                </div>
                  <!-- Gender -->
                  <div class="col-md-6">
                    <label for="gender" class="form-label fw-semibold">Gender</label>
                    <select name="gender" id="gender" class="form-control rounded-3 shadow-sm" required>
                      <option value="">Select Gender</option>
                      <option value="Male">Male</option>
                      <option value="Female">Female</option>
                      <option value="Other">Other</option>
                    </select>
                  </div>

                  <!-- Birthday -->
                  <div class="col-md-6">
                    <label for="birth_date" class="form-label fw-semibold">Birthday</label>
                    <input type="date" name="birth_date" id="birth_date"
                          class="form-control rounded-3 shadow-sm"
                          required>
                  </div>

                    <!-- Age -->
                  <div class="col-md-6">
                    <label for="age" class="form-label fw-semibold">Age</label>
                    <input type="text" name="age" id="age" placeholder="Enter Age"
                          class="form-control rounded-3 shadow-sm"
                          required>
                  </div>

                <!-- Password -->
                  <div class="mb-3 position-relative">
             <label for="password" class="form-label fw-semibold">Password</label>
  
              <input type="password" name="password" id="password"
                class="form-control rounded-3 shadow-sm pe-5"
                placeholder="Enter your password" required>
  
             <span class="toggle-password position-absolute top-50 end-0 translate-middle-y me-3"
              onclick="togglePassword(this)" style="cursor: pointer; margin-top: 17px;">
              <i class="fas fa-eye"></i>
              </span>
            </div>

            <!-- Confirm Password -->
                <div class="mb-3 position-relative">
                  <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                  <input type="password" name="password_confirmation" id="password_confirmation"
                         class="form-control rounded-3 shadow-sm pe-5"
                         placeholder="Confirm your password" required>
                  <span class="toggle-password position-absolute top-50 end-0 translate-middle-y me-3"
                        onclick="toggleConfirmPassword(this)" style="cursor: pointer; margin-top: 17px;">
                    <i class="fas fa-eye"></i>
                  </span>
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

<script>
  function togglePassword(iconWrapper) {
      const input = document.getElementById("password");
      const icon = iconWrapper.querySelector("i");

      if (input.type === "password") {
          input.type = "text";
          icon.classList.replace("fa-eye", "fa-eye-slash");
      } else {
          input.type = "password";
          icon.classList.replace("fa-eye-slash", "fa-eye");
      }
  }

  function toggleConfirmPassword(iconWrapper) {
      const input = document.getElementById("password_confirmation");
      const icon = iconWrapper.querySelector("i");

      if (input.type === "password") {
          input.type = "text";
          icon.classList.replace("fa-eye", "fa-eye-slash");
      } else {
          input.type = "password";
          icon.classList.replace("fa-eye-slash", "fa-eye");
      }
  }
</script>

<style>
    toggle-password i {
    color: #6c757d;
    font-size: 1rem;
  }
  .toggle-password:hover i {
    color: #0d6efd;
  }

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
