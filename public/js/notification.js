document.addEventListener("DOMContentLoaded", function () {
    const bell = document.getElementById("notifBell");
    const dropdown = document.getElementById("notifDropdown");
    const notifList = document.getElementById("notifList");
    const notifCount = document.getElementById("notifCount");

    if (!bell || !dropdown || !notifList || !notifCount) return;

    const doctorId = bell.dataset.doctorId;
    const notifUrl = "/doctor/notifications/fetch";

    // ✅ Toggle dropdown
    bell.addEventListener("click", function () {
        dropdown.style.display =
            dropdown.style.display === "block" ? "none" : "block";
    });

    // ✅ Close when clicking outside
    document.addEventListener("click", function (event) {
        if (!bell.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.style.display = "none";
        }
    });

    // ✅ Fetch notifications
    async function fetchNotifications() {
        try {
            const response = await fetch(notifUrl);
            const data = await response.json();

            notifList.innerHTML = "";

            // ✅ Display list
            if (data.notifications && data.notifications.length > 0) {
                data.notifications.forEach((notif) => {
                    const li = document.createElement("li");
                    li.classList.add("list-group-item", "small");

                    li.innerHTML = `
                        <strong>${notif.title || "New Appointment"}</strong><br>
                        <span class="text-muted">${
                            notif.message || ""
                        }</span><br>
                        <small>${notif.created_at} ${notif.time || ""}</small>
                    `;

                    notifList.appendChild(li);
                });

                // ✅ Update count
                notifCount.textContent = data.count > 0 ? data.count : "";
            } else {
                notifList.innerHTML = `
                    <li class="list-group-item text-muted small">
                        No new notifications
                    </li>`;
                notifCount.textContent = "";
            }
        } catch (error) {
            console.error("❌ Error fetching notifications:", error);

            notifList.innerHTML = `
                <li class="list-group-item text-danger small">
                    Failed to load notifications
                </li>`;
        }
    }

    fetchNotifications();
    setInterval(fetchNotifications, 10000);

    // ✅ Real-time updates via Echo
    const waitForEcho = setInterval(() => {
        if (window.Echo && doctorId) {
            clearInterval(waitForEcho);

            console.log("✅ Echo loaded:", `doctor.${doctorId}`);

            window.Echo.private(`doctor.${doctorId}`).listen(
                ".appointment.booked",
                (e) => {
                    console.log("📅 New Appointment Event:", e);

                    const li = document.createElement("li");
                    li.classList.add("list-group-item", "small");

                    li.innerHTML = `
                        <strong>New Appointment</strong><br>
                        <span class="text-muted">From ${e.patient_name}</span><br>
                        <small>${e.appointment_date} ${e.appointment_time}</small>
                    `;

                    // ✅ Insert on top
                    notifList.prepend(li);

                    // ✅ Update count
                    const current = parseInt(notifCount.textContent || "0", 10);
                    notifCount.textContent = current + 1;

                    // 🔔 Flash effect
                    bell.classList.add("text-warning");
                    setTimeout(() => {
                        bell.classList.remove("text-warning");
                    }, 3000);
                }
            );
        } else {
            console.warn("⏳ Waiting for Echo to load...");
        }
    }, 500);
});
