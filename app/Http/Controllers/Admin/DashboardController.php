<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
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

        return view('admin.dashboard', compact(
            'totalRegistrations',
            'maleCount',
            'femaleCount',
            'districtCount',
            'municipalityCount',
            'schoolCount'
        ));
    }
}
