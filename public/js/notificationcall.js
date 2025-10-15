setInterval(() => {
    fetch(fetchNotificationsUrl)
        .then((res) => res.json())
        .then((data) => {
            data.forEach((n) => {
                // Only process incoming calls
                if (n.title === "Incoming Call" && !n.is_read) {
                    // ✅ Parse the meeting URL from notification data
                    let meetingUrl = null;
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
                        // Show ringtone / modal / prompt
                        const callModal = new bootstrap.Modal(
                            document.getElementById("incomingCallModal")
                        );
                        document.getElementById(
                            "callerName"
                        ).innerText = `📞 Dr. is calling you...`;
                        callModal.show();

                        // Play ringtone
                        const ringtone = new Audio(
                            "{{ asset('sounds/ringtone.mp3') }}"
                        );
                        ringtone.loop = true;
                        ringtone
                            .play()
                            .catch((err) =>
                                console.warn(
                                    "Autoplay blocked until user interacts"
                                )
                            );

                        // Accept / reject buttons
                        document.getElementById("acceptCall").onclick = () => {
                            ringtone.pause();
                            callModal.hide();
                            window.open(meetingUrl, "_blank");
                        };

                        document.getElementById("rejectCall").onclick = () => {
                            ringtone.pause();
                            callModal.hide();
                            alert("❌ You rejected the call.");
                        };
                    }
                }
            });
        })
        .catch((err) => console.error("Notification fetch error:", err));
}, 5000);
