@extends('layouts.layout')
@section('title', 'Admin Dashboard | MediCare')

@section('content')
@include('includes.adminnavbar')
@include('includes.adminleftnavbar')

<div class="container-fluid">
  <div class="row">
      <!-- Main Content -->

    <main class="col-md-9 col-lg-10 offset-md-3 offset-lg-2 p-4">
      <!-- ✅ Page Header -->
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <h2 class="fw-bold mb-2 mb-md-0 text-primary">
      <i class="fas fa-bell me-2"></i> Manage Notifications
    </h2>
<small class="text-muted">Set the Available Appointment Slot for the Doctor.</small>
  </div>

        <!-- ✅ Filter & Search Controls -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
  <div class="d-flex gap-2">
      <input type="text" id="searchInput" class="form-control" placeholder="🔍 Search...">
  </div>
</div>

      <!-- Notifications Table Card -->
      <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-3 p-md-4">
          <div class="table-responsive">
            <table id="notificationsTable" class="table table-hover align-middle text-center mb-0">
              <thead class="bg-primary text-white rounded-top">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">User</th>
                  <th scope="col">Message</th>
                  <th scope="col">Sent At</th>
                  <th scope="col">Status</th>
                  <th scope="col">Actions</th>
                </tr>
              </thead>
              <tbody>
                @forelse($notifications as $notif)
                  <tr>
                    <td class="fw-semibold text-secondary">{{ $notif->id }}</td>
                    <td>{{ $notif->user->lastname ?? 'N/A' }}</td>
                    <td class="text-start">{{ $notif->message }}</td>
                    <td>{{ $notif->created_at ? $notif->created_at->format('M d, Y h:i A') : '-' }}</td>
                    <td>
                      <span class="badge px-3 py-2 rounded-pill 
                        {{ $notif->read ? 'bg-success' : 'bg-warning text-dark' }}">
                        {{ $notif->read ? 'Read' : 'Unread' }}
                      </span>
                    </td>
                    <td>
                      <div class="d-flex justify-content-center gap-2 flex-wrap">
                        @if(!$notif->read)
                            <form method="POST" action="{{ secure_url(route('admin.notifications.read', $notif->id, [], false)) }}" class="d-inline">
                            @csrf
                            <button class="btn btn-outline-primary btn-sm px-3 shadow-sm">
                              <i class="fas fa-envelope-open me-1"></i> Mark Read
                            </button>
                          </form>
                        @endif
                        <form method="POST" action="{{ secure_url(route('admin.notifications.delete', $notif->id, [], false)) }}" class="d-inline">
                          @csrf
                          @method('DELETE')
                          <button class="btn btn-outline-danger btn-sm px-3 shadow-sm">
                            <i class="fas fa-trash me-1"></i> Delete
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-muted py-4">No notifications available</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

        </div>
      </div>

    </main>
  </div>
</div>

<style>
/* Fix oversized pagination arrows */
.pagination svg {
  width: 16px !important;
  height: 16px !important;
  vertical-align: middle;
}

/* Optional: make pagination cleaner on mobile */
.pagination {
  justify-content: center;
  flex-wrap: wrap;
}
.pagination li {
  margin: 2px;
}
</style>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById("searchInput");
    const tableBody = document.querySelector("#notificationsTable tbody");

    function filterTable() {
        const searchValue = searchInput.value.toLowerCase();
        const rows = Array.from(tableBody.querySelectorAll("tr"));
        let visibleCount = 0;

        rows.forEach(row => {
            // Skip "No notifications found" row
            if (row.classList.contains("no-data-row")) return;

            const userCell = row.cells[1];     // 2nd column: User
            const messageCell = row.cells[2];  // 3rd column: Message
            const dateCell = row.cells[3];     // 4th column: Date (optional, if available)

            if (!userCell || !messageCell) return;

            const userText = userCell.textContent.toLowerCase();
            const messageText = messageCell.textContent.toLowerCase();
            const dateText = dateCell ? dateCell.textContent.toLowerCase() : "";

            // ✅ Match if any of these contain the search text
            const matchesSearch =
                userText.includes(searchValue) ||
                messageText.includes(searchValue) ||
                dateText.includes(searchValue);

            row.style.display = matchesSearch ? "" : "none";

            if (matchesSearch) visibleCount++;
        });

        // ✅ Toggle "No notifications found" row visibility
        const noDataRow = tableBody.querySelector(".no-data-row");
        if (noDataRow) {
            noDataRow.style.display = visibleCount === 0 ? "" : "none";
        }
    }

    searchInput.addEventListener("input", filterTable);
});
</script>
@endsection
