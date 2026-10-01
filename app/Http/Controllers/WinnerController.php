<?php

namespace App\Http\Controllers;

use App\Models\Prize;
use App\Models\RaffleWinner;
use App\Models\SchoolRaffleWinner;
use Illuminate\Http\Request;

class WinnerController extends Controller
{
    public function index()
    {
        $winners = RaffleWinner::with(['participant', 'prize'])->latest()->get();
        return view('admin.winners.index', compact('winners'));
    }

    public function updateSelection(Request $request)
    {
        session(['selected_winners' => $request->selected]);
        return response()->json(['success' => true]);
    }

    public function print(Request $request)
    {
        $ids = explode(',', $request->ids);
        $winners = RaffleWinner::with('participant', 'prize')->whereIn('id', $ids)->get();

        return view('admin.winners.print', compact('winners'));
    }

    public function winners(Request $request)
    {
        // Fetch all prizes for the filter dropdown
        $allPrizes = Prize::orderBy('name')->get();

        // Query winners with eager loaded participant and prize relationships
        $query = RaffleWinner::with(['participant', 'prize'])
            ->latest('created_at'); // or latest('id')

        // Apply Prize Filter if selected
        if ($request->filled('prize_id')) {
            $query->where('prize_id', $request->prize_id);
        }

        // Paginate results (12 cards per page fits nicely in a 3-column grid)
        $winners = $query->paginate(4)->withQueryString();

        return view('pages.raffle.winners', compact('winners', 'allPrizes'));
    }
    public function schoolWinners(Request $request)
    {
        // Fetch all prizes for the filter dropdown
        $allPrizes = Prize::orderBy('name')->get();

        // Query winners with eager loaded participant and prize relationships
        $query = SchoolRaffleWinner::with(['school', 'prize'])
            ->latest('created_at'); // or latest('id')

        // Apply Prize Filter if selected
        if ($request->filled('prize_id')) {
            $query->where('prize_id', $request->prize_id);
        }

        // Paginate results (12 cards per page fits nicely in a 3-column grid)
        $winners = $query->paginate(4)->withQueryString();

        return view('pages.raffle.schoolwinners', compact('winners', 'allPrizes'));
    }
}
