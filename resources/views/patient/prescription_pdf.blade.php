<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Prescription</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      color: #000;
      margin: 30px;
      font-size: 13px;
    }
    .header {
      text-align: center;
      border-bottom: 2px solid #000;
      padding-bottom: 8px;
      margin-bottom: 8px;
      position: relative;
    }
    .clinic-logo {
      position: absolute;
      left: 0;
      margin-bottom: 10px;
      width: 80px;
      height: 80px;
    }
    .header img{
      width: 60px;
      height: 60px;
      margin-top: -15px;
    }
    .header h2 {
      margin: 0;
      font-size: 18px;
    }
    .sub-header {
      font-size: 11px;
    }
    .schedule {
      margin-top: 10px;
      margin-bottom: 10px;
    }
    .rx-icon {
      width: 50px;
      margin-top: 50px;
    }
    .patient-info {
      font-size: 13px;
      margin-bottom: 8px;
    }
    .line {
      border-bottom: 1px solid #000;
      display: inline-block;
      text-align: center;
      padding: 0 5px;
      min-width: 70px;
    }
    .prescription-content {
      margin-top: 20px;
      margin-left: 35px;
      font-family: 'Segoe Script', cursive;
      font-size: 14px;
      line-height: 1.7;
    }
    .signature-section {
      text-align: right;
      margin-top: 50px;
    }
    .signature-img {
      max-width: 150px;
      height: auto;
      margin-bottom: -5px;
    }
    .footer {
      border-top: 1px solid #000;
      margin-top: 10px;
      padding-top: 5px;
      font-size: 11px;
      text-align: right;
    }
  </style>
</head>
<body>

  <!-- ✅ Header Section -->
  <div class="header">
    @if(!empty($clinicLogoData) && !empty($clinicLogoMime))
      <img class="clinic-logo" src="data:{{ $clinicLogoMime }};base64,{{ $clinicLogoData }}" alt="Clinic Logo">
    @endif

    <h2>Dr. {{ $prescription->appointment->doctor->firstname }} {{ $prescription->appointment->doctor->lastname }}, MD</h2>
    <div class="sub-header">
      {{ $prescription->appointment->doctor->clinic_address ?? 'Pagamutan ng Dasmariñas, Cavite' }}<br>
      Tel No.: {{ $prescription->appointment->doctor->contact_number ?? '(046) 435-0180' }}
    </div>
  </div>

  <!-- ✅ Date + RX Icon -->
  <div class="schedule">
    <table style="width:100%;">
      <tr>
        <td style="width:50%; font-size: 30px;">
          @if(!empty($rxIconData) && !empty($rxIconMime))
            <img class="rx-icon" src="data:{{ $rxIconMime }};base64,{{ $rxIconData }}" alt="Rx Icon">
          @endif
        </td>
        <td style="text-align:right;">
          <span>Date:</span> <span class="line" style="width:60px;">{{ now()->format('m/d/Y') }}</span>
        </td>
      </tr>
    </table>
  </div>

  <!-- ✅ Patient Info -->
  <div class="patient-info">
    <div>
      <span>Patient:</span> <span class="line">{{ $prescription->appointment->patient->firstname }} {{ $prescription->appointment->patient->lastname }}</span>
    </div>
    <div>
      <span>Age:</span> <span class="line" style="width: 25px;">{{ $prescription->appointment->patient->age ?? '—' }}</span>
      <span style="margin-left: 25px;">Sex:</span> <span class="line" style="width: 25px;">{{ $prescription->appointment->patient->gender ?? '—' }}</span>
    </div>
  </div>

  <!-- ✅ Prescription -->
  <div class="prescription-content">
    @foreach($prescription->medications ?? [$prescription] as $index => $med)
      {{ $loop->iteration }}.) {{ $med->medication ?? '' }} {{ $med->dosage ?? '' }} #{{ $med->quantity ?? '1' }}<br>
      <strong>Sig:</strong> {{ $med->instructions ?? $prescription->notes ?? 'Take as directed by physician.' }}<br><br>
    @endforeach
  </div>

  <!-- ✅ Signature -->
  <div class="signature-section">
    @if(!empty($signatureData) && !empty($signatureMime))
      <img class="signature-img" src="data:{{ $signatureMime }};base64,{{ $signatureData }}" alt="Signature"><br>
    @endif
    Dr. {{ $prescription->appointment->doctor->firstname }} {{ $prescription->appointment->doctor->lastname }}, MD
  </div>

  <!-- ✅ Footer -->
  <div class="footer">
    License No.: {{ $prescription->appointment->doctor->license_no ?? '_________' }}<br>
    PTR No.: {{ $prescription->appointment->doctor->ptr_no ?? '_________' }}<br>
    S2 No.: {{ $prescription->appointment->doctor->s2_no ?? '_________' }}
  </div>

</body>
</html>
