<tr>
  <td>{{ $pres->appointment->patient->firstname ?? '' }} {{ $pres->appointment->patient->lastname ?? '' }}</td>
  <td>{{ $pres->appointment->doctor->firstname ?? '' }} {{ $pres->appointment->doctor->lastname ?? '' }}</td>
  <td>{{ \Carbon\Carbon::parse($pres->appointment->appointment_date)->format('M d, Y') }}</td>
  <td>
    <div class="d-flex justify-content-center gap-2">
      <button type="button" class="btn btn-sm btn-secondary btn-archive" data-id="{{ $pres->id }}">
        <i class="fas fa-archive"></i> Archive
      </button>
      <button type="button" class="btn btn-sm btn-primary"
              data-bs-toggle="modal"
              data-bs-target="#viewPrescriptionModal"
              data-medication="{{ $pres->medication }}"
              data-dosage="{{ $pres->dosage }}"
              data-notes="{{ $pres->notes ?? 'N/A' }}">
        <i class="fas fa-eye"></i> View
      </button>
    </div>
  </td>
</tr>
