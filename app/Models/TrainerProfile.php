<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainerProfile extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'rating' => 'float',
        'review_count' => 'integer',
        'experience' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
