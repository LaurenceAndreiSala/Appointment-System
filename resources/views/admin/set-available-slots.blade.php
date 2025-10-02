@extends('layouts.layout')
@section('title', 'Admin Dashboard | MediCare {{ Auth::user()->name }}')

@section('content')

<!-- Top Navbar -->
<nav class="navbar navbar-light bg-white shadow-sm sticky-top">
  <div class="container-fluid d-flex align-items-center">
    <!-- Sidebar Toggle (mobile only) + Brand -->
    <div class="d-flex align-items-center">
      <!-- Hamburger (mobile only) -->
      <button class="btn d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar">
        <i data-feather="menu"></i>
      </button>
      <a class="navbar-brand d-flex align-items-center mb-0 h1" href="#">
        <i data-feather="shield" class="text-primary me-2"></i>
        <span class="fw-bold fs-6 fs-md-5">Welcome {{ Auth::user()->lastname }}!</span>
      </a>
    </div>
  </div>
</nav>


<div class="container-fluid">
  <div class="row">
  @include('includes.adminleftnavbar')


    <!-- Main Content -->
    <main class="col-md-9 col-lg-10 p-5">
      <div class="card shadow-sm border-0 mb-4 p-4">
        <h3 class="fw-bold mb-3">Set Available Appointment Slot</h3>
      <div class="mycard-content">

<!-- Archive Modal -->
<div class="modal fade" id="archivedModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded-3 shadow">
      <div class="modal-header bg-secondary text-white">
        <h5 class="modal-title">Archive Slot</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
<h2>Archived Slots</h2>
<table class="table">
    <thead>
        <tr>
            <th>Date</th>
            <th>Start</th>
            <th>End</th>
            <th>Max Patients</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
@foreach($archivedSlots ?? [] as $slot)
<tr>
    <td>{{ $slot->date }}</td>
<td>{{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }}</td>
<td>{{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}</td>
    <td>
        <form action="{{ route('admin.slots.restore', $slot->id) }}" method="POST">
            @csrf
            @method('PUT')
            <button type="submit" class="btn btn-sm btn-success">
                <i class="fas fa-undo"></i> Restore
            </button>
        </form>
    </td>
</tr>
@endforeach
</tbody>
</table>
      </div>
    </div>
  </div>
</div>

<!-- Archive Modal -->
<div class="modal fade" id="archiveModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded-3 shadow">
      <div class="modal-header bg-secondary text-white">
        <h5 class="modal-title">Archive Slot</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to archive this slot?
      </div>
      <div class="modal-footer">
        <form id="archiveForm" method="POST" action="{{ route('admin.slots.archive') }}">
          @csrf
          <input type="hidden" name="slot_id" id="archiveSlotId">
          <button type="submit" class="btn btn-secondary">Yes, Archive</button>
        </form>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

@if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('doctor.store-slot') }}" method="POST" class="mb-4">
    @csrf

    <!-- Select Doctor -->
    <div class="form-group mb-3">
        <label for="doctor_id">Select Doctor</label>
        <label for="doctor_id">Select Doctor</label>
    <select name="doctor_id" id="doctor_id" class="form-select" required>
  <option value="" disabled selected>-- Choose Doctor --</option>
  @foreach(\App\Models\User::where('role_id', 2)->get() as $doctor)
      <option value="{{ $doctor->id }}" 
              {{ $doctor->is_absent ? 'disabled' : '' }}>
          Dr. {{ $doctor->firstname }} {{ $doctor->lastname }}
          @if($doctor->is_absent) (Absent) @endif
      </option>
  @endforeach
</select>
    </div>
    <div class="form-group mb-3">
        <label>Date</label>
        <input type="date" name="date" class="form-control" required>
    </div>
    <div class="form-group mb-3">
        <label>Start Time</label>
        <input type="time" name="start_time" class="form-control" required>
    </div>
    <div class="form-group mb-3">
        <label>End Time</label>
        <input type="time" name="end_time" class="form-control" required>
    </div>
  
    <button type="submit" class="btn btn-primary mt-2">Save Slot</button>
</form>


    <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold mb-0">Available Slots</h3>
    <button type="button" class="btn btn-sm btn-secondary"
            data-bs-toggle="modal" 
            data-bs-target="#archivedModal">
        <i class="fas fa-archive"></i> Archived Slots
    </button>
</div>

    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Start</th>
                <th>End</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
    @foreach($slots as $slot) <!-- loop over each slot -->
    <tr>
        <td>{{ $slot->date }}</td>
<td>{{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }}</td>
<td>{{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}</td>
        <td>
            <div class="d-flex justify-content-center gap-2">
                <button type="button" class="btn btn-sm btn-secondary"
                        data-bs-toggle="modal" 
                        data-bs-target="#archiveModal"
                        data-id="{{ $slot->id }}"> <!-- ✅ use $slot->id -->
                    <i class="fas fa-archive"></i> Archive
                </button>
            </div>
        </td>
    </tr>
    @endforeach
</tbody>

 </table>
  </div>
</div>

<script>
  // Set slot ID in modal
    const archiveModal = document.getElementById("archiveModal");
    archiveModal.addEventListener("show.bs.modal", function (event) {
        let button = event.relatedTarget;
        let slotId = button.getAttribute("data-id");
        document.getElementById("archiveSlotId").value = slotId;
    });

      // Set slot ID in modal
    const archivedModal = document.getElementById("archivedModal");
    archivedModal.addEventListener("show.bs.modal", function (event) {
        let button = event.relatedTarget;
        let slotId = button.getAttribute("data-id");
    });
</script>

@endsection
