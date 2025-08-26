<!-- Sidebar -->
  <div class="sidebar">
    <div class="profile">
      <img src="{{ asset('img/logo.png') }}" alt="Admin">
      <h3>Dr. {{ Auth::user()->firstname }}</h3>
      <p>System Doctor</p>
    </div>
    <ul>
      <li><a href="{{ route('doctor.doctor-dashboard') }}"><i class="fas fa-chart-line"></i> Dashboard</a></li>
      <li><a href="#"><i class="fas fa-calendar-plus"></i>Set Appointment Availability</a></li>
      <li><a href="#"><i class="fas fa-calendar-check"></i>View Appointment</a></li>
      <li><a href="#"><i class="fas fa-video"></i> Chat / Video Call</a></li>
      <li><a href="#"><i class="fas fa-file-prescription"></i> Manage Prescription</a></li>
      <li><a href="#"><i class="fas fa-star"></i> Review Feed Back</a></li>
      <li><a href="#"><i class="fas fa-cog"></i> Settings</a></li>
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