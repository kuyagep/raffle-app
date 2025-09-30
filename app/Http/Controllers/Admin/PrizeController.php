<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\Prize;
use App\Models\RaffleWinner;
use Illuminate\Http\Request;

class PrizeController extends Controller
{
    public function index()
    {
        $prizes = Prize::with('winners')->get();
        return view('admin.prizes.index', compact('prizes'));
    }

    public function create()
    {
        return view('admin.prizes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
        ]);

        Prize::create($request->only('name', 'quantity'));

        return redirect()->route('admin.prizes.index')->with('success', 'Prize added successfully.');
    }

    public function draw(Prize $prize)
    {
        $winnersCount = $prize->winners()->count();

        if ($winnersCount >= $prize->quantity) {
            return back()->with('error', 'All winners already drawn for this prize.');
        }

        // Get ALL participants who already won ANY prize
        $alreadyWinners = RaffleWinner::pluck('participant_id')->toArray();

        // Eligible = never won any prize yet
        $eligible = Participant::whereNotIn('id', $alreadyWinners)->get();

        if ($eligible->isEmpty()) {
            return back()->with('error', 'No eligible participants left.');
        }

        // Group participants by municipality
        $grouped = $eligible->groupBy('municipality');

        $remaining = $prize->quantity - $winnersCount;
        $drawnWinners = [];

        // Distribute prizes in rounds until we run out
        while ($remaining > 0 && $grouped->isNotEmpty()) {
            foreach ($grouped as $municipality => $participants) {
                if ($remaining <= 0) break;

                if ($participants->isEmpty()) {
                    $grouped->forget($municipality); // no more participants here
                    continue;
                }

                // Random winner from this municipality
                $winner = $participants->random();

                RaffleWinner::create([
                    'participant_id' => $winner->id,
                    'prize_id'      => $prize->id,
                ]);

                $drawnWinners[] = $winner->full_name;
                $remaining--;

                // Remove this winner (can’t win again)
                $grouped[$municipality] = $participants->reject(fn($p) => $p->id === $winner->id);

                // If no one left in this municipality, drop it
                if ($grouped[$municipality]->isEmpty()) {
                    $grouped->forget($municipality);
                }
            }
        }

        return back()->with('success', 'Winners drawn: ' . implode(', ', $drawnWinners));
    }
}
