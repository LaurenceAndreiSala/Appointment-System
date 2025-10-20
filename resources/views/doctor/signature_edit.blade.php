@extends('layouts.layout')
@section('title','Upload Signature')
@section('content')
<div class="container py-4">
  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

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
@endsection
