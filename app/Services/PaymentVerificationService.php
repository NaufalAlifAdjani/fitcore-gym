<?php

namespace App\Services;

use App\Exceptions\PaymentAlreadyProcessedException;
use App\Models\MemberPtQuota;
use App\Models\Membership;
use App\Models\MembershipPackage;
use App\Models\Payment;
use App\Models\PtPackage;
use App\Models\User;
use App\Notifications\ChoosePtScheduleNotification;
use App\Notifications\MembershipPaymentVerifiedNotification;
use App\Notifications\PaymentRejectedNotification;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentVerificationService
{
    public function approvePayment(Payment $payment, int $adminId): Payment
    {
        return DB::transaction(function () use ($payment, $adminId): Payment {
            $lockedPayment = $this->lockPendingPayment($payment);

            $isMembershipPayment = $lockedPayment->package_type !== null
                ? $lockedPayment->package_type === 'membership'
                : $lockedPayment->payment_type === 'membership';

            if ($isMembershipPayment) {
                $this->activateMembership($lockedPayment);
            } else {
                $this->activatePtPackage($lockedPayment);
            }

            $isPtPayment = ! $isMembershipPayment
                || $lockedPayment->pt_package_id !== null
                || $lockedPayment->package_type === 'pt_session';

            $amount = (float) $lockedPayment->amount;
            $trainerRevenue = $isPtPayment ? round($amount * 0.70, 2) : 0;
            $gymRevenue = $isPtPayment ? round($amount * 0.30, 2) : $amount;

            $trainerId = $lockedPayment->trainer_id;
            if ($isPtPayment && ! $trainerId) {
                $trainerId = User::where('role', 'trainer')->first()?->id;
            }

            $lockedPayment->forceFill([
                'status' => 'verified',
                'verified_at' => now(),
                'verified_by' => $adminId,
                'rejection_reason' => null,
                'trainer_id' => $trainerId,
                'trainer_revenue' => $trainerRevenue,
                'gym_revenue' => $gymRevenue,
            ])->save();

            $lockedPayment->verificationLogs()->create([
                'admin_id' => $adminId,
                'action' => 'approve',
                'notes' => 'Pembayaran diverifikasi.',
            ]);

            return $lockedPayment->refresh();
        }, attempts: 3);
    }

    public function rejectPayment(Payment $payment, string $reason, int $adminId): Payment
    {
        $reason = trim($reason);

        if ($reason === '') {
            throw ValidationException::withMessages([
                'reason' => 'Alasan penolakan wajib diisi.',
            ]);
        }

        return DB::transaction(function () use ($payment, $reason, $adminId): Payment {
            $lockedPayment = $this->lockPendingPayment($payment);

            $lockedPayment->forceFill([
                'status' => 'rejected',
                'rejection_reason' => $reason,
                'verified_at' => now(),
                'verified_by' => $adminId,
            ])->save();

            $lockedPayment->verificationLogs()->create([
                'admin_id' => $adminId,
                'action' => 'reject',
                'notes' => $reason,
            ]);

            $lockedPayment->member?->notify(new PaymentRejectedNotification($lockedPayment, $reason));

            return $lockedPayment->refresh();
        }, attempts: 3);
    }

    private function lockPendingPayment(Payment $payment): Payment
    {
        $lockedPayment = Payment::query()
            ->whereKey($payment->getKey())
            ->lockForUpdate()
            ->firstOrFail();

        if ($lockedPayment->status !== 'pending') {
            throw new PaymentAlreadyProcessedException(
                'Pembayaran ini sudah diproses sebelumnya.',
            );
        }

        return $lockedPayment;
    }

    private function activateMembership(Payment $payment): void
    {
        User::query()
            ->whereKey($payment->member_id)
            ->lockForUpdate()
            ->firstOrFail();

        $package = MembershipPackage::query()
            ->findOrFail($payment->package_id ?? $payment->membership?->membership_package_id);
        $today = CarbonImmutable::today();

        $currentActive = Membership::query()
            ->where('member_id', $payment->member_id)
            ->where('status', 'active')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->with('package')
            ->latest('end_date')
            ->first();

        $latestActiveEndDate = Membership::query()
            ->where('member_id', $payment->member_id)
            ->where('status', 'active')
            ->whereDate('end_date', '>=', $today)
            ->max('end_date');

        $tierRanks = [
            'Basic' => 1,
            'Student Tier' => 1,
            'Standard' => 2,
            'Premium & VIP' => 3,
        ];

        $currentTierRank = $currentActive ? ($tierRanks[$currentActive->package?->tier] ?? 1) : 0;
        $newTierRank = $tierRanks[$package->tier] ?? 1;

        $isUpgrade = $currentActive && (
            $newTierRank > $currentTierRank ||
            ($package->price > ($currentActive->package?->price ?? 0) && $package->id !== $currentActive->membership_package_id)
        );

        $durationDays = $package->duration_days;

        if ($isUpgrade) {
            $startDate = $today;
            $carryOverEndDate = $latestActiveEndDate
                ? CarbonImmutable::parse($latestActiveEndDate)
                : CarbonImmutable::parse($currentActive->end_date);
            $remainingDays = max(0, (int) $today->diffInDays($carryOverEndDate, false) + 1);
            $totalDuration = $durationDays + $remainingDays;
            $endDate = $startDate->addDays($totalDuration - 1);

            Membership::query()
                ->where('member_id', $payment->member_id)
                ->where('status', 'active')
                ->whereDate('end_date', '>=', $today)
                ->update(['status' => 'expired']);
        } else {
            $startDate = $latestActiveEndDate
                ? CarbonImmutable::parse($latestActiveEndDate)->addDay()
                : $today;
            $endDate = $startDate->addDays($durationDays - 1);

            Membership::query()
                ->where('member_id', $payment->member_id)
                ->where('status', 'active')
                ->whereDate('end_date', '<', $today)
                ->update(['status' => 'expired']);
        }

        $membership = Membership::query()->create([
            'member_id' => $payment->member_id,
            'membership_package_id' => $package->id,
            'payment_id' => $payment->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'active',
        ]);

        $member = User::query()->findOrFail($payment->member_id);
        $member->notify(new MembershipPaymentVerifiedNotification($membership, $package, $payment));
    }

    private function activatePtPackage(Payment $payment): void
    {
        $package = PtPackage::query()->findOrFail($payment->package_id ?? $payment->pt_package_id);
        $today = CarbonImmutable::today();

        // 1. Mark existing quotas that have 0 remaining sessions as completed
        MemberPtQuota::query()
            ->where('member_id', $payment->member_id)
            ->where('status', 'active')
            ->where('remaining_sessions', '<=', 0)
            ->update(['status' => 'completed']);

        // 2. Check for active quota with remaining sessions
        $latestActiveQuota = MemberPtQuota::query()
            ->where('member_id', $payment->member_id)
            ->where('status', 'active')
            ->where('remaining_sessions', '>', 0)
            ->whereDate('end_date', '>=', $today)
            ->latest('end_date')
            ->first();

        if ($latestActiveQuota) {
            // STACKING: Extend validity from existing quota's end date
            $startDate = $today;
            $baseEndDate = CarbonImmutable::parse($latestActiveQuota->end_date);
            $endDate = $baseEndDate->addDays($package->validity_days);
        } else {
            // FRESH START: Start from today with full package validity
            $startDate = $today;
            $endDate = $startDate->addDays($package->validity_days - 1);
        }

        $memberQuota = MemberPtQuota::query()->create([
            'member_id' => $payment->member_id,
            'pt_package_id' => $package->id,
            'trainer_id' => $payment->trainer_id,
            'source' => 'purchase',
            'total_sessions' => $package->pt_session_count,
            'used_sessions' => 0,
            'remaining_sessions' => $package->pt_session_count,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'active',
        ]);

        $member = User::query()->findOrFail($payment->member_id);
        $member->notify(new ChoosePtScheduleNotification(
            $memberQuota->id,
            $package->name,
            $payment->trainer_id,
        ));
    }
}
