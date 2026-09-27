<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Authentication') | {{ config('app.name', 'SDO DAVSUR') }}</title>

    <!-- FontAwesome & Google Fonts -->
    <link href="{{ asset('static/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="type/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- SB Admin 2 & Custom App CSS -->
    <link href="{{ asset('static/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('static/css/app.css') }}" rel="stylesheet">

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('images/favicon/site.webmanifest') }}">

    <style>
        body,
        html {
            height: 100%;
        }

        .container {
            min-height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Dark Red Theme Custom Styles */
        .bg-theme {
            background-color: #700000;
            background-image: linear-gradient(180deg, #800000 10%, #4a0000 100%);
            background-size: cover;
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
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
        }

        .btn-dark-red:focus,
        .btn-dark-red.focus {
            box-shadow: 0 0 0 0.2rem rgba(128, 0, 0, 0.5);
        }

        .form-control-user:focus {
            border-color: #800000;
            box-shadow: 0 0 0 0.2rem rgba(128, 0, 0, 0.25);
        }

        .logo {
            width: 100px;
            height: 100px;
            object-fit: cover;
            padding: 2px;
        }

        .developer-credit {
            font-size: 0.825rem;
            color: rgba(255, 255, 255, 0.8);
            letter-spacing: 0.5px;
        }

        .custom-control-input:checked~.custom-control-label::before {
            background-color: #800000;
            border-color: #800000;
        }

        @media (max-width: 576px) {
            .logo {
                width: 80px;
                height: 80px;
            }
        }
    </style>
    @stack('styles')
</head>

<body class="bg-theme">

    <div class="container flex-column justify-content-center py-4">

        <!-- Outer Row -->
        <div class="row justify-content-center w-100">

            <div class="col-xl-5 col-lg-6 col-md-8">

                <div class="card o-hidden border-0 shadow-lg my-3">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-4 p-sm-5">

                                    <!-- Header Block -->
                                    <div class="text-center mb-4">
                                        <img src="{{ asset('images/logo.webp') }}"
                                            class="img-fluid rounded-circle mb-3 logo" alt="Logo">
                                        <h1 class="h4 text-gray-900 font-weight-bold mb-0">@yield('page_heading', 'Welcome')</h1>
                                        <p class="text-muted small mt-1">@yield('page_subheading', 'Please enter your details')</p>
                                    </div>

                                    <!-- Session Alerts -->
                                    @if (session('error'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            {{ session('error') }}
                                            <button type="button" class="close" data-dismiss="alert"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif

                                    @if (session('status'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{ session('status') }}
                                            <button type="button" class="close" data-dismiss="alert"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif

                                    <!-- Main Page Content -->
                                    @yield('content')

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- Developer Credit & Copyright Footer -->
        <div class="text-center mt-3 developer-credit">
            <span class="small">&copy; {{ date('Y') }} <strong>DepEd Division of Davao del Sur</strong>.
                All Rights Reserved.</span>
            <div class="small text-muted mt-1">
                Developed by
                <a href="https://github.com/kuyagep" target="_blank" rel="noopener noreferrer"
                    class="font-weight-bold text-dark text-decoration-none">
                    KUYAGEP
                </a>
            </div>
        </div>

    </div>

    <!-- JS Dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
