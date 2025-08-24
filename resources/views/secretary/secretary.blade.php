<!-- resources/views/doctor/dashboard.blade.php -->
@extends('layouts.layout')

@section('content')
    <h1>Secretary Dashboard</h1>
    <p>Welcome Dr. {{ Auth::user()->firstname }}</p>
@endsection

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="profile">
      <img src="img/loloy.png" alt="Admin">
      <h3>Secretary</h3>
      <p>Secretary Panel</p>
    </div>
    <a href="#"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="#"><i class="fas fa-calendar-alt"></i> Set Available Slot</a>
    <a href="#"><i class="fas fa-user-plus"></i> Book Appointment</a>
    <a href="#"><i class="fas fa-list"></i> View Appointments</a>
    <a href="#"><i class="fas fa-cog"></i> Settings</a>
    <a href="#"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="header">
      <h1>Secretary Dashboard</h1>
    </div>

    <div class="cards">
      <div class="card">
        <h3>Set Available Slot</h3>
        <p>Manage doctor’s available time slots for appointments.</p>
      </div>
      <div class="card">
        <h3>Book Appointment</h3>
        <p>Book an appointment on behalf of a patient with the doctor.</p>
      </div>
      <div class="card">
        <h3>View Appointments</h3>
        <p>View, edit, and manage all scheduled appointments.</p>
      </div>
    </div>
  </div>

