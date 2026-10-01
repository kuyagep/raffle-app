@extends('layouts.app')

@section('content')
    <style>
        /* Live Stage Container */
        .draw-container {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 85vh;
            color: #fff;
            position: relative;
            overflow: hidden;
            border-radius: 15px;
        }

        /* Animated Slot Screen */
        .winner-box {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        /* Floating Background Animations */
        .floating-bg-container {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 120px;
            overflow: hidden;
            pointer-events: none;
            z-index: 1;
        }

        .floating-icon {
            position: absolute;
            bottom: -40px;
            font-size: 1.8rem;
            opacity: 0.6;
            animation: floatUp 6s linear infinite;
        }

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

    <div class="container-fluid p-4">
        <div class="draw-container p-4 text-center d-flex flex-column justify-content-between">

            <!-- Header -->
            <div>
                <h2 class="fw-bold text-uppercase tracking-wide"><i class="fas fa-school text-warning me-2"></i> School Live
                    Draw</h2>
                <p class="text-white-50">Select a prize and spin to pick a winning school</p>
            </div>

            <!-- Center Winner Slot Display -->
            <div class="row justify-content-center my-4 position-relative" style="z-index: 5;">
                <div class="col-lg-8">
                    <div class="winner-box p-4 p-md-5">
                        <span class="badge bg-warning text-dark px-3 py-2 text-uppercase mb-3" id="prizeDisplayBadge">
                            Select a Prize First
                        </span>

                        <h1 class="display-4 fw-bold text-uppercase text-truncate mb-2" id="schoolNameSlot">
                            READY TO DRAW
                        </h1>

                        <h5 class="text-warning mb-0 text-truncate" id="districtSlot">
                            <i class="fas fa-map-marker-alt me-1"></i> District / Municipality
                        </h5>
                    </div>
                </div>
            </div>

            <!-- Controls Form -->
            <div class="row justify-content-center position-relative mb-4" style="z-index: 5;">
                <div class="col-md-6 col-lg-5">
                    <div class="input-group input-group-lg shadow-sm">
                        <select class="form-select text-uppercase fw-bold" id="prizeSelect">
                            <option value="" selected disabled>-- Select Prize --</option>
                            @foreach ($prizes as $prize)
                                <option value="{{ $prize->id }}">{{ $prize->name }} (Qty: {{ $prize->quantity }})
                                </option>
                            @endforeach
                        </select>
                        <button class="btn btn-warning px-4 fw-bold text-dark text-uppercase" id="drawBtn" type="button">
                            <i class="fas fa-random me-1"></i> Spin Draw
                        </button>
                    </div>
                </div>
            </div>

            <!-- Recent Winners Grid Section -->
            <div class="recent-winners mt-3 mb-2 position-relative" style="z-index: 5;">
                <h5 class="text-white mb-3 fw-bold text-uppercase">
                    🏆 Recent Winning Schools
                </h5>

                <div class="row g-2 justify-content-center row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6"
                    id="recentWinnersList">
                    @forelse($recentWinners as $rw)
                        <div class="col">
                            <div class="card h-100 shadow-sm border-0 border-top border-3 border-success text-dark">
                                <div class="card-body p-2 text-center d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="mb-1">
                                            <div class="bg-light text-success rounded-circle d-inline-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px;">
                                                <i class="fas fa-school small"></i>
                                            </div>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1 lh-sm text-truncate small text-uppercase"
                                            title="{{ $rw->school->school_name }}">
                                            <b>{{ $rw->school->school_name }}</b>
                                        </h6>
                                        <p class="text-muted text-truncate mb-1" style="font-size: 0.75rem;">
                                            <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                            {{ $rw->school->district_name }}
                                        </p>
                                    </div>
                                    <div class="pt-1 border-top mt-1">
                                        <span class="badge bg-success text-white text-truncate w-100"
                                            style="font-size: 0.7rem;" title="{{ $rw->prize->name }}">
                                            <i class="fas fa-gift me-1"></i>{{ $rw->prize->name }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="card shadow-sm border-0 py-2 text-center">
                                <span class="text-muted small">No winning schools drawn yet.</span>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Floating Background Animation Icons -->
            <div class="floating-bg-container">
                <i class="fas fa-gift floating-icon icon-gift"></i>
                <i class="fas fa-money-bill-wave floating-icon icon-cash"></i>
                <i class="fas fa-ticket-alt floating-icon icon-ticket"></i>
                <i class="fas fa-coins floating-icon icon-cash"></i>
                <i class="fas fa-trophy floating-icon icon-trophy"></i>
                <i class="fas fa-school floating-icon icon-gift"></i>
                <i class="fas fa-money-check-alt floating-icon icon-cash"></i>
                <i class="fas fa-ticket-alt floating-icon icon-ticket"></i>
                <i class="fas fa-gift floating-icon icon-gift"></i>
                <i class="fas fa-coins floating-icon icon-cash"></i>
            </div>

        </div>
    </div>

    <!-- JS Slot Machine Logic -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let isDrawing = false;

            $('#drawBtn').click(function() {
                let prizeId = $('#prizeSelect').val();

                if (!prizeId) {
                    alert('Please select a prize before spinning!');
                    return;
                }

                if (isDrawing) return;
                isDrawing = true;
                $('#drawBtn').prop('disabled', true);

                // Update Prize Badge Display
                let selectedPrizeText = $("#prizeSelect option:selected").text();
                $('#prizeDisplayBadge').text("PRIZE: " + selectedPrizeText);

                // Slot animation effect
                let shuffleInterval = setInterval(function() {
                    let tempNames = ["CENTRAL ELEM SCHOOL", "NATIONAL HIGH SCHOOL", "DIGOS CITY HS",
                        "DAVAO ACADEMY", "SOUTH ELEMENTARY"
                    ];
                    let tempDistricts = ["DISTRICT I", "DISTRICT II", "CENTRAL DISTRICT",
                        "NORTH DISTRICT"
                    ];

                    let randomName = tempNames[Math.floor(Math.random() * tempNames.length)];
                    let randomDistrict = tempDistricts[Math.floor(Math.random() * tempDistricts
                        .length)];

                    $('#schoolNameSlot').text(randomName);
                    $('#districtSlot').html('<i class="fas fa-map-marker-alt me-1"></i>' +
                        randomDistrict);
                }, 80);

                // Send AJAX Draw Request
                $.ajax({
                    url: "{{ route('raffle.school.draw') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        prize_id: prizeId
                    },
                    success: function(response) {
                        setTimeout(function() {
                            clearInterval(shuffleInterval);

                            // Reveal actual winner
                            $('#schoolNameSlot').text(response.winner.school_name
                                .toUpperCase());
                            $('#districtSlot').html(
                                '<i class="fas fa-map-marker-alt me-1"></i>' +
                                response.winner.district_name + ' - ' + response
                                .winner.municipality);

                            loadRecentWinners();
                            isDrawing = false;
                            $('#drawBtn').prop('disabled', false);
                        }, 3000); // Shuffle for 3 seconds
                    },
                    error: function(xhr) {
                        clearInterval(shuffleInterval);
                        let msg = xhr.responseJSON ? xhr.responseJSON.error :
                            'Failed to draw winner.';
                        alert(msg);
                        $('#schoolNameSlot').text("DRAW FAILED");
                        isDrawing = false;
                        $('#drawBtn').prop('disabled', false);
                    }
                });
            });

            // Refresh Recent Winners List
            function loadRecentWinners() {
                $.get("{{ route('raffle.school.recentWinners') }}", function(data) {
                    let html = "";
                    if (data && data.length > 0) {
                        data.forEach(w => {
                            let schoolName = w.school ? w.school.school_name.toUpperCase() : 'N/A';
                            let district = w.school ? w.school.district_name : 'N/A';
                            let prizeName = w.prize ? w.prize.name : 'Prize';

                            html += `
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0 border-top border-3 border-success text-dark">
                            <div class="card-body p-2 text-center d-flex flex-column justify-content-between">
                                <div>
                                    <div class="mb-1">
                                        <div class="bg-light text-success rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="fas fa-school small"></i>
                                        </div>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1 lh-sm text-truncate small text-uppercase" title="${schoolName}">
                                        <b>${schoolName}</b>
                                    </h6>
                                    <p class="text-muted text-truncate mb-1" style="font-size: 0.75rem;">
                                        <i class="fas fa-map-marker-alt text-danger me-1"></i>${district}
                                    </p>
                                </div>
                                <div class="pt-1 border-top mt-1">
                                    <span class="badge bg-success text-white text-truncate w-100" style="font-size: 0.7rem;" title="${prizeName}">
                                        <i class="fas fa-gift me-1"></i>${prizeName}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>`;
                        });
                    } else {
                        html = `
                <div class="col-12">
                    <div class="card shadow-sm border-0 py-2 text-center">
                        <span class="text-muted small">No winning schools drawn yet.</span>
                    </div>
                </div>`;
                    }
                    $("#recentWinnersList").html(html);
                });
            }
        });
    </script>
@endsection
