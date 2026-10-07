<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PtPackage extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function quotas()
    {
        return $this->hasMany(MemberPtQuota::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
