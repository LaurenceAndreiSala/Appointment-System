@extends('layouts.layout')
@section('title', 'Admin Dashboard | MediCare {{ Auth::user()->name }}')

@section('content')
@include('includes.adminnavbar')
@include('includes.adminleftnavbar')


<div class="container-fluid">
  <div class="row">

     <!-- Main Content -->
<div class="col-md-9 col-lg-10 offset-md-3 offset-lg-2 p-4">

  <!-- ✅ Page Header -->
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <h2 class="fw-bold mb-2 mb-md-0 text-primary">
      <i class="fas fa-calendar-alt me-2"></i> View All Appointments
    </h2>
<small class="text-muted">View All Appointments with their doctors.</small>
  </div>

    <div class="table-responsive rounded-4 shadow-sm">
      <table class="table table-hover table-bordered table-striped align-middle text-center mb-0">
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
            <!-- Patient Profile Picture -->
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

            <!-- Name -->
            <td>{{ $appt->patient?->firstname }} {{ $appt->patient?->lastname }}</td>

            <!-- Email -->
            <td>{{ $appt->patient?->email }}</td>

            <!-- Appointment Date -->
            <td>{{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y h:i A') }}</td>

            <!-- Status -->
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
            <td colspan="5" class="text-muted py-4">
              <i class="fas fa-inbox fa-2x mb-2"></i><br>
              No appointments found.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</main>

<!-- Optional Hover Styles -->
<style>
.table-hover tbody tr:hover {
  background-color: #f8f9fa;
  transform: scale(1.01);
  transition: all 0.2s ease;
}
.card {
  border-radius: 1rem;
}
</style>


<script src="{{ asset('/js/notification.js') }}"></script>

@endsection
