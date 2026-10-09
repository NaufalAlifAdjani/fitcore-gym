<?php

namespace App\Models;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'member_id',
    'package_id',
    'payment_id',
    'start_date',
    'end_date',
    'status',
])]
class Membership extends Model
{
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(MembershipPackage::class, 'package_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
=======
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;

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
>>>>>>> origin/develop
}
