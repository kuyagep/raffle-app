<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Prize extends Model
{
    use HasUlids;

    protected $fillable = ['name', 'quantity'];

    public function winners()
    {
        return $this->hasMany(RaffleWinner::class);
    }
    public function schoolRaffleWinners()
    {
        return $this->hasMany(RaffleWinner::class);
    }
}
