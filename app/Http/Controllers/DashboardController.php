<?php

namespace App\Http\Controllers;

use App\Models\Events;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'admin') {

            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        return redirect()->intended(route('user.dashboard', absolute: false));
    }

    public function userDashboard()
    {
        return view(
            'app.dashboard'
        );
    }


    public function adminDashboard()
    {
        // Total registrations
        $totalRegistrations = Participant::count();

        // Count by sex
        $maleCount = Participant::where('sex', 'Male')->count();
        $femaleCount = Participant::where('sex', 'Female')->count();

        // Count unique districts/divisions
        $districtCount = Participant::distinct('district_division')->count('district_division');

        // Count unique municipalities
        $municipalityCount = Participant::distinct('municipality')->count('municipality');

        // Count unique schools/offices
        $schoolCount = Participant::distinct('school_office')->count('school_office');

        return view('app.dashboard', compact(
            'totalRegistrations',
            'maleCount',
            'femaleCount',
            'districtCount',
            'municipalityCount',
            'schoolCount'
        ));
    }
}
