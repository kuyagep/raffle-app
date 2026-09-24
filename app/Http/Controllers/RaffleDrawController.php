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

        return view('pages.raffle.livedraw', compact('participants', 'prizes', 'recentWinners'));
    }

    // Start the draw
    public function startDraw(Request $request)
    {
        $request->validate([
            'prize_id' => 'required|exists:prizes,id',
        ]);

        $prize = Prize::findOrFail($request->prize_id);

        if ($prize->quantity <= $prize->winners()->count()) {
            return response()->json(['error' => 'All winners already drawn for this prize.'], 422);
        }

        return $this->pickWinner($prize);
    }

    // Redraw if winner didn't claim
    public function redraw(Request $request)
    {
        $request->validate([
            'prize_id' => 'required|exists:prizes,id',
            'old_winner_id' => 'required|exists:participants,id',
        ]);

        $prize = Prize::findOrFail($request->prize_id);

        /// Find and delete the old winner record for this prize
        $oldWinner = $prize->winners()
            ->where('participant_id', $request->old_winner_id)
            ->first();

        if ($oldWinner) {
            $oldWinner->delete();
        }

        return $this->pickWinner($prize);
    }

    // Common winner selection logic
    private function pickWinner(Prize $prize)

    {

        $winner = null;



        DB::transaction(function () use ($prize, &$winner) {

            $winner = Participant::whereNotIn('id', function ($q) {
                $q->select('participant_id')->from('raffle_winners');
            })->where('municipality', 'NOT LIKE', '%Division Office%')
                ->where('designation', 'Teaching (Permanent)')
                ->inRandomOrder()
                ->lockForUpdate()
                ->first();

            if (!$winner) {
                throw new \Exception('No eligible participants left.');
            }


            RaffleWinner::create([
                'participant_id' => $winner->id,
                'prize_id'       => $prize->id,
            ]);
        });



        if (!$winner) {
            return response()->json(['error' => 'No eligible participants left.'], 422);
        }

        return response()->json([
            'winner' => [
                'id' => $winner->id,  // ✅ add this
                'full_name'         => $winner->full_name,
                'school_office'     => $winner->school_office,
                'district_division' => $winner->district_division,
                'municipality'      => $winner->municipality,
                'designation'       => $winner->designation,
            ],

            'prize' => [
                'id' => $prize->id,   // ✅ add this
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
