<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'payment_id',
    'admin_id',
    'action',
    'notes',
])]
class VerificationLog extends Model
{
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
