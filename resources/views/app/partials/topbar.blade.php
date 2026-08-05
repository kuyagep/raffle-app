<nav class="navbar navbar-expand navbar-dark topbar mb-4 static-top shadow-sm" style="background-color: #800000;">
    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3 text-white">
        <i class="fa fa-bars"></i>
    </button>

    <!-- App Title -->
    <h5 class="m-0 font-weight-bold text-white">Online System</h5>

    <ul class="navbar-nav ml-auto">
        <!-- Raffle Links -->
        <li class="nav-item {{ request()->routeIs('raffle.draw') ? 'active' : '' }}">
            <a class="nav-link text-white" href="{{ route('raffle.draw') }}" target="_blank">
                <i class="fas fa-random text-white-50 mr-1"></i>
                <span class="text-white d-none d-lg-inline">Raffle Draw</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('public.winners') ? 'active' : '' }}">
            <a class="nav-link text-white" href="{{ route('public.winners') }}" target="_blank">
                <i class="fas fa-trophy text-white-50 mr-1"></i>
                <span class="text-white d-none d-lg-inline">Raffle Winners</span>
            </a>
        </li>

        <div class="topbar-divider d-none d-sm-block border-left-light opacity-25"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">

                @php
                    $displayName = Auth::user()->firstname
                        ? Auth::user()->firstname . ' ' . Auth::user()->lastname
                        : Auth::user()->name;
                @endphp

                <img class="img-profile rounded-circle mr-2"
                    src="https://ui-avatars.com/api/?name={{ urlencode($displayName) }}&background=600000&color=ffffff">
                <span class="d-none d-lg-inline text-white small font-weight-bold">
                    {{ $displayName }}
                </span>
            </a>

            <!-- Dropdown - User Info -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in border-0"
                aria-labelledby="userDropdown">
                <a class="dropdown-item py-2" href="{{ route('account.edit') }}">
                    <i class="fas fa-user-cog fa-sm fa-fw mr-2 text-muted"></i>
                    Account Settings
                </a>

                <div class="dropdown-divider"></div>

                <!-- Trigger Logout Modal or Direct POST -->
                <a class="dropdown-item py-2 text-danger font-weight-bold" href="#" data-toggle="modal"
                    data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-danger"></i>
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
