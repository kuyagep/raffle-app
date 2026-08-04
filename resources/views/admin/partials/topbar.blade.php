<nav class="navbar navbar-expand navbar-light topbar mb-4 static-top" style="background-color: #003399;">
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>
    <h5 class="m-0 fw-bold text-white">Online System</h5>

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

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">

                <img class="img-profile rounded-circle"
                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}">
                <span class="ml-2 d-none d-lg-inline text-white small">
                    {{ Auth::user()->name }}
                </span>
            </a>
            <!-- Dropdown - User Info -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{ route('account.edit') }}">
                    <i class="fas fa-user-cog fa-sm fa-fw mr-2 text-gray-400"></i>
                    Account Settings
                </a>

                <div class="dropdown-divider"></div>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </button>
                </form>
            </div>
        </li>
    </ul>
</nav>
