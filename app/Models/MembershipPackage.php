<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'type',
    'duration_days',
    'price',
    'facilities',
    'pt_session_count',
    'status',
    'badge',
    'tier',
    'description',
    'promo_price',
    'duration_value',
    'duration_unit',
    'duration_in_days',
    'pt_sessions',
    'is_active',
])]
class MembershipPackage extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'facilities' => 'array',
            'price' => 'integer',
            'promo_price' => 'integer',
            'duration_days' => 'integer',
            'duration_value' => 'integer',
            'duration_in_days' => 'integer',
            'pt_session_count' => 'integer',
            'pt_sessions' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $package): void {
            if (isset($package->duration_in_days)) {
                $package->duration_days = (int) $package->duration_in_days;
            } elseif (isset($package->duration_days) && ! isset($package->duration_in_days)) {
                $package->duration_in_days = (int) $package->duration_days;
            }

            if (isset($package->pt_sessions)) {
                $package->pt_session_count = (int) $package->pt_sessions;
            } elseif (isset($package->pt_session_count) && ! isset($package->pt_sessions)) {
                $package->pt_sessions = (int) $package->pt_session_count;
            }

            if (isset($package->is_active)) {
                $package->status = $package->is_active ? 'active' : 'inactive';
            } elseif (isset($package->status) && ! isset($package->is_active)) {
                $package->is_active = $package->status === 'active';
            }
        });
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }
}
