<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{ route('admin.dashboard') }}" class="brand-link">
    <img src="{{asset('admin/dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-bold">Admin Control Panel</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel mt-3 pb-2 mb-3 d-flex">
      <div class="image">
        <img src="{{asset('admin/dist/img/heat2.png')}}" class="img-circle elevation-4" alt="User Image">
      </div>
      <div class="info">
        <a href=" " class="d-block h5">Heat</a>
      </div>
    </div>

    <!-- SidebarSearch Form -->
    <div class="form-inline">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search modules..." aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Dashboard -->
        <li class="nav-item">
          <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
              <!-- <span class="badge badge-primary right">Overview</span> -->
            </p>
          </a>
        </li>
        
         <!-- Level Management -->
        <li class="nav-item">
          <a href="{{ route('admin.level.index') }}" class="nav-link {{ request()->routeIs('admin.level.index') ? 'active' : '' }}">
          <i class="nav-icon fas fa-chart-line"></i>
          <p>
              Level
              <!-- <span class="badge badge-info right">Core</span> -->
            </p>
          </a>
        </li>
        <!-- Classes -->
        <li class="nav-item">
          <a href="{{ route('admin.classes.index') }}" class="nav-link {{ request()->routeIs('admin.classes.index') ? 'active' : '' }}">
            <i class="nav-icon fas fa-chalkboard"></i>
            <p>
              Classes Management
              <!-- <span class="badge badge-info right">Core</span> -->
            </p>
          </a>
        </li>

        <!-- Users Menu -->
        <li class="nav-item {{ request()->routeIs(['admin.users', 'admin.admins.index', 'admin.coach.index','admin.users.not_active']) ? 'menu-open' : '' }}">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-users-cog"></i>
            <p>
              User Management
              <i class="fas fa-angle-left right"></i>
              <!-- <span class="badge badge-secondary right">Admin</span> -->
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <i class="nav-icon fas fa-users"></i>
                <p>
                  All Users
                  <!-- <span class="badge badge-light right">View</span> -->
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.coach.index') }}" class="nav-link {{ request()->routeIs('admin.coach.index') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-tie"></i>
                <p>
                  Coaches Directory
                  <!-- <span class="badge badge-light right">View</span> -->

                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.admins.index') }}" class="nav-link {{ request()->routeIs('admin.admins.index') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-shield"></i>
                <p>
                  Admin Profiles
                  <!-- <span class="badge badge-info right">Secure</span> -->
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.users.not_active') }}" class="nav-link {{ request()->routeIs('admin.users.not_active') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-slash"></i>
                <p>
                  Inactive Accounts
                 </p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Appointments -->
        <li class="nav-item {{ request()->routeIs(['admin.appointments.*', 'admin.booking.*', 'admin.class_seat.index']) ? 'menu-open' : '' }}">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-calendar-alt"></i>
            <p>
              Appointment System
              <i class="fas fa-angle-left right"></i>
              <!-- <span class="badge badge-info right">Schedule</span> -->
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.appointments.index') }}" class="nav-link {{ request()->routeIs('admin.appointments.index') ? 'active' : '' }}">
                <i class="far fa-calendar-check nav-icon"></i>
                <p>
                  Appointment
                  <!-- <span class="badge badge-light right">List</span> -->
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.booking.index') }}" class="nav-link {{ request()->routeIs('admin.booking.index') ? 'active' : '' }}">
                <i class="nav-icon fas fa-book"></i>
                <p>
                  Bookings
                  <!-- <span class="badge badge-light right">List</span> -->
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.class_seat.index') }}" class="nav-link {{ request()->routeIs('admin.class_seat.index') ? 'active' : '' }}">
                <i class="nav-icon fas fa-chair"></i>
                <p>
                  Class Seat Allocation
                  <!-- <span class="badge badge-light right">List</span> -->
                </p>
              </a>
            </li>
          </ul>
        </li>
        <!-- Section Plans -->
        <li class="nav-item">
          <a href="{{ route('admin.section_plans.index') }}" class="nav-link {{ request()->routeIs('admin.section_plans.index') ? 'active' : '' }}">
            <i class="nav-icon fas fa-th-large"></i>
            <p>
              Plan Header
              <!-- <span class="badge badge-info right">Sections</span> -->
            </p>
          </a>
        </li>
        <!-- Plans -->
        <li class="nav-item">
          <a href="{{ route('admin.plans.index') }}" class="nav-link {{ request()->routeIs('admin.plans.index') ? 'active' : '' }}">
            <i class="nav-icon fas fa-layer-group"></i>
            <p>
              Plans
              <span class="badge badge-success right">Active</span>
            </p>
          </a>
        </li>


        <li class="nav-item">
          <a href="{{ route('admin.plans.create') }}" class="nav-link {{ request()->routeIs('admin.plans.create') ? 'active' : '' }}">
            <i class="nav-icon fas fa-file-signature"></i>
            <p>Create Plan</p>
          </a>
        </li>


        <!-- Transactions -->
        <li class="nav-item">
          <a href="{{ route('admin.transaction.index') }}" class="nav-link {{ request()->routeIs('admin.transaction.index') ? 'active' : '' }}">
            <i class="nav-icon fas fa-exchange-alt"></i>
            <p>
              Transaction History
              <!-- <span class="badge badge-secondary right">Records</span> -->
            </p>
          </a>
        </li>

        <li class="nav-item">
          <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
            @csrf
          </form>
          <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p>Logout</p>
          </a>
        </li>




      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>