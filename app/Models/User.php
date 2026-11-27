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
use NotificationChannels\WebPush\HasPushSubscriptions;

#[Fillable(['name', 'email', 'password', 'role', 'shift', 'phone', 'status', 'google_id', 'avatar'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasPushSubscriptions, Notifiable;

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

    /**
     * Mendapatkan pelatih personal trainer aktif atau terakhir dari member.
     */
    public function getCurrentPtTrainer(): ?self
    {
        // 0. Dari profil member jika ditugaskan langsung
        if ($this->memberProfile?->trainer) {
            return $this->memberProfile->trainer;
        }

        // 1. Dari kuota PT aktif yang memiliki pelatih
        $quotaWithTrainer = $this->ptQuotas()
            ->whereNotNull('trainer_id')
            ->where('status', 'active')
            ->where('remaining_sessions', '>', 0)
            ->with(['trainer.trainerProfile'])
            ->latest('end_date')
            ->first();

        if ($quotaWithTrainer?->trainer) {
            return $quotaWithTrainer->trainer;
        }

        // 2. Dari kuota PT terakhir (meskipun sudah habis / kedaluwarsa)
        $latestQuota = $this->ptQuotas()
            ->whereNotNull('trainer_id')
            ->with(['trainer.trainerProfile'])
            ->latest('created_at')
            ->first();

        if ($latestQuota?->trainer) {
            return $latestQuota->trainer;
        }

        // 3. Dari sesi latihan PT terakhir
        $latestSession = $this->memberSessions()
            ->whereNotNull('trainer_id')
            ->with(['trainer.trainerProfile'])
            ->latest('session_date')
            ->latest('start_time')
            ->first();

        if ($latestSession?->trainer) {
            return $latestSession->trainer;
        }

        // 4. Dari riwayat transaksi pembayaran PT terakhir
        $latestPayment = $this->payments()
            ->whereNotNull('trainer_id')
            ->where('payment_type', 'pt_package')
            ->with(['trainer.trainerProfile'])
            ->latest('created_at')
            ->first();

        return $latestPayment?->trainer;
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

    public function getAvatarUrlAttribute(): ?string
    {
        $avatar = $this->avatar;

        if (! $avatar) {
            return null;
        }

        if (str_starts_with($avatar, 'http://') || str_starts_with($avatar, 'https://')) {
            return $avatar;
        }

        return asset('storage/'.$avatar);
    }

    public function hasProfilePhoto(): bool
    {
        return ! empty($this->avatar);
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
