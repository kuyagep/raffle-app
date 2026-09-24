@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid px-3 px-lg-4 py-4">

        <!-- Page Header -->
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-2">
            <div>
                <h1 class="h3 mb-1 text-gray-800 fw-bold">Raffle Analytics Dashboard</h1>
                <p class="text-muted small mb-0">Overview of participant demographics, prize inventory, and raffle draw
                    progress.</p>
            </div>
            <div>
                <button class="btn btn-sm btn-outline-primary shadow-sm me-1" onclick="window.location.reload()">
                    <i class="fas fa-sync-alt fa-sm text-primary me-1"></i> Refresh Data
                </button>
            </div>
        </div>

        <!-- SECTION 1: Raffle & Winner Overview -->
        <div class="mb-4">
            <h6 class="text-uppercase text-xs font-weight-bold text-muted mb-3 tracking-wide">
                <i class="fas fa-trophy me-1 text-primary"></i> Raffle & Winner Metrics
            </h6>
            <div class="row g-3">
                <!-- Total Registrations -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 border-start border-4 border-primary shadow-sm h-100 py-1">
                        <div class="card-body">
                            <div class="row align-items-center g-0">
                                <div class="col">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Registrations
                                    </div>
                                    <div class="h4 mb-0 font-weight-bold text-gray-800">
                                        {{ number_format($totalRegistrations ?? 0) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="icon-circle bg-primary-subtle text-primary p-3 rounded-circle">
                                        <i class="fas fa-users fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Winners -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 border-start border-4 border-success shadow-sm h-100 py-1">
                        <div class="card-body">
                            <div class="row align-items-center g-0">
                                <div class="col">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Total Winners
                                    </div>
                                    <div class="h4 mb-0 font-weight-bold text-gray-800">
                                        {{ number_format($totalWinners ?? 0) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="icon-circle bg-success-subtle text-success p-3 rounded-circle">
                                        <i class="fas fa-award fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Prizes -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 border-start border-4 border-warning shadow-sm h-100 py-1">
                        <div class="card-body">
                            <div class="row align-items-center g-0">
                                <div class="col">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Total Prizes Items
                                    </div>
                                    <div class="h4 mb-0 font-weight-bold text-gray-800">
                                        {{ number_format($totalPrizesCount ?? 0) }}
                                        <small class="text-muted fs-6 fw-normal">({{ $totalPrizesTypes ?? 0 }}
                                            types)</small>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="icon-circle bg-warning-subtle text-warning p-3 rounded-circle">
                                        <i class="fas fa-gift fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Remaining Prizes -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 border-start border-4 border-danger shadow-sm h-100 py-1">
                        <div class="card-body">
                            <div class="row align-items-center g-0">
                                <div class="col">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        Prizes Remaining
                                    </div>
                                    <div class="h4 mb-0 font-weight-bold text-gray-800">
                                        {{ number_format($remainingPrizes ?? 0) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="icon-circle bg-danger-subtle text-danger p-3 rounded-circle">
                                        <i class="fas fa-box-open fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 2: Demographics & Geographic Distribution -->
        <div class="mb-4">
            <h6 class="text-uppercase text-xs font-weight-bold text-muted mb-3 tracking-wide">
                <i class="fas fa-map-marked-alt me-1 text-info"></i> Participant Demographics & Distribution
            </h6>
            <div class="row g-3">
                <!-- Male Participants -->
                <div class="col-12 col-sm-6 col-md-4 col-xl-2">
                    <div class="card border-0 shadow-sm h-100 py-1 bg-white">
                        <div class="card-body text-center p-3">
                            <div class="text-info mb-2">
                                <i class="fas fa-mars fa-2x"></i>
                            </div>
                            <div class="text-xs font-weight-bold text-uppercase text-muted">Male</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 mt-1">{{ number_format($maleCount ?? 0) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Female Participants -->
                <div class="col-12 col-sm-6 col-md-4 col-xl-2">
                    <div class="card border-0 shadow-sm h-100 py-1 bg-white">
                        <div class="card-body text-center p-3">
                            <div class="text-danger mb-2">
                                <i class="fas fa-venus fa-2x"></i>
                            </div>
                            <div class="text-xs font-weight-bold text-uppercase text-muted">Female</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 mt-1">{{ number_format($femaleCount ?? 0) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Districts / Divisions -->
                <div class="col-12 col-sm-6 col-md-4 col-xl-2">
                    <div class="card border-0 shadow-sm h-100 py-1 bg-white">
                        <div class="card-body text-center p-3">
                            <div class="text-primary mb-2">
                                <i class="fas fa-map-signs fa-2x"></i>
                            </div>
                            <div class="text-xs font-weight-bold text-uppercase text-muted">Districts</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 mt-1">
                                {{ number_format($districtCount ?? 0) }}</div>
                        </div>
                    </div>
                </div>

                <!-- Municipalities -->
                <div class="col-12 col-sm-6 col-md-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100 py-1 bg-white">
                        <div class="card-body text-center p-3">
                            <div class="text-secondary mb-2">
                                <i class="fas fa-city fa-2x"></i>
                            </div>
                            <div class="text-xs font-weight-bold text-uppercase text-muted">Municipalities</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 mt-1">
                                {{ number_format($municipalityCount ?? 0) }}</div>
                        </div>
                    </div>
                </div>

                <!-- Schools / Offices -->
                <div class="col-12 col-sm-6 col-md-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100 py-1 bg-white">
                        <div class="card-body text-center p-3">
                            <div class="text-dark mb-2">
                                <i class="fas fa-school fa-2x"></i>
                            </div>
                            <div class="text-xs font-weight-bold text-uppercase text-muted">Schools / Offices</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 mt-1">{{ number_format($schoolCount ?? 0) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 3: Visual Analytics Charts -->
        <div class="row g-3 g-md-4">
            <!-- Gender Breakdown Chart -->
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div
                        class="card-header bg-white py-3 border-bottom-0 d-flex align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-gray-800">
                            <i class="fas fa-chart-pie me-1 text-primary"></i> Gender Breakdown
                        </h6>
                    </div>
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <div class="chart-container position-relative w-100" style="min-height: 250px; max-height: 280px;">
                            <canvas id="genderChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prize Claims / Winner Progress Chart -->
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div
                        class="card-header bg-white py-3 border-bottom-0 d-flex align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-gray-800">
                            <i class="fas fa-chart-donut me-1 text-success"></i> Raffle Progress
                        </h6>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div class="chart-container position-relative w-100"
                            style="min-height: 210px; max-height: 230px;">
                            <canvas id="raffleProgressChart"></canvas>
                        </div>

                        @php
                            $totalPrizes = max(1, $totalPrizesCount ?? 1);
                            $wonPrizes = $totalWinners ?? 0;
                            $progressPercent = round(($wonPrizes / $totalPrizes) * 100, 1);
                        @endphp
                        <div class="mt-3">
                            <div class="d-flex justify-content-between small text-muted mb-1">
                                <span>Draw Progress</span>
                                <span class="fw-bold">{{ $progressPercent }}% Drawn</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                                    role="progressbar" style="width: {{ $progressPercent }}%"
                                    aria-valuenow="{{ $progressPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Regional Distribution Bar Chart -->
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div
                        class="card-header bg-white py-3 border-bottom-0 d-flex align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-gray-800">
                            <i class="fas fa-chart-bar me-1 text-info"></i> Top Districts
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container position-relative w-100"
                            style="min-height: 250px; max-height: 280px;">
                            <canvas id="districtChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // 1. Gender Doughnut Chart
            const genderCtx = document.getElementById('genderChart').getContext('2d');
            new Chart(genderCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Male', 'Female'],
                    datasets: [{
                        data: [{{ $maleCount ?? 0 }}, {{ $femaleCount ?? 0 }}],
                        backgroundColor: ['#4e73df', '#e83e8c'],
                        hoverBackgroundColor: ['#2e59d9', '#be2d6b'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 15
                            }
                        }
                    }
                }
            });

            // 2. Raffle Progress Doughnut Chart
            const raffleCtx = document.getElementById('raffleProgressChart').getContext('2d');
            new Chart(raffleCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Claimed / Drawn', 'Remaining'],
                    datasets: [{
                        data: [{{ $totalWinners ?? 0 }}, {{ $remainingPrizes ?? 0 }}],
                        backgroundColor: ['#1cc88a', '#f6c23e'],
                        hoverBackgroundColor: ['#17a673', '#dda20a'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 15
                            }
                        }
                    }
                }
            });

            // 3. District / Division Bar Chart
            const districtCtx = document.getElementById('districtChart').getContext('2d');
            const districtLabels = {!! json_encode(!empty($districtLabels) ? $districtLabels : ['No Data Available']) !!};
            const districtData = {!! json_encode(!empty($districtData) ? $districtData : [0]) !!};

            new Chart(districtCtx, {
                type: 'bar',
                data: {
                    labels: districtLabels,
                    datasets: [{
                        label: 'Participants',
                        data: districtData,
                        backgroundColor: '#36b9cc',
                        hoverBackgroundColor: '#2c9faf',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                callback: function(value) {
                                    return value.toLocaleString();
                                }
                            },
                            grid: {
                                display: true,
                                drawBorder: false
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 0,
                                autoSkip: true
                            },
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += context.parsed.y.toLocaleString();
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
