<?php

namespace App\Enums;

enum PtSessionStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu',
            self::Confirmed => 'Terkonfirmasi',
            self::Cancelled => 'Dibatalkan',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Pending => 'amber',
            self::Confirmed => 'emerald',
            self::Cancelled => 'zinc',
        };
    }

    public function color(): string
    {
        return $this->badgeColor();
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Pending => 'bg-amber-500/10 text-amber-500 border border-amber-500/20',
            self::Confirmed => 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20',
            self::Cancelled => 'bg-zinc-800 text-zinc-300 border border-zinc-700',
        };
    }
}
