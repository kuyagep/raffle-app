@extends('layouts.admin')

@section('content')
    <div class="container text-center">
        <h1 class="mb-4">QR Code Attendance</h1>

        <div id="scanner-box"
            style="width: 400px; height: 300px; margin: 0 auto; border: 3px solid #333; border-radius: 15px; overflow: hidden;">
            <video id="preview" style="width: 100%; height: 100%; object-fit: cover;"></video>
        </div>

        <div id="scannedName" class="mt-4 h5 font-weight-bold"></div>
    </div>
@endsection

@push('scripts')
    <!-- Instascan -->
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <!-- Beep sound -->
    <audio id="beepSound" src="{{ asset('sounds/beep-08b.mp3') }}" preload="auto"></audio>
    <audio id="beepFail" src="{{ asset('sounds/mixkit-system-beep-buzzer-fail-2964.wav') }}" preload="auto"></audio>

    <script>
        let scanner = new Instascan.Scanner({
            video: document.getElementById('preview'),
            mirror: false
        });

        scanner.addListener('scan', function(content) {
            $.ajax({
                url: "{{ route('attendance.store') }}",
                method: "POST",
                data: {
                    qr_code: content,
                    event_name: "Teacher's Day 2025",
                    _token: "{{ csrf_token() }}"
                },
                success: function(data) {
                    // console.log(data); // Debugging

                    let nameBox = document.getElementById('scannedName');
                    if (data.status === "success") {
                        // Play beep sound
                        document.getElementById("beepSound").play();

                        nameBox.innerHTML = `✅ ${data.data.name} marked present at ${data.data.time}`;
                        nameBox.style.color = "green";

                        // Flash animation
                        nameBox.classList.add("flash");
                        setTimeout(() => nameBox.classList.remove("flash"), 1500);
                    } else {
                        nameBox.innerHTML = `❌ ${data.message}`;
                        nameBox.style.color = (data.message.includes("already")) ? "orange" : "red";
                    }
                },
                error: function(xhr) {
                     // Play beep sound
                        document.getElementById("beepFail").play();
                    let nameBox = document.getElementById('scannedName');
                    nameBox.innerHTML = `❌ ${xhr.responseJSON?.message || 'An error occurred'}`;
                    nameBox.style.color = (xhr.responseJSON?.message.includes("already")) ? "orange" :
                        "red";
                    // console.log(xhr.responseJSON); // Debugging
                    // console.error("AJAX error:", xhr.responseText);
                }
            });
        });

        Instascan.Camera.getCameras().then(function(cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]); // Use the first camera
            } else {
                alert('No cameras found.');
            }
        }).catch(function(e) {
            console.error(e);
        });
    </script>

    <style>
        #scanner-box {
            background: #000;
            /* black background behind video */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .flash {
            animation: flashEffect 0.5s alternate 3;
        }

        @keyframes flashEffect {
            from {
                color: green;
                transform: scale(1);
            }

            to {
                color: darkgreen;
                transform: scale(1.2);
            }
        }
    </style>
@endpush
