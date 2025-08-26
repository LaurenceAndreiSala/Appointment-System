@extends('layouts.layout')

@section('title', 'Appointment App | Signup')
@section('css')
      <link  href="{{ asset('css/register.css')}}" rel="stylesheet">

@section('content')
<div class="auth-wrapper">
    <div class="auth-card auth-card bg-info bg-gradient">
        <h2 class="auth-title text-white">Sign Up</h2>

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <input type="hidden" name="role" value="admin">

            <div class="form-row">
                <div class="form-group">
                    <input type="text" name="firstname" placeholder="First Name"
                           class="@error('firstname') is-invalid @enderror" 
                           value="{{ old('firstname') }}" required>
                    @error('firstname')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <input type="text" name="lastname" placeholder="Last Name"
                           class="@error('lastname') is-invalid @enderror" 
                           value="{{ old('lastname') }}" required>
                    @error('lastname')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <input type="text" name="username" placeholder="Username"
                       class="@error('username') is-invalid @enderror" 
                       value="{{ old('username') }}" required>
                @error('username')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="Email Address"
                       class="@error('email') is-invalid @enderror" 
                       value="{{ old('email') }}" required>
                @error('email')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <input type="password" name="password" placeholder="Password"
                       class="@error('password') is-invalid @enderror" required>
                @error('password')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
            </div>
            <button type="submit" class="btn btn-light w-100 mt-3 ">Register</button>

            <p class="auth-cta text-white">
                Already have an account? 
                <a href="{{ route('login') }} "class="text-white">Login</a>
            </p>
        </form>
    </div>
</div>
@endsection
