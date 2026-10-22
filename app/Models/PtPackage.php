<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'pt_session_count',
    'price',
    'min_membership_days',
    'validity_days',
    'status',
])]
class PtPackage extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'pt_session_count' => 'integer',
            'price' => 'integer',
            'min_membership_days' => 'integer',
            'validity_days' => 'integer',
        ];
    }

    public function quotas(): HasMany
    {
        return $this->hasMany(MemberPtQuota::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
