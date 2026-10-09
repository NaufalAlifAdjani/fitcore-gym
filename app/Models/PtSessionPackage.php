<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'member_id',
    'pt_package_id',
    'payment_id',
    'sessions_total',
    'sessions_remaining',
    'status',
])]
class PtSessionPackage extends Model
{
    protected function casts(): array
    {
        return [
            'sessions_total' => 'integer',
            'sessions_remaining' => 'integer',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(PtPackage::class, 'pt_package_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
