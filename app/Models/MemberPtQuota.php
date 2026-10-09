<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MemberPtQuota extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_sessions' => 'integer',
        'used_sessions' => 'integer',
        'remaining_sessions' => 'integer',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class);
    }

    public function ptPackage(): BelongsTo
    {
        return $this->belongsTo(PtPackage::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(PtSession::class);
    }

    public function getExpiredAtAttribute(): ?Carbon
    {
        return $this->end_date;
    }

    public function isExpired(): bool
    {
        return $this->end_date ? $this->end_date->endOfDay()->isPast() : false;
    }

    public function hasRemainingSessions(): bool
    {
        return $this->remaining_sessions > 0 && ! $this->isExpired();
    }

    public function scopeActiveValid(Builder $query): Builder
    {
        return $query->where('status', 'active')
            ->where('end_date', '>=', now()->toDateString())
            ->where('remaining_sessions', '>', 0);
    }
}
