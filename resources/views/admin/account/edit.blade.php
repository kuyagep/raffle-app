@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><i class="fas fa-user-cog"></i> Account Settings</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card ">
        <div class="card-body">
            <form action="{{ route('account.update') }}" method="POST">
                @csrf

                <div class="form-group mb-3">
                    <label for="name"><i class="fas fa-user"></i> Full Name</label>
                    <input type="text" name="name" id="name" class="form-control"
                           value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" name="email" id="email" class="form-control"
                           value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="password"><i class="fas fa-lock"></i> New Password (leave blank to keep current)</label>
                    <input type="password" name="password" id="password" class="form-control">
                </div>

                <div class="form-group mb-3">
                    <label for="password_confirmation"><i class="fas fa-lock"></i> Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary fw-bold">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
