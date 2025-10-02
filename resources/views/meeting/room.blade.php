@extends('layouts.layout')
@section('title', 'Meeting Room | MediCare')

@section('content')
<div class="container py-5">
  <h2 class="fw-bold mb-3">Meeting with 
    {{ auth()->user()->isDoctor() ? $appointment->patient->firstname : 'Dr. ' . $appointment->doctor->lastname }}
  </h2>

  <!-- Simple embedded video call via Jitsi -->
  <iframe src="https://meet.jit.si/{{ $appointment->id }}" 
          style="width:100%; height:80vh; border:0;" allow="camera; microphone; fullscreen">
  </iframe>
</div>
@endsection
