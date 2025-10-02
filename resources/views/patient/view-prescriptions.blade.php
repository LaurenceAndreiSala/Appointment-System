@extends('layouts.layout')
@section('title', 'View Prescriptions | MediCare')

@section('content')
@include('includes.patientNavbar')


<div class="container-fluid">
  <div class="row">

      @include('includes.patientsidebar')

<!-- ✅ Main Content -->
<div class="col-12 col-md-9 col-lg-10 p-4 p-md-5">
  <h2 class="fw-bold">My Prescriptions</h2>
  <p class="text-muted">Below are the prescriptions given by your doctors.</p>

  <div class="card shadow-sm border-0 p-4">
    @if($prescriptions->isEmpty())
      <p class="text-center text-muted">No prescriptions found.</p>
    @else
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle text-center mb-0">
          <thead class="table-dark">
            <tr>
              <th>Date</th>
              <th>Doctor</th>
              <th>Medication</th>
              <th>Dosage</th>
              <th>Notes</th>
            </tr>
          </thead>
          <tbody>
            @foreach($prescriptions as $prescription)
              <tr>
                <td data-label="Date">{{ $prescription->created_at->format('M d, Y h:i A') }}</td>
                <td data-label="Doctor">
                  {{ $prescription->appointment->doctor->firstname ?? '' }}
                  {{ $prescription->appointment->doctor->lastname ?? '' }}
                </td>
                <td data-label="Medication">{{ $prescription->medication }}</td>
                <td data-label="Dosage">{{ $prescription->dosage }}</td>
                <td data-label="Notes">{{ $prescription->notes ?? '-' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
</div>

<style>
@media (max-width: 768px) {
    table thead {
        display: none;
    }
    table tr {
        display: block;
        margin-bottom: 1rem;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 0.5rem;
        background: #fff;
    }
    table td {
        display: block;
        text-align: right;
        font-size: 0.9rem;
        border: none !important;
        border-bottom: 1px solid #f0f0f0;
    }
    table td:last-child {
        border-bottom: none;
    }
    table td::before {
        content: attr(data-label);
        float: left;
        font-weight: bold;
        text-transform: uppercase;
        color: #495057;
    }
}
</style>
@endsection
