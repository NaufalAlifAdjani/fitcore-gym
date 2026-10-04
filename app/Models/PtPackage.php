<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PtPackage extends Model
{
    protected $guarded = [];

    public function quotas()   { return $this->hasMany(MemberPtQuota::class); }
    public function payments() { return $this->hasMany(Payment::class); }
}
