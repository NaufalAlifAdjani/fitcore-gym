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

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }
}
