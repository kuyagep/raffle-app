<?php

namespace App\Http\Controllers;

use App\Models\Prize;
use App\Models\School;
use App\Models\SchoolRaffleWinner;
use Illuminate\Http\Request;

class SchoolRaffleController extends Controller
{

    /**
     * Display a listing of school winners for Admin view.
     */
    public function indexWinners(Request $request)
    {
        $query = SchoolRaffleWinner::with(['school', 'prize'])
            ->latest('created_at');

        // Filter by Prize if selected
        if ($request->filled('prize_id')) {
            $query->where('prize_id', $request->prize_id);
        }

        $winners = $query->paginate(15);
        $prizes = Prize::all(); // Fetches all prizes for the dropdown filter

        return view('admin.winners.school', compact('winners', 'prizes'));
    }

    /**
     * Show Live Draw Page
     */
    public function liveDraw()
    {
        $prizes = Prize::where('quantity', '>', 0)->get();
        $recentWinners = SchoolRaffleWinner::with(['school', 'prize'])
            ->latest()
            ->take(6)
            ->get();

        // return view('pages.raffle.school_live', compact('prizes', 'recentWinners'));
        return view('pages.raffle.liveschool', compact('prizes', 'recentWinners'));
    }

    /**
     * Draw a random winning school (Excludes previous winners)
     */
    public function drawWinner(Request $request)
    {
        $request->validate([
            'prize_id' => 'required|exists:prizes,id'
        ]);

        $prize = Prize::findOrFail($request->prize_id);

        if ($prize->quantity <= 0) {
            return response()->json(['error' => 'Selected prize is out of stock!'], 400);
        }

        // 1. Get IDs of schools that have already won ANY prize
        $winningSchoolIds = SchoolRaffleWinner::pluck('school_id')->toArray();

        // 2. Count winners per municipality FOR THIS SPECIFIC PRIZE
        $prizeWinnersPerMunicipality = SchoolRaffleWinner::where('prize_id', $prize->id)
            ->join('schools', 'school_raffle_winners.school_id', '=', 'schools.id')
            ->selectRaw('schools.municipality, count(*) as total_winners')
            ->groupBy('schools.municipality')
            ->pluck('total_winners', 'municipality')
            ->toArray();

        // 3. Find municipalities that still have eligible (unpicked) schools
        $eligibleMunicipalities = School::whereNotIn('id', $winningSchoolIds)
            ->distinct()
            ->pluck('municipality');

        if ($eligibleMunicipalities->isEmpty()) {
            return response()->json(['error' => 'No eligible schools remaining for the draw! All schools have won.'], 400);
        }

        // 4. Map each eligible municipality with its winner count for THIS prize
        $municipalityStats = $eligibleMunicipalities->map(function ($municipality) use ($prizeWinnersPerMunicipality) {
            return [
                'municipality' => $municipality,
                'count'        => $prizeWinnersPerMunicipality[$municipality] ?? 0,
            ];
        });

        // 5. Find the lowest winner count for this prize among eligible municipalities
        $minWinners = $municipalityStats->min('count');

        // 6. Get all municipalities tied for the lowest count for this prize & randomly pick one
        $targetMunicipality = $municipalityStats
            ->where('count', $minWinners)
            ->pluck('municipality')
            ->shuffle()
            ->first();

        // 7. Pick 1 random unpicked school from the selected tied municipality
        $winnerSchool = School::where('municipality', $targetMunicipality)
            ->whereNotIn('id', $winningSchoolIds)
            ->inRandomOrder()
            ->first();

        if (!$winnerSchool) {
            return response()->json(['error' => 'No eligible school found in the selected municipality!'], 400);
        }

        // 8. Record the winner and decrement prize stock
        $winner = SchoolRaffleWinner::create([
            'school_id' => $winnerSchool->id,
            'prize_id'  => $prize->id,
            'won_at'    => now(),
        ]);

        $prize->decrement('quantity');

        return response()->json([
            'success'      => true,
            'winner'       => $winnerSchool,
            'prize'        => $prize,
            'municipality' => $targetMunicipality,
        ]);
    }

    public function redrawWinner(Request $request)
    {
        $request->validate([
            'prize_id'      => 'required|exists:prizes,id',
            'old_winner_id' => 'required'
        ]);

        // Delete or soft delete previous winning entry
        SchoolRaffleWinner::where('school_id', $request->old_winner_id)
            ->where('prize_id', $request->prize_id)
            ->delete();

        // Call draw logic to select the replacement winner
        return $this->drawWinner($request);
    }

    public function getEligibleSchools()
    {
        $winningSchoolIds = SchoolRaffleWinner::pluck('school_id')->toArray();

        $schools = School::whereNotIn('id', $winningSchoolIds)
            ->select('id', 'school_name', 'district_name', 'municipality')
            ->get();

        return response()->json($schools);
    }
    /**
     * Fetch recent school winners via AJAX
     */
    public function getRecentWinners()
    {
        $recentWinners = SchoolRaffleWinner::with(['school', 'prize'])
            ->latest()
            ->take(6)
            ->get();

        return response()->json($recentWinners);
    }


    public function print(Request $request)
    {
        $query = SchoolRaffleWinner::with(['school', 'prize']);

        // Check if specific winner IDs were passed via request
        if ($request->filled('ids')) {
            $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
            $query->whereIn('id', array_filter($ids));
        } elseif ($request->filled('prize_id')) {
            // Optional: Support printing filtered by prize
            $query->where('prize_id', $request->prize_id);
        }

        $winners = $query->latest('won_at')->get();

        return view('admin.winners.print_school', compact('winners'));
    }
}
