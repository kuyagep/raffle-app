@extends('layouts.auth')

@section('title', 'Reset Password')
@section('page_heading', 'Reset Password')
@section('page_subheading', 'Create a new password for your account')

@section('content')
    <form class="user" method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="form-group mb-3">
            <input type="email" name="email" class="form-control form-control-user @error('email') is-invalid @enderror"
                placeholder="Email Address" value="{{ old('email', $request->email) }}" required autofocus readonly>
            @error('email')
                <div class="invalid-feedback text-left pl-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <input type="password" name="password"
                class="form-control form-control-user @error('password') is-invalid @enderror" placeholder="New Password"
                required>
            @error('password')
                <div class="invalid-feedback text-left pl-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <input type="password" name="password_confirmation" class="form-control form-control-user"
                placeholder="Confirm New Password" required>
        </div>

        <button type="submit" class="btn btn-dark-red btn-user btn-block mt-4">
            Reset Password
        </button>
    </form>
@endsection
