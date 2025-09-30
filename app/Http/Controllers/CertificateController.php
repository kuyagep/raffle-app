<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function generate(Attendance $attendance)
    {
        $pdf = Pdf::loadView('certificate.attendance', [
            'attendance' => $attendance,
        ]);

        return $pdf->download('certificate-' . $attendance->participant->full_name . '.pdf');
    }
}
