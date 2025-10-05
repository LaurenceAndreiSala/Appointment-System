document.addEventListener("DOMContentLoaded", function () {
    const bell = document.getElementById("notifBell");
    const dropdown = document.getElementById("notifDropdown");
    const notifList = document.getElementById("notifList");
    const notifCount = document.getElementById("notifCount");

    if (!bell || !dropdown || !notifList || !notifCount) return;

    bell.addEventListener("click", function () {
        dropdown.style.display =
            dropdown.style.display === "block" ? "none" : "block";
    });

    document.addEventListener("click", function (event) {
        if (!bell.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.style.display = "none";
        }
    });
});
