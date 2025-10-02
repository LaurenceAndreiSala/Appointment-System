import Echo from "laravel-echo";
window.Pusher = require("pusher-js");

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
});

// Listen for ringing event
window.Echo.channel("appointments").listen("CallStarted", (e) => {
    if (e.appointment.patient_id === userId) {
        alert("Doctor is calling! Click OK to join.");
        window.open(e.appointment.meeting_url, "_blank");
    }
});
