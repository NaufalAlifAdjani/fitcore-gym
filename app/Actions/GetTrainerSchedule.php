<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\PtSession;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class GetTrainerSchedule
{
    public function execute(int $trainerId, string $date, string $view): Collection
    {
        $carbonDate = Carbon::parse($date);
        
        $query = PtSession::with(['member.memberProfile', 'quota'])
            ->where('trainer_id', $trainerId)
            ->orderBy('start_time', 'asc');

        if ($view === 'weekly') {
            $start = $carbonDate->copy()->startOfWeek(Carbon::MONDAY)->toDateString();
            $end = $carbonDate->copy()->endOfWeek(Carbon::SUNDAY)->toDateString();
            $query->whereBetween('session_date', [$start, $end])
                  ->orderBy('session_date', 'asc');
        } else {
            $query->whereDate('session_date', $carbonDate->toDateString());
        }

        return $query->get();
    }
}
