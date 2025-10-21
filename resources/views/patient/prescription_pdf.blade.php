<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Prescription</title>
  <style>
    body { font-family: 'Arial', sans-serif; color: #000; margin: 30px; }
    .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 8px; }
    .clinic-logo { width: 40px; height: 40px; vertical-align: middle; margin-right: 8px; }
    .clinic-title { font-weight: bold; font-size: 20px; vertical-align: middle; }
    .info { margin-top: 12px; font-size: 13px; }
    .rx { font-size: 54px; font-weight: bold; color: #007bff; margin-top: 8px; }
    .details { margin: 18px 0; line-height: 1.6; font-size: 13px;}
    .signature { margin-top: 28px; text-align: right; }
    .signature-line { border-top: 1px solid #000; width: 240px; margin-left: auto; height: 1px; }
    .signature-img { max-width: 220px; height: auto; display: block; margin-left: auto; }
    .footer { border-top: 1px solid #000; text-align: center; font-size: 11px; margin-top: 26px; padding-top: 8px; }
  </style>
</head>
<body>

  <div class="header">
    @if(!empty($logoData) && !empty($logoMime))
      <img class="clinic-logo" src="data:{{ $logoMime }};base64,{{ $logoData }}" alt="Clinic Logo">
    @endif
    <span class="clinic-title">MediCAL CLINIC</span>
    <p>123 Health Mahayahay Avenue, Iligan City • Tel: (02) 9876-5432</p>
  </div>

  <div class="info">
    <strong>Doctor:</strong> Dr. {{ $prescription->appointment->doctor->firstname }} {{ $prescription->appointment->doctor->lastname }}<br>
    <strong>License No:</strong> {{ $prescription->appointment->doctor->license_no ?? '—' }}<br>
    <strong>Specialization:</strong> {{ $prescription->appointment->doctor->specialization ?? '—' }}<br><br>
    <strong>Patient:</strong> {{ $prescription->appointment->patient->firstname }} {{ $prescription->appointment->patient->lastname }}<br>
    <strong>Date:</strong> {{ $prescription->created_at->format('M d, Y h:i A') }}
  </div>

  <div class="rx">Rx</div>

  <div class="details">
    <strong>Medication:</strong> {{ $prescription->medication }}<br>
    <strong>Dosage:</strong> {{ $prescription->dosage }}<br>
    <strong>Notes:</strong> {{ $prescription->notes ?? '—' }}
  </div>

  <div class="signature">
    @if(!empty($signatureData) && !empty($signatureMime))
      <img class="signature-img" src="data:{{ $signatureMime }};base64,{{ $signatureData }}" alt="Doctor signature">
      <p>Dr. {{ $prescription->appointment->doctor->firstname }} {{ $prescription->appointment->doctor->lastname }}</p>
    @else
      <div class="signature-line"></div>
      <p>Dr. {{ $prescription->appointment->doctor->firstname }} {{ $prescription->appointment->doctor->lastname }}</p>
    @endif
  </div>

  <div class="footer">
    <p>“Take medicines as prescribed. For any adverse reaction, contact your doctor immediately.”</p>
  </div>
</body>
</html>
