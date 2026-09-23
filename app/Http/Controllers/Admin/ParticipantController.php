<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ParticipantsExport;
use App\Exports\ParticipantTemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\ParticipantsImport;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException as ExcelValidationException;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Participant::query()->with("raffleWinner");


        // Filtering
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%$search%")
                    ->orWhere('school_office', 'like', "%$search%")
                    ->orWhere('district_division', 'like', "%$search%")
                    ->orWhere('municipality', 'like', "%$search%")
                    ->orWhere('sex', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('contact_number', 'like', "%$search%");
            });
        }

        // Paginate results
        $participants = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.participants.index', compact('participants'));
    }


    /**
     * Display the specified resource.
     */
    public function show(Participant $participant)
    {
        return view('admin.participants.show', compact('participant'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Participant $participant)
    {
        $participant->delete();

        return redirect()->route('admin.participants.index')
            ->with('success', 'Participant deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $data = $request->json()->all();
        $ids = $data['ids'] ?? [];

        if (!$ids || count($ids) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'No participants selected.'
            ]);
        }

        Participant::whereIn('id', $ids)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Selected participants deleted successfully.'
        ]);
    }

    public function getAllIds()
    {
        $ids = Participant::pluck('id');
        return response()->json(['ids' => $ids]);
    }

    public function export()
    {
        return Excel::download(new ParticipantsExport, 'participants.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            Excel::import(new ParticipantsImport, $request->file('file'));

            return response()->json([
                'success' => true,
                'message' => 'Participants imported successfully!',
            ]);
        } catch (ExcelValidationException $e) {
            $failures = $e->failures();
            $errors = [];

            foreach ($failures as $failure) {
                $errors[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
            }

            return response()->json([
                'success' => false,
                'errors' => $errors,
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Participants import error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred during import.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }


    public function downloadTemplate()
    {
        return Excel::download(new ParticipantTemplateExport, 'participants_template.xlsx');
    }
}
