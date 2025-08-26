<!-- Sidebar -->
  <div class="sidebar">
    <div class="profile">
      <img src="{{ asset('img/logo.png') }}" alt="Admin">
      <h3>Sec. {{ Auth::user()->firstname }}</h3>
      <p>System Administrative</p>
    </div>
    <ul>
      <li><a href="{{ route('admin.admin-dashboard') }}"><i class="fas fa-chart-line"></i> Dashboard</a></li>
      <li><a href="{{ route('admin.set-appointment') }}"><i class="fas fa-calendar-alt"></i> Set Available Slot</a> <li>
      <li><a href="{{ route('admin.view-appointment') }}"><i class="fas fa-list"></i> View Appointments</a> <li>
      <li><a href="{{ route('admin.create-doctors') }}"><i class="fas fa-book"></i>Manage doctor's</a> <li>
      <li><a href="{{ route('admin.settings') }}"><i class="fas fa-cog"></i> Settings</a></li>
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