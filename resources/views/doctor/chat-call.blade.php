@extends('layouts.layout')
@section('title', 'Doctor Dashboard | MediCare')

@section('content')
@include('includes.doctornavbar')
@include('includes.doctorsidebar')

<div class="container-fluid">
  <div class="row">

    <!-- Main Content -->
    <div class="col-md-9 col-lg-10 offset-md-3 offset-lg-2 p-4">
      <h3 class="fw-bold mb-3">Upcoming Appointments</h3>
  <div class="table-responsive rounded-4 shadow-sm">
    <table class="table table-hover align-middle text-center mb-0">
      <thead class="table-dark">
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
            <tr>
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
                        class="btn btn-success start-call-btn px-3 py-1"
                        data-appointment-id="{{ $appt->id }}">
                  📞 Start Call
                </button>
              </td>
            </tr>
          @endif
        @empty
          <tr>
            <td colspan="4" class="text-muted py-4">No meetings scheduled.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
      </div>
    </div>
  </div>
</div>

<script>
  const notifUrl = "{{ route('doctor.notifications.fetch') }}";
</script>
<script src="{{ asset('js/notification.js') }}"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
document.addEventListener("click", function(e) {
  const btn = e.target.closest(".start-call-btn");
  if (!btn) return;

  const appointmentId = btn.dataset.appointmentId;

  fetch(`/doctor/start-call/${appointmentId}`, {
    method: "POST",
    headers: {
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
        "Accept": "application/json",
        "Content-Type": "application/json"
    },
    body: JSON.stringify({})
  })
  .then(res => res.json())
.then(data => {
    if (data.success) {
        console.log("✅ Meeting URL:", data.meeting_url);
        alert("Meeting started! Share link: " + data.meeting_url);
        // Optionally auto-open for doctor
        window.open(data.meeting_url, "_blank");
    } else {
        console.error("❌ Error:", data.error || "Unable to start call");
    }
})
  .catch(err => console.error("❌ Fetch error:", err));
}); // 👈 You were missing this closing
</script>
@endsection
