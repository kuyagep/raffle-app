@extends('layouts.apps')

@section('content')
    <div>
        <h4 class="text-center mb-4 fw-bold" style="color: #003399;">Registration Form</h4>

        <form method="POST" action="{{ route('registrations.store') }}">
            @csrf

            <div class="mb-3">
                <label for="full_name" class="form-label fw-bold">Full Name</label>
                <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name') }}"
                    placeholder="Enter your full name">
                @error('full_name')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="employment_type" class="form-label fw-bold">Employment Type</label>
                <select class="form-select" id="employment_type" name="employment_type">
                    <option value="">Select...</option>
                    <option value="Teaching" {{ old('employment_type') == 'Teaching' ? 'selected' : '' }}>
                        Teaching</option>
                    <option value="Non-Teaching" {{ old('employment_type') == 'Non-Teaching' ? 'selected' : '' }}>
                        Non-Teaching
                    </option>
                </select>
                @error('employment_type')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="school_office" class="form-label fw-bold">School / Office</label>
                <input type="text" class="form-control" id="school_office" name="school_office"
                    value="{{ old('school_office') }}" placeholder="Enter your school or office">
                @error('school_office')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="position" class="form-label fw-bold">Position</label>
                <input type="text" class="form-control" id="position" name="position" value="{{ old('position') }}"
                    placeholder="Enter your position (optional)">
                @error('position')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-bold">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
                    placeholder="name@example.com">
                @error('email')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="contact_number" class="form-label fw-bold">Contact Number</label>
                <input type="text" class="form-control" id="contact_number" name="contact_number"
                    value="{{ old('contact_number') }}" placeholder="09XXXXXXXXX">
                @error('contact_number')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Captcha</label>
                <div class="d-flex align-items-center gap-2">
                    <img id="captchaImg" src="{{ captcha_src('flat') }}" alt="captcha" class="rounded shadow-sm">
                    <button type="button" class="btn btn-sm btn-secondary" id="reloadCaptcha">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
                <input type="text" class="form-control mt-2" name="captcha" placeholder="Enter Captcha">
                @error('captcha')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>




            <div class="d-grid">
                <button type="submit" class="btn btn-lg fw-bold btn-deped">Submit Registration</button>

            </div>
        </form>

    </div>
@endsection
@section('script')
    <script>
        document.getElementById('reloadCaptcha').addEventListener('click', function() {
            let captcha = document.getElementById('captchaImg');
            captcha.src = "{{ captcha_src('flat') }}" + "?" + Date.now(); // force refresh with timestamp
        });
    </script>
@endsection
