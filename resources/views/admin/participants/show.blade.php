@extends('layouts.app')
@section('title', 'Show Details')
@section('content')
    <div class="container-fluid">
        <!-- Page Title -->
        <h1 class="h3 mb-4 text-gray-800"> Participant Details
        </h1>

        <!-- Participant Card -->
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body">
                <div class="row g-4 align-items-center">

                    <!-- Participant Info -->
                    <div class="col-md-8">
                        <h4 class="fw-bold text-primary mb-3">Information
                        </h4>

                        <dl class="row mb-0">
                            <dt class="col-sm-4">Full Name:</dt>
                            <dd class="col-sm-8">{{ $participant->full_name }}</dd>

                            <dt class="col-sm-4">District/Division:</dt>
                            <dd class="col-sm-8">{{ $participant->district_division }}</dd>

                            <dt class="col-sm-4">School / Office:</dt>
                            <dd class="col-sm-8">{{ $participant->school_office }}</dd>

                            <dt class="col-sm-4">Position:</dt>
                            <dd class="col-sm-8">{{ $participant->designation ?? '-' }}</dd>

                            <dt class="col-sm-4">Email:</dt>
                            <dd class="col-sm-8">{{ $participant->email ?? '-' }}</dd>

                            <dt class="col-sm-4">Contact Number:</dt>
                            <dd class="col-sm-8">{{ $participant->contact_number ?? '-' }}</dd>

                            <dt class="col-sm-4">Registered At:</dt>
                            <dd class="col-sm-8">{{ $participant->created_at->format('M d, Y h:i A') }}</dd>
                        </dl>
                    </div>

                    <!-- QR Code -->
                    <div class="col-md-4 text-center">
                        <h6 class="fw-bold text-muted mb-3">QR Code</h6>
                        <div class="p-3 border-2 rounded d-inline-block bg-white shadow-sm">
                            {!! QrCode::size(160)->generate($participant->qr_code) !!}
                        </div>
                        <p class="small text-muted mt-2">{{ $participant->qr_code }}</p>
                        <p class="small text-muted">Scan this QR code for attendance</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-4">
            <a href="{{ route('admin.participants.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>
@endsection
