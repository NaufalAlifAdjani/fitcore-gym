<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Ambil nilai pengaturan berdasarkan kunci dengan fallback default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        try {
            $setting = static::query()->where('key', $key)->first();

            return $setting !== null ? $setting->value : $default;
        } catch (\Throwable) {
            return $default;
        }
    }

    /**
     * Simpan atau perbarui nilai pengaturan.
     */
    public static function set(string $key, mixed $value): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => (string) $value]
        );
    }
}
