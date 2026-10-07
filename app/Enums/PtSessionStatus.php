<?php

namespace App\Enums;

enum PtSessionStatus: string
{
    case Scheduled = 'scheduled';
    case Done = 'done';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Scheduled => 'Mendatang',
            self::Done => 'Selesai',
            self::Cancelled => 'Dibatalkan',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Scheduled => 'emerald',
            self::Done => 'gray',
            self::Cancelled => 'red',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Scheduled => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
            self::Done => 'bg-zinc-800 text-zinc-300 border-zinc-700',
            self::Cancelled => 'bg-red-500/10 text-red-400 border-red-500/20',
        };
    }
}
