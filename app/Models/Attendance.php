<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasUlids;

    protected $fillable = [
        'participant_id',
        'event_name',
        'scanned_at',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    // Relation: Attendance belongs to a participant
    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }
}
