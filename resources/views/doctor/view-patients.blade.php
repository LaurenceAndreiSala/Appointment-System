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
            </tr>
          </thead>
          <tbody id="appointmentsTable">
            @forelse($patients as $patient)
              @php
                $latestAppt = $patient->appointments()
                                ->where('doctor_id', auth()->id())
                                ->with('slot')
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

                <!-- Date & Time -->
                <td>
                 {{ $patient->contact_no }}
                </td>
<td>
                  @if($latestAppt)
                    {{ \Carbon\Carbon::parse($latestAppt->appointment_date)->format('M d, Y') }}<br>
                    @if($latestAppt->slot)
                    @else
                      {{ \Carbon\Carbon::parse($latestAppt->appointment_time)->format('h:i A') }}
                    @endif
                  @else
                    <em class="text-muted">No appointment</em>
                  @endif
                </td>
                <!-- Status -->
                <!-- <td class="status-cell">
                  @if($latestAppt)
                    @php $status = $latestAppt->status; @endphp
                    <span class="badge px-3 py-2 rounded-pill text-capitalize
                      {{ $status == 'pending' ? 'bg-warning text-dark' : '' }}
                      {{ $status == 'approved' ? 'bg-success' : '' }}
                      {{ $status == 'denied' ? 'bg-danger' : '' }}
                      {{ $status == 'cancelled' ? 'bg-secondary' : '' }}
                    ">
                      {{ ucfirst($status) }}
                    </span>
                  @else
                    <span class="badge bg-light text-muted">No Status</span>
                  @endif
                </td> -->
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-muted py-5">
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

<!-- ✅ Styles -->
<style>
.table-hover tbody tr:hover {
  background-color: #f8f9fa;
  transform: scale(1.01);
  transition: all 0.2s ease;
}

.card {
  border-radius: 1rem;
}

.badge {
  font-size: 0.85rem;
}

.btn {
  transition: 0.2s ease-in-out;
}

.btn:hover {
  transform: translateY(-2px);
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById("searchInput");
    const table = document.getElementById("appointmentsTable");

    function filterTable() {
        const searchValue = searchInput.value.toLowerCase();
        const rows = Array.from(table.querySelectorAll("tr"));

        rows.forEach(row => {
            // Skip "no patients" row
            if (row.querySelector("td[colspan='5']")) return;

            const patientCell = row.cells[1]; // 2nd column: Patient
            const patientName = patientCell.textContent.toLowerCase();

            row.style.display = patientName.includes(searchValue) ? "" : "none";
        });
    }

    searchInput.addEventListener("input", filterTable);
});
</script>

<!-- ✅ JS Search & Status Filter -->
<script>
document.addEventListener("DOMContentLoaded", function() {
  const searchInput = document.getElementById("searchInput");
  const statusFilter = document.getElementById("statusFilter");
  const rows = Array.from(document.querySelectorAll(".patient-row"));

  function filterTable() {
    const searchValue = searchInput.value.toLowerCase();
    const statusValue = statusFilter.value.toLowerCase();

    rows.forEach(row => {
      const patientName = row.querySelector(".patient-name")?.textContent.toLowerCase() || "";
      const statusText = row.querySelector(".status-cell span")?.textContent.toLowerCase() || "";

      const matchesSearch = patientName.includes(searchValue);
      const matchesStatus = statusValue === "" || statusText === statusValue;

      row.style.display = (matchesSearch && matchesStatus) ? "" : "none";
    });
  }

  searchInput.addEventListener("input", filterTable);
  statusFilter.addEventListener("change", filterTable);
});
</script>


<!-- JS -->
<script>
const sidebar = document.getElementById('sidebar');
const mainContent = document.getElementById('mainContent');
const toggleBtn = document.getElementById('sidebarToggle');
const sidebarClose = document.getElementById('sidebarClose');

// Hamburger toggle (mobile)
toggleBtn.addEventListener('click', () => {
  sidebar.style.transform = 'translateX(0)';
});

// Close button (mobile)
sidebarClose.addEventListener('click', () => {
  sidebar.style.transform = 'translateX(-100%)';
});

// Click outside to close sidebar (mobile)
document.addEventListener('click', function(e){
  if(window.innerWidth < 992){
    if(!sidebar.contains(e.target) && !toggleBtn.contains(e.target)){
      sidebar.style.transform = 'translateX(-100%)';
    }
  }
});

// Adjust main content margin based on sidebar (desktop)
function handleResize() {
  if(window.innerWidth >= 992){
    sidebar.style.transform = 'translateX(0)';
    mainContent.style.marginLeft = '250px'; // match sidebar width
  } else {
    sidebar.style.transform = 'translateX(-100%)';
    mainContent.style.marginLeft = '0';
  }
}

window.addEventListener('resize', handleResize);
window.addEventListener('load', handleResize);
</script>
<script>
  const notifUrl = "{{ route('doctor.notifications.fetch') }}";
</script>
<script src="{{ asset('js/notification.js') }}"></script>

@endsection
