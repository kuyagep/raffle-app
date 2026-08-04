<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PreRegistrationController extends Controller
{
    public function create()
    {
        $departments = Department::with('offices')->orderBy('name')->get();
        return view('preregistration.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstname'      => 'required|string|max:255',
            'lastname'       => 'required|string|max:255',
            'position'       => 'nullable|string|max:255',
            'sex'            => 'nullable|in:Male,Female',
            'office_id'      => 'required|exists:offices,id',
            'email'          => 'required|email|max:255|unique:users,email',
            'contact_number' => 'nullable|string|max:50',
            'password'       => 'nullable|string|min:8', // optional default password if provided
        ]);

        // Wrap database operations in a transaction
        DB::transaction(function () use ($validated, &$participant) {

            // 1. Generate unique 10-digit QR Code
            do {
                $code = Str::padLeft(random_int(0, 9999999999), 10, '0');
            } while (Participant::where('qr_code', $code)->exists());

            $validated['qr_code'] = $code;

            // 2. Create Participant record
            $participant = Participant::create($validated);

            // 3. Create User Account with Role
            $userPassword = $participant->qr_code ?? 'Password123!'; // Default password fallback

            $user = User::create([
                'name'     => $participant->full_name,
                'email'    => $validated['email'],
                'password' => Hash::make($userPassword),
                'role'     => 'user', // Assign default role
            ]);

            // Optional: Link user_id to participant if your database schema has a foreign key
            if (\Schema::hasColumn('participants', 'user_id')) {
                $participant->update(['user_id' => $user->id]);
            }
        });

        session()->flash('success', 'Participant pre-registered and user account created successfully!');

        return response()->json([
            'success' => true,
            'message' => 'Pre-registration successful! Reloading page...'
        ]);
    }
}
