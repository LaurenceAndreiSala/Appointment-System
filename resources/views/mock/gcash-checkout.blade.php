@extends('layouts.layout')

@section('content')
<div class="container py-5 text-center">
  <h3>Fake GCash Checkout</h3>
  <p>Pay for Appointment with Dr. {{ $appt->doctor?->firstname }} {{ $appt->doctor?->lastname }}</p>
  <p><strong>Amount:</strong> ₱{{ $appt->amount ?? 500 }}</p>

  <form action="{{ route('gcash.mock.pay', $appt->id) }}" method="POST">
    @csrf
    <button class="btn btn-success">Simulate Pay with GCash</button>
  </form>
</div>
@endsection
