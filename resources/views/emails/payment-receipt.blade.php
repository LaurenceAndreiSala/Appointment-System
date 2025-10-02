<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Payment Receipt</title>
</head>
<body>
  <h3>Payment Confirmation</h3>
  <p>Hello {{ $payment->user->firstname }},</p>
  <p>Your payment has been successfully processed.</p>

  <p><strong>Reference Number:</strong> {{ $payment->reference_number }}</p>
  <p><strong>Doctor:</strong> Dr. {{ $payment->appointment->doctor->firstname }} {{ $payment->appointment->doctor->lastname }}</p>
  <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($payment->transaction_date)->format('M d, Y h:i A') }}</p>
  <p><strong>Amount:</strong> ₱{{ number_format($payment->amount,2) }}</p>
  <p><strong>Payment Method:</strong> {{ ucfirst($payment->payment_method) }}</p>

  <p>Thank you for trusting MediCare.</p>
</body>
</html>
