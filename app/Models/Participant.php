<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
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

    protected $keyType = 'string';

    public $incrementing = false;

    protected static function boot()
    {


        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) strtolower(Str::ulid());
            }
        });
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
}
