<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raffle Draw</title>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
     <link href="{{ asset('static/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('static/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <link rel="apple-touch-icon" sizes="180x180" href="{{asset("images/favicon/apple-touch-icon.png")}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset("images/favicon/favicon-32x32.png")}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset("images/favicon/favicon-16x16.png")}}">
    <link rel="manifest" href="{{asset("images/favicon/site.webmanifest")}}">

        <!-- Google Fonts: Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

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
    </style>
</head>

<body>
    <div class="container">

        <h1 class="mb-4">National Teachers' Day Raffle Draw</h1>

        <!-- Prize Selection -->
        <div class="form-group mb-5">
            <label for="prize_id">Select Prize</label>
            <select id="prize_id" class="form-control w-50 mx-auto">
                <option value="">-- Select Prize --</option>
                @foreach ($prizes as $prize)
                    <option value="{{ $prize->id }}">
                        {{ $prize->name }} (Remaining: {{ $prize->quantity - $prize->winners()->count() }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Rolling Animation -->
        <div id="rolling" class="rolling mt-5 mb-5">Press Start to Begin</div>

        <!-- Final Winner -->
        <h1 id="winner" class="winner mt-5"></h1>

        <button id="startBtn" class="btn btn-lg bg-gradient-success text-white">Start Draw</button>
        <button id="redrawBtn" class="btn bg-gradient-danger btn-lg d-none text-white">Redraw Prize</button>

        <!-- Recent Winners -->
        <div class="recent-winners mt-5 mb-5">
            <h3 class="text-white mb-2">🏆
                <a href="{{ route('public.winners') }}" class="text-white">Recent Winners</a>
            </h3>
            <ul class="list-group w-75 mx-auto" id="recentWinnersList">
                @forelse($recentWinners as $rw)
                    <li class="list-group-item d-flex justify-content-between text-dark">
                        <span><b>{{ $rw->participant->full_name }}</b> - {{ $rw->participant->district_division }}</span>
                        <span class="badge badge-success">{{ $rw->prize->name }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No winners yet.</li>
                @endforelse
            </ul>
        </div>
        <div><span class="text-white">Made with ❤️ Geperson Mamalias</span></div>
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
            <h3 class="font-weight-bold mb-2">${finalWinner.full_name}</h3>
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
                if (data.length > 0) {
                    data.forEach(w => {
                        html += `<li class="list-group-item d-flex justify-content-between text-dark">
                                    <span><b>${w.participant.full_name}</b> - ${w.participant.district_division}</span>
                                    <span class="badge badge-success">${w.prize.name}</span>
                                </li>`;
                    });
                } else {
                    html = `<li class="list-group-item text-muted">No winners yet.</li>`;
                }
                $("#recentWinnersList").html(html);
            });
        }

        function updatePrizeOptions() {
            $.get("{{ route('raffle.prizesRemaining') }}", function(data) {
                let $select = $('#prize_id');
                $select.empty().append('<option value="">-- Select Prize --</option>');
                data.forEach(prize => {
                    let disabled = prize.remaining <= 0 ? 'disabled' : '';
                    $select.append(`<option value="${prize.id}" ${disabled}>
                        ${prize.name} (Remaining: ${prize.remaining})
                    </option>`);
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
