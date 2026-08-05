@extends('layouts.app')

@section('content')
    <div class="container-fluid px-2 px-sm-3 px-lg-4 py-3">
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800 fw-bold">Dashboard</h1>
        </div>
        @if (auth()->user()->role === 'admin')
            <!-- Stat Cards Row -->
            <div class="row g-3 g-md-4 mb-4">
                <!-- Total Registrations -->
                <div class="col-12 col-sm-6 col-lg-4 mb-2">
                    <div class="card border-left-primary shadow-sm h-100 py-2">
                        <div class="card-body">
                            <div class="row align-items-center g-0">
                                <div class="col pe-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Registrations
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $totalRegistrations }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Male Participants -->
                <div class="col-12 col-sm-6 col-lg-4 mb-2">
                    <div class="card border-left-success shadow-sm h-100 py-2">
                        <div class="card-body">
                            <div class="row align-items-center g-0">
                                <div class="col pe-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Male Participants
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $maleCount }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-male fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Female Participants -->
                <div class="col-12 col-sm-6 col-lg-4 mb-2">
                    <div class="card border-left-warning shadow-sm h-100 py-2">
                        <div class="card-body">
                            <div class="row align-items-center g-0">
                                <div class="col pe-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Female Participants
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $femaleCount }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-female fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Districts -->
                <div class="col-12 col-sm-6 col-lg-4 mb-2">
                    <div class="card border-left-info shadow-sm h-100 py-2">
                        <div class="card-body">
                            <div class="row align-items-center g-0">
                                <div class="col pe-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Districts/Divisions
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $districtCount }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-map fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Municipalities -->
                <div class="col-12 col-sm-6 col-lg-4 mb-2">
                    <div class="card border-left-secondary shadow-sm h-100 py-2">
                        <div class="card-body">
                            <div class="row align-items-center g-0">
                                <div class="col pe-2">
                                    <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                        Municipalities
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $municipalityCount }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-city fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Schools/Offices -->
                <div class="col-12 col-sm-6 col-lg-4 mb-2">
                    <div class="card border-left-dark shadow-sm h-100 py-2">
                        <div class="card-body">
                            <div class="row align-items-center g-0">
                                <div class="col pe-2">
                                    <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                        Schools/Offices
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $schoolCount }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-school fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row g-3 g-md-4">
                <div class="col-12 col-lg-6 col-xl-5">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header py-3 bg-white border-bottom-0">
                            <h6 class="m-0 font-weight-bold text-primary">Participants by Gender</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-container position-relative" style="min-height: 250px; max-height: 320px;">
                                <canvas id="genderChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- <script>

        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('genderChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Male', 'Female'],
                    datasets: [{
                        data: [{{ $maleCount }}, {{ $femaleCount }}],
                        backgroundColor: ['#4e73df', '#e83e8c'],
                        hoverBackgroundColor: ['#2e59d9', '#be2d6b'],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
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
        });
    </script> --}}
@endpush
