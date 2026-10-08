<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'badge',
    'tier',
    'description',
    'price',
    'promo_price',
    'duration_value',
    'duration_unit',
    'duration_in_days',
    'facilities',
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
            'is_active' => 'boolean',
            'price' => 'integer',
            'promo_price' => 'integer',
        ];
    }
}
