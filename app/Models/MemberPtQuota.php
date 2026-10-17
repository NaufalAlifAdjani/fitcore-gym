<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberPtQuota extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function member()     { return $this->belongsTo(User::class, 'member_id'); }
    public function membership() { return $this->belongsTo(Membership::class); }
    public function ptPackage()  { return $this->belongsTo(PtPackage::class); }
    public function sessions()   { return $this->hasMany(PtSession::class); }
}
