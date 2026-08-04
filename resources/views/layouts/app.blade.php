<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DepEd Davao del Sur') }}</title>

    <!-- Custom Fonts & Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"
        type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 4 CSS (or link your local theme CSS) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <style>
        :root {
            --theme-color: #800000;
            /* Dark Red / Maroon */
            --theme-hover: #600000;
        }

        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f8f9fc;
            color: #5a5c69;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .bg-theme {
            background-color: var(--theme-color) !important;
        }

        .text-theme {
            color: var(--theme-color) !important;
        }

        .btn-dark-red {
            background-color: var(--theme-color);
            color: #ffffff;
            border-color: var(--theme-color);
        }

        .btn-dark-red:hover,
        .btn-dark-red:focus {
            background-color: var(--theme-hover);
            color: #ffffff;
            border-color: var(--theme-hover);
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .guest-navbar {
            border-bottom: 3px solid var(--theme-color);
        }

        .main-content {
            flex: 1;
        }

        .sticky-footer {
            padding: 1.5rem 0;
            margin-top: auto;
            border-top: 1px solid #e3e6f0;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-light">

    <!-- Top Guest Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-white bg-white shadow-sm guest-navbar">
        <div class="container">
            <a class="navbar-brand font-weight-bold text-theme d-flex align-items-center" href="{{ url('/') }}">
                <i class="fas fa-graduation-cap text-danger fa-lg mr-2"></i>
                <span>DepEd Davao del Sur</span>
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#guestNavbar"
                aria-controls="guestNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"><i class="fas fa-bars mt-1 text-dark"></i></span>
            </button>

            <div class="collapse navbar-collapse" id="guestNavbar">
                <ul class="navbar-nav ml-auto align-items-center">
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a class="btn btn-sm btn-dark-red px-3" href="{{ route('admin.events.index') }}">
                                    <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                                </a>
                            </li>
                        @else
                            <li class="nav-item mr-2">
                                <a class="nav-link font-weight-bold text-dark" href="{{ route('login') }}">
                                    <i class="fas fa-sign-in-alt mr-1"></i> Log In
                                </a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="btn btn-sm btn-dark-red px-3" href="{{ route('register') }}">
                                        Register
                                    </a>
                                </li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Body -->
    <main class="main-content py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="text-center my-auto">
                <span>© 2025 DepEd Division of Davao del Sur. All Rights Reserved.</span>
                <div class="small text-muted mt-1">
                    Developed by <span class="font-weight-bold text-dark">ICT Services Unit</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts: jQuery, Popper.js, and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

    @stack('scripts')
</body>

</html>
