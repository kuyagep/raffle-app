<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Dashboard')) | {{ config('app.name', 'DepEd Portal') }}</title>

    <!-- FontAwesome & SB Admin 2 CSS -->
    <link href="{{ asset('static/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="{{ asset('static/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('static/css/app.css') }}" rel="stylesheet">

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('images/favicon/site.webmanifest') }}">

    <!-- Universal Dark Red Theme Custom Styles -->
    <style>
        /* Base Dark Red Color Vars */
        :root {
            --dark-red-primary: #800000;
            --dark-red-hover: #600000;
            --dark-red-darker: #4a0000;
            --dark-red-gradient: linear-gradient(180deg, #800000 10%, #4a0000 100%);
        }

        /* Sidebar Override to Dark Red Gradient */
        .bg-gradient-primary,
        .sidebar-dark {
            background-color: var(--dark-red-primary) !important;
            background-image: var(--dark-red-gradient) !important;
            background-size: cover;
        }

        /* Primary Dark Red Buttons */
        .btn-primary,
        .btn-dark-red,
        .btn-deped {
            background-color: var(--dark-red-primary) !important;
            border-color: var(--dark-red-primary) !important;
            color: #ffffff !important;
        }

        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active,
        .btn-dark-red:hover,
        .btn-dark-red:focus,
        .btn-deped:hover,
        .btn-deped:focus {
            background-color: var(--dark-red-hover) !important;
            border-color: var(--dark-red-hover) !important;
            color: #ffffff !important;
            box-shadow: 0 0 0 0.2rem rgba(128, 0, 0, 0.25) !important;
        }

        /* Outline Dark Red Buttons */
        .btn-outline-primary {
            color: var(--dark-red-primary) !important;
            border-color: var(--dark-red-primary) !important;
        }

        .btn-outline-primary:hover {
            background-color: var(--dark-red-primary) !important;
            color: #ffffff !important;
        }

        /* Background Theme Helpers */
        .bg-theme,
        .bg-dark-red {
            background-color: var(--dark-red-primary) !important;
            color: #ffffff !important;
        }

        /* Text Accents & Badges */
        .text-primary,
        .text-dark-red {
            color: var(--dark-red-primary) !important;
        }

        .badge-primary {
            background-color: var(--dark-red-primary) !important;
        }

        /* Scroll to Top Button Override */
        a.scroll-to-top {
            background-color: var(--dark-red-primary) !important;
        }

        a.scroll-to-top:hover {
            background-color: var(--dark-red-hover) !important;
        }

        /* Focus Ring Highlights */
        .form-control:focus {
            border-color: var(--dark-red-primary) !important;
            box-shadow: 0 0 0 0.2rem rgba(128, 0, 0, 0.15) !important;
        }

        /* Custom Scrollbar for Dark Red Aesthetic */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--dark-red-primary);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--dark-red-hover);
        }
    </style>
    <style>
        /* Custom hover styling for the topbar mobile sidebar toggle */
        #sidebarToggleTop.sidebar-toggle-btn {
            transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
        }

        .topbar #sidebarToggleTop.sidebar-toggle-btn:hover,
        .topbar #sidebarToggleTop.sidebar-toggle-btn:focus {
            background-color: #600000 !important;
            color: #ffffff !important;
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.2);
        }

        .topbar #sidebarToggleTop.sidebar-toggle-btn:active {
            background-color: #4a0000 !important;
        }
    </style>
    @stack('styles')
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('app.partials.sidebar')
        <!-- End Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('app.partials.topbar')
                <!-- End Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading / Header Section -->
                    @hasSection('page_heading')
                        <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
                            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">@yield('page_heading')</h1>
                            @yield('page_actions')
                        </div>
                    @endif

                    <!-- Main Dashboard View Content -->
                    @yield('content')

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End Main Content -->

            <!-- Footer -->
            @include('app.partials.footer')
            <!-- End Footer -->

        </div>
        <!-- End Content Wrapper -->

    </div>
    <!-- End Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Universal Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content border-top-danger" style="border-top: 4px solid var(--dark-red-primary);">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold text-dark" id="logoutModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-dark-red">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Core Scripts -->
    <script src="{{ asset('static/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('static/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('static/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('static/js/sb-admin-2.min.js') }}"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Global AJAX CSRF Setup & Session Alert Handler -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Flash Message Alerts via SweetAlert2 (styled with theme dark red confirm button)
        const swalCustom = Swal.mixin({
            confirmButtonColor: '#800000',
        });

        @if (session('success'))
            swalCustom.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                timer: 3500,
                showConfirmButton: false
            });
        @endif

        @if (session('error'))
            swalCustom.fire({
                icon: 'error',
                title: 'Error!',
                text: "{{ session('error') }}",
            });
        @endif

        @if (session('warning'))
            swalCustom.fire({
                icon: 'warning',
                title: 'Warning!',
                text: "{{ session('warning') }}",
            });
        @endif
    </script>

    @stack('scripts')

</body>

</html>
