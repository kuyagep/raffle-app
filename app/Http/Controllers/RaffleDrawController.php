<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Prize;
use App\Models\RaffleWinner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RaffleDrawController extends Controller
{


    public function showDrawPage()
    {
        $participants = Participant::pluck('full_name', 'id');
        $prizes = Prize::latest()->get();
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

        DB::transaction(function () use ($prize, &$winner) {
            $winner = Participant::whereNotIn('id', function ($q) {
                $q->select('participant_id')->from('raffle_winners');
            })
                ->inRandomOrder()
                ->lockForUpdate() // prevents race condition
                ->first();

            if (!$winner) {
                throw new \Exception('No eligible participants left.');
            }

            RaffleWinner::create([
                'participant_id' => $winner->id,
                'prize_id' => $prize->id,
            ]);
        });


        return response()->json([
            'winner' => [
                'full_name' => $winner->full_name,
                'school_office' => $winner->school_office,
                'district_division' => $winner->district_division,
                'municipality' => $winner->municipality,
                'designation' => $winner->designation,
            ],
            'prize' => [
                'name' => $prize->name
            ]
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
