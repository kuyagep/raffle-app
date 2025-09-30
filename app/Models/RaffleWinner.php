<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class RaffleWinner extends Model
{
    use HasUlids;

    protected $fillable = ['participant_id', 'prize_id'];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function prize()
    {
        return $this->belongsTo(Prize::class);
    }
}
