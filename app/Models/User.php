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

    public function trainerAvailabilities(): HasMany
    {
        return $this->hasMany(TrainerAvailability::class, 'trainer_id');
    }

    public function trainerDayOffs(): HasMany
    {
        return $this->hasMany(TrainerDayOff::class, 'trainer_id');
    }

    /**
     * Mendapatkan ringkasan jadwal kerja rutin untuk tampilan kartu PT.
     *
     * @return array{days_label: string, periods: array<string>, periods_label: string, has_custom_schedule: bool}
     */
    public function getWorkingScheduleSummary(): array
    {
        $availabilities = $this->relationLoaded('trainerAvailabilities')
            ? $this->trainerAvailabilities
            : $this->trainerAvailabilities()->get();

        if ($availabilities->isEmpty()) {
            return [
                'days_label' => 'Senin – Sabtu',
                'periods' => ['morning', 'afternoon', 'evening'],
                'periods_label' => 'Pagi – Malam (07:00 – 21:00)',
                'has_custom_schedule' => false,
            ];
        }

        $activeDays = $availabilities->where('is_active', true)->sortBy(function ($item) {
            // Urutkan dari Senin (1) sampai Minggu (0 diposisikan 7)
            return $item->day_of_week === 0 ? 7 : $item->day_of_week;
        });

        if ($activeDays->isEmpty()) {
            return [
                'days_label' => 'Sedang Tidak Aktif',
                'periods' => [],
                'periods_label' => 'Tidak Ada Slot',
                'has_custom_schedule' => true,
            ];
        }

        $dayNamesShort = [
            1 => 'Sen',
            2 => 'Sel',
            3 => 'Rab',
            4 => 'Kam',
            5 => 'Jum',
            6 => 'Sab',
            0 => 'Min',
        ];

        $activeDayNumbers = $activeDays->pluck('day_of_week')->all();

        // Label Hari
        if (count($activeDayNumbers) === 7) {
            $daysLabel = 'Setiap Hari';
        } elseif ($activeDayNumbers === [1, 2, 3, 4, 5]) {
            $daysLabel = 'Senin – Jumat';
        } elseif ($activeDayNumbers === [1, 2, 3, 4, 5, 6]) {
            $daysLabel = 'Senin – Sabtu';
        } else {
            $daysLabel = implode(', ', array_map(fn ($d) => $dayNamesShort[$d] ?? (string) $d, $activeDayNumbers));
        }

        // Kumpulkan semua slot unik yang aktif
        $allSlots = [];
        foreach ($activeDays as $day) {
            if (is_array($day->slots)) {
                $allSlots = array_merge($allSlots, $day->slots);
            }
        }
        $allSlots = array_unique($allSlots);

        $morningSlots = config('pt_booking.slots.morning', ['07:00', '08:00', '09:00', '10:00']);
        $afternoonSlots = config('pt_booking.slots.afternoon', ['13:00', '14:00', '15:00', '16:00']);
        $eveningSlots = config('pt_booking.slots.evening', ['18:00', '19:00', '20:00', '21:00']);

        $periods = [];
        if (! empty(array_intersect($allSlots, $morningSlots))) {
            $periods[] = 'morning';
        }
        if (! empty(array_intersect($allSlots, $afternoonSlots))) {
            $periods[] = 'afternoon';
        }
        if (! empty(array_intersect($allSlots, $eveningSlots))) {
            $periods[] = 'evening';
        }

        $periodLabels = [
            'morning' => 'Pagi',
            'afternoon' => 'Siang',
            'evening' => 'Sore/Malam',
        ];

        $namedPeriods = array_map(fn ($p) => $periodLabels[$p] ?? $p, $periods);
        $periodsLabel = ! empty($namedPeriods) ? implode(' & ', $namedPeriods) : 'Sesuai Janji Temu';

        return [
            'days_label' => $daysLabel,
            'periods' => $periods,
            'periods_label' => $periodsLabel,
            'has_custom_schedule' => true,
        ];
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
