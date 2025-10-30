@extends('layouts.layout')
@section('title', 'Doctor Dashboard | MediCare')

@section('content')
@include('includes.doctornavbar')
@include('includes.doctorsidebar')

<div class="container-fluid">
  <div class="row">

  <!-- ✅ Main Content -->
<div class="col-12 col-md-9 col-lg-10 offset-lg-2  p-4 p-md-2">
  <div class="bg-light rounded-4 shadow-sm p-4 mb-4 d-flex align-items-center">
    <i class="fas fa-users text-primary fa-2x me-3"></i>
    <h3 class="fw-bold mb-0 text-dark">View All Patients</h3>
  </div>

  <p class="text-muted mb-4">Review patient appointments and statuses.</p>

  <!-- ✅ Filter & Search Controls -->
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
    <div class="d-flex gap-2">
      <input type="text" id="searchInput" class="form-control" placeholder="🔍 Search...">
    </div>
  </div>

  <!-- ✅ Patients Table -->
  <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 text-center">
          <thead class="bg-primary text-white">
            <tr>
              <th>Profile</th>
              <th>Patient</th>
              <th>Email</th>
              <th>Phone #</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="appointmentsTable">
            @forelse($patients as $patient)
              @php
                $latestAppt = $patient->appointments()
                                ->where('doctor_id', auth()->id())
                                ->with(['slot', 'prescription', 'doctor'])
                                ->orderBy('appointment_date', 'desc')
                                ->orderBy('appointment_time', 'desc')
                                ->first();
              @endphp
              <tr class="align-middle patient-row">
                <!-- Profile -->
                <td>
                  <img src="{{ $patient->profile_picture ? asset($patient->profile_picture) : asset('img/default-avatar.png') }}"
                       alt="Profile"
                       class="rounded-circle shadow-sm border"
                       style="width:50px; height:50px; object-fit:cover;">
                </td>

                <!-- Patient -->
                <td class="fw-semibold patient-name">{{ $patient->firstname }} {{ $patient->lastname }}</td>

                <!-- Email -->
                <td>{{ $patient->email }}</td>

                <!-- Phone -->
                <td>{{ $patient->contact_no }}</td>

                <!-- Date -->
                <td>
                  @if($latestAppt)
                    {{ \Carbon\Carbon::parse($latestAppt->appointment_date)->format('M d, Y') }}<br>
                    @if($latestAppt->slot)
                      {{ \Carbon\Carbon::parse($latestAppt->slot->start_time)->format('h:i A') }} - 
                      {{ \Carbon\Carbon::parse($latestAppt->slot->end_time)->format('h:i A') }}
                    @else
                      {{ \Carbon\Carbon::parse($latestAppt->appointment_time)->format('h:i A') }}
                    @endif
                  @else
                    <em class="text-muted">No appointment</em>
                  @endif
                </td>

                <!-- Actions -->
                <td>
                  <button type="button" 
                          class="btn btn-sm btn-primary rounded-pill shadow-sm px-3"
                          data-bs-toggle="modal" 
                          data-bs-target="#viewPrescriptionModal"
                          data-name="{{ $patient->firstname }} {{ $patient->lastname }}"
                          data-age="{{ $patient->age && $patient->age > 0 ? $patient->age : 'N/A' }}"
                          data-gender="{{ $patient->gender ?? 'N/A' }}"
                          data-address="{{ $patient->address && trim($patient->address) != '' ? $patient->address : 'N/A' }}"
                          data-medication="{{ $latestAppt->prescription->medication ?? 'N/A' }}"
                          data-dosage="{{ $latestAppt->prescription->dosage ?? 'N/A' }}"
                          data-notes="{{ $latestAppt->prescription->notes ?? 'N/A' }}"
                          data-quantity="{{ $latestAppt->prescription->quantity ?? 'N/A' }}"
                          data-appointment-datetime="{{ $latestAppt ? \Carbon\Carbon::parse($latestAppt->appointment_date)->format('M d, Y') : 'N/A' }}{{ $latestAppt && $latestAppt->slot ? ' | ' . \Carbon\Carbon::parse($latestAppt->slot->start_time)->format('h:i A') . ' - ' . \Carbon\Carbon::parse($latestAppt->slot->end_time)->format('h:i A') : '' }}"
                          data-height="{{ $latestAppt->height ?? 'N/A' }}"
                          data-weight="{{ $latestAppt->weight ?? 'N/A' }}"
                          data-bmi="{{ $latestAppt->bmi ?? 'N/A' }}"
                          data-blood="{{ $latestAppt->blood_type ?? 'N/A' }}"
                          data-advice="{{ $latestAppt->advice ?? 'N/A' }}"
                          data-doctor="{{ $latestAppt->doctor?->firstname ?? 'N/A' }} {{ $latestAppt->doctor?->lastname ?? '' }}">
                    <i class="fas fa-eye me-1"></i> View
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-muted py-5">
                  <i class="fas fa-inbox fa-2x mb-2"></i><br>
                  No patients found.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<!-- ✅ View Patient Info Modal -->
<div class="modal fade" id="viewPrescriptionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content rounded-3 shadow">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold">View Patient Info</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body">
        
        <!-- Patient Info Section -->
        <div class="section-header">
          <h6 class="section-title text-muted">Patient Information</h6>
        </div>
        <div class="row">
          <div class="col-md-6">
            <p><strong>Name:</strong> <span id="vName">N/A</span></p>
            <p><strong>Age:</strong> <span id="vAge">N/A</span></p>
            <p><strong>Gender:</strong> <span id="vGender">N/A</span></p>
            <p><strong>Address:</strong> <span id="vAddress">N/A</span></p>
          </div>
          <div class="col-md-6">
            <p><strong>Date & Time:</strong> <span id="viewAppointmentDateTime">N/A</span></p>
            <p><strong>Height:</strong> <span id="vHeight">N/A</span></p>
            <p><strong>Weight:</strong> <span id="vWeight">N/A</span></p>
            <p><strong>BMI:</strong> <span id="vBmi">N/A</span></p>
            <p><strong>Blood Type:</strong> <span id="vBlood">N/A</span></p>
          </div>
        </div>

        <!-- Consultation Section -->
        <div class="section-header mt-3">
          <h6 class="section-title text-muted">Consultation/Advice</h6>
        </div>
        <p><strong>Advice / Consultation:</strong> <span id="vAdvice">N/A</span></p>

        <hr class="my-4">

        <!-- Prescription Info Section -->
        <div class="section-header">
          <h6 class="section-title text-muted">Prescription (RX)</h6>
        </div>
        <p><strong>Medication:</strong> <span id="viewMedication">N/A</span></p>
        <p><strong>Dosage:</strong> <span id="viewDosage">N/A</span></p>
        <p><strong>Quantity:</strong> <span id="viewQuantity">N/A</span></p>
        <p><strong>Notes:</strong> <span id="viewNotes">N/A</span></p>
        <p><strong>Doctor:</strong> <span id="vDoctor">N/A</span></p>
      </div>
    </div>
  </div>
</div>

<!-- ✅ Styles -->
<style>
.table-hover tbody tr:hover {
  background-color: #f8f9fa;
  transform: scale(1.01);
  transition: all 0.2s ease;
}
.card { border-radius: 1rem; }
.badge { font-size: 0.85rem; }
.btn { transition: 0.2s ease-in-out; }
.btn:hover { transform: translateY(-2px); }
</style>

<!-- ✅ JS -->
<script>
document.addEventListener("DOMContentLoaded", function() {

  // ✅ Filter/Search
  const searchInput = document.getElementById("searchInput");
  const table = document.getElementById("appointmentsTable");

  searchInput.addEventListener("input", function() {
    const searchValue = this.value.toLowerCase();
    const rows = table.querySelectorAll("tr.patient-row");

    rows.forEach(row => {
      const name = row.querySelector(".patient-name")?.textContent.toLowerCase() || "";
      row.style.display = name.includes(searchValue) ? "" : "none";
    });
  });

  // ✅ Modal dynamic fill
  const viewModal = document.getElementById("viewPrescriptionModal");
  viewModal.addEventListener("show.bs.modal", function (event) {
    const button = event.relatedTarget;

    document.getElementById("vName").innerText = button.getAttribute("data-name");
    document.getElementById("vAge").innerText = button.getAttribute("data-age");
    document.getElementById("vGender").innerText = button.getAttribute("data-gender");
    document.getElementById("vAddress").innerText = button.getAttribute("data-address");
    document.getElementById("viewMedication").innerText = button.getAttribute("data-medication");
    document.getElementById("viewDosage").innerText = button.getAttribute("data-dosage");
    document.getElementById("viewNotes").innerText = button.getAttribute("data-notes");
    document.getElementById("viewQuantity").innerText = button.getAttribute("data-quantity");
    document.getElementById("viewAppointmentDateTime").innerText = button.getAttribute("data-appointment-datetime");
    document.getElementById("vHeight").innerText = button.getAttribute("data-height");
    document.getElementById("vWeight").innerText = button.getAttribute("data-weight");
    document.getElementById("vBmi").innerText = button.getAttribute("data-bmi");
    document.getElementById("vBlood").innerText = button.getAttribute("data-blood");
    document.getElementById("vAdvice").innerText = button.getAttribute("data-advice");
    document.getElementById("vDoctor").innerText = button.getAttribute("data-doctor");
  });
});
</script>

<script>
  const notifUrl = "{{ route('doctor.notifications.fetch') }}";
</script>
<script src="{{ asset('js/notification.js') }}"></script>

@endsection
