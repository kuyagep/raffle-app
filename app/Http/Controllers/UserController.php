<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search filter (Name or Email)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        if ($request->wantsJson()) {
            return response()->json($users);
        }

        return view('admin.users.index', compact('users'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'role'     => ['required', Rule::in(['admin', 'staff', 'user'])],
            'password' => 'nullable|string|min:8', // Generates temp password if left empty
        ]);

        // Generate temporary password if none provided
        $tempPassword = $validated['password'] ?? $this->generateTemporaryPassword();
        $validated['password'] = Hash::make($tempPassword);

        $user = User::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success'       => true,
                'message'       => 'User created successfully.',
                'user'          => $user,
                'temp_password' => $tempPassword,
            ], 201);
        }

        return redirect()->route('admin.users.index')
            ->with('success', "User created successfully. Temporary password: {$tempPassword}");
    }

    /**
     * Display the specified user.
     */
    public function show(User $user, Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json($user);
        }

        return view('admin.users.show', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role'  => ['required', Rule::in(['admin', 'staff', 'user'])],
        ]);

        // Update password only if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8',
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully.',
                'user'    => $user,
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user, Request $request)
    {
        $user->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.',
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Generate a secure 10-character temporary password.
     */
    private function generateTemporaryPassword(): string
    {
        return 'Tmp-' . Str::random(6) . '!' . random_int(10, 99);
    }
}
