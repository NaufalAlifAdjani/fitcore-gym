<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PtSession extends Model
{
    protected $guarded = [];

    protected $casts = [
        'session_date' => 'date',
    ];

    public function quota()   { return $this->belongsTo(MemberPtQuota::class, 'member_pt_quota_id'); }
    public function member()  { return $this->belongsTo(User::class, 'member_id'); }
    public function trainer() { return $this->belongsTo(User::class, 'trainer_id'); }
    public function rating()  { return $this->hasOne(Rating::class); }
}
