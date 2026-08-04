<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Participant Pre-Registration - Raffle System</title>

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

        .form-control:focus {
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

        @media (max-width: 576px) {
            .logo {
                width: 80px;
                height: 80px;
            }
        }
    </style>
</head>

<body class="bg-theme">

    <div class="container flex-column justify-content-center py-4">

        <!-- Outer Row -->
        <div class="row justify-content-center w-100">

            <div class="col-xl-7 col-lg-8 col-md-10">

                <div class="card o-hidden border-0 shadow-lg my-3">
                    <div class="card-body p-0">
                        <!-- Nested Row -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-4 p-sm-5">

                                    <!-- Header Block -->
                                    <div class="text-center mb-4">
                                        <img src="{{ asset('images/logo.webp') }}"
                                            class="img-fluid rounded-circle mb-3 logo" alt="Logo">
                                        <h1 class="h4 text-gray-900 font-weight-bold mb-0">Pre-Registration</h1>
                                        <p class="text-muted small mt-1">Please enter your details to pre-register</p>
                                    </div>

                                    <!-- AJAX Alert Container -->
                                    <div id="alertContainer"></div>

                                    <!-- Session Messages (for standard reloads) -->
                                    @if (session('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                                            <button type="button" class="close" data-dismiss="alert"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif

                                    <!-- Form -->
                                    <form id="preRegistrationForm">
                                        @csrf

                                        <div class="form-row">
                                            <div class="form-group col-md-6 mb-3">
                                                <label class="small font-weight-bold text-dark">First Name <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="firstname" id="firstname"
                                                    class="form-control" placeholder="First Name" required autofocus>
                                                <div class="invalid-feedback" id="err-firstname"></div>
                                            </div>

                                            <div class="form-group col-md-6 mb-3">
                                                <label class="small font-weight-bold text-dark">Last Name <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="lastname" id="lastname"
                                                    class="form-control" placeholder="Last Name" required>
                                                <div class="invalid-feedback" id="err-lastname"></div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6 mb-3">
                                                <label class="small font-weight-bold text-dark">Position /
                                                    Designation</label>
                                                <input type="text" name="position" id="position"
                                                    class="form-control" placeholder="e.g. Teacher III / AO II">
                                                <div class="invalid-feedback" id="err-position"></div>
                                            </div>

                                            <div class="form-group col-md-6 mb-3">
                                                <label class="small font-weight-bold text-dark">Sex</label>
                                                <select name="sex" id="sex" class="form-control">
                                                    <option value="">-- Select Sex --</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                </select>
                                                <div class="invalid-feedback" id="err-sex"></div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6 mb-3">
                                                <label class="small font-weight-bold text-dark">District / Department
                                                    <span class="text-danger">*</span></label>
                                                <select id="department_id" class="form-control" required>
                                                    <option value="">-- Select District --</option>
                                                    @foreach ($departments as $dept)
                                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6 mb-3">
                                                <label class="small font-weight-bold text-dark">School / Office <span
                                                        class="text-danger">*</span></label>
                                                <select name="office_id" id="office_id" class="form-control" disabled
                                                    required>
                                                    <option value="">-- Select Department First --</option>
                                                </select>
                                                <div class="invalid-feedback" id="err-office_id"></div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6 mb-3">
                                                <label class="small font-weight-bold text-dark">Email Address</label>
                                                <input type="email" name="email" id="email"
                                                    class="form-control" placeholder="name@domain.com">
                                                <div class="invalid-feedback" id="err-email"></div>
                                            </div>

                                            <div class="form-group col-md-6 mb-3">
                                                <label class="small font-weight-bold text-dark">Contact Number</label>
                                                <input type="text" name="contact_number" id="contact_number"
                                                    class="form-control" placeholder="09123456789">
                                                <div class="invalid-feedback" id="err-contact_number"></div>
                                            </div>
                                        </div>

                                        <button type="submit" id="submitBtn"
                                            class="btn btn-dark-red btn-block mt-4 py-2">
                                            <span id="btnText"><i class="fas fa-user-plus mr-1"></i> Register
                                                Participant</span>
                                            <span id="btnSpinner" class="spinner-border spinner-border-sm d-none"
                                                role="status" aria-hidden="true"></span>
                                        </button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- Developer Credit Footer -->
        <div class="text-center mt-3 developer-credit">
            Developed by <strong>Geperson Mamalias</strong>
        </div>

    </div>

    <!-- JS Dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Setup CSRF Token for jQuery AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Pass departments array from Laravel to JavaScript
            const departments = @json($departments);

            // Handle District / Department Dropdown Change
            $('#department_id').on('change', function() {
                const deptId = $(this).val();
                const $officeSelect = $('#office_id');

                $officeSelect.empty().append('<option value="">-- Select School/Office --</option>');

                if (deptId) {
                    const selectedDept = departments.find(d => d.id == deptId);
                    if (selectedDept && selectedDept.offices.length > 0) {
                        $.each(selectedDept.offices, function(index, office) {
                            $officeSelect.append(
                                `<option value="${office.id}">${office.name}</option>`);
                        });
                        $officeSelect.prop('disabled', false);
                    } else {
                        $officeSelect.append('<option value="">No offices found</option>').prop('disabled',
                            true);
                    }
                } else {
                    $officeSelect.prop('disabled', true);
                }
            });

            // Handle Form AJAX Submission
            $('#preRegistrationForm').on('submit', function(e) {
                e.preventDefault();

                // Clear previous validation styling
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                $('#alertContainer').empty();

                // Disable submit button & show loading indicator
                $('#submitBtn').prop('disabled', true);
                $('#btnText').addClass('d-none');
                $('#btnSpinner').removeClass('d-none');

                $.ajax({
                    url: "{{ route('preregistration.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            $('#alertContainer').html(`
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle mr-2"></i>${response.message}
                                </div>
                            `);

                            // Reload the page after 1.2 seconds to refresh session/view
                            setTimeout(function() {
                                location.reload();
                            }, 1200);
                        }
                    },
                    error: function(xhr) {
                        // Reset submit button state
                        $('#submitBtn').prop('disabled', false);
                        $('#btnText').removeClass('d-none');
                        $('#btnSpinner').addClass('d-none');

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            $.each(errors, function(field, messages) {
                                $(`#${field}`).addClass('is-invalid');
                                $(`#err-${field}`).text(messages[0]);
                            });
                        } else {
                            $('#alertContainer').html(`
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>${xhr.responseJSON?.message || 'An unexpected error occurred.'}
                                </div>
                            `);
                        }
                    }
                });
            });
        });
    </script>

</body>

</html>
