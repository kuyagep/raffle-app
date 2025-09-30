<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Raffle Winners - National Teachers' Day 2025</title>

    <!-- SB Admin 2 CSS -->
    <link href="{{ asset('static/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('static/css/sb-admin-2.min.css') }}" rel="stylesheet">
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
