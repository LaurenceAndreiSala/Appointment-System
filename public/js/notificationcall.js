setInterval(() => {
    fetch(fetchNotificationsUrl)
        .then((res) => res.json())
        .then((data) => {
            data.forEach((n) => {
                if (n.title === "Incoming Call" && !n.is_read) {
                    let meetingUrl = null;

                    // ✅ safely parse JSON data if available
                    if (n.data) {
                        try {
                            const parsed = JSON.parse(n.data);
                            meetingUrl = parsed.meeting_url ?? null;
                        } catch (err) {
                            console.warn(
                                "Invalid JSON in notification:",
                                n.data
                            );
                        }
                    }

                    if (meetingUrl) {
                        if (confirm(n.message + "\nDo you want to join?")) {
                            window.open(meetingUrl, "_blank");
                        }
                    }
                }
            });
        })
        .catch((err) => console.error("Notification fetch error:", err));
}, 5000);
