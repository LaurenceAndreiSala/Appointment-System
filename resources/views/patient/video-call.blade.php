@extends('layouts.layout')
@section('title', 'Patient Dashboard | MediCare')

@section('content')
@include('includes.patientNavbar')

<div class="container-fluid">
  <div class="row">
    @include('includes.patientsidebar')

        <!-- ✅ Main Content -->
<div class="col-12 col-md-9 col-lg-10 offset-lg-2  p-4 p-md-2">
      <div class="bg-light rounded-4 shadow-sm p-4 mb-4 d-flex align-items-center">
    <i class="fas fa-video text-primary fa-2x me-3"></i>
        <h3 class="fw-bold mb-0 text-dark">Chat / Video Call</h3>
      </div>

      <p class="text-muted mb-4">Join your scheduled video consultations with your doctor.</p>

     <div class="card shadow-sm border-0 rounded-4 mb-4 p-4">
  <!-- Header -->
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
<span class="fw-bold mb-2 mb-md-0 text-primary" style="font-size: 1.15rem;">
      <i class="fas fa-calendar-check me-2"></i>My Approved Appointments
    </span>
  </div>

  <!-- Responsive Table -->
  <div class="table-responsive rounded-4 shadow-sm">
    <table class="table table-hover align-middle text-center mb-0">
      <thead class="bg-primary text-white">
        <tr>
          <th>Doctor</th>
          <th>Date</th>
          <th>Time</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($appointments as $appt)
          <tr>
            <td class="fw-semibold">
              Dr. {{ $appt->doctor?->firstname }} {{ $appt->doctor?->lastname }}
            </td>
            <td>
              {{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y') }}
            </td>
            <td>
              @if($appt->slot)
                {{ \Carbon\Carbon::parse($appt->slot->start_time)->format('h:i A') }} -
                {{ \Carbon\Carbon::parse($appt->slot->end_time)->format('h:i A') }}
              @else
                <em class="text-muted">No slot assigned</em>
              @endif
            </td>
            <td>
<button type="button"
        class="btn btn-primary chat-btn rounded-pill shadow-sm px-3 py-1 position-relative"
        data-receiver-id="{{ $appt->doctor_id ?? $appt->doctor_id }}"
        data-name="{{ $appt->doctor_id?->firstname ?? $appt->doctor?->firstname }} {{ $appt->doctor_id?->lastname ?? $appt->doctor?->lastname }}"
        data-profile_picture="{{ $appt->doctor?->profile_picture ? asset($appt->doctor->profile_picture) : asset('uploads/default-user.png') }}">
        <i class="fas fa-comments me-1"></i> Chat
  <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none" id="chatBadge{{ $appt->doctor_id ?? $appt->doctor_id }}">0</span>
</button>

</td>
          </tr>
        @empty
          <tr>
            <td colspan="3" class="text-muted py-5">
              <i class="fas fa-inbox fa-2x mb-2"></i><br>
              No approved meetings yet.
            </td>
          </tr>
          <td>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- ✅ Chat Box -->
<div id="chatSection" class="card shadow-lg position-fixed bottom-0 end-0 m-4 d-none" style="width: 350px; z-index:1050;">
  <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
    <span id="chatWithName">Chat</span>
    <button class="btn-close btn-close-white" id="closeChatBtn"></button>
  </div>
  <div id="chatBox" class="card-body overflow-auto" style="max-height: 300px;"></div>
  <div class="card-footer">
    <input type="hidden" id="receiver_id">
    <div class="input-group">
      <input type="text" id="messageInput" class="form-control" placeholder="Type a message...">
      <button class="btn btn-primary" id="sendBtn">Send</button>
    </div>
  </div>
</div>

<!-- ✅ Styles (reuse from other sections) -->
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
</style>


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

<script src="https://js.pusher.com/8.2/pusher.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
  const chatBox = document.getElementById("chatBox");
  const receiverInput = document.getElementById("receiver_id");
  const chatSection = document.getElementById("chatSection");
  const messageInput = document.getElementById("messageInput");
  const sendBtn = document.getElementById("sendBtn");
  const closeChatBtn = document.getElementById("closeChatBtn");
  const chatWithName = document.getElementById("chatWithName");
  const userId = {{ Auth::id() }};
  const userProfile = "{{ Auth::user()->profile_picture ? asset(Auth::user()->profile_picture) : asset('uploads/default-user.png') }}";

  let activeReceiver = null;
  let activeReceiverImage = null;
  let activeReceiverName = null;

  // ✅ Load unread counts
  async function loadUnreadCounts() {
    try {
      const res = await fetch("/messages/unread-counts");
      const data = await res.json();
      Object.entries(data).forEach(([senderId, count]) => {
        const badge = document.getElementById("chatBadge" + senderId);
        if (badge) {
          badge.textContent = count;
          badge.classList.toggle("d-none", count === 0);
        }
      });
    } catch (err) {
      console.error("Failed to load unread counts", err);
    }
  }
  loadUnreadCounts();

  // ✅ Open chat
  document.addEventListener("click", async (e) => {
    const btn = e.target.closest(".chat-btn");
    if (!btn) return;

    const receiverId = btn.dataset.receiverId;
    const name = btn.dataset.name;
    const profileImage = btn.dataset.profile_picture || "{{ asset('uploads/default-user.png') }}";

    activeReceiver = receiverId;
    activeReceiverName = name;
    activeReceiverImage = profileImage;

    receiverInput.value = receiverId;

    // Header info
    chatWithName.innerHTML = `
      <div class="d-flex align-items-center gap-2">
        <img src="${activeReceiverImage}" alt="Profile" class="rounded-circle border" width="35" height="35" style="object-fit: cover;">
        <span>Chat with ${activeReceiverName}</span>
      </div>
    `;

    chatSection.classList.remove("d-none");
    chatBox.innerHTML = "";

    // Reset unread badge
    const badge = document.getElementById("chatBadge" + receiverId);
    if (badge) badge.classList.add("d-none");

    // Mark messages as read
    fetch(`/messages/mark-read/${receiverId}`, {
      method: "POST",
      headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
    });

    // ✅ Load chat conversation
    const res = await fetch(`/messages/fetch/${receiverId}`);
    const messages = await res.json();

    messages.forEach(msg => {
      const isSender = msg.sender_id == userId;
      const align = isSender ? "justify-content-end" : "justify-content-start";
      const bg = isSender ? "bg-primary text-white" : "bg-light";
      const imgSrc = isSender ? userProfile : activeReceiverImage;

      chatBox.innerHTML += `
        <div class="d-flex ${align} my-2">
          ${!isSender ? `<img src="${imgSrc}" class="rounded-circle me-2" width="30" height="30" style="object-fit: cover;">` : ""}
          <div class="p-2 rounded-3 ${bg}" style="max-width: 75%;">${msg.message}</div>
          ${isSender ? `<img src="${imgSrc}" class="rounded-circle ms-2" width="30" height="30" style="object-fit: cover;">` : ""}
        </div>
      `;
    });

    chatBox.scrollTop = chatBox.scrollHeight;
  });

  // ✅ Close chat
  closeChatBtn.addEventListener("click", () => {
    chatSection.classList.add("d-none");
    activeReceiver = null;
  });

  // ✅ Send message
  sendBtn.addEventListener("click", async () => {
    const message = messageInput.value.trim();
    const receiverId = receiverInput.value;
    if (!message) return;

    await fetch(`{{ route('messages.send') }}`, {
      method: "POST",
      headers: {
        "X-CSRF-TOKEN": "{{ csrf_token() }}",
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ receiver_id: receiverId, message }),
    });

    chatBox.innerHTML += `
      <div class="d-flex justify-content-end my-2">
        <div class="p-2 rounded-3 bg-primary text-white" style="max-width: 75%;">${message}</div>
        <img src="${userProfile}" class="rounded-circle ms-2" width="30" height="30" style="object-fit: cover;">
      </div>
    `;
    chatBox.scrollTop = chatBox.scrollHeight;
    messageInput.value = "";
  });

  // ✅ Listen for Pusher messages
  const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
    cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
    forceTLS: true,
  });

  const channel = pusher.subscribe("chat." + userId);
  channel.bind("App\\Events\\MessageSent", (e) => {
    const msg = e.message;
    const senderId = msg.sender_id;
    const badge = document.getElementById("chatBadge" + senderId);

    if (msg.sender_id == activeReceiver && !chatSection.classList.contains("d-none")) {
      chatBox.innerHTML += `
        <div class="d-flex justify-content-start my-2">
          <img src="${activeReceiverImage}" class="rounded-circle me-2" width="30" height="30" style="object-fit: cover;">
          <div class="p-2 rounded-3 bg-light" style="max-width: 75%;">${msg.message}</div>
        </div>
      `;
      chatBox.scrollTop = chatBox.scrollHeight;

      // Mark message as read
      fetch(`/messages/mark-read/${senderId}`, {
        method: "POST",
        headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
      });
    } else {
      if (badge) {
        let count = parseInt(badge.textContent || "0") + 1;
        badge.textContent = count;
        badge.classList.remove("d-none");
      }
      new Audio("{{ asset('sounds/message.mp3') }}").play().catch(() => {});
    }
  });
});
</script>



@endsection
