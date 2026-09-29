@extends('layouts.auth')

@section('title', 'Login')
@section('page_heading', 'Sign In')
@section('page_subheading', 'Please enter your account details')

@section('content')
    <form class="user" method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group mb-3">
            <input type="email" name="email" class="form-control form-control-user @error('email') is-invalid @enderror"
                placeholder="Enter Email Address..." value="{{ old('email') }}" autocomplete="username" required autofocus>
            @error('email')
                <div class="invalid-feedback text-left pl-2">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <input type="password" name="password" id="password"
                class="form-control form-control-user @error('password') is-invalid @enderror" placeholder="Password"
                autocomplete="current-password" value="{{ old('email') }}" required>
            @error('password')
                <div class="invalid-feedback text-left pl-2">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Show Password Checkbox -->
        <div class="d-flex justify-content-between align-items-center mb-3 pl-1 pr-1">
            <div class="custom-control custom-checkbox small">
                <input type="checkbox" class="custom-control-input" id="showPassword">
                <label class="custom-control-label text-muted" for="showPassword" style="cursor: pointer;">Show
                    Password</label>
            </div>
        </div>

        <button type="submit" class="btn btn-dark-red btn-user btn-block mt-4">
            Login
        </button>
    </form>

    <hr>

    <div class="text-center">
        <span class="small text-muted">{{ config('app.version') }}</span>
    </div>
    {{-- <div class="text-center">
        <span class="small text-muted">Don't have an account? </span>
        <a class="small font-weight-bold text-danger" href="{{ route('register') }}">Create an Account!</a>
    </div> --}}
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const showPasswordCheckbox = document.getElementById('showPassword');

            if (showPasswordCheckbox && passwordInput) {
                showPasswordCheckbox.addEventListener('change', function() {
                    passwordInput.type = this.checked ? 'text' : 'password';
                });
            }
        });
    </script>
@endpush
