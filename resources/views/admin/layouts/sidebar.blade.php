<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <!-- <a href="{{ route('admin.dashboard') }}" class="brand-link">
    <img src="{{asset('admin/dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-bold">Admin Panel</span>
  </a> -->

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
    <!-- <div class="form-inline">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search modules..." aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div> -->

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        
        <!-- Dashboard -->
        @if(auth()->user()->hasRole(['admin']))
          <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
        @endif
        
        <!-- Level Management -->
        @if(auth()->user()->hasRole('admin'))
          <li class="nav-item">
            <a href="{{ route('admin.level.index') }}" class="nav-link {{ request()->routeIs('admin.level.index') ? 'active' : '' }}">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>Levels</p>
            </a>
          </li>
        @endif

        <!-- Classes Management -->
        @if(auth()->user()->hasRole('admin'))
          <li class="nav-item">
            <a href="{{ route('admin.classes.index') }}" class="nav-link {{ request()->routeIs('admin.classes.index') ? 'active' : '' }}">
              <i class="nav-icon fas fa-chalkboard"></i>
              <p>Classes Management</p>
            </a>
          </li>
        @endif

        <!-- Appointments -->
        @if(auth()->user()->hasRole(['admin','office_manager']))
          <li class="nav-item {{ request()->routeIs(['admin.appointments.*', 'admin.booking.*', 'admin.class_seat.index']) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                Appointment System
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
  

            <ul class="nav nav-treeview">
            @if(auth()->user()->hasRole(['admin']))
              <li class="nav-item">
                <a href="{{ route('admin.appointments.index') }}" class="nav-link {{ request()->routeIs('admin.appointments.index') ? 'active' : '' }}">
                  <i class="far fa-calendar-check nav-icon"></i>
                  <p>Appointments</p>
                </a>
              </li>
              @endif
              <li class="nav-item">
                <a href="{{ route('admin.booking.index') }}" class="nav-link {{ request()->routeIs('admin.booking.index') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-book"></i>
                  <p>Bookings</p>
                </a>
              </li>
              <!-- @if(auth()->user()->hasRole(['admin']))

              <li class="nav-item">
                <a href="{{ route('admin.class_seat.index') }}" class="nav-link {{ request()->routeIs('admin.class_seat.index') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-chair"></i>
                  <p>Class Seat Allocation</p>
                </a>
              </li>
              @endif -->
            </ul>
          </li>
        @endif

        <!-- Section Plans -->
        @if(auth()->user()->hasRole(['admin']))

     <li class="nav-item {{ request()->routeIs(['admin.users', 'admin.admins.index', 'admin.coach.index','admin.users.not_active']) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-cogs"></i>  
            <p>Plan Management
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
        @if(auth()->user()->hasRole('admin'))
          <li class="nav-item">
            <a href="{{ route('admin.section_plans.index') }}" class="nav-link {{ request()->routeIs('admin.section_plans.index') ? 'active' : '' }}">
              <i class="nav-icon fas fa-th-large"></i>
              <p>Plan Header</p>
            </a>
          </li>
        @endif

        <!-- Plans -->
        @if(auth()->user()->hasRole('admin'))
          <li class="nav-item">
            <a href="{{ route('admin.plans.index') }}" class="nav-link {{ request()->routeIs('admin.plans.index') ? 'active' : '' }}">
              <i class="nav-icon fas fa-layer-group"></i>
              <p>Plans</p>
            </a>
          </li>
        @endif

        <!-- Create Plan -->
        <!-- @if(auth()->user()->hasRole('admin'))
          <li class="nav-item">
            <a href="{{ route('admin.plans.create') }}" class="nav-link {{ request()->routeIs('admin.plans.create') ? 'active' : '' }}">
              <i class="nav-icon fas fa-file-signature"></i>
              <p>Create Plan</p>
            </a>
          </li>
        @endif -->
      </ul>
    </li>
    @endif
        <!-- Transaction History -->
        @if(auth()->user()->hasRole('admin'))
          <li class="nav-item">
            <a href="{{ route('admin.transaction.index') }}" class="nav-link {{ request()->routeIs('admin.transaction.index') ? 'active' : '' }}">
              <i class="nav-icon fas fa-exchange-alt"></i>
              <p>Transaction History</p>
            </a>
          </li>
        @endif

        <!-- Users Menu -->
        @if(auth()->user()->hasRole('admin'))
          <li class="nav-item {{ request()->routeIs(['admin.users', 'admin.admins.index', 'admin.coach.index','admin.users.not_active']) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users-cog"></i>
              <p>User Management
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-users"></i>
                  <p>All Users</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.coach.index') }}" class="nav-link {{ request()->routeIs('admin.coach.index') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-user-tie"></i>
                  <p>Coaches Directory</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.office_manager.index') }}" class="nav-link {{ request()->routeIs('admin.office_manager.index') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-user-shield"></i>
                  <p>Office Manager</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.admins.index') }}" class="nav-link {{ request()->routeIs('admin.admins.index') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-user-shield"></i>
                  <p>Admin Profiles</p>
                </a>
              </li>
            </ul>
          </li>
        @endif

        <!-- Notifications -->
        @if(auth()->user()->hasRole('admin'))
          <li class="nav-item">
            <a href="{{ route('admin.notifications.index') }}" class="nav-link {{ request()->routeIs('admin.notifications.index') ? 'active' : '' }}">
              <i class="nav-icon fas fa-exchange-alt"></i>
              <p>Notifications</p>
            </a>
          </li>
        @endif

        <!-- Logout -->
        @if(auth()->user()->hasRole(['admin','office_manager']))
          <li class="nav-item">
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
              @csrf
            </form>
            <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Logout</p>
            </a>
          </li>
        @endif

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
