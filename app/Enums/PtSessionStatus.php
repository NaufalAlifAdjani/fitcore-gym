<?php

namespace App\Enums;

enum PtSessionStatus: string
{
    case Pending = 'pending';
    case Scheduled = 'scheduled';
    case Confirmed = 'confirmed';
    case Done = 'done';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu Konfirmasi',
            self::Scheduled, self::Confirmed => 'Terkonfirmasi',
            self::Done => 'Selesai',
            self::Cancelled => 'Dibatalkan',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Pending => 'amber',
            self::Scheduled, self::Confirmed => 'emerald',
            self::Done => 'gray',
            self::Cancelled => 'red',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Pending => 'bg-amber-50 text-amber-700 border-amber-200',
            self::Scheduled, self::Confirmed => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            self::Done => 'bg-zinc-800 text-zinc-300 border-zinc-700',
            self::Cancelled => 'bg-red-500/10 text-red-400 border-red-500/20',
        };
    }
}
