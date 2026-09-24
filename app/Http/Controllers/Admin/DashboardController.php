<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\Prize;
use App\Models\RaffleWinner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Participant Demographics Stats
        $totalRegistrations = Participant::count();
        $maleCount = Participant::where('sex', 'Male')->count();
        $femaleCount = Participant::where('sex', 'Female')->count();
        $districtCount = Participant::whereNotNull('district_division')->distinct('district_division')->count('district_division');
        $municipalityCount = Participant::whereNotNull('municipality')->distinct('municipality')->count('municipality');
        $schoolCount = Participant::whereNotNull('school_office')->distinct('school_office')->count('school_office');

        // Raffle Stats
        $totalPrizesCount = Prize::sum('quantity');
        $totalPrizesTypes = Prize::count();
        $totalWinners = RaffleWinner::count();
        $remainingPrizes = max(0, $totalPrizesCount - $totalWinners);

        // Fetch Top 10 Districts/Divisions with dynamic labels and counts
        $districtStats = Participant::select('district_division', DB::raw('count(*) as total'))
            ->whereNotNull('district_division')
            ->where('district_division', '!=', '')
            ->groupBy('district_division')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $districtLabels = $districtStats->pluck('district_division')->toArray();
        $districtData = $districtStats->pluck('total')->toArray();

        return view('admin.dashboard', compact(
            'totalRegistrations',
            'maleCount',
            'femaleCount',
            'districtCount',
            'municipalityCount',
            'schoolCount',
            'totalPrizesCount',
            'totalPrizesTypes',
            'totalWinners',
            'remainingPrizes',
            'districtLabels',
            'districtData'
        ));
    }
}
