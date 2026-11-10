<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainerAvailability extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'day_of_week' => 'integer',
        'is_active' => 'boolean',
        'slots' => 'array',
    ];

    /**
     * Nama hari dalam bahasa Indonesia (0 = Minggu, 1 = Senin, dst).
     */
    public function getDayNameAttribute(): string
    {
        $names = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];

        return $names[$this->day_of_week] ?? 'Hari '.$this->day_of_week;
    }

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }
}
