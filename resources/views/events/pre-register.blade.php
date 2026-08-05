@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-theme text-white text-center py-4">
                        <h4 class="mb-0 font-weight-bold">Event Pre-Registration</h4>
                        <p class="small mb-0 text-white-50">DepEd Division of Davao del Sur</p>
                    </div>
                    <div class="card-body p-4">

                        <!-- Event Details Card -->
                        <div class="alert alert-light border mb-4 shadow-sm">
                            <h5 class="font-weight-bold text-danger mb-1">{{ $event->title }}</h5>
                            <div class="small text-muted mb-1">
                                <i class="far fa-clock mr-1"></i> {{ $event->formatted_date_range }}
                            </div>
                            <div class="small text-muted">
                                <i class="fas fa-map-marker-alt mr-1"></i> {{ $event->location }}
                            </div>
                        </div>

                        <div id="alertContainer"></div>

                        <!-- Pre-Registration Form -->
                        <form id="preRegistrationForm" action="{{ route('events.pre-register.store', $event->join_code) }}"
                            method="POST">
                            @csrf

                            <div class="form-row">
                                <div class="form-group col-md-6 mb-3">
                                    <label class="small font-weight-bold text-dark">First Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="firstname" id="firstname" class="form-control"
                                        placeholder="First Name" required autofocus>
                                    <div class="invalid-feedback" id="err-firstname"></div>
                                </div>

                                <div class="form-group col-md-6 mb-3">
                                    <label class="small font-weight-bold text-dark">Last Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="lastname" id="lastname" class="form-control"
                                        placeholder="Last Name" required>
                                    <div class="invalid-feedback" id="err-lastname"></div>
                                </div>
                            </div>

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
                                </div>

                                <div class="form-group col-md-6 mb-3">
                                    <label class="small font-weight-bold text-dark">School / Office <span
                                            class="text-danger">*</span></label>
                                    <select name="office_id" id="office_id" class="custom-select" disabled required>
                                        <option value="">-- Select Department First --</option>
                                    </select>
                                    <div class="invalid-feedback" id="err-office_id"></div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6 mb-3">
                                    <label class="small font-weight-bold text-dark">Email Address</label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        placeholder="name@domain.com">
                                    <div class="invalid-feedback" id="err-email"></div>
                                </div>

                                <div class="form-group col-md-6 mb-3">
                                    <label class="small font-weight-bold text-dark">Contact Number</label>
                                    <input type="text" name="contact_number" id="contact_number" class="form-control"
                                        placeholder="09123456789">
                                    <div class="invalid-feedback" id="err-contact_number"></div>
                                </div>
                            </div>

                            <button type="submit" id="submitBtn" class="btn btn-dark-red btn-block mt-4 py-2">
                                <span id="btnText"><i class="fas fa-user-plus mr-1"></i> Register & Join Event</span>
                                <span id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status"
                                    aria-hidden="true"></span>
                            </button>
                        </form>

                        <div class="text-center mt-3">
                            <span class="small text-muted">Already registered as a user? </span>
                            <a href="{{ route('login') }}" class="small font-weight-bold text-danger">Log in here</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const departmentSelect = document.getElementById("department_id");
            const officeSelect = document.getElementById("office_id");
            const preRegForm = document.getElementById("preRegistrationForm");

            // Dynamic Office loading based on Department/District Selection
            if (departmentSelect) {
                departmentSelect.addEventListener("change", function() {
                    const deptId = this.value;
                    officeSelect.innerHTML = '<option value="">-- Loading Offices... --</option>';
                    officeSelect.disabled = true;

                    if (!deptId) {
                        officeSelect.innerHTML = '<option value="">-- Select Department First --</option>';
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

            // AJAX Form Submission
            if (preRegForm) {
                preRegForm.addEventListener("submit", function(e) {
                    e.preventDefault();

                    const submitBtn = document.getElementById("submitBtn");
                    const btnText = document.getElementById("btnText");
                    const btnSpinner = document.getElementById("btnSpinner");

                    // Reset Errors
                    document.querySelectorAll(".form-control").forEach(el => el.classList.remove(
                        "is-invalid"));
                    document.querySelectorAll(".invalid-feedback").forEach(el => el.textContent = "");

                    submitBtn.disabled = true;
                    btnText.classList.add("d-none");
                    btnSpinner.classList.remove("d-none");

                    fetch(preRegForm.action, {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
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
                                // Validation Errors
                                Object.keys(data.errors).forEach(key => {
                                    const input = document.getElementById(key);
                                    const errContainer = document.getElementById(`err-${key}`);
                                    if (input) input.classList.add("is-invalid");
                                    if (errContainer) errContainer.textContent = data.errors[
                                        key][0];
                                });
                            } else if (status === 200 || status === 201) {
                                // Success
                                document.getElementById("alertContainer").innerHTML = `
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle mr-2"></i>${data.message}
                        </div>
                    `;
                                preRegForm.reset();
                                officeSelect.disabled = true;
                                if (data.redirect) {
                                    setTimeout(() => window.location.href = data.redirect, 1500);
                                }
                            } else {
                                document.getElementById("alertContainer").innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle mr-2"></i>${data.message || 'An error occurred.'}
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
