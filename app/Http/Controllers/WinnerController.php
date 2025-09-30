<?php

namespace App\Http\Controllers;

use App\Models\Prize;
use App\Models\RaffleWinner;
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

    public function winners()
    {
        $prizes = Prize::with(['winners.participant'])->get();

        return view('pages.raffle.winners', compact('prizes'));
    }
}
