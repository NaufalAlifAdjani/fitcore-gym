<?php

namespace App\Enums;

enum PtSessionStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Scheduled = 'scheduled';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Done = 'done';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu',
            self::Confirmed, self::Scheduled => 'Terkonfirmasi',
            self::InProgress => 'Berlangsung',
            self::Completed, self::Done => 'Selesai',
            self::Cancelled => 'Dibatalkan',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Pending => 'amber',
            self::Confirmed, self::Scheduled => 'emerald',
            self::InProgress => 'blue',
            self::Completed, self::Done => 'emerald',
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
            self::Confirmed, self::Scheduled => 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20',
            self::InProgress => 'bg-blue-500/10 text-blue-600 border border-blue-500/20',
            self::Completed, self::Done => 'bg-emerald-50 text-emerald-700 border border-emerald-600/20',
            self::Cancelled => 'bg-zinc-800 text-zinc-300 border border-zinc-700',
        };
    }
}
