<?php

namespace App\Http\Controllers;

use App\Models\Events;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EventController extends Controller
{
    // List all events
    public function index()
    {
        $events = Events::withCount('participants')->latest()->paginate(10);
        return view('app.events.index', compact('events'));
    }

    // Admin: Store new event
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'location'   => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'capacity'   => 'nullable|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $event = Events::create($validated);

        return response()->json([
            'message'  => 'Event created successfully!',
            'event'    => $event,
            'join_url' => $event->join_url
        ]);
    }

    public function joinByLink($code)
    {
        $event = Events::where('join_code', $code)->firstOrFail();

        if (!Auth::check()) {
            $departments = \App\Models\Department::all();
            return view('events.pre-register', compact('event', 'departments'));
        }

        return $this->processUserJoin($event, Auth::user());
    }

    public function storePreRegistration(Request $request, $code)
    {
        $event = Events::where('join_code', $code)->firstOrFail();

        if ($event->capacity && $event->participants()->count() >= $event->capacity) {
            return response()->json(['message' => 'Sorry, this event has reached its maximum participant capacity.'], 422);
        }

        $validated = $request->validate([
            'firstname'      => 'required|string|max:255',
            'lastname'       => 'required|string|max:255',
            'position'       => 'nullable|string|max:255',
            'sex'            => 'nullable|in:Male,Female',
            'office_id'      => 'required|exists:offices,id',
            'email'          => 'required|email|max:255|unique:users,email',
            'contact_number' => 'nullable|string|max:50|unique:users,contact_number',
        ]);

        // Create or find user based on email (or full name if email not provided)
        $email = $validated['email'] ?? Str::slug($validated['firstname'] . '.' . $validated['lastname']) . '@gmail.com';

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name'           => $validated['firstname'] . ' ' . $validated['lastname'],
                'position'       => $validated['position'],
                'sex'            => $validated['sex'],
                'office_id'      => $validated['office_id'],
                'contact_number' => $validated['contact_number'],
                'role'           => 'user',
                'password'       => Hash::make(Str::random(12)),
            ]
        );

        // Attach participant to event
        if (!$event->isJoinedBy($user->id)) {
            $event->participants()->attach($user->id);
        }

        Auth::login($user);

        return response()->json([
            'message'  => 'Pre-registration successful! Redirecting...',
            'redirect' => route('dashboard')
        ]);
    }

    /**
     * Helper to process participant attach
     */
    private function processUserJoin(Events $event, User $user)
    {
        if ($event->isJoinedBy($user->id)) {
            return redirect()->route('admin.events.index')
                ->with('info', "You are already a participant of '{$event->title}'.");
        }

        if ($event->capacity && $event->participants()->count() >= $event->capacity) {
            return redirect()->route('admin.events.index')
                ->with('error', 'Unable to join: This event is full.');
        }

        $event->participants()->attach($user->id);

        return redirect()->route('admin.events.index')
            ->with('success', "Welcome! You are now registered as a participant for '{$event->title}'.");
    }




    // User: Join or Leave Event (Toggle)
    public function toggleJoin($id)
    {
        $event = Events::findOrFail($id);
        $user = auth()->user();

        if ($event->isJoinedBy($user->id)) {
            // Leave Event
            $event->participants()->detach($user->id);
            $isJoined = false;
            $message = 'You have left the event.';
        } else {
            // Check Capacity
            if ($event->capacity && $event->participants()->count() >= $event->capacity) {
                return response()->json([
                    'success' => false,
                    'message' => 'This event is already full.'
                ], 422);
            }

            // Join Event
            $event->participants()->attach($user->id);
            $isJoined = true;
            $message = 'You are now a participant!';
        }

        return response()->json([
            'success'     => true,
            'message'     => $message,
            'isJoined'    => $isJoined,
            'total_count' => $event->participants()->count()
        ]);
    }

    // Admin: Show Participants
    public function participants($id)
    {
        $event = Events::with('participants')->findOrFail($id);
        return response()->json([
            'title'        => $event->title,
            'participants' => $event->participants
        ]);
    }
}
