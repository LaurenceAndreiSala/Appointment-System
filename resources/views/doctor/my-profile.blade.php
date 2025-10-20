@extends('layouts.layout')
@section('title', 'Doctor Dashboard | MediCare')

@section('content')
@include('includes.doctornavbar')
@include('includes.doctorsidebar')


<div class="container-fluid">
  <div class="row">

  <!-- ✅ Main Content -->
<div class="col-12 col-md-9 col-lg-10 offset-lg-2  p-4 p-md-2">
  <div class="bg-light rounded-4 shadow-sm p-4 mb-4 d-flex align-items-center">
    <i class="fas fa-user-circle text-primary fa-2x me-3"></i>
    <h3 class="fw-bold mb-0 text-dark">My Doctor Profile</h3>
  </div>

  <p class="text-muted mb-4">Manage your personal information and update your profile settings.</p>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

   <form action="{{ route('doctor.update-profile') }}" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
    @csrf

    <div class="text-center mb-4">
      <img src="{{ asset(Auth::user()->profile_picture ?? 'img/default.png') }}" 
           alt="Profile Picture" 
           class="rounded-circle mb-2"
           style="width:100px; height:100px; object-fit:cover;">
      <input type="file" name="profile_picture" class="form-control mt-2">
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">First Name</label>
      <input type="text" name="firstname" value="{{ old('firstname', Auth::user()->firstname) }}" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">Last Name</label>
      <input type="text" name="lastname" value="{{ old('lastname', Auth::user()->lastname) }}" class="form-control" required>
    </div>

       <div class="mb-3">
      <label class="form-label fw-bold">Contact Number</label>
      <input type="text" name="contact_no" value="{{ old('contact_no', Auth::user()->contact_no) }}" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">Email</label>
      <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="form-control" required>
    </div>

    <hr>

    <div class="mb-3">
      <label class="form-label fw-bold">New Password (optional)</label>
      <input type="password" name="password" class="form-control">
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">Confirm New Password</label>
      <input type="password" name="password_confirmation" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Update Profile</button>
  </form>
   <form action="{{ route('doctor.signature.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
      <label class="form-label">Signature (PNG / JPG)</label>
      <input type="file" name="signature" class="form-control" accept="image/*" required>
      @error('signature') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    @if($doctor && $doctor->signature)
      <div class="mb-3">
        <p>Current signature preview:</p>
        <img src="{{ asset('storage/' . $doctor->signature) }}" alt="Signature" style="max-width:300px; height:auto; border:1px solid #ddd; padding:6px;">
      </div>
    @endif

    <button class="btn btn-primary">Upload</button>
  </form>
</div>
</div>

<script>
  const notifUrl = "{{ route('doctor.notifications.fetch') }}";
</script>
<script src="{{ asset('js/notification.js') }}"></script>

@endsection
