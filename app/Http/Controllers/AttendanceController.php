<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Participant;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function scan()
    {
        return view('pages.attendance-scan');
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'qr_code'    => 'required|string',
            'event_name' => 'required|string',
        ]);

        $participant = Participant::where('qr_code', $validated['qr_code'])->first();

        if (!$participant) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid QR Code',
            ], 404);
        }

        // Prevent duplicate attendance
        $already = Attendance::where('participant_id', $participant->id)
            ->where('event_name', $validated['event_name'])
            ->exists();

        if ($already) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Attendance already recorded',
            ], 409);
        }

        $attendance = Attendance::create([
            'participant_id' => $participant->id,
            'event_name'     => $validated['event_name'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => "{$participant->full_name} attendance recorded",
            'data' => [
                'name' => $participant->full_name,
                'time' => $attendance->created_at->format('M d, Y h:i A'),
            ],
        ]);
    }
}
