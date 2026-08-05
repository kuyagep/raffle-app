@extends('layouts.auth')

@section('title', 'Forgot Password')
@section('page_heading', 'Forgot Your Password?')
@section('page_subheading', 'Enter your email address and we will send you a reset link.')

@section('content')
    <form class="user" method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="form-group mb-3">
            <input type="email" name="email" class="form-control form-control-user @error('email') is-invalid @enderror"
                placeholder="Enter Email Address..." value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="invalid-feedback text-left pl-2">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-dark-red btn-user btn-block mt-4">
            Send Password Reset Link
        </button>
    </form>

    <hr>

    <div class="text-center">
        <a class="small font-weight-bold text-danger" href="{{ route('login') }}">Back to Login</a>
    </div>
@endsection
