@extends('layouts.layout')
@section('title', 'Patient Dashboard | MediCare')

@section('content')
@include('includes.patientNavbar')

<div class="container-fluid">
  <div class="row">

      @include('includes.patientsidebar')

     <!-- ✅ Main Dashboard Content -->
    <div class="col-12 col-md-9 col-lg-10 p-4 p-md-5">
      <h2 class="fw-bold mb-4">Welcome, {{ Auth::user()->firstname }}!</h2>
      <div class="card shadow-sm border-0 mb-4 p-4">
        <h4 class="fw-bold mb-3">My Recent Appointments</h4>
        @if($appointments->isEmpty())
          <p class="text-muted">No appointments yet.</p>
        @else
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Date & Time</th>
                <th>Doctor</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach($appointments as $appt)
              <tr>
                <td>
  {{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y') }}
  <br>
  @if($appt->slot)
    {{ \Carbon\Carbon::parse($appt->slot->start_time)->format('h:i A') }} - 
    {{ \Carbon\Carbon::parse($appt->slot->end_time)->format('h:i A') }}
  @else
    <em>No slot assigned</em>
  @endif
</td>
                <td>Dr. {{ $appt->doctor->firstname }} {{ $appt->doctor->lastname }}</td>
                <td>
                  <span class="badge 
                    @if($appt->status == 'pending') bg-warning 
                    @elseif($appt->status == 'approved') bg-success 
                    @elseif($appt->status == 'denied') bg-danger 
                    @endif">
                    {{ ucfirst($appt->status) }}
                  </span>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        @endif
      </div>

      <!-- ✅ Prescription Report -->
      <div class="card shadow-sm border-0 p-4">
        <h4 class="fw-bold mb-3">My Recent Prescriptions</h4>
        @if($prescriptions->isEmpty())
          <p class="text-muted">No prescriptions yet.</p>
        @else
          <ul class="list-group">
            @foreach($prescriptions as $prescription)
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                  <strong>{{ $prescription->appointment->appointment_date }}</strong> <br>
                  Prescribed by: Dr. {{ $prescription->appointment->doctor->firstname }} {{ $prescription->appointment->doctor->lastname }}
                </div>
                <a href="{{ route('patient.view-precription') }}" class="btn btn-sm btn-primary">View</a>
              </li>
            @endforeach
          </ul>
        @endif
      </div>
    </div>
  </div>
</div>
</div>

@endsection
