<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUlids;



    protected $fillable = [
        'office_id',
        'firstname',
        'lastname',
        'position',
        'sex',
        'contact_number',
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

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

    /**
     * Relationship: User belongs to an Office/School
     */
    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    /**
     * Relationship: Events joined by the user (as a participant)
     */
    public function events()
    {
        return $this->belongsToMany(Events::class, 'event_participants')
            ->withTimestamps();
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    public function joinedEvents()
    {
        return $this->belongsToMany(Events::class, 'event_user', 'user_id', 'event_id')->withTimestamps();
    }
}
