<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'description',
    'sessions_count',
    'price',
    'is_active',
])]
class PtPackage extends Model
{
    protected function casts(): array
    {
        return [
            'sessions_count' => 'integer',
            'price' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function memberPackages(): HasMany
    {
        return $this->hasMany(PtSessionPackage::class);
    }
}
