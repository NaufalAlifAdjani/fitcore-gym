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

#[Fillable(['name', 'email', 'password', 'role', 'shift', 'phone', 'status', 'google_id', 'avatar'])]
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
        'shift',
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

    public function latestMembership(): HasOne
    {
        return $this->hasOne(Membership::class, 'member_id')->latestOfMany();
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

    public function getShiftLabelAttribute(): string
    {
        return match ($this->shift) {
            'sore' => 'Sore',
            'fleksibel' => 'Fleksibel',
            default => 'Pagi',
        };
    }

    public function getShiftHoursTextAttribute(): string
    {
        return match ($this->shift) {
            'sore' => '14:00 – 22:00 WIB',
            'fleksibel' => '06:00 – 22:00 WIB',
            default => '06:00 – 14:00 WIB',
        };
    }

    /**
     * Mendapatkan ringkasan jadwal kerja rutin untuk tampilan kartu PT berdasarkan shift.
     *
     * @return array{days_label: string, periods: array<string>, periods_label: string, has_custom_schedule: bool}
     */
    public function getWorkingScheduleSummary(): array
    {
        $shift = $this->shift ?? 'pagi';

        return match ($shift) {
            'sore' => [
                'days_label' => 'Setiap Hari',
                'periods' => ['afternoon', 'evening'],
                'periods_label' => 'Sore / Malam (14:00 – 22:00)',
                'has_custom_schedule' => false,
            ],
            'fleksibel' => [
                'days_label' => 'Setiap Hari',
                'periods' => ['morning', 'afternoon', 'evening'],
                'periods_label' => 'Fleksibel (06:00 – 22:00)',
                'has_custom_schedule' => false,
            ],
            default => [
                'days_label' => 'Setiap Hari',
                'periods' => ['morning'],
                'periods_label' => 'Pagi (06:00 – 14:00)',
                'has_custom_schedule' => false,
            ],
        };
    }

    // Native Role Authorization Helpers (No Spatie)
    public function getIsVerifiedAttribute(): bool
    {
        return $this->hasVerifiedEmail();
    }

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

    /**
     * Memeriksa apakah biodata profil member sudah lengkap (nama, nomor telepon, dan alamat).
     */
    public function isProfileComplete(): bool
    {
        if ($this->role !== 'member') {
            return true;
        }

        $hasPhone = ! empty(trim((string) ($this->phone ?? '')));
        $profile = $this->memberProfile ?? $this->memberProfile()->first();
        $hasAddress = ! empty(trim((string) ($profile?->address ?? '')));

        return $hasPhone && $hasAddress;
    }

    /**
     * Check if user has a specific role or matches any of the given roles.
     *
     * @param  string|array<int, string>  $roles
     */
    public function hasRole(string|array $roles): bool
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles, true);
        }

        return $this->role === $roles;
    }

    /**
     * Check if user has any of the given roles.
     *
     * @param  array<int, string>  $roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles, true);
    }
}
