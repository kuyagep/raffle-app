<?php

namespace App\Imports;

use App\Models\Participant;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ParticipantsImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    /**
     * Define which row contains the column headers.
     * Matches Row 1 from ParticipantTemplateExport.
     */
    public function headingRow(): int
    {
        return 1;
    }

    /**
     * Map each row to the Participant model.
     */
    public function model(array $row)
    {
        // Avoid inserting blank rows
        if (empty(array_filter($row))) {
            return null;
        }

        return Participant::updateOrCreate(
            [
                'full_name' => trim($row['full_name']),
            ],
            [
                'id'                => strtolower(Str::ulid()),
                'district_division' => $row['district_division'],
                'municipality'      => $row['municipality'],
                'designation'       => $row['designation'] ?? null,
                'sex'               => $row['sex'] ?? null,
                'school_office'     => $row['school_office'] ?? null,
                'email'             => $row['email'] ?? null,
                'contact_number'    => $row['contact_number'] ?? null,
                'qr_code'           => uniqid(),
            ]
        );
    }

    /**
     * Validation rules matching export schema.
     */
    public function rules(): array
    {
        return [
            '*.district_division' => ['required', 'string', 'max:255'],
            '*.municipality'      => ['required', 'string', 'max:255'],
            '*.full_name'         => ['required', 'string', 'max:255'],
            '*.sex'               => ['required', Rule::in(['Male', 'Female'])],
            '*.designation'       => ['nullable', 'string', 'max:255'],
            '*.school_office'     => ['nullable', 'string', 'max:255'],
            '*.email'             => ['nullable', 'email', 'max:255'],
            '*.contact_number'    => ['nullable', 'max:255'],
        ];
    }

    /**
     * Custom error messages for row validation failures.
     */
    public function customValidationMessages(): array
    {
        return [
            '*.district_division.required' => 'District / Division is required.',
            '*.municipality.required'      => 'Municipality is required.',
            '*.full_name.required'         => 'Full Name is required.',
            '*.sex.required'               => 'Sex is required.',
            '*.sex.in'                     => 'Sex must be either Male or Female.',
            '*.email.email'                => 'The email address must be valid.',
        ];
    }
}
