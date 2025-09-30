<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ParticipantTemplateExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Optional: include one example row to show sample format (users can delete)
        return new Collection([
            [
                'district_division' => 'District 1',
                'municipality'      => 'Digos',
                'full_name'         => 'Juan Dela Cruz',
                'designation'       => 'Teacher I',
                'sex'               => 'Male',
                'school_office'     => 'XYZ Elementary School',
                'email'             => 'juan@example.com',
                'contact_number'    => '09171234567',
            ],
        ]);
    }

    public function headings(): array
    {
        // these headings must match what your import expects (snake_case, exact keys)
        return [
            'district_division',
            'municipality',
            'full_name',
            'designation',
            'sex',
            'school_office',
            'email',
            'contact_number',
        ];
    }
}
