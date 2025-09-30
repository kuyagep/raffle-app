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
    <div class="container-fluid">

        <h1 class="mb-4">🎉 National Teachers' Day Raffle Draw 🎉</h1>

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

        <button id="startBtn" class="btn btn-lg btn-success mb-5">🎲 Start Draw</button>

        <!-- Recent Winners -->
        <div class="recent-winners mt-5">
            <h3 class="text-white mb-4">🏆 Recent Winners</h3>
            <ul class="list-group w-75 mx-auto" id="recentWinnersList">
                @forelse($recentWinners as $rw)
                    <li class="list-group-item d-flex justify-content-between align-items-center text-dark">
                        <span>{{ $rw->participant->full_name }}</span>
                        <span class="badge badge-info">{{ $rw->prize->name }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No winners yet.</li>
                @endforelse
            </ul>
        </div>

    </div>

    <!-- Confetti, Sounds, jQuery, SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <audio id="rollingSound" src="/sounds/drum-rolling.mp3" preload="auto"></audio>
    <audio id="winnerSound" src="/sounds/winner.mp3" preload="auto"></audio>

    <script>
        let rollingInterval;

        function startRolling(names) {
            let index = 0;
            $('#winner').hide();
            $('#rolling').show().text("🎲 Drawing...");

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

            let rollingSound = document.getElementById("rollingSound");
            rollingSound.pause();
            rollingSound.currentTime = 0;

            let winnerSound = document.getElementById("winnerSound");
            winnerSound.currentTime = 0;
            winnerSound.play();

            $('#rolling').hide();
            $('#winner').text("🎉 " + finalName + " 🎉").fadeIn();

            fireConfetti();
            updatePrizeOptions();
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
                let html = data.length > 0 ?
                    data.map(w => `<li class="list-group-item d-flex justify-content-between text-dark">
                                    <span>${w.participant.full_name}</span>
                                    <span class="badge badge-info">${w.prize.name}</span>
                                </li>`).join('') :
                    `<li class="list-group-item text-muted">No winners yet.</li>`;
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
                    Swal.fire({
                        icon: 'error',
                        title: 'No More Winners',
                        text: 'This prize is fully drawn!',
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
                            text: 'No eligible participants left.',
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
                            stopRolling(response.winner.full_name);
                            Swal.fire({
                                icon: 'success',
                                title: '🎉 Winner!',
                                html: `<h2><strong>${response.winner.full_name}</strong></h2><br>
                                       Prize: <span class="badge badge-info">${response.prize.name}</span>`,
                                confirmButtonColor: '#003399'
                            });
                            loadRecentWinners();
                            $('#startBtn').prop("disabled", false).text(
                                "Start Draw");
                        }).fail(function(xhr) {
                            clearInterval(rollingInterval);
                            document.getElementById("rollingSound").pause();
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
    </script>
</body>

</html>
