@extends('layouts.layout')
@section('title', 'Admin Dashboard | MediCare {{ Auth::user()->name }}')

@section('content')
@include('includes.adminnavbar')
@include('includes.adminleftnavbar')

<div class="container-fluid">
  <div class="row">

    <main class="col-lg-10 offset-lg-2 p-5">
    <div class="card-body">   
        <h3 class="fw-bold mb-3">Manage Notifications</h3>

        <div class="table-responsive">
          <table class="table table-bordered table-striped align-middle text-center">
            <thead class="table-dark">
              <tr>
                <th>ID</th>
                <th>User</th>
                <th>Message</th>
                <th>Sent At</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($notifications as $notif)
                <tr>
                  <td>{{ $notif->id }}</td>
                  <td>{{ $notif->user->lastname ?? 'N/A' }}</td>
                  <td>{{ $notif->message }}</td>
                  <td>{{ $notif->created_at ? $notif->created_at->format('M d, Y H:i') : '-' }}</td>
                  <td>
                    @if($notif->read)
                      <span class="badge bg-success">Read</span>
                    @else
                      <span class="badge bg-warning text-dark">Unread</span>
                    @endif
                  </td>
                  <td>
                    @if(!$notif->read)
                      <form action="{{ route('admin.notifications.read', $notif->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-primary">Mark as Read</button>
                      </form>
                    @endif

                    <form action="{{ route('admin.notifications.delete', $notif->id) }}" method="POST" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6">No notifications available</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="mt-3">
          {{ $notifications->links() }}
        </div>
      </div>
    </main>
  </div>
</div>

  </div>
    </div>

@endsection
