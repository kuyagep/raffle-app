<?php

namespace App\Imports;

use App\Models\School;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SchoolsImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    /**
     * Map each row to the School Model
     */
    public function model(array $row)
    {
        return new School([
            'school_name'    => trim($row['school_name']),
            'district_name'  => trim($row['district_name']),
            'municipality'   => trim($row['municipality']),
            'designation'    => isset($row['designation']) ? trim($row['designation']) : null,
            'school_office'  => isset($row['school_office']) ? trim($row['school_office']) : null,
            'sex'            => isset($row['sex']) ? ucfirst(strtolower(trim($row['sex']))) : null,
            'email'          => !empty($row['email']) ? trim($row['email']) : null,
            'contact_number' => !empty($row['contact_number']) ? trim($row['contact_number']) : null,
        ]);
    }

    /**
     * Row Validation Rules
     */
    public function rules(): array
    {
        return [
            '*.school_name'   => 'required|string|unique:schools,school_name',
            '*.district_name' => 'required|string',
            '*.municipality'  => 'required|string',
            '*.sex'           => 'nullable|in:Male,Female,male,female',
            '*.email'         => 'nullable|email|unique:schools,email',
        ];
    }

    /**
     * Custom Attribute Names for Validation Errors
     */
    public function customValidationAttributes(): array
    {
        return [
            'school_name'   => 'School Name',
            'district_name' => 'District Name',
            'municipality'  => 'Municipality',
        ];
    }
}
