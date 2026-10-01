@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow border-0">
                    <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold"><i class="fas fa-edit me-1"></i> Edit School</h6>
                        <a href="{{ route('admin.schools.index') }}" class="btn btn-sm btn-light">Back</a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.schools.update', $school->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group mb-3">
                                <label class="font-weight-bold" for="school_name">School Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="school_name" id="school_name"
                                    class="form-control @error('school_name') is-invalid @enderror"
                                    value="{{ old('school_name', $school->school_name) }}" required>
                                @error('school_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label class="font-weight-bold" for="district_name">District Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="district_name" id="district_name"
                                        class="form-control @error('district_name') is-invalid @enderror"
                                        value="{{ old('district_name', $school->district_name) }}" required>
                                    @error('district_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label class="font-weight-bold" for="municipality">Municipality <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="municipality" id="municipality"
                                        class="form-control @error('municipality') is-invalid @enderror"
                                        value="{{ old('municipality', $school->municipality) }}" required>
                                    @error('municipality')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label class="font-weight-bold" for="designation">Designation</label>
                                    <input type="text" name="designation" id="designation"
                                        class="form-control @error('designation') is-invalid @enderror"
                                        value="{{ old('designation', $school->designation) }}">
                                    @error('designation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label class="font-weight-bold" for="school_office">School Office</label>
                                    <input type="text" name="school_office" id="school_office"
                                        class="form-control @error('school_office') is-invalid @enderror"
                                        value="{{ old('school_office', $school->school_office) }}">
                                    @error('school_office')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 form-group mb-3">
                                    <label class="font-weight-bold" for="sex">Sex</label>
                                    <select name="sex" id="sex"
                                        class="form-control @error('sex') is-invalid @enderror">
                                        <option value="">-- Select --</option>
                                        <option value="Male" {{ old('sex', $school->sex) == 'Male' ? 'selected' : '' }}>
                                            Male</option>
                                        <option value="Female"
                                            {{ old('sex', $school->sex) == 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                    @error('sex')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 form-group mb-3">
                                    <label class="font-weight-bold" for="email">Email</label>
                                    <input type="email" name="email" id="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $school->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 form-group mb-3">
                                    <label class="font-weight-bold" for="contact_number">Contact Number</label>
                                    <input type="text" name="contact_number" id="contact_number"
                                        class="form-control @error('contact_number') is-invalid @enderror"
                                        value="{{ old('contact_number', $school->contact_number) }}">
                                    @error('contact_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="text-right mt-3">
                                <button type="submit" class="btn btn-warning px-4"><i class="fas fa-save me-1"></i> Update
                                    School</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
