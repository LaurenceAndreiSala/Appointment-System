@extends('layouts.layout')
@section('title', 'My Appointments | MediCare')

@section('content')
@include('includes.patientNavbar')


<div class="container-fluid">
  <div class="row">

      @include('includes.patientsidebar')

    <!-- ✅ Main Content -->
<div class="col-12 col-md-9 col-lg-10 offset-lg-2  p-4 p-md-2">
      <div class="bg-light rounded-4 shadow-sm p-4 mb-4 d-flex align-items-center">
    <i class="fas fa-clock text-primary fa-2x me-3"></i>
        <h3 class="fw-bold mb-0 text-dark">My Appointments</h3>
      </div>

      <p class="text-muted mb-4">Here are view all your appointments.</p>


      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <div class="card shadow-sm border-0">
        <div class="card-body">
          <style>
/* 🟩 Responsive table-to-card design */
@media (max-width: 768px) {
  table thead {
    display: none;
  }
  table tbody tr {
    display: block;
    background: #fff;
    margin-bottom: 1rem;
    border-radius: 0.75rem;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
  }
  table tbody td {
    display: flex;
    justify-content: space-between;
    text-align: left;
    padding: 0.75rem 1rem;
    border: none;
    border-bottom: 1px solid #f0f0f0;
  }
  table tbody td:last-child {
    border-bottom: none;
  }
  table tbody td::before {
    content: attr(data-label);
    font-weight: 600;
    color: #333;
  }
}
</style>

<div class="table-responsive">
  <table class="table table-bordered table-striped align-middle text-center mb-0">
    <thead class="table-dark">
      <tr>
        <th>Doctor</th>
        <th>Date & Time</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @forelse($appointments as $appt)
        <tr>
          <td data-label="Doctor">
            {{ $appt->doctor?->firstname }} {{ $appt->doctor?->lastname }}
          </td>
          <td data-label="Date & Time">
            {{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y') }} <br>
            @if($appt->slot)
              {{ \Carbon\Carbon::parse($appt->slot->start_time)->format('h:i A') }} -
              {{ \Carbon\Carbon::parse($appt->slot->end_time)->format('h:i A') }}
            @else
              <em>No slot assigned</em>
            @endif
          </td>
          <td data-label="Status">
            @switch($appt->status)
              @case('pending') <span class="badge bg-warning text-dark">Pending</span> @break
              @case('approved') <span class="badge bg-success">Approved</span> @break
              @case('denied') <span class="badge bg-danger">Denied</span> @break
              @case('cancelled') <span class="badge bg-secondary">Cancelled</span> @break
              @default <span class="badge bg-info">{{ ucfirst($appt->status) }}</span>
            @endswitch
          </td>
          <td data-label="Action">
            <div class="d-flex flex-column gap-2">
              @if($appt->status == 'pending')
                <form action="{{ route('patient.cancel', $appt->id) }}" method="POST">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-secondary w-100">Cancel</button>
                </form>

              @elseif($appt->status == 'approved' && !$appt->payment)
                <button type="button" 
                        class="btn btn-sm btn-warning payNowBtn w-100" 
                        data-bs-toggle="modal" 
                        data-bs-target="#paymentModal"
                        data-id="{{ $appt->id }}"
                        data-doctor="{{ $appt->doctor?->firstname }} {{ $appt->doctor?->lastname }}"
                        data-date="{{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y') }}"
                        data-time="{{ $appt->appointment_time ? \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') : '' }}"
                        data-amount="{{ $appt->amount ?? 500 }}">
                  <i class="fas fa-coins"></i> Pay Now
                </button>

              @elseif($appt->payment && $appt->payment->payment_status == 'success')
                <span class="badge bg-success">Paid</span><br>
                <small>Ref: {{ $appt->payment->reference_number }}</small><br>
                <button type="button" 
                        class="btn btn-sm btn-outline-info mt-1 receiptBtn w-100" 
                        data-bs-toggle="modal" 
                        data-bs-target="#receiptModal"
                        data-url="{{ route('patient.payment.receipt', $appt->payment->id) }}">
                  <i class="fas fa-download"></i> Receipt
                </button>
              @endif
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="4" class="text-muted py-4">You have no appointments.</td>
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

<!-- ✅ Receipt Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="receiptModalLabel">Payment Receipt</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body p-0" style="height: 80vh;">
        <iframe id="receiptIframe" src="" style="width:100%; height:100%;" frameborder="0"></iframe>
      </div>

    </div>
  </div>
</div>


<!-- ✅ Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="paymentModalLabel">Payment for Appointment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      
      <form id="paymentForm" method="POST">
        @csrf
        <div class="modal-body">
          <p><strong>Doctor:</strong> <span id="modalDoctor"></span></p>
          <p><strong>Date:</strong> <span id="modalDate"></span></p>
          <p><strong>Time:</strong> <span id="modalTime"></span></p>
<div class="mb-3">
  <label class="form-label">Amount</label>
  <input type="number" name="amount" id="modalAmount" class="form-control" readonly>
</div>

          <div class="mb-3">
            <label class="form-label">Payment Method</label>
            
            <!-- Custom dropdown -->
            <div class="dropdown">
              <button class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-between" 
                      type="button" id="paymentDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <span id="selectedMethod">-- Select Method --</span>
                <i class="fas fa-chevron-down ms-2"></i>
              </button>
              <ul class="dropdown-menu w-100" aria-labelledby="paymentDropdown">
                <li>
                  <a class="dropdown-item d-flex align-items-center payment-option" href="#" data-value="gcash" data-text="GCash">
                    <img src="{{ asset('img/gcash.png') }}" class="me-2" style="width:30px; height:24px;"> GCash
                  </a>
                </li>
                <li>
                  <a class="dropdown-item d-flex align-items-center payment-option" href="#" data-value="paypal" data-text="PayPal">
                    <img src="{{ asset('img/paypal.png') }}" class="me-2" style="width:30px; height:24px;"> PayPal
                  </a>
                </li>
                <li>
                  <a class="dropdown-item d-flex align-items-center payment-option" href="#" data-value="credit_card" data-text="Credit Card">
                    <img src="{{ asset('img/credit-card.png') }}" class="me-2" style="width:30px; height:24px;"> Credit Card
                  </a>
                </li>
              </ul>
            </div>

            <!-- hidden input for form submission -->
            <input type="hidden" name="payment_method" id="paymentMethod" required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" id="payBtn" class="btn btn-success" disabled>
            <i class="fas fa-check-circle"></i> Pay
          </button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>

    </div>
  </div>
</div>

<!-- ✅ Fake GCash Checkout Modal -->
<div class="modal fade" id="gcashCheckoutModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header bg-info text-white">
        <h5 class="modal-title">Mock GCash Checkout</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body text-center">
        <h4>GCash Payment Simulation</h4>
        <p><strong>Doctor:</strong> <span id="gcashDoctor"></span></p>
        <p><strong>Date:</strong> <span id="gcashDate"></span></p>
        <p><strong>Time:</strong> <span id="gcashTime"></span></p>
        <p><strong>Amount:</strong> ₱<span id="gcashAmount"></span></p>

        <form id="gcashPayForm" method="POST">
          @csrf
          <button type="submit" class="btn btn-success">
            <i class="fas fa-check-circle"></i> Simulate Pay with GCash
          </button>
        </form>
      </div>

    </div>
  </div>
</div>`

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
  const payButtons = document.querySelectorAll(".payNowBtn");
  const modalDoctor = document.getElementById("modalDoctor");
  const modalDate = document.getElementById("modalDate");
  const modalTime = document.getElementById("modalTime");
  const modalAmount = document.getElementById("modalAmount");
  const paymentForm = document.getElementById("paymentForm");

  const receiptButtons = document.querySelectorAll(".receiptBtn");
  const receiptIframe = document.getElementById("receiptIframe");

  const paymentOptions = document.querySelectorAll(".payment-option");
  const selectedMethod = document.getElementById("selectedMethod");
  const paymentMethodInput = document.getElementById("paymentMethod");
  const payBtn = document.getElementById("payBtn");

  const gcashDoctor = document.getElementById("gcashDoctor");
  const gcashDate = document.getElementById("gcashDate");
  const gcashTime = document.getElementById("gcashTime");
  const gcashAmount = document.getElementById("gcashAmount");
  const gcashPayForm = document.getElementById("gcashPayForm");

  let apptId = null; // keep track of current appointment id

  // ✅ Pay Now button click → fill modal
  payButtons.forEach(btn => {
    btn.addEventListener("click", function () {
      apptId = this.dataset.id;
      modalDoctor.textContent = this.dataset.doctor;
      modalDate.textContent = this.dataset.date;
      modalTime.textContent = this.dataset.time;
      modalAmount.value = this.dataset.amount;

      paymentForm.action = `{{ url('/patient/payment') }}/${apptId}/process`;
    });
  });

  // ✅ Receipt button → load iframe
  receiptButtons.forEach(btn => {
    btn.addEventListener("click", function () {
      receiptIframe.src = this.dataset.url;
    });
  });

  document.getElementById("receiptModal").addEventListener("hidden.bs.modal", function () {
    receiptIframe.src = "";
  });

  // ✅ Select payment method
  paymentOptions.forEach(option => {
    option.addEventListener("click", function (e) {
      e.preventDefault();
      const value = this.dataset.value;
      const text = this.dataset.text;
      const icon = this.querySelector("img").outerHTML;

      selectedMethod.innerHTML = icon + " " + text;
      paymentMethodInput.value = value;
      payBtn.disabled = false;
    });
  });

  // ✅ Handle submit
  paymentForm.addEventListener("submit", function (e) {
    const method = paymentMethodInput.value;

    if (method === "gcash") {
      e.preventDefault();

      // Fill fake GCash modal
      gcashDoctor.textContent = modalDoctor.textContent;
      gcashDate.textContent = modalDate.textContent;
      gcashTime.textContent = modalTime.textContent;
      gcashAmount.textContent = modalAmount.value;

      // ✅ Correct action for mock GCash
      gcashPayForm.action = `{{ url('/patient/payment') }}/${apptId}/gcash/mock/pay`;

      // Show modal
      new bootstrap.Modal(document.getElementById("gcashCheckoutModal")).show();
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
