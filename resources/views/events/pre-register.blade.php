@extends('layouts.auth-wide')

@section('title', 'Pre-Registration - ' . $event->title)
@section('page_heading', 'Event Pre-Registration')
@section('page_subheading', 'DepEd Division of Davao del Sur')

@section('content')
    <!-- Event Details Banner Card -->
    <div class="card bg-light border-1  mb-4">
        <div class="card-body p-3">
            <h5 class="font-weight-bold text-dark-red mb-2">{{ $event->title }}</h5>
            <div class="d-flex flex-column flex-sm-row justify-content-between small text-muted">
                <div class="mb-1 mb-sm-0">
                    <i class="far fa-clock text-primary mr-1"></i> {{ $event->formatted_date_range }}
                </div>
                <div>
                    <i class="fas fa-map-marker-alt text-danger mr-1"></i> {{ $event->location }}
                </div>
            </div>
        </div>
    </div>

    <!-- Success / Error Notifications Container (For Dynamic AJAX Alerts) -->
    <div id="alertContainer"></div>

    <!-- Pre-Registration Form -->
    <form id="preRegistrationForm" action="{{ route('events.pre-register.store', $event->join_code) }}" method="POST">
        @csrf

        <!-- First Name & Last Name -->
        <div class="form-row">
            <div class="form-group col-md-6 mb-3">
                <label class="small font-weight-bold text-dark">First Name <span class="text-danger">*</span></label>
                <input type="text" name="firstname" id="firstname" class="form-control" placeholder="First Name" required
                    autofocus>
                <div class="invalid-feedback" id="err-firstname"></div>
            </div>

            <div class="form-group col-md-6 mb-3">
                <label class="small font-weight-bold text-dark">Last Name <span class="text-danger">*</span></label>
                <input type="text" name="lastname" id="lastname" class="form-control" placeholder="Last Name" required>
                <div class="invalid-feedback" id="err-lastname"></div>
            </div>
        </div>

        <!-- Position & Sex -->
        <div class="form-row">
            <div class="form-group col-md-6 mb-3">
                <label class="small font-weight-bold text-dark">Position / Designation</label>
                <input type="text" name="position" id="position" class="form-control"
                    placeholder="e.g. Teacher III / AO II">
                <div class="invalid-feedback" id="err-position"></div>
            </div>

            <div class="form-group col-md-6 mb-3">
                <label class="small font-weight-bold text-dark">Sex</label>
                <select name="sex" id="sex" class="custom-select">
                    <option value="">-- Select Sex --</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
                <div class="invalid-feedback" id="err-sex"></div>
            </div>
        </div>

        <!-- District & Office Dropdowns -->
        <div class="form-row">
            <div class="form-group col-md-6 mb-3">
                <label class="small font-weight-bold text-dark">District / Department <span
                        class="text-danger">*</span></label>
                <select id="department_id" class="custom-select" required>
                    <option value="">-- Select District --</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback" id="err-department_id"></div>
            </div>

            <div class="form-group col-md-6 mb-3">
                <label class="small font-weight-bold text-dark">School / Office <span class="text-danger">*</span></label>
                <select name="office_id" id="office_id" class="custom-select" disabled required>
                    <option value="">-- Select District First --</option>
                </select>
                <div class="invalid-feedback" id="err-office_id"></div>
            </div>
        </div>

        <!-- Email & Contact Number -->
        <div class="form-row">
            <div class="form-group col-md-6 mb-3">
                <label class="small font-weight-bold text-dark">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="name@domain.com">
                <div class="invalid-feedback" id="err-email"></div>
            </div>

            <div class="form-group col-md-6 mb-3">
                <label class="small font-weight-bold text-dark">Contact Number</label>
                <input type="text" name="contact_number" id="contact_number" class="form-control"
                    placeholder="09123456789">
                <div class="invalid-feedback" id="err-contact_number"></div>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" id="submitBtn" class="btn btn-dark-red btn-block mt-4 py-2 font-weight-bold">
            <span id="btnText"><i class="fas fa-user-plus mr-1"></i> Register & Join Event</span>
            <span id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
        </button>
    </form>

    <!-- Footer Links -->
    <div class="text-center mt-4 pt-3 border-top">
        <span class="small text-muted">Already registered as a user? </span>
        <a href="{{ route('login') }}" class="small font-weight-bold text-dark-red">Log in here</a>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const departmentSelect = document.getElementById("department_id");
            const officeSelect = document.getElementById("office_id");
            const preRegForm = document.getElementById("preRegistrationForm");

            // Load offices dynamic choices upon district selection
            if (departmentSelect) {
                departmentSelect.addEventListener("change", function() {
                    const deptId = this.value;
                    officeSelect.innerHTML = '<option value="">-- Loading Offices... --</option>';
                    officeSelect.disabled = true;

                    if (!deptId) {
                        officeSelect.innerHTML = '<option value="">-- Select District First --</option>';
                        return;
                    }

                    fetch(`/api/departments/${deptId}/offices`)
                        .then(res => res.json())
                        .then(data => {
                            officeSelect.innerHTML =
                                '<option value="">-- Select School / Office --</option>';
                            data.forEach(office => {
                                officeSelect.innerHTML +=
                                    `<option value="${office.id}">${office.name}</option>`;
                            });
                            officeSelect.disabled = false;
                        })
                        .catch(() => {
                            officeSelect.innerHTML = '<option value="">Failed to load offices</option>';
                        });
                });
            }

            // AJAX Form Submission Handler
            if (preRegForm) {
                preRegForm.addEventListener("submit", function(e) {
                    e.preventDefault();

                    const submitBtn = document.getElementById("submitBtn");
                    const btnText = document.getElementById("btnText");
                    const btnSpinner = document.getElementById("btnSpinner");

                    // Clear existing inline error states
                    document.querySelectorAll(".form-control, .custom-select").forEach(el => el.classList
                        .remove("is-invalid"));
                    document.querySelectorAll(".invalid-feedback").forEach(el => el.textContent = "");

                    submitBtn.disabled = true;
                    btnText.classList.add("d-none");
                    btnSpinner.classList.remove("d-none");

                    fetch(preRegForm.action, {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                "Accept": "application/json"
                            },
                            body: new FormData(preRegForm)
                        })
                        .then(res => res.json().then(data => ({
                            status: res.status,
                            data
                        })))
                        .then(({
                            status,
                            data
                        }) => {
                            submitBtn.disabled = false;
                            btnText.classList.remove("d-none");
                            btnSpinner.classList.add("d-none");

                            if (status === 422) {
                                // Display backend validation error messages
                                Object.keys(data.errors).forEach(key => {
                                    const input = document.getElementById(key);
                                    const errContainer = document.getElementById(`err-${key}`);
                                    if (input) input.classList.add("is-invalid");
                                    if (errContainer) errContainer.textContent = data.errors[
                                        key][0];
                                });
                            } else if (status === 200 || status === 201) {
                                // Render success notification and reset form
                                document.getElementById("alertContainer").innerHTML = `
                                <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                                    <i class="fas fa-check-circle mr-2"></i>${data.message}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            `;
                                preRegForm.reset();
                                officeSelect.disabled = true;

                                if (data.redirect) {
                                    setTimeout(() => window.location.href = data.redirect, 1500);
                                }
                            } else {
                                // Render generic backend error message
                                document.getElementById("alertContainer").innerHTML = `
                                <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
                                    <i class="fas fa-exclamation-circle mr-2"></i>${data.message || 'An error occurred.'}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            `;
                            }
                        })
                        .catch(() => {
                            submitBtn.disabled = false;
                            btnText.classList.remove("d-none");
                            btnSpinner.classList.add("d-none");
                        });
                });
            }
        });
    </script>
@endpush
