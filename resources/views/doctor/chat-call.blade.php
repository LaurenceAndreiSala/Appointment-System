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

    <p class="text-muted mb-4">Start or join calls and chat with your patients.</p>

    <!-- ✅ Filter & Search Controls -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
      <div class="d-flex gap-2">
        <input type="text" id="searchInput" class="form-control" placeholder="🔍 Search...">
      </div>
    </div>

    <!-- ✅ Appointments Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table id="callsTable" class="table table-hover align-middle text-center mb-0">
            <thead class="bg-primary text-white">
              <tr>
                <th>Patient</th>
                <th>Date</th>
                <th>Time</th>
                <th>Actions</th>
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
                  <div class="d-flex justify-content-center gap-2">
                    <!-- ✅ Start Call -->
                    <button type="button"
                            class="btn btn-success start-call-btn rounded-pill shadow-sm px-3 py-1"
                            data-appointment-id="{{ $appt->id }}"
                            data-receiver-id="{{ $appt->patient_id }}"
                            data-patient-name="{{ $appt->patient?->firstname }} {{ $appt->patient?->lastname }}">
                      <i class="fas fa-phone-alt me-1"></i> Call
                    </button>

                <!-- ✅ Chat Button -->
                 <button type="button"
  class="btn btn-primary chat-btn rounded-pill shadow-sm px-3 py-1 position-relative"
  data-receiver-id="{{ $appt->patient_id ?? $appt->doctor_id }}"
  data-name="{{ $appt->patient?->firstname ?? $appt->doctor?->firstname }} {{ $appt->patient?->lastname ?? $appt->doctor?->lastname }}"
  data-profile_picture="{{ $appt->patient?->profile_picture ? asset($appt->patient->profile_picture) : asset('uploads/default-user.png') }}">
  <i class="fas fa-comments me-1"></i> Chat
  <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none"
        id="chatBadge{{ $appt->patient_id ?? $appt->doctor_id }}">0</span>
</button>
                  </div>
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


<!-- ✅ Chat Box -->
<div id="chatSection" class="card shadow-lg position-fixed bottom-0 end-0 m-4 d-none" style="width: 350px; z-index:0;">
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
<!-- ✅ Styles -->
<style>
.table-hover tbody tr:hover {
  background-color: #f8f9fa;
  transform: scale(1.01);
  transition: all 0.2s ease;
}
.card { border-radius: 1rem; }
.btn { transition: 0.2s ease-in-out; }
.btn:hover { transform: translateY(-2px); }
</style>

<!-- ✅ Search Filter Script -->
<script>
document.addEventListener("DOMContentLoaded", function() {
  const searchInput = document.getElementById("searchInput");
  const tableBody = document.querySelector("#callsTable tbody");

  function filterTable() {
    const searchValue = searchInput.value.toLowerCase();
    const rows = Array.from(tableBody.querySelectorAll("tr"));
    let visibleCount = 0;

    rows.forEach(row => {
      if (row.classList.contains("no-data-row")) return;
      const patientCell = row.cells[0];
      const dateCell = row.cells[1];
      if (!patientCell || !dateCell) return;

      const patientName = patientCell.textContent.toLowerCase();
      const meetingDate = dateCell.textContent.toLowerCase();
      const matchesSearch = patientName.includes(searchValue) || meetingDate.includes(searchValue);
      row.style.display = matchesSearch ? "" : "none";
      if (matchesSearch) visibleCount++;
    });

    const noDataRow = tableBody.querySelector(".no-data-row");
    if (noDataRow) noDataRow.style.display = visibleCount === 0 ? "" : "none";
  }

  searchInput.addEventListener("input", filterTable);
});
</script>

<!-- ✅ Call Script -->
<script>
document.addEventListener("click", async (e) => {
  const btn = e.target.closest(".start-call-btn");
  if (!btn) return;

  const appointmentId = btn.dataset.appointmentId;
  const receiverId = btn.dataset.receiverId;
  const patientName = btn.dataset.patientName;
  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Starting...';

  try {
    const response = await fetch(`{{ secure_url('doctor/start-call') }}/${appointmentId}`, {
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
      alert("✅ Meeting started! Opening call...");
      window.open(data.meeting_url, "_blank");
    } else {
      alert("❌ Unable to start the meeting.");
      console.error("Error:", data.error);
    }
  } catch (error) {
    console.error("Fetch error:", error);
    alert("⚠️ Network error. Please try again later.");
  } finally {
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-phone-alt me-1"></i> Call';
  }
});
</script>
<script src="https://js.pusher.com/8.2/pusher.min.js"></script>
<script src="https://js.pusher.com/8.2/pusher.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
  const chatBox = document.getElementById("chatBox");
  const receiverInput = document.getElementById("receiver_id");
  const chatSection = document.getElementById("chatSection");
  const messageInput = document.getElementById("messageInput");
  const sendBtn = document.getElementById("sendBtn");
  const closeChatBtn = document.getElementById("closeChatBtn");
  const userId = {{ Auth::id() }};
  const userProfile = "{{ Auth::user()->profile_picture ? asset(Auth::user()->profile_picture) : asset('uploads/default-user.png') }}";

  // 🔹 Global chat variables
  let activeReceiver = null;
  let activeReceiverImage = null;
  let activeReceiverName = null;

  // 🔹 Load unread message counts initially
  async function loadUnreadCounts() {
    try {
      const res = await fetch(`{{ secure_url('messages/unread-counts') }}`);
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

  // 🔹 Open chat box and load conversation
  document.addEventListener("click", async (e) => {
    const btn = e.target.closest(".chat-btn");
    if (!btn) return;

    const receiverId = btn.dataset.receiverId;
    activeReceiver = receiverId;
    activeReceiverImage = btn.dataset.profile_picture || "{{ asset('uploads/default-user.png') }}";
    activeReceiverName = btn.dataset.name;

    receiverInput.value = receiverId;

    document.getElementById("chatWithName").innerHTML = `
      <div class="d-flex align-items-center gap-2">
        <img src="${activeReceiverImage}" alt="Profile" class="rounded-circle" width="35" height="35" style="object-fit: cover;">
        <span>Chat with ${activeReceiverName}</span>
      </div>
    `;

    chatSection.classList.remove("d-none");
    chatBox.innerHTML = "";

    // 🔹 Reset badge count
    const badge = document.getElementById("chatBadge" + receiverId);
    if (badge) badge.classList.add("d-none");

    // 🔹 Mark as read
    fetch(`{{ secure_url('messages/mark-read') }}/${receiverId}`, {
      method: "POST",
      headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
    });

    // 🔹 Fetch old messages
    const res = await fetch(`{{ secure_url('messages/fetch') }}/${receiverId}`);
    const messages = await res.json();

    messages.forEach(msg => {
      const isSender = msg.sender_id == userId;
      const align = isSender ? "justify-content-end" : "justify-content-start";
      const bg = isSender ? "bg-primary text-white" : "bg-light";
      const imgSrc = isSender ? userProfile : activeReceiverImage;

      chatBox.innerHTML += `
        <div class="d-flex ${align} my-1">
          ${!isSender ? `<img src="${imgSrc}" class="rounded-circle me-2" width="30" height="30" style="object-fit: cover;">` : ""}
          <div class="p-2 rounded-3 ${bg}" style="max-width: 75%;">${msg.message}</div>
          ${isSender ? `<img src="${imgSrc}" class="rounded-circle ms-2" width="30" height="30" style="object-fit: cover;">` : ""}
        </div>
      `;
    });

    chatBox.scrollTop = chatBox.scrollHeight;
  });

  // 🔹 Close chat box
  closeChatBtn.addEventListener("click", () => {
    chatSection.classList.add("d-none");
    activeReceiver = null;
  });

  // 🔹 Send message
  sendBtn.addEventListener("click", async () => {
    const message = messageInput.value.trim();
    const receiverId = receiverInput.value;
    if (!message) return;

    await fetch(`{{ secure_url(route('messages.send', [], false)) }}`, {
      method: "POST",
      headers: {
        "X-CSRF-TOKEN": "{{ csrf_token() }}",
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ receiver_id: receiverId, message }),
    });

    chatBox.innerHTML += `
      <div class="d-flex justify-content-end my-1">
        <div class="p-2 rounded-3 bg-primary text-white" style="max-width: 75%;">${message}</div>
        <img src="${userProfile}" class="rounded-circle ms-2" width="30" height="30" style="object-fit: cover;">
      </div>
    `;
    chatBox.scrollTop = chatBox.scrollHeight;
    messageInput.value = "";
  });

  // 🔹 Listen for new incoming messages (Pusher)
  const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
    cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
    forceTLS: true,
  });

  const channel = pusher.subscribe("chat." + userId);
  channel.bind("App\\Events\\MessageSent", (e) => {
    const msg = e.message;
    const senderId = msg.sender_id;
    const badge = document.getElementById("chatBadge" + senderId);

    // Message is for the open chat
    if (msg.sender_id == activeReceiver && !chatSection.classList.contains("d-none")) {
      chatBox.innerHTML += `
        <div class="d-flex justify-content-start my-1">
          <img src="${activeReceiverImage}" class="rounded-circle me-2" width="30" height="30" style="object-fit: cover;">
          <div class="p-2 rounded-3 bg-light" style="max-width: 75%;">${msg.message}</div>
        </div>
      `;
      chatBox.scrollTop = chatBox.scrollHeight;

      // Mark as read
      fetch(`{{ secure_url('messages/mark-read') }}/${receiverId}`, {
        method: "POST",
        headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
      });
    } 
    // Message from another chat
    else {
      if (badge) {
        let count = parseInt(badge.textContent || "0") + 1;
        badge.textContent = count;
        badge.classList.remove("d-none");
      }
      // 🔔 Play notification sound
      new Audio("{{ asset('sounds/message.mp3') }}").play().catch(() => {});
      // 🔔 Optional: show browser notification
      if (Notification.permission === "granted") {
        new Notification("💬 New message", {
          body: e.sender_name || "You have a new message",
          icon: e.sender_picture || "{{ asset('uploads/default-user.png') }}"
        });
      }
    }
  });

  // Request browser notification permission
  if (Notification.permission !== "granted") {
    Notification.requestPermission();
  }
});
</script>



@endsection
