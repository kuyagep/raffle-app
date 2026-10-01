<?php

namespace App\Http\Controllers;

use App\Imports\SchoolsImport;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class SchoolController extends Controller
{
    /**
     * Display a listing of schools.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $schools = School::when($search, function ($query, $search) {
            return $query->where('school_name', 'like', "%{$search}%")
                ->orWhere('district_name', 'like', "%{$search}%")
                ->orWhere('municipality', 'like', "%{$search}%")
                ->orWhere('school_office', 'like', "%{$search}%");
        })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.schools.index', compact('schools', 'search'));
    }

    /**
     * Show the form for creating a new school.
     */
    public function create()
    {
        return view('admin.schools.create');
    }

    /**
     * Store a newly created school in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_name'    => 'required|string|max:255|unique:schools,school_name',
            'district_name'  => 'required|string|max:255',
            'municipality'   => 'required|string|max:255',
            'designation'    => 'nullable|string|max:255',
            'school_office'  => 'nullable|string|max:255',
            'sex'            => 'nullable|in:Male,Female',
            'email'          => 'nullable|email|max:255|unique:schools,email',
            'contact_number' => 'nullable|string|max:50',
        ]);

        School::create($validated);

        return redirect()->route('admin.schools.index')->with('status', 'School created successfully!');
    }

    /**
     * Display the specified school details.
     */
    public function show(School $school)
    {
        return view('admin.schools.show', compact('school'));
    }

    /**
     * Show the form for editing the specified school.
     */
    public function edit(School $school)
    {
        return view('admin.schools.edit', compact('school'));
    }

    /**
     * Update the specified school in storage.
     */
    public function update(Request $request, School $school)
    {
        $validated = $request->validate([
            'school_name'    => ['required', 'string', 'max:255', Rule::unique('schools')->ignore($school->id)],
            'district_name'  => 'required|string|max:255',
            'municipality'   => 'required|string|max:255',
            'designation'    => 'nullable|string|max:255',
            'school_office'  => 'nullable|string|max:255',
            'sex'            => 'nullable|in:Male,Female',
            'email'          => ['nullable', 'email'],
            'contact_number' => 'nullable|string|max:50',
        ]);

        $school->update($validated);

        return redirect()->route('admin.schools.index')->with('status', 'School details updated successfully!');
    }

    /**
     * Remove the specified school from storage.
     */
    public function destroy(School $school)
    {
        $school->delete();

        return redirect()->route('admin.schools.index')->with('status', 'School deleted successfully!');
    }

    /**
     * Import schools from CSV/Excel file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120', // Max 5MB
        ]);

        try {
            Excel::import(new SchoolsImport, $request->file('file'));

            return redirect()->route('admin.schools.index')
                ->with('status', 'Schools imported successfully!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];

            foreach ($failures as $failure) {
                $errors[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
            }

            return redirect()->back()->with('import_errors', $errors);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }

    /**
     * Download sample CSV template for schools import.
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="schools_import_template.csv"',
        ];

        $columns = [
            'school_name',
            'district_name',
            'municipality',
            'designation',
            'school_office',
            'sex',
            'email',
            'contact_number',
        ];

        $sampleRow = [
            'Central Elementary School',
            'District I',
            'Digos City',
            'Principal I',
            'DepEd Central',
            'Female',
            'central@deped.gov.ph',
            '09123456789',
        ];

        $callback = function () use ($columns, $sampleRow) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fputcsv($file, $sampleRow);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
