<?php

namespace App\Models;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'invoice_id',
    'member_id',
    'package_id',
    'package_type',
    'amount',
    'bank_sender',
    'bank_destination',
    'proof_image_url',
    'transfer_date',
    'status',
    'rejection_reason',
    'verified_by',
    'verified_at',
])]
class Payment extends Model
{
    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'transfer_date' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function verificationLogs(): HasMany
    {
        return $this->hasMany(VerificationLog::class);
    }

    public function membershipPackage(): BelongsTo
    {
        return $this->belongsTo(MembershipPackage::class, 'package_id');
    }

    public function ptPackage(): BelongsTo
    {
        return $this->belongsTo(PtPackage::class, 'package_id');
    }

    public function membership(): HasOne
    {
        return $this->hasOne(Membership::class);
    }

    public function ptSessionPackage(): HasOne
    {
        return $this->hasOne(PtSessionPackage::class);
    }
=======
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
>>>>>>> origin/develop
}
