@extends('layouts.layout')
@section('title', 'Admin Dashboard | MediCare {{ Auth::user()->name }}')

@section('content')
@include('includes.adminnavbar')
@include('includes.adminleftnavbar')


<div class="container-fluid">
  <div class="row">

    <!-- Main Content -->
   <main class="col-lg-10 offset-lg-2 p-5">
    <div class="card-body">
        <h3 class="fw-bold mb-3">View All Appointments</h3>
          <ul class="appointment-list">
         <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle text-center">
  <thead class="table-dark">
    <tr>
      <th>Profile</th>
      <th>Patient</th>
      <th>Email</th>
      <th>Date & Time</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    @forelse($appointments as $appt)
      <tr>
        <!-- ✅ Patient Profile Picture -->
        <td>
          @if($appt->patient?->profile_picture)
            <img src="{{ asset($appt->patient->profile_picture) }}" 
                 alt="Profile Picture" 
                 class="rounded-circle"
                 style="width:50px; height:50px; object-fit:cover;">
          @else
            <img src="{{ asset('img/default-avatar.png') }}" 
                 alt="Default" 
                 class="rounded-circle"
                 style="width:50px; height:50px; object-fit:cover;">
          @endif
        </td>

        <!-- ✅ Name -->
        <td>{{ $appt->patient?->firstname }} {{ $appt->patient?->lastname }}</td>

        <!-- ✅ Email -->
        <td>{{ $appt->patient?->email }}</td>

        <!-- ✅ Appointment Date -->
        <td>{{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y h:i A') }}</td>

        <!-- ✅ Status -->
        <td>
          @if($appt->status == 'pending')
            <span class="badge bg-warning text-dark">Pending</span>
          @elseif($appt->status == 'approved')
            <span class="badge bg-success">Approved</span>
          @elseif($appt->status == 'denied')
            <span class="badge bg-danger">Denied</span>
          @elseif($appt->status == 'cancelled')
            <span class="badge bg-secondary">Cancelled</span>
          @else
            <span class="badge bg-info">{{ ucfirst($appt->status) }}</span>
          @endif
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="5">No appointments found.</td>
      </tr>
    @endforelse
  </tbody>
</table>
    </div>
      <div class="mycard-content">
 </div>
  </div>
    </div>

<script src="{{ asset('/js/notification.js') }}"></script>

@endsection
