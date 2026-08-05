@extends('layouts.auth')

@section('title', 'Verify Email')
@section('page_heading', 'Verify Your Email')
@section('page_subheading', 'Thanks for signing up!')

@section('content')
    <div class="text-center text-muted small mb-4">
        Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you
        didn't receive the email, we will gladly send you another.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success alert-dismissible fade show small" role="alert">
            <i class="fas fa-check-circle mr-1"></i>
            A new verification link has been sent to the email address you provided during registration.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Resend Verification Email Form -->
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-dark-red btn-user btn-block mb-3">
            Resend Verification Email
        </button>
    </form>

    <hr>

    <!-- Logout Form -->
    <div class="text-center">
        <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-link text-danger font-weight-bold p-0 border-0 align-baseline small">
                Log Out
            </button>
        </form>
    </div>
@endsection
