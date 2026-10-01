<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raffle Draw</title>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link href="{{ asset('static/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('static/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('images/favicon/site.webmanifest') }}">

    <!-- Google Fonts: Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS (For form-select and utility class support) -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <style>
        body {
            font-family: 'Roboto', sans-serif !important;
        }
    </style>

    <style>
        body {
            background: #003399;
            color: #fff;
            text-align: center;
            padding-top: 30px;
        }

        .rolling {
            font-size: 2rem;
            font-weight: bold;
            margin: 20px 0;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .winner {
            display: none;
            margin: 30px auto;
            max-width: 500px;
            background: #ffffff;
            /* white card background */
            color: #212529;
            /* dark text for readability */
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease-in-out;
        }

        .winner h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .winner h3 {
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .winner p {
            font-size: 1rem;
            margin-bottom: 5px;
            color: #6c757d;
            /* muted text for details */
        }

        .winner .badge {
            font-size: 1.1rem;
            padding: 0.5rem 1rem;
        }


        .recent-winners {
            margin-top: 40px;
        }

        .recent-winners h3 {
            color: #0dcaf0;
        }

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
</head>

<body>
    <div class="container">

        <h1 class="mb-4"><b>Grand Raffle Draw</b></h1>

        <!-- Prize Selection -->
        <div class="form-group mb-5">
            <label for="prize_id">Select Prize</label>
            <select id="prize_id" class="form-control w-50 mx-auto">
                <option value="">-- Select Prize --</option>
                @foreach ($prizes as $prize)
                    @php
                        $remaining = $prize->quantity - $prize->winners()->count();
                    @endphp
                    @if ($remaining > 0)
                        <option value="{{ $prize->id }}">
                            {{ $prize->name }} (Remaining: {{ $remaining }})
                        </option>
                    @endif
                @endforeach
            </select>
        </div>

        <!-- Rolling Animation -->
        <div id="rolling" class="rolling mt-5 mb-5 text-uppercase">Press Start to Begin</div>

        <!-- Final Winner -->
        <h1 id="winner" class="winner mt-5"></h1>

        <button id="startBtn" class="btn btn-lg bg-gradient-success text-white">Start Draw</button>
        <button id="redrawBtn" class="btn bg-gradient-danger btn-lg d-none text-white">Redraw Prize</button>

        <!-- Recent Winners -->
        <div class="recent-winners mt-5 mb-5">
            <h3 class="text-white mb-3 text-center">🏆
                <a href="{{ route('public.winners') }}" class="text-white text-decoration-none fw-bold">Recent
                    Winners</a>
            </h3>

            <div class="row g-2 justify-content-center row-cols-1 row-cols-sm-1 row-cols-md-1 row-cols-lg-1"
                id="recentWinnersList">
                @forelse($recentWinners as $rw)
                    <div class="col mb-2">
                        <div class="card  h-100 shadow-sm border-0 border-top border-3 border-success text-dark">
                            <div class="card-body p-2 text-center d-flex flex-column justify-content-between">
                                <div>

                                    <!-- Full Name (Uppercase) -->
                                    <h4 class="fw-bold text-dark mb-1 text-truncate text-uppercase">
                                        <b>{{ $rw->participant->full_name }}</b>
                                    </h4>

                                    <!-- District / Division -->
                                    <h6 class="text-muted text-truncate mb-1">
                                        {{ $rw->participant->district_division ?? 'N/A' }}
                                    </h6>
                                </div>

                                <!-- Prize Badge -->
                                <div class="pt-1 border-top mt-1">
                                    <span class="badge badge-success bg-success text-white text-truncate "
                                        style="font-size: 12pt;">
                                        <i class="fas fa-gift mr-1"></i> {{ $rw->prize->name }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card shadow-sm border-0 py-3 text-center">
                            <span class="text-muted small">No winners yet.</span>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
        <div><span class="text-white">Developed by: Geperson Mamalias</span></div>
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

    <!-- Confetti JS -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Add Audio Files -->
    <audio id="rollingSound" src="/sounds/drum-rolling.mp3" preload="auto"></audio>
    <audio id="winnerSound" src="/sounds/winner.mp3" preload="auto"></audio>

    <script>
        let rollingInterval;
        let currentPrizeId = null;
        let currentWinnerId = null;

        $(document).ready(function() {
            updatePrizeOptions();
        });

        function startRolling(names) {
            let index = 0;
            $('#winner').hide();
            $('#rolling').show();

            let rollingSound = document.getElementById("rollingSound");
            if (rollingSound) {
                rollingSound.currentTime = 0;
                rollingSound.loop = true;
                rollingSound.play().catch(() => {});
            }

            rollingInterval = setInterval(() => {
                $('#rolling').text(names[index]);
                index = (index + 1) % names.length;
            }, 100);
        }

        function stopRolling(finalWinner, prize) {
            clearInterval(rollingInterval);

            let rollingSound = document.getElementById("rollingSound");
            if (rollingSound) {
                rollingSound.pause();
                rollingSound.currentTime = 0;
            }

            let winnerSound = document.getElementById("winnerSound");
            if (winnerSound) {
                winnerSound.currentTime = 0;
                winnerSound.play().catch(() => {});
            }

            $('#rolling').hide();

            $('#winner').html(`
                <div class="card bg-light text-dark mx-auto" style="max-width: 500px;">
                    <div class="card-body text-center">
                        <h2 class="card-title mb-3">🏆 Winner!</h2>
                        <h3 class="font-weight-bold mb-2 text-uppercase">${finalWinner.full_name}</h3>
                        ${finalWinner.designation ? `<p class="mb-1 text-muted">${finalWinner.designation}</p>` : ''}
                        <p class="mb-1">${finalWinner.school_office}</p>
                        <p class="mb-3">${finalWinner.district_division}</p>
                        <hr>
                        <p class="mb-0">
                            Prize: <span class="badge badge-success p-2">${prize.name}</span>
                        </p>
                    </div>
                </div>
            `).fadeIn();

            fireConfetti();
            updatePrizeOptions();

            // Save for redraw
            currentPrizeId = prize.id;
            currentWinnerId = finalWinner.id;
            $('#redrawBtn').removeClass("d-none");
        }

        function fireConfetti() {
            let duration = 3000;
            let end = Date.now() + duration;

            (function frame() {
                confetti({
                    particleCount: 5,
                    angle: 60,
                    spread: 55,
                    origin: {
                        x: 0
                    }
                });
                confetti({
                    particleCount: 5,
                    angle: 120,
                    spread: 55,
                    origin: {
                        x: 1
                    }
                });
                if (Date.now() < end) requestAnimationFrame(frame);
            })();
        }

        function loadRecentWinners() {
            $.get("{{ route('raffle.recentWinners') }}", function(data) {
                let html = "";
                if (data && data.length > 0) {
                    data.forEach(w => {
                        let fullName = w.participant ? w.participant.full_name : 'N/A';
                        let fullNameUpper = fullName.toUpperCase();
                        let district = (w.participant && w.participant.district_division) ? w.participant
                            .district_division : 'N/A';
                        let prizeName = w.prize ? w.prize.name : 'Prize';

                        html += `
                <div class="col mb-2">
                    <div class="card h-100 shadow-sm border-0 border-top border-3 border-success text-dark">
                        <div class="card-body p-2 text-center d-flex flex-column justify-content-between">
                            <div>


                                <h4 class="fw-bold text-dark mb-1 text-truncate text-uppercase"

                                    <b>${fullNameUpper}</b>
                                </h4>

                                <h6 class="text-muted text-truncate mb-1" >

                                    ${district}
                                </h6>
                            </div>

                            <div class="pt-1 border-top mt-1">
                                <span class="badge badge-success bg-success text-white text-truncate " style="font-size: 12pt;"  >
                                    <i class="fas fa-gift mr-1"></i>${prizeName}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>`;
                    });
                } else {
                    html = `
            <div class="col-12">
                <div class="card shadow-sm border-0 py-3 text-center">
                    <span class="text-muted small">No winners yet.</span>
                </div>
            </div>`;
                }
                $("#recentWinnersList").html(html);
            });
        }

        function updatePrizeOptions() {
            $.get("{{ route('raffle.prizesRemaining') }}", function(data) {
                let $select = $('#prize_id');
                let selectedPrizeId = $select.val(); // Keep current selection if still available

                $select.empty().append('<option value="">-- Select Prize --</option>');

                data.forEach(prize => {
                    // Only add option if there are remaining items left
                    if (prize.remaining > 0) {
                        let isSelected = (prize.id == selectedPrizeId) ? 'selected' : '';
                        $select.append(`<option value="${prize.id}" ${isSelected}>
                    ${prize.name} (Remaining: ${prize.remaining})
                </option>`);
                    }
                });
            });
        }

        $('#startBtn').click(function() {
            let prizeId = $('#prize_id').val();
            if (!prizeId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Prize Selected',
                    text: 'Please select a prize first.',
                    confirmButtonColor: '#003399'
                });
                return;
            }

            $(this).prop("disabled", true).text("Drawing...");

            $.get("{{ route('raffle.checkPrize') }}", {
                prize_id: prizeId
            }, function(data) {
                if (data.remaining <= 0) {
                    stopAllSounds();
                    Swal.fire({
                        icon: 'error',
                        title: 'No More Winners',
                        text: 'All winners for this prize have already been drawn!',
                        confirmButtonColor: '#d33'
                    });
                    $('#startBtn').prop("disabled", false).text("Start Draw");
                    return;
                }

                $.get("{{ route('participants.list') }}", function(data) {
                    if (data.length === 0) {
                        Swal.fire({
                            icon: 'info',
                            title: 'No Participants',
                            text: 'There are no participants available for the draw.',
                            confirmButtonColor: '#003399'
                        });
                        $('#startBtn').prop("disabled", false).text("Start Draw");
                        return;
                    }

                    startRolling(data.map(p => p.full_name));

                    setTimeout(() => {
                        $.post("{{ route('raffle.start') }}", {
                            _token: "{{ csrf_token() }}",
                            prize_id: prizeId
                        }, function(response) {
                            stopRolling(response.winner, response.prize);

                            Swal.fire({
                                icon: 'success',
                                title: '🎉 We have a Winner! 🎉',
                                html: `
                                    <h2><strong>${response.winner.full_name}</strong></h2>
                                    ${response.winner.designation ? `<span>${response.winner.designation}</span>` : ''}<br>
                                    <span>${response.winner.school_office}</span><br>
                                    <span>${response.winner.district_division}</span>
                                    <hr>
                                    <span><b>Prize:</b> <span class="badge badge-success">${response.prize.name}</span></span>
                                `,
                                confirmButtonColor: '#003399'
                            });

                            loadRecentWinners();
                            $('#startBtn').prop("disabled", false).text(
                                "Start Draw");
                        }).fail(function(xhr) {
                            clearInterval(rollingInterval);
                            stopAllSounds();
                            $('#rolling').hide();

                            Swal.fire({
                                icon: 'error',
                                title: 'Draw Failed',
                                text: xhr.responseJSON.error,
                                confirmButtonColor: '#d33'
                            });
                            $('#startBtn').prop("disabled", false).text(
                                "Start Draw");
                        });
                    }, 5000);
                });
            });
        });

        $('#redrawBtn').click(function() {
            if (!currentPrizeId || !currentWinnerId) {
                Swal.fire({
                    icon: 'error',
                    title: 'No Winner to Redraw',
                    text: 'Please draw a winner first before redrawing.',
                    confirmButtonColor: '#d33'
                });
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: "This will replace the current winner with a new one.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#003399',
                confirmButtonText: 'Yes, Redraw!'
            }).then((result) => {
                if (result.isConfirmed) {

                    // ✅ Step 1: Get all remaining participants for this prize
                    $.get("{{ route('participants.list') }}", {
                        prize_id: currentPrizeId
                    }, function(data) {
                        if (data.length === 0) {
                            Swal.fire({
                                icon: 'info',
                                title: 'No Participants',
                                text: 'There are no participants left for redraw.',
                                confirmButtonColor: '#003399'
                            });
                            return;
                        }

                        // ✅ Step 2: Start rolling animation
                        startRolling(data.map(p => p.full_name));

                        // ✅ Step 3: After 5 seconds, call redraw API
                        setTimeout(() => {
                            $.post("{{ route('raffle.redraw') }}", {
                                _token: "{{ csrf_token() }}",
                                prize_id: currentPrizeId,
                                old_winner_id: currentWinnerId
                            }, function(response) {
                                // Stop rolling and show new winner
                                stopRolling(response.winner, response.prize);

                                Swal.fire({
                                    icon: 'success',
                                    title: '🎉 Winner Redrawn! 🎉',
                                    html: `
                                <h2><strong>${response.winner.full_name}</strong></h2>
                                ${response.winner.designation ? `<span>${response.winner.designation}</span>` : ''}<br>
                                <span>${response.winner.school_office}</span><br>
                                <span>${response.winner.district_division}</span>
                                <hr>
                                <span><b>Prize:</b> <span class="badge badge-success">${response.prize.name}</span></span>
                            `,
                                    confirmButtonColor: '#003399'
                                });

                                // ✅ Update current winner IDs
                                currentWinnerId = response.winner.id;
                                currentPrizeId = response.prize.id;

                                // ✅ Reload recent winners
                                loadRecentWinners();
                            }).fail(function(xhr) {
                                clearInterval(rollingInterval);
                                stopAllSounds();
                                $('#rolling').hide();

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Redraw Failed',
                                    text: xhr.responseJSON.error,
                                    confirmButtonColor: '#d33'
                                });
                            });
                        }, 5000);
                    });

                }
            });
        });


        function stopAllSounds() {
            let rollingSound = document.getElementById("rollingSound");
            let winnerSound = document.getElementById("winnerSound");
            if (rollingSound) {
                rollingSound.pause();
                rollingSound.currentTime = 0;
            }
            if (winnerSound) {
                winnerSound.pause();
                winnerSound.currentTime = 0;
            }
        }
    </script>
</body>

</html>
