@extends('layouts.auth')

@section('title', 'Register')
@section('page_heading', 'Create an Account')
@section('page_subheading', 'Complete the steps below to register')

@push('styles')
    <style>
        /* Expand container column width for multi-step form */
        @media (min-width: 992px) {
            .col-xl-5.col-lg-6 {
                flex: 0 0 60% !important;
                max-width: 60% !important;
            }
        }

        /* Wizard Steps Bar */
        .wizard-steps {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-bottom: 2rem;
        }

        .wizard-steps::before {
            content: '';
            position: absolute;
            top: 18px;
            left: 10%;
            right: 10%;
            height: 2px;
            background-color: #e3e6f0;
            z-index: 1;
        }

        .wizard-step-item {
            position: relative;
            z-index: 2;
            text-align: center;
            background: #fff;
            padding: 0 10px;
        }

        .wizard-circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background-color: #e3e6f0;
            color: #858796;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin: 0 auto 6px;
            transition: all 0.3s ease;
        }

        .wizard-step-item.active .wizard-circle {
            background-color: #800000;
            /* Dark Red */
            color: #fff;
            box-shadow: 0 0 0 4px rgba(128, 0, 0, 0.2);
        }

        .wizard-step-item.completed .wizard-circle {
            background-color: #1cc88a;
            /* Success Green */
            color: #fff;
        }

        .wizard-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #858796;
            text-transform: uppercase;
        }

        .wizard-step-item.active .wizard-label {
            color: #800000;
        }

        /* Step content transitions */
        .wizard-step-content {
            display: none;
        }

        .wizard-step-content.active {
            display: block;
            animation: fadeIn 0.4s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush

@section('content')
    <!-- Wizard Steps Indicator -->
    <div class="wizard-steps">
        <div class="wizard-step-item active" id="indicator-step-1">
            <div class="wizard-circle">1</div>
            <div class="wizard-label">Office</div>
        </div>
        <div class="wizard-step-item" id="indicator-step-2">
            <div class="wizard-circle">2</div>
            <div class="wizard-label">Personal Info</div>
        </div>
        <div class="wizard-step-item" id="indicator-step-3">
            <div class="wizard-circle">3</div>
            <div class="wizard-label">Account</div>
        </div>
    </div>

    <form class="user" id="registerWizardForm" method="POST" action="{{ route('register') }}">
        @csrf

        <!-- STEP 1: Department & Office -->
        <div class="wizard-step-content active" id="step-1">
            <div class="form-group mb-3">
                <label class="small font-weight-bold text-dark">District / Department <span
                        class="text-danger">*</span></label>
                <select id="department_id" class="form-control" required>
                    <option value="">-- Select District / Department --</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label class="small font-weight-bold text-dark">School / Office <span class="text-danger">*</span></label>
                <select name="office_id" id="office_id" class="form-control @error('office_id') is-invalid @enderror"
                    disabled required>
                    <option value="">-- Select Department First --</option>
                </select>
                @error('office_id')
                    <div class="invalid-feedback text-left pl-2" id="err-office_id">{{ $message }}</div>
                @else
                    <div class="invalid-feedback text-left pl-2" id="err-office_id"></div>
                @enderror
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="button" class="btn btn-dark-red btn-user px-4 btn-next">Next Step <i
                        class="fas fa-arrow-right ml-1"></i></button>
            </div>
        </div>

        <!-- STEP 2: First Name, Last Name, Position, Sex, Contact Number -->
        <div class="wizard-step-content" id="step-2">
            <div class="form-row">
                <div class="form-group col-md-6 mb-3">
                    <label class="small font-weight-bold text-dark">First Name <span class="text-danger">*</span></label>
                    <input type="text" name="firstname" id="firstname"
                        class="form-control @error('firstname') is-invalid @enderror" placeholder="First Name"
                        value="{{ old('firstname') }}" required>
                    @error('firstname')
                        <div class="invalid-feedback text-left pl-2" id="err-firstname">{{ $message }}</div>
                    @else
                        <div class="invalid-feedback text-left pl-2" id="err-firstname"></div>
                    @enderror
                </div>

                <div class="form-group col-md-6 mb-3">
                    <label class="small font-weight-bold text-dark">Last Name <span class="text-danger">*</span></label>
                    <input type="text" name="lastname" id="lastname"
                        class="form-control @error('lastname') is-invalid @enderror" placeholder="Last Name"
                        value="{{ old('lastname') }}" required>
                    @error('lastname')
                        <div class="invalid-feedback text-left pl-2" id="err-lastname">{{ $message }}</div>
                    @else
                        <div class="invalid-feedback text-left pl-2" id="err-lastname"></div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6 mb-3">
                    <label class="small font-weight-bold text-dark">Position / Designation</label>
                    <input type="text" name="position" id="position"
                        class="form-control @error('position') is-invalid @enderror" placeholder="e.g. Teacher III / AO II"
                        value="{{ old('position') }}">
                    @error('position')
                        <div class="invalid-feedback text-left pl-2" id="err-position">{{ $message }}</div>
                    @else
                        <div class="invalid-feedback text-left pl-2" id="err-position"></div>
                    @enderror
                </div>

                <div class="form-group col-md-6 mb-3">
                    <label class="small font-weight-bold text-dark">Sex</label>
                    <select name="sex" id="sex" class="form-control @error('sex') is-invalid @enderror">
                        <option value="">-- Select Sex --</option>
                        <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                    @error('sex')
                        <div class="invalid-feedback text-left pl-2" id="err-sex">{{ $message }}</div>
                    @else
                        <div class="invalid-feedback text-left pl-2" id="err-sex"></div>
                    @enderror
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="small font-weight-bold text-dark">Contact Number</label>
                <input type="text" name="contact_number" id="contact_number"
                    class="form-control @error('contact_number') is-invalid @enderror" placeholder="09123456789"
                    value="{{ old('contact_number') }}">
                @error('contact_number')
                    <div class="invalid-feedback text-left pl-2" id="err-contact_number">{{ $message }}</div>
                @else
                    <div class="invalid-feedback text-left pl-2" id="err-contact_number"></div>
                @enderror
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-secondary btn-user px-4 btn-prev"><i
                        class="fas fa-arrow-left mr-1"></i> Previous</button>
                <button type="button" class="btn btn-dark-red btn-user px-4 btn-next">Next Step <i
                        class="fas fa-arrow-right ml-1"></i></button>
            </div>
        </div>

        <!-- STEP 3: Email, Password, Confirm Password, Terms & Privacy -->
        <div class="wizard-step-content" id="step-3">
            <div class="form-group mb-3">
                <label class="small font-weight-bold text-dark">Email Address <span class="text-danger">*</span></label>
                <input type="email" name="email" id="email"
                    class="form-control @error('email') is-invalid @enderror" placeholder="name@domain.com"
                    value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback text-left pl-2" id="err-email">{{ $message }}</div>
                @else
                    <div class="invalid-feedback text-left pl-2" id="err-email"></div>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group col-md-6 mb-3">
                    <label class="small font-weight-bold text-dark">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" id="password"
                        class="form-control @error('password') is-invalid @enderror" placeholder="Password" required>
                    @error('password')
                        <div class="invalid-feedback text-left pl-2" id="err-password">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group col-md-6 mb-3">
                    <label class="small font-weight-bold text-dark">Confirm Password <span
                            class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                        placeholder="Repeat Password" required>
                </div>
            </div>

            <!-- Terms of Service & Privacy Policy Checkbox -->
            <div class="form-group mb-3 pl-1">
                <div class="custom-control custom-checkbox small">
                    <input type="checkbox" class="custom-control-input" id="terms" name="terms" required>
                    <label class="custom-control-label text-dark" for="terms">
                        I agree to the <a href="#" class="text-danger font-weight-bold" data-toggle="modal"
                            data-target="#termsModal">Terms of Service</a> and <a href="#"
                            class="text-danger font-weight-bold" data-toggle="modal" data-target="#privacyModal">Privacy
                            Policy</a> <span class="text-danger">*</span>
                    </label>
                </div>
                @error('terms')
                    <div class="text-danger small pl-1 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-secondary btn-user px-4 btn-prev"><i
                        class="fas fa-arrow-left mr-1"></i> Previous</button>
                <button type="submit" class="btn btn-dark-red btn-user px-4">Register Account</button>
            </div>
        </div>
    </form>

    <hr>

    <div class="text-center">
        <span class="small text-muted">Already have an account? </span>
        <a class="small font-weight-bold text-danger" href="{{ route('login') }}">Sign In!</a>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let currentStep = 1;
            const totalSteps = 3;

            const nextBtns = document.querySelectorAll(".btn-next");
            const prevBtns = document.querySelectorAll(".btn-prev");

            // Dynamic office selection logic
            const departmentSelect = document.getElementById("department_id");
            const officeSelect = document.getElementById("office_id");
            const oldOfficeId = "{{ old('office_id') }}";

            function loadOffices(deptId, selectedOfficeId = null) {
                officeSelect.innerHTML = '<option value="">-- Loading Offices... --</option>';
                officeSelect.disabled = true;

                if (!deptId) {
                    officeSelect.innerHTML = '<option value="">-- Select Department First --</option>';
                    return;
                }

                fetch(`/api/departments/${deptId}/offices`)
                    .then(res => res.json())
                    .then(data => {
                        officeSelect.innerHTML = '<option value="">-- Select School / Office --</option>';
                        data.forEach(office => {
                            const selected = (selectedOfficeId && selectedOfficeId == office.id) ?
                                'selected' : '';
                            officeSelect.innerHTML +=
                                `<option value="${office.id}" ${selected}>${office.name}</option>`;
                        });
                        officeSelect.disabled = false;
                    })
                    .catch(() => {
                        officeSelect.innerHTML = '<option value="">Failed to load offices</option>';
                    });
            }

            if (departmentSelect) {
                if (departmentSelect.value) {
                    loadOffices(departmentSelect.value, oldOfficeId);
                }
                departmentSelect.addEventListener("change", function() {
                    loadOffices(this.value);
                });
            }

            // Step Validation Function
            function validateStep(step) {
                const stepContainer = document.getElementById(`step-${step}`);
                const inputs = stepContainer.querySelectorAll("input[required], select[required]");
                let isValid = true;

                inputs.forEach(input => {
                    if (!input.checkValidity()) {
                        input.classList.add("is-invalid");
                        isValid = false;
                    } else {
                        input.classList.remove("is-invalid");
                    }
                });

                // Special password match check on step 3
                if (step === 3) {
                    const pass = document.getElementById("password").value;
                    const confirmPass = document.getElementById("password_confirmation").value;
                    if (pass !== confirmPass) {
                        document.getElementById("password_confirmation").classList.add("is-invalid");
                        isValid = false;
                    }
                }

                return isValid;
            }

            // Update Step UI
            function showStep(step) {
                document.querySelectorAll(".wizard-step-content").forEach(el => el.classList.remove("active"));
                document.getElementById(`step-${step}`).classList.add("active");

                for (let i = 1; i <= totalSteps; i++) {
                    const indicator = document.getElementById(`indicator-step-${i}`);
                    if (i < step) {
                        indicator.classList.add("completed");
                        indicator.classList.remove("active");
                        indicator.querySelector(".wizard-circle").innerHTML = '<i class="fas fa-check"></i>';
                    } else if (i === step) {
                        indicator.classList.add("active");
                        indicator.classList.remove("completed");
                        indicator.querySelector(".wizard-circle").innerText = i;
                    } else {
                        indicator.classList.remove("active", "completed");
                        indicator.querySelector(".wizard-circle").innerText = i;
                    }
                }
            }

            // Next Button Handler
            nextBtns.forEach(btn => {
                btn.addEventListener("click", function() {
                    if (validateStep(currentStep)) {
                        if (currentStep < totalSteps) {
                            currentStep++;
                            showStep(currentStep);
                        }
                    }
                });
            });

            // Previous Button Handler
            prevBtns.forEach(btn => {
                btn.addEventListener("click", function() {
                    if (currentStep > 1) {
                        currentStep--;
                        showStep(currentStep);
                    }
                });
            });
        });
    </script>
@endpush
