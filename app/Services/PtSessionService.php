<?php

namespace App\Services;

use App\Enums\PtSessionStatus;
use App\Exceptions\ChangeDeadlinePassedException;
use App\Exceptions\QuotaExceededException;
use App\Exceptions\SlotUnavailableException;
use App\Models\MemberPtQuota;
use App\Models\PtSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PtSessionService
{
    /**
     * Mendapatkan daftar slot jam harian beserta statusnya (available, booked, passed).
     */
    public function getAvailableSlots(
        User|int $trainer,
        Carbon|string $date,
        ?PtSession $ignoreSession = null
    ): array {
        $trainerId = $trainer instanceof User ? $trainer->id : (int) $trainer;
        $carbonDate = $date instanceof Carbon ? $date->copy() : Carbon::parse($date);
        $dateString = $carbonDate->toDateString();

        $configuredSlots = config('pt_booking.slots', [
            'morning' => ['07:00', '08:00', '09:00', '10:00'],
            'afternoon' => ['13:00', '14:00', '15:00', '16:00'],
            'evening' => ['18:00', '19:00', '20:00', '21:00'],
        ]);

        $query = PtSession::query()
            ->where('trainer_id', $trainerId)
            ->whereDate('session_date', $dateString)
            ->where('status', '!=', PtSessionStatus::Cancelled->value);

        if ($ignoreSession) {
            $query->where('id', '!=', $ignoreSession->id);
        }

        $bookedSessions = $query->get()->keyBy(function (PtSession $session) {
            return substr((string) $session->start_time, 0, 5);
        });

        $durationMinutes = (int) config('pt_booking.session_duration_minutes', 60);
        $now = now();
        $isToday = $carbonDate->isToday();
        $isPastDate = $carbonDate->isPast() && ! $isToday;

        $groups = [];

        foreach ($configuredSlots as $groupKey => $times) {
            $groups[$groupKey] = [];

            foreach ($times as $time) {
                $timeShort = substr($time, 0, 5);
                $slotStartCarbon = Carbon::parse($dateString.' '.$timeShort);
                $endTime = Carbon::parse($timeShort)->addMinutes($durationMinutes)->format('H:i');

                if ($isPastDate || ($isToday && $slotStartCarbon->isPast())) {
                    $status = 'passed';
                    $label = 'Sudah Lewat';
                } elseif ($bookedSessions->has($timeShort)) {
                    $status = 'booked';
                    $label = 'Penuh';
                } else {
                    $status = 'available';
                    $label = 'Tersedia';
                }

                $groups[$groupKey][] = [
                    'time' => $timeShort,
                    'end_time' => $endTime,
                    'status' => $status,
                    'label' => $label,
                ];
            }
        }

        return $groups;
    }

    /**
     * Mendapatkan ketersediaan slot untuk strip 7 hari kalender.
     */
    public function getDateAvailability(
        User|int $trainer,
        Carbon|string $startDate,
        int $days = 7
    ): array {
        $trainerId = $trainer instanceof User ? $trainer->id : (int) $trainer;
        $start = $startDate instanceof Carbon ? $startDate->copy() : Carbon::parse($startDate);

        $dayNamesIndo = [
            0 => 'Min',
            1 => 'Sen',
            2 => 'Sel',
            3 => 'Rab',
            4 => 'Kam',
            5 => 'Jum',
            6 => 'Sab',
        ];

        $daysList = [];

        for ($i = 0; $i < $days; $i++) {
            $currentDate = $start->copy()->addDays($i);
            $groups = $this->getAvailableSlots($trainerId, $currentDate);

            $hasAvailableSlot = false;
            foreach ($groups as $slots) {
                foreach ($slots as $slot) {
                    if ($slot['status'] === 'available') {
                        $hasAvailableSlot = true;
                        break 2;
                    }
                }
            }

            $dayOfWeek = (int) $currentDate->dayOfWeek;

            $daysList[] = [
                'date' => $currentDate->toDateString(),
                'day_name' => $dayNamesIndo[$dayOfWeek] ?? $currentDate->format('D'),
                'day_num' => (int) $currentDate->format('d'),
                'has_slots' => $hasAvailableSlot,
                'status_label' => $hasAvailableSlot ? 'Ada slot' : 'Penuh',
            ];
        }

        return $daysList;
    }

    /**
     * Memvalidasi prasyarat booking sebelum eksekusi transaksi.
     *
     * @throws QuotaExceededException|SlotUnavailableException
     */
    public function validateBooking(
        User $member,
        User $trainer,
        Carbon $sessionDate,
        string $startTime,
        ?PtSession $ignoreSession = null
    ): MemberPtQuota {
        $timeShort = substr($startTime, 0, 5);
        $sessionDateTime = Carbon::parse($sessionDate->toDateString().' '.$timeShort);

        if ($sessionDateTime->isPast()) {
            throw new SlotUnavailableException('Tidak dapat memilih jadwal dan jam di masa lampau.');
        }

        // 1. Quota check with lock
        $quota = MemberPtQuota::where('member_id', $member->id)
            ->where('status', 'active')
            ->where('end_date', '>=', now()->toDateString())
            ->where('remaining_sessions', '>', 0)
            ->orderBy('end_date', 'asc')
            ->lockForUpdate()
            ->first();

        if (! $quota) {
            throw new QuotaExceededException('Kuota sesi Anda telah habis atau tidak memiliki paket aktif.');
        }

        // 2. Trainer slot check
        $trainerQuery = PtSession::where('trainer_id', $trainer->id)
            ->whereDate('session_date', $sessionDate->toDateString())
            ->where('start_time', $startTime)
            ->where('status', '!=', PtSessionStatus::Cancelled->value);

        if ($ignoreSession) {
            $trainerQuery->where('id', '!=', $ignoreSession->id);
        }

        if ($trainerQuery->lockForUpdate()->exists()) {
            throw new SlotUnavailableException('Slot pelatih pada jam tersebut sudah terisi.');
        }

        // 3. Member conflict check
        $memberQuery = PtSession::where('member_id', $member->id)
            ->whereDate('session_date', $sessionDate->toDateString())
            ->where('start_time', $startTime)
            ->where('status', '!=', PtSessionStatus::Cancelled->value);

        if ($ignoreSession) {
            $memberQuery->where('id', '!=', $ignoreSession->id);
        }

        if ($memberQuery->lockForUpdate()->exists()) {
            throw new SlotUnavailableException('Anda sudah memiliki sesi yang dijadwalkan pada jam tersebut.');
        }

        return $quota;
    }

    /**
     * Membuat booking sesi baru dalam transaksi database dengan pessimistic lock.
     *
     * @throws QuotaExceededException|SlotUnavailableException
     */
    public function createBooking(User $member, array $data): PtSession
    {
        return DB::transaction(function () use ($member, $data) {
            $trainer = User::findOrFail($data['trainer_id']);
            $sessionDate = Carbon::parse($data['session_date']);
            $startTime = substr($data['start_time'], 0, 5).':00';

            $quota = $this->validateBooking($member, $trainer, $sessionDate, $startTime);

            $durationMinutes = (int) config('pt_booking.session_duration_minutes', 60);
            $endTime = Carbon::parse($startTime)->addMinutes($durationMinutes)->toTimeString();

            $quota->increment('used_sessions');
            $quota->decrement('remaining_sessions');

            return PtSession::create([
                'member_pt_quota_id' => $quota->id,
                'member_id' => $member->id,
                'trainer_id' => $trainer->id,
                'session_date' => $sessionDate->toDateString(),
                'start_time' => $startTime,
                'end_time' => $endTime,
                'status' => PtSessionStatus::Scheduled,
            ]);
        });
    }

    /**
     * Memeriksa apakah sesi masih dalam batas waktu untuk diubah/dibatalkan (>= 4 jam).
     */
    public function canChange(PtSession $session): bool
    {
        return $session->can_be_changed;
    }

    /**
     * Mengubah jadwal sesi (reschedule) dalam transaksi database.
     *
     * @throws ChangeDeadlinePassedException|SlotUnavailableException
     */
    public function reschedule(PtSession $session, array $data): PtSession
    {
        return DB::transaction(function () use ($session, $data) {
            $lockedSession = PtSession::where('id', $session->id)->lockForUpdate()->firstOrFail();

            if (! $this->canChange($lockedSession)) {
                throw new ChangeDeadlinePassedException;
            }

            $sessionDate = Carbon::parse($data['session_date']);
            $startTime = substr($data['start_time'], 0, 5).':00';
            $sessionDateTime = Carbon::parse($sessionDate->toDateString().' '.substr($startTime, 0, 5));

            if ($sessionDateTime->isPast()) {
                throw new SlotUnavailableException('Tidak dapat memilih jadwal dan jam di masa lampau.');
            }

            // Trainer check
            $trainerBusy = PtSession::where('trainer_id', $lockedSession->trainer_id)
                ->whereDate('session_date', $sessionDate->toDateString())
                ->where('start_time', $startTime)
                ->where('status', '!=', PtSessionStatus::Cancelled->value)
                ->where('id', '!=', $lockedSession->id)
                ->lockForUpdate()
                ->exists();

            if ($trainerBusy) {
                throw new SlotUnavailableException('Slot pelatih pada jam tersebut sudah terisi.');
            }

            // Member check
            $memberBusy = PtSession::where('member_id', $lockedSession->member_id)
                ->whereDate('session_date', $sessionDate->toDateString())
                ->where('start_time', $startTime)
                ->where('status', '!=', PtSessionStatus::Cancelled->value)
                ->where('id', '!=', $lockedSession->id)
                ->lockForUpdate()
                ->exists();

            if ($memberBusy) {
                throw new SlotUnavailableException('Anda sudah memiliki sesi yang dijadwalkan pada jam tersebut.');
            }

            $durationMinutes = (int) config('pt_booking.session_duration_minutes', 60);
            $endTime = Carbon::parse($startTime)->addMinutes($durationMinutes)->toTimeString();

            $lockedSession->update([
                'session_date' => $sessionDate->toDateString(),
                'start_time' => $startTime,
                'end_time' => $endTime,
            ]);

            return $lockedSession->fresh();
        });
    }

    /**
     * Membatalkan sesi dan mengembalikan kuota aktif.
     *
     * @throws ChangeDeadlinePassedException
     */
    public function cancel(PtSession $session, ?string $reason = null): PtSession
    {
        return DB::transaction(function () use ($session) {
            $lockedSession = PtSession::where('id', $session->id)->lockForUpdate()->firstOrFail();

            if (! $this->canChange($lockedSession)) {
                throw new ChangeDeadlinePassedException;
            }

            $lockedSession->update([
                'status' => PtSessionStatus::Cancelled,
            ]);

            $quota = MemberPtQuota::where('id', $lockedSession->member_pt_quota_id)
                ->lockForUpdate()
                ->first();

            if ($quota) {
                $quota->decrement('used_sessions');
                $quota->increment('remaining_sessions');
            }

            return $lockedSession->fresh();
        });
    }
}
