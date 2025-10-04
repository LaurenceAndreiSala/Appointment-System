@extends('layouts.layout')
@section('title', 'Book Appointment | MediCare')

@section('content')
@include('includes.patientNavbar')

<div class="container-fluid">
  <div class="row">

    @include('includes.patientsidebar')

    <!-- ✅ Main Content -->
    <div class="col-12 col-md-9 col-lg-10 p-4 p-md-5">
      <h2 class="fw-bold">Book Appointment</h2>
      <p class="text-muted">Please select your appointment date and time below.</p>

         @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <div class="row">
        <!-- ✅ Date Section -->
        <div class="col-md-6 mb-4">
          <h5 class="fw-bold">Date</h5>
          <p class="text-success">
            Earliest available appointment:
            <strong>{{ \Carbon\Carbon::parse($slots->where('is_taken', false)->first()->date ?? now())->format('d F Y') }}</strong>
          </p>
          <p class="text-danger">To the extent possible, additional slots are made regularly.</p>

          <!-- Dynamic Calendar -->
          <div class="border rounded p-3 text-center">
  <div class="d-flex justify-content-between mb-2">
    <button id="prevMonth" class="btn btn-sm btn-light">&laquo;</button>
    <span id="calendarMonth" class="fw-bold">{{ now()->format('F Y') }}</span>
    <button id="nextMonth" class="btn btn-sm btn-light">&raquo;</button>
  </div>

  <div id="calendarContainer">
    {{-- Calendar will be dynamically injected here --}}
    @include('partials.calendar', ['slots' => $slots, 'year' => now()->year, 'month' => now()->month])
  </div>

  <div class="d-flex justify-content-between mt-2">
    <span class="badge bg-success">Available</span>
    <span class="badge bg-danger">Fully Booked</span>
  </div>
</div>
        </div>

        <!-- ✅ Time Section -->
        <div class="col-md-6 mb-4">
  <h5 class="fw-bold">Time</h5>
  <form action="{{ route('patient.appointments.store') }}" method="POST">
    @csrf
    <input type="hidden" name="doctor_id" id="doctor_id">

    <div id="timeSlotsContainer">
      <p class="text-muted">Click a date on the calendar to view available times.</p>
    </div>

    <div class="d-flex justify-content-between mt-4">
  <a href="{{ route('patient.book-appointment') }}" class="btn btn-secondary">Back</a>

            <button type="submit" id="bookBtn" class="btn btn-primary" disabled data-bs-toggle="modal">Book Appointment</button>

    </div>
  </form>
</div>
      </div>

    </div>
  </div>
</div>

<!-- ✅ Confirmation Modal -->
<div class="modal fade" id="confirmBookingModal" tabindex="-1" aria-labelledby="confirmBookingLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-3 shadow">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="confirmBookingLabel">Confirm Appointment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="mb-2">You are about to book an appointment:</p>
        <ul class="list-unstyled">
          <li><strong>Date:</strong> <span id="confirmDate"></span></li>
          <li><strong>Time:</strong> <span id="confirmTime"></span></li>
        </ul>
        <p class="text-muted small">Please confirm your selection before proceeding.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmBtn" class="btn btn-primary">Confirm Booking</button>
      </div>
    </div>
  </div>
</div>

<!-- ✅ Call Popup Modal -->
<div class="modal fade" id="incomingCallModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-3 border-0 shadow-lg">
      <h5 id="callerName" class="fw-bold mt-2"></h5>
      <div class="d-flex justify-content-center mt-3 mb-2">
        <button id="acceptCall" class="btn btn-success me-3 px-4">
          <i class="fas fa-phone-alt me-1"></i> Accept
        </button>
        <button id="rejectCall" class="btn btn-danger px-4">
          <i class="fas fa-phone-slash me-1"></i> Reject
        </button>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const bookBtn = document.getElementById("bookBtn");

    // Watch for radio selection changes
    document.addEventListener("change", function (e) {
        if (e.target.name === "slot_id") {
            if (e.target.checked) {
                bookBtn.removeAttribute("disabled");
            }
        }
    });

    // Reset button state when switching dates
    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("calendar-day") && e.target.dataset.date) {
            bookBtn.setAttribute("disabled", true);
        }
    });
});


document.addEventListener("DOMContentLoaded", function () {
    let currentMonth = {{ now()->month }};
    let currentYear = {{ now()->year }};

    function loadCalendar(year, month) {
        fetch(`/appointments/slots/${year}/${month}`)
            .then(res => res.json())
            .then(data => {
                // Replace calendarContainer content with new Blade partial via AJAX
                fetch(`/appointments/render-calendar/${year}/${month}`)
                    .then(r => r.text())
                    .then(html => {
                        document.getElementById("calendarContainer").innerHTML = html;
                        document.getElementById("calendarMonth").innerText =
                            new Date(year, month - 1).toLocaleString("default", { month: "long", year: "numeric" });
                    });
            });
    }

    document.getElementById("prevMonth").addEventListener("click", function () {
        currentMonth--;
        if (currentMonth < 1) { currentMonth = 12; currentYear--; }
        loadCalendar(currentYear, currentMonth);
    });

    document.getElementById("nextMonth").addEventListener("click", function () {
        currentMonth++;
        if (currentMonth > 12) { currentMonth = 1; currentYear++; }
        loadCalendar(currentYear, currentMonth);
    });
});

document.addEventListener("DOMContentLoaded", function () {
    // Delegate click handler for calendar days
    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("calendar-day") && e.target.dataset.date) {
            let date = e.target.dataset.date;

            fetch(`/appointments/day-slots/${date}`)
                .then(res => res.text())
                .then(html => {
                    document.getElementById("timeSlotsContainer").innerHTML = html;
                });
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const bookBtn = document.getElementById("bookBtn");
    const confirmBtn = document.getElementById("confirmBtn");
    const confirmDate = document.getElementById("confirmDate");
    const confirmTime = document.getElementById("confirmTime");
    const form = document.querySelector("form");

    
    // When opening modal, fill date/time
    bookBtn.addEventListener("click", function () {
        const selected = document.querySelector('input[name="slot_id"]:checked');
        if (selected) {
            const label = selected.closest("label");
            const timeText = label.childNodes[2].textContent.trim(); // ✅ only the time
            const dateCell = document.querySelector(".calendar-day.selected");

            // Show date & time in modal
            confirmTime.textContent = timeText;
            confirmDate.textContent = dateCell ? new Date(dateCell.dataset.date).toLocaleDateString("en-US", {
                weekday: "long",
                year: "numeric",
                month: "long",
                day: "numeric"
            }) : "N/A";

            // ✅ Set doctor_id value before submit
document.getElementById("doctor_id").value = selected.dataset.doctorId;
        }
    });

    // When confirmed, submit form
    confirmBtn.addEventListener("click", function () {
        form.submit();
    });

    // Highlight selected day
    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("calendar-day")) {
            document.querySelectorAll(".calendar-day").forEach(el => el.classList.remove("selected", "border", "border-dark"));
            e.target.classList.add("selected", "border", "border-dark");
        }
    });
});

</script>

<script>
   const fetchNotificationsUrl = "{{ route('patient.notifications.fetch') }}";
</script>
<script src="{{ asset('js/notificationcall.js') }}"></script>

{{-- ✅ Ringing Popup JS --}}
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>
const userId = "{{ Auth::id() }}";

const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
  cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
  forceTLS: true
});

const channel = pusher.subscribe("appointments." + userId);

let ringtone;

channel.bind("App\\Events\\CallStarted", function(data) {
  // create ringtone only once
  if (!ringtone) {
    ringtone = new Audio("{{ asset('sounds/ringtone.mp3') }}");
    ringtone.loop = true;
  }

  // show modal
  document.getElementById("callerName").innerText =
    `📞 Dr. ${data.appointment.doctor.firstname} is calling...`;

  const callModal = new bootstrap.Modal(document.getElementById("incomingCallModal"));
  callModal.show();

  // play ringtone when modal buttons are clicked
  ringtone.play().catch(err => console.warn("Autoplay blocked until user interacts"));

  document.getElementById("acceptCall").onclick = () => {
    ringtone.pause();
    callModal.hide();
    window.open(data.appointment.meeting_url, "_blank");
  };

  document.getElementById("rejectCall").onclick = () => {
    ringtone.pause();
    callModal.hide();
    alert("❌ You rejected the call.");
  };
});

</script>
@endsection
