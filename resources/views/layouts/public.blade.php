<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Raffle Winners - National Teachers' Day 2025</title>

    <!-- SB Admin 2 CSS -->
    <link href="{{ asset('static/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('static/css/sb-admin-2.min.css') }}" rel="stylesheet">
        <link rel="apple-touch-icon" sizes="180x180" href="{{asset("images/favicon/apple-touch-icon.png")}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset("images/favicon/favicon-32x32.png")}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset("images/favicon/favicon-16x16.png")}}">
    <link rel="manifest" href="{{asset("images/favicon/site.webmanifest")}}">

    <!-- Google Fonts: Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Roboto', sans-serif !important;
        }
    </style>


    <style>
        body {
            /* background-color: #003399; */
        }
        .bg-primary {
            background-color: #003399 !important;
        }
        .text-primary {
            color: #003399 !important;
        }
        .table thead th {
            background-color: #003399;
            color: #fff;
        }


    </style>
</head>

<body >

    <div class="container py-5">
        @yield('content')
    </div>

    <!-- SB Admin 2 JS -->
    <script src="{{ asset('static/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('static/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('static/js/sb-admin-2.min.js') }}"></script>
</body>
</html>
