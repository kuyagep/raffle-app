<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\Prize;
use App\Models\RaffleWinner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function edit(Prize $prize)
    {
        if ($prize->winners()->exists()) {
            return redirect()->route('admin.prizes.index')
                ->with('error', 'This prize already has winners and cannot be edited.');
        }

        return view('admin.prizes.edit', compact('prize'));
    }

    public function update(Request $request, Prize $prize)
    {
        if ($prize->winners()->exists()) {
            return redirect()->route('admin.prizes.index')
                ->with('error', 'This prize already has winners and cannot be updated.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
        ]);

        $prize->update($request->only('name', 'quantity'));

        return redirect()->route('admin.prizes.index')->with('success', 'Prize updated successfully.');
    }

    public function destroy(Prize $prize)
    {
        if ($prize->winners()->exists()) {
            return redirect()->route('admin.prizes.index')
                ->with('error', 'This prize already has winners and cannot be deleted.');
        }

        $prize->delete();
        return redirect()->route('admin.prizes.index')->with('success', 'Prize deleted successfully.');
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

    /**
     * Advanced Pre-Draw with filters: Municipality, Position, and Custom Count.
     */
    public function preDraw(Request $request, Prize $prize)
    {
        $winnersCount = $prize->winners()->count();

        if ($winnersCount >= $prize->quantity) {
            return back()->with('error', 'All winners already drawn for this prize.');
        }

        // Validate pre-draw request options
        $request->validate([
            'municipality' => 'nullable|string',
            'position'     => 'nullable|string',
            'count'        => 'required|integer|min:1',
        ]);

        $maxAllowed = $prize->quantity - $winnersCount;
        $requestedCount = (int) $request->input('count');

        // Ensure we don't draw more than remaining prize quota
        $remainingQuantity = min($requestedCount, $maxAllowed);

        // Get IDs of participants who already won ANY prize
        $alreadyWinners = RaffleWinner::pluck('participant_id');

        // Default excluded divisions (if applicable)
        $excludedDivisions = [
            'Division Office'
        ];

        // Base Query: Exclude previous winners & excluded divisions
        $query = Participant::whereNotIn('id', $alreadyWinners)
            ->whereNotIn('district_division', $excludedDivisions);

        // Filter by Municipality / Exclusions
        if ($request->filled('municipality')) {
            if ($request->municipality === 'all_except_division') {
                // Exclude Division Office from draw
                $query->where('municipality', '!=', 'Division Office');
            } elseif ($request->municipality !== 'all') {
                // Specific municipality chosen
                $query->where('municipality', $request->municipality);
            }
        }

        // Filter by Position/Designation if specific one selected
        if ($request->filled('position') && $request->position !== 'all') {
            $query->where('designation', $request->position);
        }

        $eligible = $query->get();

        if ($eligible->isEmpty()) {
            return back()->with('error', 'No eligible participants found matching the selected criteria.');
        }

        // Determine grouping attribute (group by Municipality if "all" selected, otherwise group by Division)
        $groupKey = ($request->municipality === 'all' || !$request->filled('municipality'))
            ? 'municipality'
            : 'district_division';

        // Run raffle inside database transaction
        $drawnWinners = DB::transaction(function () use ($eligible, $groupKey, $prize, $remainingQuantity) {
            return $this->distributeWinners($eligible, $groupKey, $prize, $remainingQuantity, true);
        });

        return back()->with('success', 'Pre-Draw winners drawn (' . count($drawnWinners) . '): ' . implode(', ', $drawnWinners));
    }

    /**
     * Helper method to fairly distribute prize draws across a grouped attribute randomly.
     */
    private function distributeWinners($eligibleParticipants, string $groupKey, Prize $prize, int $remaining, bool $includeGroupInName = false): array
    {
        $grouped = $eligibleParticipants->groupBy($groupKey);
        $drawnWinners = [];

        while ($remaining > 0 && $grouped->isNotEmpty()) {
            // 1. Get current active group keys and shuffle them randomly for this round
            $groupKeys = $grouped->keys()->shuffle();

            foreach ($groupKeys as $groupName) {
                if ($remaining <= 0) {
                    break;
                }

                $participants = $grouped->get($groupName);

                // Skip and remove if empty
                if (!$participants || $participants->isEmpty()) {
                    $grouped->forget($groupName);
                    continue;
                }

                // 2. Pick a random winner from this group
                $winner = $participants->random();

                RaffleWinner::create([
                    'participant_id' => $winner->id,
                    'prize_id'       => $prize->id,
                ]);

                $drawnWinners[] = $includeGroupInName
                    ? "{$winner->full_name} ({$groupName})"
                    : $winner->full_name;

                $remaining--;

                // 3. Remove winner from the group pool
                $updatedGroup = $participants->reject(fn($p) => $p->id === $winner->id);

                if ($updatedGroup->isEmpty()) {
                    $grouped->forget($groupName);
                } else {
                    $grouped->put($groupName, $updatedGroup);
                }
            }
        }

        return $drawnWinners;
    }
}
