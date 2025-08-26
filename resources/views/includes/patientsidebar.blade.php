<!-- Sidebar -->
<div class="sidebar">
  <div class="profile">
    <img src="{{ asset('img/logo.png') }}" alt="Admin">
    <h3>{{ Auth::user()->firstname }}</h3>
    <p>System Patient</p>

    <ul class="patientside">
      <li><a href="{{ route('patient.patient-dashboard') }}"><i class="fas fa-chart-line"></i> Dashboard</a></li>
      <li><a href="{{ route('patient.view-appointment') }}"><i class="fas fa-calendar-check"></i> View Appointment</a></li>
      <li><a href="{{ route('patient.video-call') }}"><i class="fas fa-video"></i> Chat / Video Call</a></li>
      <li><a href="{{ route('patient.give-feedback') }}"><i class="fas fa-star"></i> Give Feed Back</a></li>
      <li><a href="{{ route('patient.view-precription') }}"><i class="fas fa-file-prescription"></i> View Prescription</a></li>

      <!-- Logout styled like others -->
      <li>
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
           <i class="fas fa-sign-out-alt"></i> Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
      </li>
    </ul>
  </div>
</div>