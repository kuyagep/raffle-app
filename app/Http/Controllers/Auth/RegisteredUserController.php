<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {

        $departments = Department::with('offices')->orderBy('name')->get();
        return view('auth.register', compact('departments'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'office_id'      => ['required', 'exists:offices,id'],
            'firstname'      => ['required', 'string', 'max:255'],
            'lastname'       => ['required', 'string', 'max:255'],
            'position'       => ['nullable', 'string', 'max:255'],
            'sex'            => ['nullable', 'string', 'in:Male,Female'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'email'          => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password'       => ['required', 'confirmed', Rules\Password::defaults()],
            'terms'          => ['accepted'],
        ], [
            'office_id.required' => 'Please select a school / office.',
            'office_id.exists'   => 'The selected school / office is invalid.',
            'terms.accepted'     => 'You must agree to the Terms of Service and Privacy Policy to register.',
        ]);

        $user = User::create([
            'office_id'      => $request->office_id,
            'name'            => $request->firstname . ' ' . $request->lastname,
            'firstname'      => $request->firstname,
            'lastname'       => $request->lastname,
            'position'       => $request->position,
            'sex'            => $request->sex,
            'contact_number' => $request->contact_number,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'role'     => 'user', // Assign default role
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('admin.dashboard', absolute: false));
    }
}
