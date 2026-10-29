<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MemberProfile extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Automatically generate a qr_token when creating a new MemberProfile
     * if one has not been explicitly set.
     */
    protected static function booted(): void
    {
        static::creating(function (MemberProfile $profile) {
            if (empty($profile->qr_token)) {
                $profile->qr_token = Str::uuid()->toString();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
