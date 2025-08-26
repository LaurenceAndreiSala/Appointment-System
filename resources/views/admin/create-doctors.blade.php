@extends('layouts.layout')

@section('content')
@include('includes.adminleftnavbar')

<!-- Notification Bell -->
<div class="notification-bell">
  <i class="fas fa-bell"></i>
  <span class="badge">3</span>
</div>

<!-- Main Content -->
<div class="main">
    <h1>Create Doctor Account</h1>

    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    <div class="stats">
        <form method="POST" action="{{ route('doctors.store') }}" class="doctor-form">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="firstname" required>
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="lastname" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" required>
                </div>
            </div>

            <button type="submit" class="btn-submit">Create Doctor</button>
        </form>
    </div>
</div>

@endsection