<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function member()   { return $this->belongsTo(User::class, 'member_id'); }
    public function package()  { return $this->belongsTo(MembershipPackage::class, 'membership_package_id'); }
    public function ptQuotas() { return $this->hasMany(MemberPtQuota::class); }
    public function payments() { return $this->hasMany(Payment::class); }
    public function checkIns() { return $this->hasMany(CheckIn::class); }
}
