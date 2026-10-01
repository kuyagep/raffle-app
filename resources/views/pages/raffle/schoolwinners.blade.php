@extends('layouts.public')

@section('content')
    <div class="min-vh-100 d-flex flex-column justify-content-between py-3">

        <div>
            <!-- Header Section -->
            <div class="text-center mb-3">
                <h1 class="h4 mb-1">
                    <i class="fas fa-trophy text-warning me-1"></i>
                    <a href="{{ route('raffle.draw') }}" class="text-white text-decoration-none fw-bold">
                        Raffle Winners
                    </a>
                </h1>
                {{-- <p class="text-white small mb-0">Grand Raffle Draw</p> --}}
            </div>

            <!-- Prize Filter Bar -->
            <div class="row justify-content-center mb-3">
                <div class="col-12 col-sm-10 col-md-8 col-lg-6">
                    <form method="GET" action="{{ url()->current() }}" id="filterForm">
                        <div class="input-group input-group-sm">
                            <label class="input-group-text bg-primary text-white " for="prize_id">
                                <i class="fas fa-filter me-1 text-sm"></i>
                            </label>
                            <select name="prize_id" id="prize_id" class="form-control border-secondary"
                                onchange="document.getElementById('filterForm').submit();">
                                <option value="">-- All Prizes --</option>
                                @foreach ($allPrizes as $prize)
                                    <option value="{{ $prize->id }}"
                                        {{ request('prize_id') == $prize->id ? 'selected' : '' }}>
                                        {{ $prize->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if (request('prize_id'))
                                <a href="{{ url()->current() }}" class="btn btn-outline-secondary btn-sm "
                                    title="Clear Filter">
                                    <i class="fas fa-times "></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Winners Compact Cards Grid -->
            @if ($winners->count() > 0)
                <div class="row g-2 row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-2">
                    @foreach ($winners as $winner)
                        <div class="col mb-2">
                            <div class="card h-100 border-1 border-top border-3 ">
                                <div class="card-body p-2 text-center d-flex flex-column justify-content-between">
                                    <div>
                                        <!-- Winner Icon -->
                                        {{-- <div class="mb-1">
                                            <div class="bg-light text-primary rounded-circle d-inline-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px;">
                                                <i class="fas fa-user small"></i>
                                            </div>
                                        </div> --}}

                                        <!-- Full Name -->
                                        <h2 class="fw-bold text-dark mb-1   text-uppercase">
                                            <b>{{ $winner->school->school_name }}</b>
                                        </h2>

                                        <!-- District / Division -->
                                        <h4 class="text-truncate mb-1 text-dark">

                                            Municipality of {{ $winner->school->municipality ?? 'N/A' }}
                                        </h4>
                                    </div>

                                    <!-- Prize Badge -->
                                    <h6 class="pt-1 border-top mt-1">
                                        <span class="badge badge-success bg-success text-white" style="font-size: 12pt;">
                                            <i class="fas fa-gift mr-1"></i>{{ $winner->prize->name ?? 'Prize' }}
                                        </span>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="card shadow-sm border-0 my-4">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-award fa-2x text-muted mb-2"></i>
                        <h6 class="text-secondary mb-1">No winners found</h6>
                        <p class="text-muted small mb-0">Try selecting a different prize filter or draw a winner first.</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Pagination Links pinned towards bottom -->
        @if ($winners->count() > 0)
            <div class="d-flex justify-content-center mt-3">
                {{ $winners->links() }}
            </div>
        @endif

    </div>

    <!-- Floating Gifts, Cash & Raffle Bottom Animation -->
    <div class="floating-bg-container">
        <i class="fas fa-gift floating-icon icon-gift"></i>
        <i class="fas fa-money-bill-wave floating-icon icon-cash"></i>
        <i class="fas fa-ticket-alt floating-icon icon-ticket"></i>
        <i class="fas fa-coins floating-icon icon-cash"></i>
        <i class="fas fa-trophy floating-icon icon-trophy"></i>
        <i class="fas fa-gift floating-icon icon-gift"></i>
        <i class="fas fa-money-check-alt floating-icon icon-cash"></i>
        <i class="fas fa-ticket-alt floating-icon icon-ticket"></i>
        <i class="fas fa-gift floating-icon icon-gift"></i>
        <i class="fas fa-coins floating-icon icon-cash"></i>
    </div>
@endsection
@push('styles')
    <style>
        /* Container for bottom floating icons */
        .floating-bg-container {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 120px;
            overflow: hidden;
            pointer-events: none;
            z-index: 10;
        }

        /* Floating Icon Styles */
        .floating-icon {
            position: absolute;
            bottom: -40px;
            font-size: 1.8rem;
            opacity: 0.6;
            animation: floatUp 6s linear infinite;
        }

        /* Color Themes */
        .icon-gift {
            color: #ff4757;
        }

        .icon-cash {
            color: #2ed573;
        }

        .icon-ticket {
            color: #ffa502;
        }

        .icon-trophy {
            color: #eccc68;
        }

        /* Keyframe for upward floating animation */
        @keyframes floatUp {
            0% {
                transform: translateY(0) rotate(0deg) scale(0.8);
                opacity: 0;
            }

            20% {
                opacity: 0.7;
            }

            80% {
                opacity: 0.7;
            }

            100% {
                transform: translateY(-130px) rotate(360deg) scale(1.2);
                opacity: 0;
            }
        }

        /* Staggered positions & delays for continuous natural movement */
        .floating-icon:nth-child(1) {
            left: 5%;
            animation-delay: 0s;
            animation-duration: 5.5s;
        }

        .floating-icon:nth-child(2) {
            left: 15%;
            animation-delay: 1.2s;
            animation-duration: 6.5s;
        }

        .floating-icon:nth-child(3) {
            left: 25%;
            animation-delay: 2.5s;
            animation-duration: 5.0s;
        }

        .floating-icon:nth-child(4) {
            left: 35%;
            animation-delay: 0.8s;
            animation-duration: 7.0s;
        }

        .floating-icon:nth-child(5) {
            left: 45%;
            animation-delay: 3.1s;
            animation-duration: 5.8s;
        }

        .floating-icon:nth-child(6) {
            left: 55%;
            animation-delay: 1.8s;
            animation-duration: 6.2s;
        }

        .floating-icon:nth-child(7) {
            left: 65%;
            animation-delay: 2.2s;
            animation-duration: 5.2s;
        }

        .floating-icon:nth-child(8) {
            left: 75%;
            animation-delay: 0.4s;
            animation-duration: 6.8s;
        }

        .floating-icon:nth-child(9) {
            left: 85%;
            animation-delay: 3.5s;
            animation-duration: 5.4s;
        }

        .floating-icon:nth-child(10) {
            left: 95%;
            animation-delay: 1.5s;
            animation-duration: 6.0s;
        }
    </style>
@endpush
