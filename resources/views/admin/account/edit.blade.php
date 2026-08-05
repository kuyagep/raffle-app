@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800 fw-bold">
                Account Settings
            </h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-transparent py-3 border-bottom-0">
                <h5 class="card-title m-0 font-weight-bold text-primary">Profile Details</h5>
                <p class="text-muted small mb-0">Update your account information and password.</p>
            </div>
            <div class="card-body p-4 pt-2">
                <form action="{{ route('account.update') }}" method="POST">
                    @csrf

                    <!-- Full Name -->
                    <div class="row mb-3 align-items-center">
                        <label for="name" class="col-md-3 col-form-label fw-semibold text-secondary">
                            <i class="fas fa-user me-2"></i> Full Name
                        </label>
                        <div class="col-md-9">
                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="row mb-3 align-items-center">
                        <label for="email" class="col-md-3 col-form-label fw-semibold text-secondary">
                            <i class="fas fa-envelope me-2"></i> Email Address
                        </label>
                        <div class="col-md-9">
                            <input type="email" name="email" id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4 text-muted opacity-25">

                    <!-- New Password -->
                    <div class="row mb-3 align-items-center">
                        <label for="password" class="col-md-3 col-form-label fw-semibold text-secondary">
                            <i class="fas fa-lock me-2"></i> New Password
                        </label>
                        <div class="col-md-9">
                            <input type="password" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Leave blank to keep current password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="row mb-4 align-items-center">
                        <label for="password_confirmation" class="col-md-3 col-form-label fw-semibold text-secondary">
                            <i class="fas fa-shield-alt me-2"></i> Confirm Password
                        </label>
                        <div class="col-md-9">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control" placeholder="Re-enter new password">
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="row">
                        <div class="col-md-9 offset-md-3">
                            <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold shadow-sm">
                                <i class="fas fa-save me-1"></i> Save Changes
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
