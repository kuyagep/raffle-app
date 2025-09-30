<?php

namespace App\Exports;

use App\Models\Participant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ParticipantsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Participant::select(
            'district_division',
            'municipality',
            'full_name',
            'designation',
            'sex',
            'school_office',
            'email',
            'contact_number',
        )->get();
    }

    public function headings(): array
    {
        return [
            'District / Division',
            'Municipality',
            'Full Name',
            'Designation',
            'Sex',
            'School / Office Name',
            'Email',
            'Contact Number',
        ];
    }
}
