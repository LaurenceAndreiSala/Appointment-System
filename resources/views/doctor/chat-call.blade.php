@extends('layouts.layout')
@section('title', 'Doctor Calls | MediCare')

@section('content')
@include('includes.doctornavbar')
@include('includes.doctorsidebar')

<div class="container-fluid">
  <div class="row">

  <!-- ✅ Main Content -->
<div class="col-12 col-md-9 col-lg-10 offset-lg-2  p-4 p-md-2">
  <div class="bg-light rounded-4 shadow-sm p-4 mb-4 d-flex align-items-center">
    <i class="fas fa-video text-primary fa-2x me-3"></i>
    <h3 class="fw-bold mb-0 text-dark">Upcoming Video Appointments</h3>
  </div>

    <p class="text-muted mb-4">Start or join calls with your patients.</p>

  <!-- ✅ Appointments Table -->
  <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle text-center mb-0">
          <thead class="bg-primary text-white">
            <tr>
              <th>Patient</th>
              <th>Date</th>
              <th>Time</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($appointments as $appt)
              @if($appt->status == 'approved')
                <tr class="align-middle">
                  <td class="fw-semibold">{{ $appt->patient?->firstname }} {{ $appt->patient?->lastname }}</td>
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
                    <button type="button"
                            class="btn btn-success start-call-btn rounded-pill shadow-sm px-3 py-1"
                            data-appointment-id="{{ $appt->id }}">
                      <i class="fas fa-phone-alt me-1"></i> Start Call
                    </button>
                  </td>
                </tr>
              @endif
            @empty
              <tr>
                <td colspan="4" class="text-muted py-5">
                  <i class="fas fa-inbox fa-2x mb-2"></i><br>
                  No meetings scheduled.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<!-- ✅ Styles -->
<style>
.table-hover tbody tr:hover {
  background-color: #f8f9fa;
  transform: scale(1.01);
  transition: all 0.2s ease;
}

.card {
  border-radius: 1rem;
}

.btn {
  transition: 0.2s ease-in-out;
}

.btn:hover {
  transform: translateY(-2px);
}
</style>


<!-- ✅ Notification + Call Script -->
<script>
  const notifUrl = "{{ route('doctor.notifications.fetch') }}";
</script>
<script src="{{ asset('js/notification.js') }}"></script>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
document.addEventListener("click", async (e) => {
  const btn = e.target.closest(".start-call-btn");
  if (!btn) return;

  const appointmentId = btn.dataset.appointmentId;
  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Starting...';

  try {
    const response = await fetch(`/doctor/start-call/${appointmentId}`, {
      method: "POST",
      headers: {
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
        "Accept": "application/json",
        "Content-Type": "application/json"
      },
      body: JSON.stringify({})
    });

    const data = await response.json();

    if (data.success && data.meeting_url) {
      alert("✅ Meeting started! Share this link with your patient:\n" + data.meeting_url);
      window.open(data.meeting_url, "_blank");
    } else {
      alert("❌ Unable to start the meeting. Please try again.");
      console.error("Error:", data.error);
    }
  } catch (error) {
    console.error("Fetch error:", error);
    alert("⚠️ Network error. Please try again later.");
  } finally {
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-phone-alt me-1"></i> Start Call';
  }
});
</script>

@endsection
