<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PtPackage extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'pt_session_count'    => 'integer',
        'price'               => 'decimal:2',
        'min_membership_days' => 'integer',
        'validity_days'       => 'integer',
    ];

    public function quotas()   { return $this->hasMany(MemberPtQuota::class); }
    public function payments() { return $this->hasMany(Payment::class); }
}
