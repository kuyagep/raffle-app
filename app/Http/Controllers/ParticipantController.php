<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    // Show registration form
    public function create()
    {
        return view('pages.registrations.create');
    }

    public function join()
    {
        return view('pages.registrations.join');
    }

    // Store registration
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'employment_type' => 'required|in:Teaching,Non-Teaching',
            'school_office' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'contact_number' => 'nullable|string|max:20',
            'captcha' => 'required|captcha',
        ]);

        // Generate unique QR Code string
        // $qrCodeValue = uniqid('td-');
        $qrCodeValue = uniqid();

        $registration = Participant::create([
            'full_name' => $request->full_name,
            'employment_type' => $request->employment_type,
            'school_office' => $request->school_office,
            'position' => $request->position,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'qr_code' => $qrCodeValue,
        ]);

        return redirect()->route('registrations.show', $registration->id);
    }

    // Show confirmation with QR code
    public function show($id)
    {
        $registration = Participant::findOrFail($id);

        return view('pages.registrations.show', compact('registration'));
    }
}
