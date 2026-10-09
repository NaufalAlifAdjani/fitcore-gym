<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function member()     { return $this->belongsTo(User::class, 'member_id'); }
    public function membership() { return $this->belongsTo(Membership::class); }
    public function ptPackage()  { return $this->belongsTo(PtPackage::class); }
    public function bank()       { return $this->belongsTo(Bank::class); }
    public function verifier()   { return $this->belongsTo(User::class, 'verified_by'); }
}
