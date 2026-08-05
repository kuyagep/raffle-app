@extends('layouts.auth')

@section('title', 'Confirm Password')
@section('page_heading', 'Confirm Password')
@section('page_subheading', 'This is a secure area of the application. Please confirm your password before continuing.')

@section('content')
    <form class="user" method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="form-group mb-3">
            <input type="password" name="password"
                class="form-control form-control-user @error('password') is-invalid @enderror" placeholder="Password"
                autocomplete="current-password" required autofocus>
            @error('password')
                <div class="invalid-feedback text-left pl-2">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-dark-red btn-user btn-block mt-4">
            Confirm Password
        </button>
    </form>

    <hr>

    <div class="text-center">
        @if (Route::has('password.request'))
            <a class="small font-weight-bold text-danger" href="{{ route('password.request') }}">Forgot Your Password?</a>
        @endif
    </div>
@endsection
