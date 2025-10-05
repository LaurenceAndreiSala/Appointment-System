@extends('layouts.layout')
@section('title', 'Doctor Dashboard | MediCare')

@section('content')
@include('includes.doctornavbar')
@include('includes.doctorsidebar')


<div class="container-fluid">
  <div class="row">

  <!-- ✅ Main Content -->
    <div class="col-md-9 col-lg-10 offset-md-3 offset-lg-2 p-4">
<!-- 👋 Welcome Banner -->
      <div class="d-flex align-items-center justify-content-between flex-wrap bg-light p-3 rounded-4 shadow-sm mb-4">
        <div class="d-flex align-items-center">
        <i class="fas fa-user-md text-primary fa-1x me-3"></i>
        <span class="fw-bold fs-10 fs-md-10">
    Welcome Dr. {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}!
  </span>
        </div>
        <small class="text-muted mt-2 mt-md-0">Doctor Dashboard</small>
      </div>

    <!-- ✅ Stats Cards -->
      <div class="row g-4 mb-5">
        <div class="col-12 col-md-4">
          <div class="card shadow-sm border-0 text-center bg-primary text-white rounded-4">
            <div class="card-body">
              <i class="fas fa-calendar-check fa-2x mb-2"></i>
              <h2 class="fw-bold">{{ $appointmentCount }}</h2>
              <p class="mb-0">Today’s Appointments</p>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="card shadow-sm border-0 text-center bg-success text-white rounded-4">
            <div class="card-body">
              <i class="fas fa-users fa-2x mb-2"></i>
              <h2 class="fw-bold">{{ $patientCount }}</h2>
              <p class="mb-0">Total Patients</p>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="card shadow-sm border-0 text-center bg-warning text-white rounded-4">
            <div class="card-body">
              <i class="fas fa-prescription-bottle-alt fa-2x mb-2"></i>
              <h2 class="fw-bold">{{ $prescriptionsCount ?? 0 }}</h2>
              <p class="mb-0">Prescriptions Written</p>
            </div>
          </div>
        </div>
      </div>


      <!-- ✅ Appointments Table -->
      <div class="card shadow-sm border-0 p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
          <h2 class="fw-bold mb-2 mb-md-0 text-primary">
          <i class="fas fa-calendar-check me-2"></i>Manage Appointments
        </h2>
      </div>

      <!-- ✅ Filter & Search Controls -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
  <div class="d-flex gap-2">
    <input type="text" id="searchInput" class="form-control" placeholder="Search by patient name...">
    <select id="statusFilter" class="form-select">
      <option value="">All Status</option>
      <option value="pending">Pending</option>
      <option value="approved">Approved</option>
      <option value="denied">Denied</option>
      <option value="cancelled">Cancelled</option>
    </select>
  </div>
</div>

      <!-- ✅ Appointments Table -->
      <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="bg-primary text-white text-center">
                <tr>
                  <th>Profile</th>
                  <th>Patient</th>
                  <th>Doctor</th>
                  <th>Date</th>
                  <th>Time</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="appointmentsTable" class="text-center">
  @forelse($appointments as $appt)
    <tr class="align-middle">
      <!-- Profile -->
      <td>
        <img src="{{ $appt->patient?->profile_picture ? asset($appt->patient->profile_picture) : asset('img/default-avatar.png') }}"
             alt="Profile"
             class="rounded-circle shadow-sm border"
             style="width:50px; height:50px; object-fit:cover;">
      </td>

      <!-- Patient -->
      <td class="fw-semibold patient-name">
        {{ $appt->patient?->firstname }} {{ $appt->patient?->lastname }}
      </td>

      <!-- Doctor -->
      <td>{{ $appt->doctor?->firstname }} {{ $appt->doctor?->lastname }}</td>

      <!-- Date -->
      <td>{{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y') }}</td>

      <!-- Time -->
      <td>
        @if($appt->slot)
          {{ \Carbon\Carbon::parse($appt->slot->start_time)->format('h:i A') }} -
          {{ \Carbon\Carbon::parse($appt->slot->end_time)->format('h:i A') }}
        @else
          <em class="text-muted">No slot</em>
        @endif
      </td>

      <!-- Status -->
      <td class="status-cell">
        <span class="badge px-3 py-2 rounded-pill text-capitalize 
          @if($appt->status == 'pending') bg-warning text-dark 
          @elseif($appt->status == 'approved') bg-success 
          @elseif($appt->status == 'denied') bg-danger 
          @elseif($appt->status == 'cancelled') bg-secondary 
          @else bg-info text-dark @endif">
          {{ $appt->status }}
        </span>
      </td>

      <!-- Actions -->
      <td>
        <div class="d-flex justify-content-center gap-2 flex-wrap">
          <form action="{{ route('doctor.appointments.approve', $appt->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3 shadow-sm">
              <i class="fas fa-check me-1"></i> Approve
            </button>
          </form>

          <form action="{{ route('doctor.view-appointment.deny', $appt->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 shadow-sm">
              <i class="fas fa-times me-1"></i> Deny
            </button>
          </form>
        </div>
      </td>
    </tr>
  @empty
    <tr>
      <td colspan="7" class="text-muted py-5">
        <i class="fas fa-inbox fa-2x mb-2"></i><br>
        No appointments found.
      </td>
    </tr>
  @endforelse
</tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<style>
  .table-hover tbody tr:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
    transition: all 0.2s ease;
  }

  .card {
    border-radius: 1rem;
  }

  .badge {
    font-size: 0.85rem;
  }

  .btn {
    transition: 0.2s ease-in-out;
  }

  .btn:hover {
    transform: translateY(-2px);
  }
</style>

<script src="{{ asset('js/notification.js') }}"></script>

@endsection

