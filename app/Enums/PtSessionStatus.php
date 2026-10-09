<?php

namespace App\Enums;

enum PtSessionStatus: string
{
    case Scheduled = 'scheduled';
    case PendingConfirmation = 'pending_confirmation';
    case Rescheduled = 'rescheduled';
    case Ongoing = 'ongoing';
    case Completed = 'completed';
    case Done = 'done';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Scheduled => '',
            self::PendingConfirmation => 'Konfirmasi',
            self::Rescheduled => 'Rescheduled',
            self::Ongoing => 'LIVE',
            self::Completed, self::Done => 'Selesai',
            self::Cancelled => 'Dibatalkan',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Scheduled => 'rose',
            self::PendingConfirmation => 'amber',
            self::Rescheduled => 'orange',
            self::Ongoing => 'red',
            self::Completed, self::Done => 'emerald',
            self::Cancelled => 'zinc',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Scheduled => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
            self::PendingConfirmation => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
            self::Rescheduled => 'bg-orange-500/10 text-orange-400 border-orange-500/20',
            self::Ongoing => 'bg-red-500/10 text-red-400 border-red-500/20',
            self::Completed, self::Done => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
            self::Cancelled => 'bg-zinc-800 text-zinc-300 border-zinc-700',
        };
    }
}
