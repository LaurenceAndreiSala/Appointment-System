@extends('layouts.layout')

@section('title', 'Appointment App | Login')
@section('css')
      <link  href="{{ asset('css/login.css')}}" rel="stylesheet">

@section('content')
<div class="auth-wrapper">
    <div class="login-card login-card bg-info bg-gradient">
        <div class="logo-wrapper">
            <img class="logo" src="{{ asset('img/logo.png') }}" alt="Logo">
        </div>
        <h2 class="login-title text-white ">Login</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="form-group">
                <input type="email" name="email" placeholder="Email address" 
                       class="@error('email') is-invalid @enderror" 
                       value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <input type="password" name="password" placeholder="Password"
                       class="@error('password') is-invalid @enderror" required>
                @error('password')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <!-- Remember + Forgot -->
            <div class="form-options text-white">
                <label>
                    <input type="checkbox" name="remember"> Remember me
                </label>
                <a href="#" class="forgot-link text-white ">Forgot Password?</a>
            </div>

            <!-- Login Button -->
            <button type="submit" class="btn btn-light w-100 mt-3 ">Login</button>

            <!-- Register CTA -->
            <p class="register-cta text-white ">
                Don’t have an account? <a href="{{ route('register') }}"class="text-light fw-bold">Register</a>
                
            </p>
        
        </form>
    </div>
</div>
@endsection
