@extends('layouts.apps')

@section('content')
    <div class="text-center">
        <div class="w-100 text-center mb-3">
            <i class="fas fa-check-circle text-success fa-4x"></i>
        </div>

        <h4 class="fw-bold mb-3 text-success">Thank you, {{ $registration->full_name }}!</h4>
        <p class="text-success ">Your registration has been completed successfully.</p>

        <p class="fw-semibold mt-4">Here is your QR Code:</p>
        <div class="d-flex justify-content-center mb-4">
            <div class="p-3 border rounded bg-light" id="qrCodeContainer">
                {!! QrCode::size(200)->generate($registration->qr_code) !!}
            </div>
        </div>
        <p class="mt-4">Registration Code: <b>{{ $registration->qr_code }}</b></p>
        <p class="small text-muted">
            <i class="fas fa-thumbtack me-2"></i> Please save this QR code. You will need it for event attendance.
        </p>

        <div class="d-flex justify-content-center gap-2 flex-wrap mt-3 mb-2">
            <!-- Download QR Code -->
            <button id="downloadQrBtn" class="btn fw-bold btn-deped"
               >
                <i class="fas fa-download me-2"></i> Download QR Code
            </button>

            <!-- Register Again -->
            <a href="{{ route('registration.create') }}" class="btn fw-bold btn-deped">
                <i class="fas fa-redo me-2"></i> Register Again
            </a>
            <!-- Register Again -->
            <a  href="{{ url('/') }}"  class="btn fw-bold btn-deped">
                <i class="fas fa-home me-2"></i> Go Back to Home
            </a>


        </div>
        {{-- <div class="d-grid">
            <a href="{{ url('/') }}" class="btn btn-lg fw-bold btn-deped"><i class="fas fa-home me-2"></i> Go Back to Home</a>

        </div> --}}


    </div>
@endsection
@section('script')
    <!-- JavaScript for QR Download -->
    <script>
        document.getElementById('downloadQrBtn').addEventListener('click', function() {
            const svg = document.querySelector('#qrCodeContainer svg');
            const serializer = new XMLSerializer();
            const svgData = serializer.serializeToString(svg);

            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            const img = new Image();

            img.onload = function() {
                canvas.width = img.width;
                canvas.height = img.height;
                ctx.drawImage(img, 0, 0);

                const link = document.createElement('a');
                link.download = "qr-code.png";
                link.href = canvas.toDataURL("image/png");
                link.click();
            };

            img.src = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svgData)));
        });
    </script>
@endsection
