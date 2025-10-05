@extends('layouts.layout')
@section('title', 'Admin Dashboard | MediCare {{ Auth::user()->name }}')

@section('content')
@include('includes.adminnavbar')
@include('includes.adminleftnavbar')


<div class="container-fluid">
  <div class="row">

   <main class="col-md-9 col-lg-10 offset-md-3 offset-lg-2 p-4">
      <!-- ✅ Page Header -->
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <h2 class="fw-bold mb-2 mb-md-0 text-primary">
      <i class="fas fa-user-md me-2"></i> Create Doctor Account
    </h2>
  </div>
  
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

          <div class="card shadow-sm border-0 rounded-4 mb-3">
        <div class="card-body p-3 p-md-4">
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
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="gender" id="male" value="male" required>
              <label class="form-check-label d-flex align-items-center" for="male">
                <i class="fas fa-mars text-primary me-1"></i> Male
              </label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="gender" id="female" value="female">
              <label class="form-check-label d-flex align-items-center" for="female">
                <i class="fas fa-venus text-danger me-1"></i> Female
              </label>
            </div>
          </div>
        </div>
      </div>

      <!-- Hidden Status -->
      <input type="hidden" name="status" value="active">

      <button type="submit" class="btn btn-primary mb-5">Create Doctor</button>
    </form>
 </div>
      </div>
      

  <!-- Doctors Table -->
  <div class="card shadow-sm border-0 rounded-4 p-4">
    <h4 class="fw-bold mb-3 text-primary"><i class="fas fa-users me-2"></i>All Doctors</h4>
    <div class="table-responsive rounded-4 shadow-sm">
      <table class="table table-hover table-bordered table-striped align-middle text-center mb-0">
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
              <img src="{{ $doctor->profile_picture ? asset($doctor->profile_picture) : asset('img/default-avatar.png') }}" 
                   alt="Doctor Picture" 
                   class="rounded-circle" 
                   style="width:50px; height:50px; object-fit:cover;">
            </td>
            <td>{{ $doctor->firstname }} {{ $doctor->lastname }}</td>
            <td>{{ $doctor->email }}</td>
            <td>{{ $doctor->contact_no }}</td>
            <td>
              <span class="badge {{ $doctor->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                {{ ucfirst($doctor->status) }}
              </span>
            </td>
            <td>
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
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-muted py-4">
              <i class="fas fa-inbox fa-2x mb-2"></i><br>No doctors found.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</main>



@endsection
