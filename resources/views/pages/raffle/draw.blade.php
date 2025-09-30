<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raffle Draw</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #003399;
            color: #fff;
            text-align: center;
            padding-top: 50px;
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
            font-size: 2.5rem;
            font-weight: bold;
            color: #ffd700;
            display: none;
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

        <h1 class="mb-4">🎉 Raffle Draw 🎉</h1>

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
        <div id="rolling" class="rolling mt-5  mb-5">Press Start to Begin</div>

        <!-- Final Winner -->
        <h1 id="winner" class="winner mt-5"></h1>

        <button id="startBtn" class="btn btn-lg  btn-success mb-5">Start Draw</button>

        <!-- Recent Winners -->
        <div class="recent-winners  mt-5">
            <h3 class="text-white mb-2">🏆 Recent Winners</h3>
            <ul class="list-group w-75 mx-auto" id="recentWinnersList">
                @forelse($recentWinners as $rw)
                    <li class="list-group-item d-flex justify-content-between text-dark">
                        <span>{{ $rw->participant->full_name }}</span>
                        <span class="badge badge-info">{{ $rw->prize->name }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No winners yet.</li>
                @endforelse
            </ul>
        </div>

    </div>
    <!-- Confetti JS -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Add Audio Files -->
    <audio id="rollingSound" src="/sounds/drum-rolling.mp3" preload="auto"></audio>
    <audio id="winnerSound" src="/sounds/winner.mp3" preload="auto"></audio>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let rollingInterval;

        // Run once when page loads
        updatePrizeOptions();

        function startRolling(names) {
            let index = 0;
            $('#winner').hide();
            $('#rolling').show();

            let rollingSound = document.getElementById("rollingSound");
            rollingSound.currentTime = 0;
            rollingSound.loop = true;
            rollingSound.play();

            rollingInterval = setInterval(() => {
                $('#rolling').text(names[index]);
                index = (index + 1) % names.length;
            }, 100);
        }

        function stopRolling(finalName) {
            clearInterval(rollingInterval);
            // Stop rolling sound
            let rollingSound = document.getElementById("rollingSound");
            rollingSound.pause();
            rollingSound.currentTime = 0;

            // Play winner sound
            let winnerSound = document.getElementById("winnerSound");
            winnerSound.currentTime = 0;
            winnerSound.play();

            $('#rolling').hide();
            $('#winner').text("🎉 " + finalName + " 🎉").fadeIn();

            // 🎊 Fire confetti
            fireConfetti();
            updatePrizeOptions();
        }

        function fireConfetti() {
            let duration = 3 * 1000; // 3 seconds
            let end = Date.now() + duration;

            (function frame() {
                // Left side
                confetti({
                    particleCount: 5,
                    angle: 60,
                    spread: 55,
                    origin: {
                        x: 0
                    }
                });
                // Right side
                confetti({
                    particleCount: 5,
                    angle: 120,
                    spread: 55,
                    origin: {
                        x: 1
                    }
                });

                if (Date.now() < end) {
                    requestAnimationFrame(frame);
                }
            })();
        }

        function loadRecentWinners() {
            $.get("{{ route('raffle.recentWinners') }}", function(data) {
                let html = "";
                if (data.length > 0) {
                    data.forEach(w => {
                        html += `<li class="list-group-item d-flex justify-content-between text-dark">
                                    <span>${w.participant.full_name}</span>
                                    <span class="badge badge-info">${w.prize.name}</span>
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
                    $select.append(
                        `<option value="${prize.id}" ${disabled}>
                    ${prize.name} (Remaining: ${prize.remaining})
                </option>`
                    );
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
            // ✅ Check from server if prize still has slots
            $.get("{{ route('raffle.checkPrize') }}", {
                prize_id: prizeId
            }, function(data) {
                if (data.remaining <= 0) {
                    // Stop all sounds
                    let rollingSound = document.getElementById("rollingSound");
                    let winnerSound = document.getElementById("winnerSound");

                    rollingSound.pause();
                    rollingSound.currentTime = 0;
                    winnerSound.pause();
                    winnerSound.currentTime = 0;

                    Swal.fire({
                        icon: 'error',
                        title: 'No More Winners',
                        text: 'All winners for this prize have already been drawn!',
                        confirmButtonColor: '#d33'
                    });
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
                        return;
                    }

                    // Start rolling animation
                    startRolling(data.map(p => p.full_name));

                    // Stop after 5 seconds and fetch actual winner
                    setTimeout(() => {
                        $.post("{{ route('raffle.start') }}", {
                            _token: "{{ csrf_token() }}",
                            prize_id: prizeId
                        }, function(response) {
                            stopRolling(response.winner.full_name);

                            Swal.fire({
                                icon: 'success',
                                title: '🎉 Winner!',
                                html: `<h2><strong>${response.winner.full_name}</strong></h2><br>
                           Prize: <span class="badge badge-info">${response.prize.name}</span>`,
                                confirmButtonColor: '#003399'
                            });

                            loadRecentWinners();
                        }).fail(function(xhr) {
                            clearInterval(rollingInterval);

                            // Stop sounds if error
                            let rollingSound = document.getElementById(
                                "rollingSound");
                            rollingSound.pause();
                            rollingSound.currentTime = 0;

                            $('#rolling').hide();

                            Swal.fire({
                                icon: 'error',
                                title: 'Draw Failed',
                                text: xhr.responseJSON.error,
                                confirmButtonColor: '#d33'
                            });

                            alert(xhr.responseJSON.error);
                        });
                    }, 5000);
                });



            });


        });
    </script>
</body>

</html>
