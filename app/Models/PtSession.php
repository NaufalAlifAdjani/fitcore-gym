<?php

namespace App\Models;

use App\Enums\PtSessionStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PtSession extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'session_date' => 'date',
        'status' => PtSessionStatus::class,
    ];

    public function setSessionDateAttribute($value): void
    {
        $this->attributes['session_date'] = $value instanceof Carbon
            ? $value->toDateString()
            : substr((string) $value, 0, 10);
    }

    public function quota(): BelongsTo
    {
        return $this->belongsTo(MemberPtQuota::class, 'member_pt_quota_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function rating(): HasOne
    {
        return $this->hasOne(Rating::class);
    }

    // Accessors
    public function getStartsAtAttribute(): ?Carbon
    {
        if (!$this->session_date || !$this->start_time) {
            return null;
        }

        $dateString = $this->session_date instanceof Carbon
            ? $this->session_date->toDateString()
            : (string) $this->session_date;

        return Carbon::parse($dateString . ' ' . $this->start_time);
    }

    public function getEndsAtAttribute(): ?Carbon
    {
        if (!$this->session_date || !$this->end_time) {
            return null;
        }

        $dateString = $this->session_date instanceof Carbon
            ? $this->session_date->toDateString()
            : (string) $this->session_date;

        return Carbon::parse($dateString . ' ' . $this->end_time);
    }

    public function getCanBeChangedAttribute(): bool
    {
        if ($this->status !== PtSessionStatus::Scheduled) {
            return false;
        }

        $startsAt = $this->starts_at;
        if (!$startsAt) {
            return false;
        }

        $deadlineHours = (int) config('pt_booking.change_deadline_hours', 4);

        return $startsAt->greaterThanOrEqualTo(now()->addHours($deadlineHours));
    }

    public function getTimeRangeAttribute(): string
    {
        $start = substr((string) $this->start_time, 0, 5);
        $end = substr((string) $this->end_time, 0, 5);

        return "{$start} - {$end} WIB";
    }

    public function getRelativeTimeBadgeAttribute(): string
    {
        if ($this->status === PtSessionStatus::Cancelled) {
            return 'Dibatalkan';
        }

        $startsAt = $this->starts_at;
        if (!$startsAt) {
            return $this->status->label();
        }

        if ($startsAt->isPast()) {
            return 'Selesai';
        }

        $now = now();
        $diffHours = (int) $now->diffInHours($startsAt);

        if ($startsAt->isToday()) {
            return "Hari Ini ({$diffHours} Jam Lagi)";
        }

        if ($startsAt->isTomorrow()) {
            return "Besok ({$diffHours} Jam Lagi)";
        }

        $diffDays = (int) $now->diffInDays($startsAt);

        return "Mendatang ({$diffDays} Hari Lagi)";
    }

    // Scopes
    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where('status', PtSessionStatus::Scheduled->value);
    }

    public function scopeDone(Builder $query): Builder
    {
        return $query->where('status', PtSessionStatus::Done->value);
    }

    public function scopeCancelled(Builder $query): Builder
    {
        return $query->where('status', PtSessionStatus::Cancelled->value);
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('status', PtSessionStatus::Scheduled->value)
            ->where(function (Builder $q) {
                $q->where('session_date', '>', now()->toDateString())
                    ->orWhere(function (Builder $sub) {
                        $sub->where('session_date', now()->toDateString())
                            ->where('start_time', '>=', now()->toTimeString());
                    });
            });
    }
}
