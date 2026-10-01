<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = [
        'school_name',
        'district_name',
        'municipality',
        'designation',
        'sex',
        'school_office',
        'email',
        'contact_number',
    ];



    public function schoolRaffleWinner()
    {
        return $this->hasMany(RaffleWinner::class);
    }
}
