<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'invoice_id',
    'member_id',
    'membership_id',
    'pt_package_id',
    'bank_id',
    'payment_type',
    'package_id',
    'package_type',
    'amount',
    'bank_sender',
    'bank_destination',
    'proof_image_path',
    'proof_image_url',
    'transfer_date',
    'status',
    'rejection_reason',
    'verified_by',
    'verified_at',
])]
class Payment extends Model
{
    use HasFactory;

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

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class);
    }

    public function ptPackage(): BelongsTo
    {
        return $this->belongsTo(PtPackage::class);
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    public function verificationLogs(): HasMany
    {
        return $this->hasMany(VerificationLog::class);
    }

    public function activatedMembership(): HasOne
    {
        return $this->hasOne(Membership::class);
    }

    public function ptQuota(): HasOne
    {
        return $this->hasOne(MemberPtQuota::class);
    }

    public function membershipPackage(): BelongsTo
    {
        return $this->belongsTo(MembershipPackage::class, 'package_id');
    }
}
