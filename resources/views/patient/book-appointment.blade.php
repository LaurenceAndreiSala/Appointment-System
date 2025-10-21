@extends('layouts.layout')
@section('title', 'Book Appointment | MediCare')

@section('content')
@include('includes.patientNavbar')

<div class="container-fluid">
  <div class="row">

    @include('includes.patientsidebar')

    <!-- ✅ Main Content -->
<div class="col-12 col-md-9 col-lg-10 offset-lg-2  p-4 p-md-2">
      <div class="bg-light rounded-4 shadow-sm p-4 mb-4 d-flex align-items-center">
        <i class="fas fa-calendar-plus text-primary fa-2x me-3"></i>
        <h3 class="fw-bold mb-0 text-dark">Book Appointment</h3>
      </div>

      <p class="text-muted mb-4">Please select your preferred date and time for your appointment below.</p>

      @if(session('success'))
        <div class="alert alert-success shadow-sm rounded-3">{{ session('success') }}</div>
      @endif

      <div class="row g-4">
        <!-- ✅ Date Selection -->
        <div class="col-12 col-lg-6">
          <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
            <h5 class="fw-bold text-primary mb-3"><i class="fas fa-calendar-day me-2"></i>Select Date</h5>

            <p class="text-success small mb-1">
              Earliest available appointment:
              <strong>{{ \Carbon\Carbon::parse($slots->where('is_taken', false)->first()->date ?? now())->format('d F Y') }}</strong>
            </p>
            <p class="text-danger small mb-3">Additional slots are made available regularly.</p>

            <div class="border rounded-4 p-3 bg-white shadow-sm">
              <div class="d-flex justify-content-between mb-2">
                <button id="prevMonth" class="btn btn-sm btn-outline-primary rounded-pill">&laquo;</button>
                <span id="calendarMonth" class="fw-bold text-primary">{{ now()->format('F Y') }}</span>
                <button id="nextMonth" class="btn btn-sm btn-outline-primary rounded-pill">&raquo;</button>
              </div>

              <div id="calendarContainer">
                @include('partials.calendar', ['slots' => $slots, 'year' => now()->year, 'month' => now()->month])
              </div>

              <div class="d-flex justify-content-between mt-3 small">
                <span class="badge bg-success px-3 py-2">Available</span>
                <span class="badge bg-danger px-3 py-2">Fully Booked</span>
              </div>
            </div>
          </div>
        </div>

        <!-- ✅ Time Selection -->
        <div class="col-12 col-lg-6">
          <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
            <h5 class="fw-bold text-primary mb-3"><i class="fas fa-clock me-2"></i>Select Time</h5>

          <form id="appointmentForm" action="{{ secure_url(route('patient.appointments.store', [], false)) }}" method="POST">
    @csrf
    <input type="hidden" name="doctor_id" id="doctor_id">
    <input type="hidden" name="slot_id" id="slot_id">

              <div id="timeSlotsContainer" class="border rounded-4 bg-light p-3 text-center">
                <p class="text-muted mb-0">Click a date on the calendar to view available time slots.</p>
              </div>

              <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('patient.book-appointment') }}" class="btn btn-outline-secondary rounded-pill px-4">
                  <i class="fas fa-arrow-left me-2"></i>Back
                </a>

                <button type="button" id="bookBtn" class="btn btn-primary rounded-pill px-4" disabled data-bs-toggle="modal" data-bs-target="#confirmBookingModal">
                  <i class="fas fa-check-circle me-2"></i>Book Appointment
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- ✅ Confirmation Modal -->
<div class="modal fade" id="confirmBookingModal" tabindex="-1" aria-labelledby="confirmBookingLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 shadow-lg border-0">
      <div class="modal-header bg-primary text-white rounded-top-4">
        <h5 class="modal-title fw-bold" id="confirmBookingLabel"><i class="fas fa-clipboard-check me-2"></i>Confirm Appointment</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <p>You are about to book an appointment:</p>
        <ul class="list-unstyled mb-3">
          <li><strong>Date:</strong> <span id="confirmDate" class="text-primary"></span></li>
          <li><strong>Time:</strong> <span id="confirmTime" class="text-success"></span></li>
        </ul>
        <p class="text-muted small">Please confirm your details before proceeding.</p>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmBtn" class="btn btn-primary rounded-pill px-4">Confirm Booking</button>
      </div>
    </div>
  </div>
</div>

<!-- ✅ Call Popup Modal -->
<div class="modal fade" id="incomingCallModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-3 border-0 shadow-lg rounded-4">
      <h5 id="callerName" class="fw-bold mt-2"></h5>
      <div class="d-flex justify-content-center mt-3 mb-2">
        <button id="acceptCall" class="btn btn-success me-3 px-4 rounded-pill">
          <i class="fas fa-phone-alt me-1"></i> Accept
        </button>
        <button id="rejectCall" class="btn btn-danger px-4 rounded-pill">
          <i class="fas fa-phone-slash me-1"></i> Reject
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ✅ Styles -->
<style>
  .card {
    transition: 0.3s ease;
  }
  .card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
  }
  .calendar-day.selected {
    background-color: #0d6efd !important;
    color: #fff !important;
    border-radius: 8px;
  }
  @media (max-width: 768px) {
    .card { padding: 1.25rem; }
  }
</style>
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
    const selected = document.querySelector('input[name="slot_id"]:checked');
    if (!selected) {
        alert("Please select a time slot");
        return;
    }

    document.getElementById("doctor_id").value = selected.dataset.doctorId;
    document.getElementById("slot_id").value = selected.value;

    document.getElementById("appointmentForm").submit();
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
