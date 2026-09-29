@extends('layouts.public')

@section('content')
    <div class="min-vh-100 d-flex flex-column justify-content-between py-3">

        <div>
            <!-- Header Section -->
            <div class="text-center mb-3">
                <h1 class="h4 mb-1">
                    <i class="fas fa-trophy text-warning me-1"></i>
                    <a href="{{ route('raffle.draw') }}" class="text-primary text-decoration-none fw-bold">
                        Raffle Winners
                    </a>
                </h1>
                <p class="text-muted small mb-0">2026 World Teachers' Day Grand Raffle Draw</p>
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
                                        <h4 class="fw-bold text-dark mb-1  text-truncate text-uppercase"
                                            title="{{ $winner->participant->full_name }}">
                                            <b>{{ $winner->participant->full_name }}</b>
                                        </h4>

                                        <!-- District / Division -->
                                        <h6 class="text-truncate mb-1 text-dark">
                                            <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                            {{ $winner->participant->district_division ?? 'N/A' }}
                                        </h6>
                                    </div>

                                    <!-- Prize Badge -->
                                    <div class="pt-1 border-top mt-1">
                                        <span class="badge bg-warning text-dark text-truncate w-100"
                                            style="font-size: 0.7rem;" title="{{ $winner->prize->name ?? 'Prize' }}">
                                            <i class="fas fa-gift mr-1"></i>{{ $winner->prize->name ?? 'Prize' }}
                                        </span>
                                    </div>
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
@endsection
