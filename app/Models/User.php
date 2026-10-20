<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'phone', 'status', 'google_id', 'avatar'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'status',
        'google_id',
        'avatar',
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
            'email_verified_at' => 'datetime',
        ];
    }

    public function memberProfile(): HasOne
    {
        return $this->hasOne(MemberProfile::class);
    }

    public function trainerProfile(): HasOne
    {
        return $this->hasOne(TrainerProfile::class);
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class, 'member_id');
    }

    public function ptQuotas(): HasMany
    {
        return $this->hasMany(MemberPtQuota::class, 'member_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'member_id');
    }

    public function checkIns(): HasMany
    {
        return $this->hasMany(CheckIn::class, 'member_id');
    }

    public function memberSessions(): HasMany
    {
        return $this->hasMany(PtSession::class, 'member_id');
    }

    public function trainerSessions(): HasMany
    {
        return $this->hasMany(PtSession::class, 'trainer_id');
    }

    public function givenRatings(): HasMany
    {
        return $this->hasMany(Rating::class, 'member_id');
    }

    public function receivedRatings(): HasMany
    {
        return $this->hasMany(Rating::class, 'trainer_id');
    }

    // Native Role Authorization Helpers (No Spatie)
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTrainer(): bool
    {
        return $this->role === 'trainer';
    }

    public function isMember(): bool
    {
        return $this->role === 'member';
    }
}
