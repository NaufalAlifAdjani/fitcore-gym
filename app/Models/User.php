<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function memberProfile()
    {
        return $this->hasOne(MemberProfile::class);
    }

    public function trainerProfile()
    {
        return $this->hasOne(TrainerProfile::class);
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class, 'member_id');
    }

    public function ptQuotas()
    {
        return $this->hasMany(MemberPtQuota::class, 'member_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'member_id');
    }

    public function checkIns()
    {
        return $this->hasMany(CheckIn::class, 'member_id');
    }

    public function memberSessions()
    {
        return $this->hasMany(PtSession::class, 'member_id');
    }

    public function trainerSessions()
    {
        return $this->hasMany(PtSession::class, 'trainer_id');
    }

    public function givenRatings()
    {
        return $this->hasMany(Rating::class, 'member_id');
    }

    public function receivedRatings()
    {
        return $this->hasMany(Rating::class, 'trainer_id');
    }

    public function isTrainer(): bool
    {
        return $this->role === 'trainer';
    }

    public function isMember(): bool
    {
        return $this->role === 'member';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
