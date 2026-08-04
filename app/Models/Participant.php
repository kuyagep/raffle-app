<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Participant extends Model
{
    use HasFactory, HasUlids;


    protected $fillable = [
        'office_id',
        'firstname',
        'lastname',
        'position',
        'sex',
        'email',
        'contact_number',
        'qr_code',
    ];

    /**
     * Include 'full_name' automatically when converting model to Array or JSON.
     */
    protected $appends = ['full_name'];

    /**
     * Get the participant's full name.
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn() => trim("{$this->firstname} {$this->lastname}")
        );
    }

    // Relation: Participant has many attendances
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    public function raffleWinner()
    {
        return $this->hasMany(RaffleWinner::class);
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }
}
