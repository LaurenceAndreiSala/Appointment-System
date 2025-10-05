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

      <!-- Notifications Table Card -->
      <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-3 p-md-4">
          <div class="table-responsive">
            <table class="table table-hover align-middle text-center mb-0">
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
                          <form action="{{ route('admin.notifications.read', $notif->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-outline-primary btn-sm px-3 shadow-sm">
                              <i class="fas fa-envelope-open me-1"></i> Mark Read
                            </button>
                          </form>
                        @endif

                        <form action="{{ route('admin.notifications.delete', $notif->id) }}" method="POST" class="d-inline">
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

@endsection
