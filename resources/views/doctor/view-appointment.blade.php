@extends('layouts.layout')
@section('title', 'Doctor Dashboard | MediCare')

@section('content')
@include('includes.doctornavbar')
@include('includes.doctorsidebar')

<div class="container-fluid">
  <div class="row">

<!-- Main Content -->
    <div class="col-md-9 col-lg-10 offset-md-3 offset-lg-2 p-4">

       <!-- ✅ View All Appointments Table -->
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
    <h3 class="fw-bold mb-2 mb-md-0">View All Appointments</h3>
  </div>

  <!-- Responsive Table Wrapper -->
  <div class="table-responsive rounded-4 shadow-sm">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-dark text-center">
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
      <tbody class="text-center">
        @forelse($appointments as $appt)
          <tr>
            <td>
              <img src="{{ $appt->patient?->profile_picture ? asset($appt->patient->profile_picture) : asset('img/default-avatar.png') }}" 
                   alt="Profile" 
                   class="rounded-circle shadow-sm"
                   style="width:45px; height:45px; object-fit:cover;">
            </td>
            <td class="fw-semibold">{{ $appt->patient?->firstname }} {{ $appt->patient?->lastname }}</td>
            <td>{{ $appt->doctor?->firstname }} {{ $appt->doctor?->lastname }}</td>
            <td>{{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y') }}</td>
            <td>
              @if($appt->slot)
                {{ \Carbon\Carbon::parse($appt->slot->start_time)->format('h:i A') }} -
                {{ \Carbon\Carbon::parse($appt->slot->end_time)->format('h:i A') }}
              @else
                <em class="text-muted">No slot</em>
              @endif
            </td>
            <td>
              <span class="badge px-3 py-2 rounded-pill 
                @if($appt->status == 'pending') bg-warning text-dark 
                @elseif($appt->status == 'approved') bg-success 
                @elseif($appt->status == 'denied') bg-danger 
                @elseif($appt->status == 'cancelled') bg-secondary 
                @else bg-info @endif">
                {{ ucfirst($appt->status) }}
              </span>
            </td>
            <td>
              <div class="d-flex justify-content-center gap-2 flex-wrap">
                <form action="{{ route('doctor.appointments.approve', $appt->id) }}" method="POST">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-outline-success px-3">Approve</button>
                </form>
                <form action="{{ route('doctor.view-appointment.deny', $appt->id) }}" method="POST">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-outline-danger px-3">Deny</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-muted py-4">No appointments found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
    </div>

  </div>
</div>

<!-- Sidebar Toggle Script -->
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("sidebarToggle");
    const closeBtn = document.getElementById("sidebarClose");

    toggleBtn.addEventListener("click", () => {
      sidebar.classList.add("active");
    });

    closeBtn.addEventListener("click", () => {
      sidebar.classList.remove("active");
    }); 
  });
</script>
<script>
  const notifUrl = "{{ route('doctor.notifications.fetch') }}";
</script>
<script src="{{ asset('js/notification.js') }}"></script>
@endsection

