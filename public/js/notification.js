document.addEventListener("DOMContentLoaded", function () {
    const bell = document.getElementById("notifBell");
    const dropdown = document.getElementById("notifDropdown");
    const notifList = document.getElementById("notifList"); // UL inside dropdown
    const notifCount = document.getElementById("notifCount"); // badge counter

    // Toggle dropdown
    bell.addEventListener("click", function () {
        if (
            dropdown.style.display === "none" ||
            dropdown.style.display === ""
        ) {
            dropdown.style.display = "block";
        } else {
            dropdown.style.display = "none";
        }
    });

    // Close when clicking outside
    document.addEventListener("click", function (event) {
        if (!bell.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.style.display = "none";
        }
    });

    // ✅ Function to fetch notifications
    async function fetchNotifications() {
        try {
            const response = await fetch(notifUrl);
            const data = await response.json();

            // Update count badge
            notifCount.textContent = data.count > 0 ? data.count : "";

            // Update dropdown list
            notifList.innerHTML = "";
            if (data.notifications.length > 0) {
                data.notifications.forEach((notif) => {
                    let li = document.createElement("li");
                    li.classList.add("list-group-item", "small");
                    li.style.cursor = "pointer";

                    li.innerHTML = `
      <strong>${notif.patient.firstname} ${notif.patient.lastname}</strong><br>
      <span class="text-muted">${notif.appointment_date} at ${notif.appointment_time}</span>
    `;

                    // ✅ Mark as read on click
                    li.addEventListener("click", async () => {
                        try {
                            await fetch(
                                `/doctor/notifications/${notif.id}/read`,
                                {
                                    method: "POST",
                                    headers: {
                                        "X-CSRF-TOKEN": document.querySelector(
                                            'meta[name="csrf-token"]'
                                        ).content,
                                        "Content-Type": "application/json",
                                    },
                                }
                            );
                            // Remove from list after marking as read
                            li.remove();
                            // Refresh the counter
                            fetchNotifications();
                        } catch (err) {
                            console.error(
                                "Error marking notification as read:",
                                err
                            );
                        }
                    });

                    notifList.appendChild(li);
                });
            } else {
                notifList.innerHTML = `<li class="list-group-item text-muted small">No new appointments</li>`;
            }
        } catch (error) {
            console.error("Error fetching notifications:", error);
        }
    }

    // ✅ Auto-refresh every 10 seconds
    setInterval(fetchNotifications, 10000);

    // ✅ Run immediately on page load
    fetchNotifications();
});
