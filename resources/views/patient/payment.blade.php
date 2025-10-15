@extends('layouts.layout')
@section('title', 'Payment | MediCare')

@section('content')
<div class="container py-5">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h4>Payment for Appointment</h4>
    </div>
    <div class="card-body">
      <p><strong>Doctor:</strong> {{ $appointment->doctor->firstname }} {{ $appointment->doctor->lastname }}</p>
      <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</p>
      <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>

      <form action="{{ route('patient.payment.process', $appointment->id) }}" method="POST">
        @csrf
        <div class="mb-3">
          <label class="form-label">Payment Method</label>
          <select name="payment_method" class="form-control" required>
            <option value="">-- Select Method --</option>
            <option value="gcash">GCash-0923</option>
            <!-- <option value="paypal">PayPal</option>
            <option value="credit_card">Credit Card</option> -->
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Amount</label>
          <input type="number" name="amount" class="form-control" value="500" required>
        </div>

        <button type="submit" class="btn btn-success">Confirm Payment</button>
        <a href="{{ route('patient.view-appointment') }}" class="btn btn-secondary">Cancel</a>
      </form>
    </div>
  </div>
</div>
@endsection
