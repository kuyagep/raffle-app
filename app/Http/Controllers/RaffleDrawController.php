<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Prize;
use App\Models\RaffleWinner;
use Illuminate\Http\Request;

class RaffleDrawController extends Controller
{
    public function draw(Prize $prize)
    {
        // Count how many winners already selected for this prize
        $winnersCount = $prize->winners()->count();

        if ($winnersCount >= $prize->quantity) {
            return back()->with('error', 'All prizes already drawn for ' . $prize->name);
        }

        // Get participants not yet winners of this prize
        $eligible = Participant::whereNotIn('id', $prize->winners()->pluck('participant_id'))->get();

        if ($eligible->isEmpty()) {
            return back()->with('error', 'No more participants available for drawing.');
        }

        $winner = $eligible->random();

        RaffleWinner::create([
            'participant_id' => $winner->id,
            'prize_id' => $prize->id,
        ]);

        return back()->with('success', 'Winner selected: ' . $winner->full_name);
    }

    public function showDrawPage()
    {
        $participants = Participant::pluck('full_name', 'id');
        $prizes = Prize::all();
        $recentWinners = RaffleWinner::with(['participant', 'prize'])
            ->latest()
            ->take(5)
            ->get();

        return view('pages.raffle.draw', compact('participants', 'prizes', 'recentWinners'));
    }

    public function startDraw(Request $request)
    {
        $request->validate([
            'prize_id' => 'required|exists:prizes,id',
        ]);

        $prize = Prize::findOrFail($request->prize_id);

        // Check if prize still has quantity left
        if ($prize->quantity <= $prize->winners()->count()) {
            return response()->json(['error' => 'All winners already drawn for this prize.'], 422);
        }

        // Pick a random participant who hasn't won this prize yet
        $winner = Participant::whereNotIn('id', function ($q) use ($prize) {
            $q->select('participant_id')
                ->from('raffle_winners')
                ->where('prize_id', $prize->id);
        })->inRandomOrder()->first();

        if (!$winner) {
            return response()->json(['error' => 'No eligible participants left.'], 422);
        }

        // Save winner
        $raffleWinner = RaffleWinner::create([
            'participant_id' => $winner->id,
            'prize_id' => $prize->id,
        ]);

        return response()->json([
            'winner' => $winner,
            'prize' => $prize,
        ]);
    }

    public function recentWinners()
    {
        $recentWinners = RaffleWinner::with(['participant', 'prize'])
            ->latest()
            ->take(5)
            ->get();

        return response()->json($recentWinners);
    }

    public function list()
    {
        $participants = \App\Models\Participant::select('id', 'full_name')->get();
        return response()->json($participants);
    }

    public function checkPrize(Request $request)
    {
        $prize = Prize::findOrFail($request->prize_id);
        $remaining = $prize->quantity - $prize->winners()->count();

        return response()->json([
            'remaining' => $remaining
        ]);
    }

    public function prizesRemaining()
    {
        $prizes = Prize::all()->map(function ($prize) {
            return [
                'id' => $prize->id,
                'name' => $prize->name,
                'remaining' => $prize->quantity - $prize->winners()->count(),
            ];
        });

        return response()->json($prizes);
    }
}
