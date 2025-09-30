<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #003399;">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
        <div class="sidebar-brand-icon">
            <i class="fas fa-school"></i>
        </div>
        <div class="sidebar-brand-text mx-3">DASHBOARD</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <a class="nav-link " href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>


    <!-- Nav Item - Registrations -->
    <li class="nav-item {{ request()->routeIs('admin.participants.*') ? 'active' : '' }}">
        <a class="nav-link " href="{{ route('admin.participants.index') }}">
            <i class="fas fa-users"></i>
            <span>Participants</span>
        </a>
    </li>
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

    <li class="nav-item {{ request()->routeIs('raffle.draw') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('raffle.draw') }}" target="_blank">
            <i class="fas fa-random"></i>
            <span>Raffle Draw</span>
        </a>
    </li>
    <li class="nav-item {{ request()->routeIs('public.winners') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('public.winners') }}" target="_blank">
            <i class="fas fa-trophy"></i>
            <span>Raffle Wiiners</span>
        </a>
    </li>
    <li class="nav-item {{ request()->routeIs('attendance.scan') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('attendance.scan') }}" target="_blank">
            <i class="fas fa-qrcode"></i>
            <span>Scan QRCODE</span>
        </a>
    </li>



    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">


    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
