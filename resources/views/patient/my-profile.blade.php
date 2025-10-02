@extends('layouts.layout')
@section('title', 'Patient Dashboard | MediCare')

@section('content')
@include('includes.patientNavbar')


<div class="container-fluid">
  <div class="row">

    @include('includes.patientsidebar')

    <!-- ✅ Main Content -->
    <div class="col-12 col-md-9 col-lg-10 p-4 p-md-5">
       <h2 class="fw-bold mb-4">My Profile</h2>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <form action="{{ route('patient.update-profile') }}" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
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
</div>
</div>


@endsection
