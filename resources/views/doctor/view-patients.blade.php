@extends('layouts.layout')
@section('title', 'Doctor Dashboard | MediCare')

@section('content')
@include('includes.doctornavbar')
@include('includes.doctorsidebar')

<div class="container-fluid">
  <div class="row">

<!-- Main Content -->
    <div class="col-md-9 col-lg-10 offset-md-3 offset-lg-2 p-4">
        <h3 class="fw-bold mb-3">View All Patients</h3>
  <div class="table-responsive rounded-4 shadow-sm">
    <table class="table table-hover align-middle text-center mb-0">
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
  @forelse($patients as $patient)
  <tr>
    <td>
      @if($patient->profile_picture)
        <img src="{{ asset($patient->profile_picture) }}" 
             class="rounded-circle" 
             style="width:50px; height:50px; object-fit:cover;">
      @else
        <img src="{{ asset('img/default-avatar.png') }}" 
             class="rounded-circle" 
             style="width:50px; height:50px; object-fit:cover;">
      @endif
    </td>
    <td>{{ $patient->firstname }} {{ $patient->lastname }}</td>
    <td>{{ $patient->email }}</td>
   <td>
  {{-- show latest appointment date & status --}}
  @php
    $latestAppt = $patient->appointments()
                    ->where('doctor_id', auth()->id())
                    ->with('slot') // ✅ eager load slot
                    ->orderBy('appointment_date', 'desc')
                    ->orderBy('appointment_time', 'desc')
                    ->first();
  @endphp

  @if($latestAppt)
    {{ \Carbon\Carbon::parse($latestAppt->appointment_date)->format('M d, Y') }}
    <br>
    @if($latestAppt->slot)
      {{ \Carbon\Carbon::parse($latestAppt->slot->start_time)->format('h:i A') }} - 
      {{ \Carbon\Carbon::parse($latestAppt->slot->end_time)->format('h:i A') }}
    @else
      {{ \Carbon\Carbon::parse($latestAppt->appointment_time)->format('h:i A') }}
    @endif
  @else
    <em>No appointment</em>
  @endif
</td>

    <td>
      @if($latestAppt)
        @if($latestAppt->status == 'pending')
          <span class="badge bg-warning text-dark">Pending</span>
        @elseif($latestAppt->status == 'approved')
          <span class="badge bg-success">Approved</span>
        @elseif($latestAppt->status == 'denied')
          <span class="badge bg-danger">Denied</span>
        @elseif($latestAppt->status == 'cancelled')
          <span class="badge bg-secondary">Cancelled</span>
        @else
          <span class="badge bg-info">{{ ucfirst($latestAppt->status) }}</span>
        @endif
      @else
        <span class="badge bg-light text-muted">No Status</span>
      @endif
    </td>
  </tr>
  @empty
  <tr>
    <td colspan="5">No patients found.</td>
  </tr>
  @endforelse
</tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>

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
<script>
  const notifUrl = "{{ route('doctor.notifications.fetch') }}";
</script>
<script src="{{ asset('js/notification.js') }}"></script>

@endsection
