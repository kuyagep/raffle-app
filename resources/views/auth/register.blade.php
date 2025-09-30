<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher’s Day | Login</title>

    <!-- SB Admin 2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

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

        .bg-theme {
            background-color: #003399;
        }

        .logo {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        @media (max-width: 576px) {
            .logo {
                width: 80px;
                height: 80px;
            }
        }
    </style>
</head>

<body class="bg-theme">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center w-100">

            <div class="col-xl-6 col-lg-8 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-4">
                                    <div class="text-center">

                                        <!-- Circle Logo -->
                                        <img src="/images/logo.webp" class="img-fluid rounded-circle mb-3 logo"
                                            alt="Logo">

                                        <!-- System Title -->
                                        <h1 class="h4 text-gray-900 mb-2">Teacher’s Day</h1>
                                        <p class="mb-4">Online Registration &amp; QR Code Attendance System</p>
                                    </div>

                                    <form class="user" method="POST" action="/register">
                                        @csrf

                                        <div class="form-group">
                                            <input type="text" name="name"
                                                class="form-control form-control-user @error('name') is-invalid @enderror"
                                                placeholder="Full Name" value="{{ old('name') }}" required>
                                            @error('name')
                                                <div class="invalid-feedback text-left">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <input type="email" name="email"
                                                class="form-control form-control-user @error('email') is-invalid @enderror"
                                                placeholder="Email Address" value="{{ old('email') }}" required>
                                            @error('email')
                                                <div class="invalid-feedback text-left">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password"
                                                    class="form-control form-control-user @error('password') is-invalid @enderror"
                                                    placeholder="Password" required>
                                                @error('password')
                                                    <div class="invalid-feedback text-left">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password_confirmation"
                                                    class="form-control form-control-user" placeholder="Repeat Password"
                                                    required>
                                        </div>


                                        <button type="submit" class="btn btn-deped btn-user btn-block">
                                            Register Account
                                        </button>
                                    </form>



                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="/forgot-password">Forgot Password?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="/login">Already have an account!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- SB Admin 2 JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
