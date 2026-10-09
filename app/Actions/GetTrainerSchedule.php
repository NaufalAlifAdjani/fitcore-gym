<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\PtSessionStatus;
use App\Models\PtSession;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class GetTrainerSchedule
{
    public function execute(int $trainerId, string $date, string $view): Collection
    {
        $carbonDate = Carbon::parse($date);

        $query = PtSession::with(['member.memberProfile', 'quota', 'rating'])
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

        $sessions = $query->get();

        if ($carbonDate->isToday()) {
            $now = Carbon::now();

            return $sessions->sortBy(function (PtSession $session) use ($now) {
                // 1. Sesi yang eksplisit berstatus Ongoing selalu paling pertama (Prioritas 0 / Teratas)
                if ($session->status === PtSessionStatus::Ongoing) {
                    return 0;
                }

                $startsAt = $session->starts_at;
                $endsAt = $session->ends_at;

                // 2. Sesi yang sudah selesai atau dibatalkan selalu ditaruh paling belakang
                $isDoneOrCancelled = in_array($session->status, [
                    PtSessionStatus::Done,
                    PtSessionStatus::Completed,
                    PtSessionStatus::Cancelled,
                ]);

                if ($isDoneOrCancelled) {
                    return 10_000_000 + ($startsAt ? $startsAt->timestamp : 0);
                }

                // 3. Jika belum Ongoing, tapi rentang jamnya sedang berlangsung sekarang
                if ($startsAt && $endsAt && $now->between($startsAt, $endsAt)) {
                    return 100;
                }

                // 4. Sesi mendatang (yang paling mendekati jam saat ini muncul lebih dulu)
                if ($startsAt && $startsAt->greaterThanOrEqualTo($now)) {
                    return 1_000_000 + ($startsAt->timestamp - $now->timestamp);
                }

                // 5. Sesi aktif yang jam mulainya telah lewat
                if ($startsAt) {
                    return 5_000_000 + ($startsAt->timestamp - $now->timestamp);
                }

                return 20_000_000;
            })->values();
        }

        return $sessions;
    }
}
