@extends('layouts.layout')
@section('title', 'Admin Dashboard | MediCare {{ Auth::user()->name }}')

@section('content')
@include('includes.adminnavbar')
@include('includes.adminleftnavbar')


<div class="container-fluid">
  <div class="row">

    <!-- Main Content -->
   <main class="col-lg-10 offset-lg-2 p-5">
    <div class="card-body">
         <h3 class="fw-bold mb-3">Create Doctor Account</h3>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('doctors.store') }}">
      @csrf

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">First Name</label>
          <input type="text" name="firstname" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Last Name</label>
          <input type="text" name="lastname" class="form-control" required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Confirm Password</label>
          <input type="password" name="password_confirmation" class="form-control" required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Contact Number</label>
          <input type="text" name="contact_no" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Address</label>
          <input type="text" name="address" class="form-control" required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Birth Date</label>
          <input type="date" name="birth_date" class="form-control" required>
        </div>
        <div class="col-md-6">
  <label class="form-label d-block mb-2 fw-semibold">Gender</label>
  <div class="d-flex gap-3">
    <!-- Male Option -->
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="gender" id="male" value="male" required>
      <label class="form-check-label d-flex align-items-center" for="male">
        <i class="fas fa-mars text-primary me-1"></i> Male
      </label>
    </div>

    <!-- Female Option -->
    <div class="form-check form-check-inline mb-3">
      <input class="form-check-input" type="radio" name="gender" id="female" value="female">
      <label class="form-check-label d-flex align-items-center" for="female">
        <i class="fas fa-venus text-danger me-1"></i> Female
      </label>
    </div>
  </div>


      <div class="row mb-3 d-none">
        <div class="col-md-6">
          <label class="form-label">Status</label>
          <select name="status" class="form-select" required>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>
      </div>

      <button type="submit" class="btn btn-primary mb-5">Create Doctor</button>
    </form>
  </div
  </div>
  <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle text-center">
      <thead class="table-dark">
        <tr>
          <th>Profile</th>
          <th>Name</th>
          <th>Email</th>
          <th>Contact</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($doctors as $doctor)
          <tr>
            <!-- Profile Picture -->
            <td>
              @if($doctor->profile_picture)
                <img src="{{ asset($doctor->profile_picture) }}" 
                     alt="Doctor Picture" 
                     class="rounded-circle"
                     style="width:50px; height:50px; object-fit:cover;">
              @else
                <img src="{{ asset('img/default-avatar.png') }}" 
                     alt="Default" 
                     class="rounded-circle"
                     style="width:50px; height:50px; object-fit:cover;">
              @endif
            </td>

            <!-- Doctor Info -->
            <td>{{ $doctor->firstname }} {{ $doctor->lastname }}</td>
            <td>{{ $doctor->email }}</td>
            <td>{{ $doctor->contact_no }}</td>
            <td>
              @if($doctor->status == 'active')
                <span class="badge bg-success">Active</span>
              @else
                <span class="badge bg-secondary">Inactive</span>
              @endif
            </td>

            <td>
  <!-- Toggle absence form -->
  <form action="{{ route('doctors.toggleAbsence', $doctor->id) }}" method="POST" style="display:inline;">
    @csrf
    @method('PATCH')
    @if($doctor->is_absent)
      <button type="submit" class="btn btn-sm btn-warning">Mark Present</button>
    @else
<button type="submit" class="btn btn-sm btn-outline-warning">
  <i class="fas fa-exclamation-triangle"></i> Mark Absent
</button>
    @endif
  </form>
</td>

          <!-- Actions -->
</tr>
        @empty
          <tr>
            <td colspan="6">No doctors found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
</div>


@endsection
