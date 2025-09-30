<!DOCTYPE html>
<html>
<head>
    <style>
        body { text-align: center; font-family: 'Times New Roman', serif; }
        .certificate { border: 8px solid #003399; padding: 50px; }
        h1 { font-size: 40px; color: #003399; }
        h2 { margin-top: 20px; }
        .name { font-size: 30px; font-weight: bold; margin-top: 30px; }
        .footer { margin-top: 50px; font-size: 16px; }
    </style>
</head>
<body>
    <div class="certificate">
        <h1>Certificate of Attendance</h1>
        <p>This is to certify that</p>
        <div class="name">{{ $attendance->participant->full_name }}</div>
        <p>has attended the</p>
        <h2>{{ $attendance->event_name }}</h2>
        <p>held on {{ $attendance->scanned_at->format('F d, Y') }}</p>
        <div class="footer">Signed, <br><br> _________________________ <br> Event Organizer</div>
    </div>
</body>
</html>
