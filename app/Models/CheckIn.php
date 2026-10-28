<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckIn extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'checked_in_at' => 'datetime',
        'checked_out_at' => 'datetime',
        'check_in_date'  => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    public function checkedBy()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }
}
