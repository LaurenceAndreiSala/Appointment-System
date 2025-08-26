@extends('layouts.layout')

@section('title', 'Doctor Dashboard | Dashboard')
@section('content')
@include('includes.doctorsidebar')

  <!-- Main Content -->
  <div class="main">
    <h1>Doctor Dashboard</h1>

    <!-- Stats -->
    <div class="stats">
      <div class="stat-card" id="manageUsersCard">
        <h2>{{ $patientCount }}</h2>
        <p>Manage Patients</p>
      </div>
      <div class="stat-card">
        <h2>68</h2>
        <p>Today New Users</p>
      </div>
      <div class="stat-card">
        <h2>{{ $patientCount }}</h2>
        <p>Today Appointments</p>
      </div>
    </div>

    <!-- Grid Section -->
    <div class="grid">
      <!-- Left Side -->
      <div>
        <div class="card">
          <h3>Upcoming Appointments</h3>
          <ul class="appointment-list">
          @forelse($patients as $patient)
        <li>
            <div class="patient-info">
                <div>
                    <strong>{{ $patient->firstname }} {{ $patient->lastname }}</strong><br>
                    <small>Report Patient</small>
                </div>
            </div>
            <span class="tag green">On Going</span>
        </li>
    @empty
        <li>
            <div class="patient-info">
                <div>
                    <strong>No Patients</strong><br>
                    <small>Nothing queued</small>
                </div>
            </div>
        </li>
    @endforelse
          </ul>
        </div>
      </div>



  <!-- Modal for Manage Users -->
<div class="modal" id="userModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Approve Patients</h2>
            <span class="close-btn" id="closeModal">&times;</span>
        </div>
        <div class="user-list" id="userList">
            @forelse($patients as $patient)
            <div class="user-item">
                <div>
                    <strong>{{ $patient->firstname }} {{ $patient->lastname }}</strong><br>
                    @if($patient->status == 'pending')
                        <span class="tag green">Pending</span>
                    @elseif($patient->status == 'approved')
                        <span class="tag green">Approved</span>
                    @else
                        <span class="tag red">Denied</span>
                    @endif
                </div>
                <div class="user-actions">
                    <form method="POST" action="{{ route('patients.approve', $patient->id) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn-edit">Confirm</button>
                    </form>
                    <form method="POST" action="{{ route('patients.deny', $patient->id) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn-delete">Denied</button>
                    </form>
                </div>
            </div>
            @empty
                <p>No patients found.</p>
            @endforelse
        </div>
    </div>
</div>

  <script>
    const manageUsersCard = document.getElementById('manageUsersCard');
    const modal = document.getElementById('userModal');
    const closeModal = document.getElementById('closeModal');
    const addUserBtn = document.getElementById('addUserBtn');
    const userList = document.getElementById('userList');

    // Open modal when clicking the card
    manageUsersCard.addEventListener('click', () => {
      modal.style.display = 'flex';
    });

    // Close modal
    closeModal.addEventListener('click', () => {
      modal.style.display = 'none';
    });

    // Add User Example
    addUserBtn.addEventListener('click', () => {
      const newUser = document.createElement('div');
      newUser.classList.add('user-item');
      newUser.innerHTML = `
        <span>New User</span>
        <div class="user-actions">
          <button class="btn-edit">Edit</button>
          <button class="btn-delete">Delete</button>
        </div>
      `;
      userList.appendChild(newUser);
    });

    // Close when clicking outside modal
    window.addEventListener('click', (e) => {
      if (e.target === modal) {
        modal.style.display = 'none';
      }
    });
  </script>

  @endsection