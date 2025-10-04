@extends('layouts.layout')
@section('title', 'Doctor Dashboard | MediCare')

@section('content')
@include('includes.doctornavbar')
@include('includes.doctorsidebar')


<div class="container-fluid">
  <div class="row">

<!-- ✅ Main Content -->
    <div class="col-md-9 col-lg-10 offset-md-3 offset-lg-2 p-4">
            <a class="navbar-brand d-flex align-items-center mb-3 h1 p-1 rounded shadow-sm" 
   href="{{ route('admin.admin-dashboard') }}" 
   style="background-color: #f8f9fa; padding: 5px 5px;">
  <i data-feather="user" class="text-primary me-2"></i>
  <span class="fw-bold fs-10 fs-md-10">
    Welcome Dr. {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}!
  </span>
</a>
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
          <h3 class="fw-bold mb-2 mb-md-0">View All Appointments</h3>
        </div>

        <div class="table-responsive rounded-3 shadow-sm">
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
                         alt="Profile" class="rounded-circle shadow-sm"
                         style="width:50px; height:50px; object-fit:cover;">
                  </td>
                  <td>{{ $appt->patient?->firstname }} {{ $appt->patient?->lastname }}</td>
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
                  <td>
                    <div class="d-flex justify-content-center gap-2">
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

<script>
  const notifUrl = "{{ route('doctor.notifications.fetch') }}";
</script>
<script src="{{ asset('js/notification.js') }}"></script>


<!-- JS -->
<script>
const sidebar = document.getElementById('sidebar');
const mainContent = document.getElementById('mainContent');
const toggleBtn = document.getElementById('sidebarToggle');
const sidebarClose = document.getElementById('sidebarClose');

// Hamburger toggle (mobile)
toggleBtn.addEventListener('click', () => {
  sidebar.style.transform = 'translateX(0)';
});

// Close button (mobile)
sidebarClose.addEventListener('click', () => {
  sidebar.style.transform = 'translateX(-100%)';
});

// Click outside to close sidebar (mobile)
document.addEventListener('click', function(e){
  if(window.innerWidth < 992){
    if(!sidebar.contains(e.target) && !toggleBtn.contains(e.target)){
      sidebar.style.transform = 'translateX(-100%)';
    }
  }
});

// Adjust main content margin based on sidebar (desktop)
function handleResize() {
  if(window.innerWidth >= 992){
    sidebar.style.transform = 'translateX(0)';
    mainContent.style.marginLeft = '250px'; // match sidebar width
  } else {
    sidebar.style.transform = 'translateX(-100%)';
    mainContent.style.marginLeft = '0';
  }
}

window.addEventListener('resize', handleResize);
window.addEventListener('load', handleResize);
</script>

@endsection

