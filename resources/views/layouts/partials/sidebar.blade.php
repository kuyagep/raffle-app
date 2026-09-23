<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #003399;">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center py-3" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('images/logo.webp') }}" class="logo-main img-fluid" style="max-height: 40px; width: auto;"
                alt="DepEd Logo">
        </div>
        <div class="sidebar-brand-text mx-2 text-left">
            <span class="font-weight-bold d-block leading-tight">DASHBOARD</span>
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->routeIs('user.dashboard', 'admin.dashboard') ? 'active' : '' }}">
        <a class="nav-link " href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    @if (auth()->user()->role === 'admin')
        <!-- Nav Item - Registrations -->
        <li class="nav-item {{ request()->routeIs('admin.participants.*') ? 'active' : '' }}">
            <a class="nav-link " href="{{ route('admin.participants.index') }}">
                <i class="fas fa-users"></i>
                <span>Participants</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <a class="nav-link " href="{{ route('admin.users.index') }}">
                <i class="fas fa-users"></i>
                <span>Manage Users</span>
            </a>
        </li>
        {{-- <li class="nav-item {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
            <a class="nav-link " href="{{ route('admin.events.index') }}">
                <i class="fas fa-users"></i>
                <span>Manage Events</span>
            </a>
        </li> --}}
        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Raffle Section -->
        <div class="sidebar-heading">
            Raffle
        </div>

        <li class="nav-item {{ request()->routeIs('admin.prizes.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.prizes.index') }}">
                <i class="fas fa-gift"></i>
                <span>Prizes</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('admin.winners.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.winners.index') }}">
                <i class="fas fa-trophy"></i>
                <span>Winners</span>
            </a>
        </li>
    @endif


    @if (auth()->user()->role === 'user')
        <li class="nav-item {{ request()->routeIs('user.events.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('user.events.index') }}">
                <i class="fas fa-calendar-alt"></i>
                <span>Events</span>
            </a>
        </li>
    @endif
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">


    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
