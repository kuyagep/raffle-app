<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolRaffleWinner extends Model
{
    protected $fillable = ['school_id', 'prize_id'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function prize()
    {
        return $this->belongsTo(Prize::class);
    }
}
