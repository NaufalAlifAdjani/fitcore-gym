<?php

namespace App\Models;

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
    ];

    public function quota(): BelongsTo
    {
        return $this->belongsTo(MemberPtQuota::class, 'member_pt_quota_id');
    }

    public function memberPtQuota(): BelongsTo
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
}
