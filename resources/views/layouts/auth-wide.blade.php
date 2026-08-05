<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pre-Registration') | {{ config('app.name', 'SDO DAVSUR') }}</title>

    <!-- CSS Dependencies -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('images/favicon/site.webmanifest') }}">

    <style>
        body,
        html {
            height: 100%;
            background-color: #f4f6f9;
        }

        .layout-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Top Brand Header Bar */
        .brand-header-bar {
            background-color: #800000;
            background-image: linear-gradient(135deg, #800000 0%, #4a0000 100%);
            color: #ffffff;
            border-bottom: 4px solid #d9534f;
        }

        /* Dark Red Custom Accents */
        .bg-theme-head {
            background: linear-gradient(180deg, #800000 0%, #600000 100%);
        }

        .text-dark-red {
            color: #800000 !important;
        }

        .btn-dark-red {
            background-color: #800000;
            border-color: #800000;
            color: #ffffff;
            font-weight: 600;
            transition: all 0.2s ease-in-out;
        }

        .btn-dark-red:hover {
            background-color: #5a0000;
            border-color: #5a0000;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(128, 0, 0, 0.3);
        }

        .btn-dark-red:focus,
        .btn-dark-red.focus {
            box-shadow: 0 0 0 0.2rem rgba(128, 0, 0, 0.4);
        }

        .form-control:focus,
        .custom-select:focus {
            border-color: #800000;
            box-shadow: 0 0 0 0.2rem rgba(128, 0, 0, 0.2);
        }

        .card-custom {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .logo-main {
            width: 85px;
            height: 85px;
            object-fit: contain;
        }

        .developer-credit {
            font-size: 0.825rem;
            color: #6c757d;
            letter-spacing: 0.5px;
        }

        /* Custom Input Styling */
        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
        }

        .input-group>.form-control:not(:first-child),
        .input-group>.custom-select:not(:first-child) {
            border-left: none;
        }

        @media (max-width: 576px) {
            .logo-main {
                width: 65px;
                height: 65px;
            }
        }
    </style>
    @stack('styles')
</head>

<body>

    <div class="layout-wrapper">

        <!-- Top Branding Banner Bar -->
        <header class="brand-header-bar py-3 shadow-sm">
            <div class="container-fluid px-md-5">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('images/logo.webp') }}" class="logo-main mr-3" alt="DepEd Logo">
                        <div>
                            <h5 class="mb-0 font-weight-bold text-uppercase tracking-wide">Department of Education</h5>
                            <p class="small mb-0 text-white-50 d-none d-sm-block">Schools Division of Davao del Sur</p>
                        </div>
                    </div>
                    <div>
                        @yield('header_actions')
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Workspace Container -->
        <main class="container py-4 my-auto">
            <div class="row justify-content-center">
                <!-- Expanded Breakpoint Width for Complex/Multi-column Forms -->
                <div class="col-xl-9 col-lg-10 col-md-11">

                    <div class="card ">



                        <div class="card-body p-4 p-md-5">

                            <!-- Page Heading Section -->
                            <div class="text-center mb-4">
                                <h2 class="h3 font-weight-bold text-dark-red mb-1">
                                    @yield('page_heading', 'Event Registration')
                                </h2>
                                <p class="text-muted small mb-0">
                                    @yield('page_subheading', 'Please fill out all required fields carefully.')
                                </p>
                            </div>

                            <hr class="my-4">

                            <!-- Global Session Alerts -->
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4"
                                    role="alert">
                                    <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            @if (session('status') || session('success'))
                                <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4"
                                    role="alert">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    {{ session('status') ?? session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <!-- Display Laravel Backend Validation Errors automatically -->
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4"
                                    role="alert">
                                    <div class="font-weight-bold mb-1"><i
                                            class="fas fa-exclamation-circle mr-2"></i>Please resolve the following
                                        errors:</div>
                                    <ul class="mb-0 pl-4 small">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <!-- Dynamic Page Content -->
                            @yield('content')

                        </div>
                    </div>

                </div>
            </div>
        </main>

        <!-- Footer Credit Bar -->
        <footer class="py-3 bg-white border-top text-center developer-credit">
            <div class="container">
                <span>&copy; {{ date('Y') }} {{ config('app.name', 'SDO DAVSUR') }}. All rights reserved.</span>
                <span class="d-block d-sm-inline-block ml-sm-2 text-muted">
                    Developed by <strong>Geperson Mamalias</strong>
                </span>
            </div>
        </footer>

    </div>

    <!-- JS Dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- CSRF Ajax Header Pre-configuration -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
