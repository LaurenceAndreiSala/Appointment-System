<!-- ✅ Top Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container-fluid d-flex align-items-center justify-content-between">

    <!-- LEFT SECTION (Hamburger + Brand) -->
    <div class="d-flex align-items-center">
      <!-- Hamburger Icon -->
      <button class="btn d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#patientSidebar">
        <i data-feather="menu"></i>
      </button>

      <!-- Brand Logo & Name -->
      <a class="navbar-brand d-flex align-items-center" style="margin-left: 10px;" href="#">
        <i class="fas fa-heartbeat text-primary me-2"></i>
        <span class="fw-bold fs-4">MediCAL</span>
      </a>
    </div>

    <!-- RIGHT SECTION (Notification Bell) -->
    <div class="text-center position-relative">
      <button id="notifBell" class="btn btn-light rounded-circle position-relative">
        <i data-feather="bell"></i>
        <span id="notifCount" 
              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
          <!-- dynamic count -->
        </span>
      </button>

      <!-- ✅ Dropdown List -->
      <ul id="notifDropdown" 
          class="list-group shadow-sm position-absolute bg-white text-dark mt-2"
          style="display:none; width:250px; right:0; z-index:1050;">
        <li class="list-group-item text-muted small">Loading...</li>
      </ul>
    </div>

  </div>
</nav>


<script>
document.addEventListener("DOMContentLoaded", () => {
  const notifBell = document.getElementById("notifBell");
  const notifCount = document.getElementById("notifCount");
  const notifList = document.getElementById("notifDropdown");

  // ✅ Toggle dropdown on bell click
  notifBell.addEventListener("click", () => {
    notifList.style.display =
      notifList.style.display === "none" || notifList.style.display === ""
        ? "block"
        : "none";
  });

  // ✅ Close dropdown if clicked outside
  document.addEventListener("click", (e) => {
    if (!notifBell.contains(e.target) && !notifList.contains(e.target)) {
      notifList.style.display = "none";
    }
  });

  // ✅ Fetch notifications
  async function fetchNotifications() {
    try {
      const response = await fetch("/patient/notifications");
      if (!response.ok) throw new Error("Network response was not ok");

      const data = await response.json();

      // Update count
      notifCount.textContent = data.count > 0 ? data.count : "";

      // Clear list
      notifList.innerHTML = "";

      if (data.notifications.length > 0) {
        data.notifications.forEach((notif) => {
          let li = document.createElement("li");
          li.classList.add("list-group-item", "small");
          li.innerHTML = notif.message;
          notifList.appendChild(li);
        });
      } else {
        notifList.innerHTML =
          `<li class="list-group-item text-muted small">No notifications</li>`;
      }
    } catch (error) {
      console.error("Error fetching notifications:", error);
      notifList.innerHTML =
        `<li class="list-group-item text-danger small">Failed to load</li>`;
    }
  }

  // ✅ Initial fetch
  fetchNotifications();

  // ✅ Auto refresh every 30s
  setInterval(fetchNotifications, 30000);
});
</script>