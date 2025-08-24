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
        <h2>2000+</h2>
        <p>Manage Users</p>
      </div>
      <div class="stat-card">
        <h2>68</h2>
        <p>Today New Users</p>
      </div>
      <div class="stat-card">
        <h2>85</h2>
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
            <li>
              <div class="patient-info">
                <img src="{{ asset('img/loloy.png') }}" alt="Admin">
                <div>
                  <strong>M.J. Mical</strong><br>
                  <small>Health Checkup</small>
                </div>
              </div>
              <span class="tag green">On Going</span>
            </li>
            <li>
              <div class="patient-info">
               <img src="{{ asset('img/loloy.png') }}" alt="Admin">
                <div>
                  <strong>Sanath Deo</strong><br>
                  <small>Report</small>
                </div>
              </div>
              <span class="time">12:30 PM</span>
            </li>
            <li>
              <div class="patient-info">
                <img src="{{ asset('img/loloy.png') }}" alt="Admin">
                <div>
                  <strong>Loeara Phanj</strong><br>
                  <small>Consultation</small>
                </div>
              </div>
              <span class="time">01:00 PM</span>
            </li>
          </ul>
        </div>
      </div>

      <!-- Right Side -->
      <div>
        <div class="card">
          <h3>Next Appointmen Details</h3>
          <div class="patient-info">
            <img src="{{ asset('img/loloy.png') }}" alt="Admin">
            <div>
              <strong>Sanath Deo</strong><br>
              <small>Patient ID: 022009220005</small><br>
              <small>Diagnosis: Health Checkup</small><br>
              <small>Last Appointment: 15 Dec 2021</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal for Manage Users -->
  <div class="modal" id="userModal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Manage Users</h2>
        <span class="close-btn" id="closeModal">&times;</span>
      </div>
      <div class="user-list" id="userList">
        <!-- Example Users -->
        <div class="user-item">
          <span>1. John Doe</span>
          <div class="user-actions">
            <button class="btn-edit">Edit</button>
            <button class="btn-delete">Delete</button>
          </div>
        </div>
        <div class="user-item">
          <span>2. Jane Smith</span>
          <div class="user-actions">
            <button class="btn-edit">Edit</button>
            <button class="btn-delete">Delete</button>
          </div>
        </div>
      </div>
      <button class="btn-add" id="addUserBtn">+ Add User</button>
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
