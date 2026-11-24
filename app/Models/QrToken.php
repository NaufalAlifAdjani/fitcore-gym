<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QrToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'token',
        'expires_at',
        'used_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    // ── Status Helpers ───────────────────────────────────────────────────────

    /**
     * Check whether the token has passed its expiry time.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check whether the token has already been consumed by a check-in.
     */
    public function isUsed(): bool
    {
        return $this->used_at !== null;
    }

    /**
     * A token is valid when it has not expired and has not been used.
     */
    public function isValid(): bool
    {
        return ! $this->isExpired() && ! $this->isUsed();
    }
}
