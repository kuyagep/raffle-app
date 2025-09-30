<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher’s Day Registration & Attendance</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .btn-deped {
            background-color: #003399;
            border-color: #003399;
            color: #fff;
        }

        .btn-deped:hover {
            background-color: #002b73;
            border-color: #002b73;
            color: #fff;

        }

        .hero {
            background: url('{{ asset('images/teacher.png') }}') center/cover no-repeat;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            position: relative;
        }

        .hero::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .feature-icon {
            font-size: 3rem;
            color: #003399;
        }
    </style>
</head>

<body>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="rounded-circle mb-3"
                style="width:100px; height:100px; object-fit:cover;">
            <h1 class="display-4 font-weight-bold">Teacher’s Day 2025</h1>
            <p class="lead">Online Registration & QR Code Attendance System</p>
            <a href="/join" class="btn btn-deped btn-lg m-2">Join the Event</a>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <img src="{{ asset('images/teachers.png') }}" alt="Teachers" class="img-fluid rounded shadow">
                </div>
                <div class="col-md-6">
                    <h2>About the System</h2>
                    <p>
                        The Teacher’s Day Online Registration & Attendance System provides a seamless way to register
                        participants and track attendance using QR codes. Designed to celebrate our educators, this
                        system
                        makes event management faster, more efficient, and paperless.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="bg-light py-5">
        <div class="container text-center">
            <h2 class="mb-4">Key Features</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h5>Online Registration</h5>
                    <p>Teachers can easily sign up and secure their slot online.</p>
                </div>
                <div class="col-md-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-qrcode"></i>
                    </div>
                    <h5>QR Code Attendance</h5>
                    <p>Fast and reliable attendance tracking using QR codes.</p>
                </div>
                <div class="col-md-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h5>Reports & Analytics</h5>
                    <p>Generate summaries and monitor attendance in real-time.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4 bg-dark text-white text-center">
        <div class="container">
            <p class="mb-0">&copy; 2025 Teacher’s Day Registration & Attendance System | Powered by Division of Davao
                del Sur</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
</body>

</html>
