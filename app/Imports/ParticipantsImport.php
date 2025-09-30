<?php

namespace App\Imports;

use App\Models\Participant;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ParticipantsImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    public function model(array $row)
    {
        return new Participant([
            'district_division' => $row['district_division'] ?? null,
            'municipality'      => $row['municipality'] ?? null,
            'full_name'         => $row['full_name'] ?? null,
            'designation'       => $row['designation'] ?? null,
            'sex'               => $row['sex'] ?? null,
            'school_office'     => $row['school_office'] ?? null,
            'email'             => $row['email'] ?? null,
            'contact_number'    => $row['contact_number'] ?? null,
            'qr_code'           => uniqid(),
        ]);
    }

    public function rules(): array
    {
        return [
            '*.district_division' => ['required', 'string', 'max:255'],
            '*.municipality'      => ['required', 'string', 'max:255'],
            '*.full_name'         => ['required', 'string', 'max:255'],
            '*.sex'               => ['required', Rule::in(['Male', 'Female'])],
            '*.designation'       => ['nullable', 'string', 'max:255'],
            '*.school_office'     => ['nullable', 'string', 'max:255'],
            '*.email'             => ['nullable', 'email'],
            '*.contact_number'    => ['nullable', 'string', 'max:20'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.district_division.required' => 'District / Division is required.',
            '*.municipality.required'      => 'Municipality is required.',
            '*.full_name.required'         => 'Full Name is required.',
            '*.sex.required'               => 'Sex is required.',
            '*.sex.in'                     => 'Sex must be either Male or Female.',
            '*.email.email'                => 'The email must be a valid email address.',
        ];
    }
}
