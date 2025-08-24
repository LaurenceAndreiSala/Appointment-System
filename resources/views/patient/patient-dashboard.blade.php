@extends('layouts.layout')

@section('content')

 @include('includes.patientsidebar')

  <!-- Main Content -->
  <div class="main">
    <h1>Patient Dashboard</h1>

    <div class="cards">
      <div class="card">
        <i class="fas fa-calendar-plus"></i>
        <h3>Book Appointment</h3>
        <p>Schedule a new appointment with a doctor.</p>
      </div>
      <div class="card">
        <i class="fas fa-calendar-check"></i>
        <h3>View Appointment</h3>
        <p>Check your upcoming and past appointments.</p>
      </div>
      <div class="card">
        <i class="fas fa-video"></i>
        <h3>Chat / Video Call</h3>
        <p>Consult with your doctor online.</p>
      </div>
      <div class="card">
        <i class="fas fa-file-prescription"></i>
        <h3>View Prescription</h3>
        <p>Access your prescribed medicines anytime.</p>
      </div>
    </div>
  </div>
