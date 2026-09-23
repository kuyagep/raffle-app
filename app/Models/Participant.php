<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Participant extends Model
{
    use HasFactory, HasUlids;


    protected $fillable = [
        'district_division',
        'municipality',
        'full_name',
        'designation',
        'sex',
        'school_office',
        'email',
        'contact_number',
        'qr_code',
    ];


    // Relation: Participant has many attendances
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    public function raffleWinner()
    {
        return $this->hasMany(RaffleWinner::class);
    }
}
