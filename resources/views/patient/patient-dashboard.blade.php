@extends('layouts.layout')

@section('content')

 @include('includes.patientsidebar')

  <!-- Notification Bell -->
<div class="notification-bell">
  <i class="fas fa-bell"></i>
  <span class="badge">3</span> <!-- sample notification count -->
</div>

  <!-- Main Content -->
  <div class="main">
    <h1>Patient Dashboard</h1>

    <div class="cards">
      <div class="card">
         <a href="book-appointment">
        <i class="fas fa-calendar-plus"></i>
        <h3>Book Appointment</h3>
        <p>Schedule a new appointment with a doctor.</p>
</a>
      </div>
      <div class="card">
        <a href="view-appointment">
        <i class="fas fa-calendar-check"></i>
        <h3>View Appointment</h3>
        <p>Check your upcoming and past appointments.</p>
</a>
      </div>
      <div class="card">
         <a href="video-call">
        <i class="fas fa-video"></i>
        <h3>Chat / Video Call</h3>
        <p>Consult with your doctor online.</p>
 </a>
      </div>
      <div class="card">
         <a href="view-precription">
        <i class="fas fa-file-prescription"></i>
        <h3>View Prescription</h3>
        <p>Access your prescribed medicines anytime.</p>
</a>
      </div>
    </div>
  </div>
