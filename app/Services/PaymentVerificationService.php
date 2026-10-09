<?php

namespace App\Services;

use App\Exceptions\PaymentAlreadyProcessedException;
use App\Models\Membership;
use App\Models\MembershipPackage;
use App\Models\Payment;
use App\Models\PtPackage;
use App\Models\PtSessionPackage;
use App\Models\User;
use App\Notifications\ChoosePtScheduleNotification;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentVerificationService
{
    public function approvePayment(Payment $payment, int $adminId): Payment
    {
        return DB::transaction(function () use ($payment, $adminId): Payment {
            $lockedPayment = $this->lockPendingPayment($payment);

            if ($lockedPayment->package_type === 'membership') {
                $this->activateMembership($lockedPayment);
            } else {
                $this->activatePtPackage($lockedPayment);
            }

            $lockedPayment->forceFill([
                'status' => 'verified',
                'verified_at' => now(),
                'verified_by' => $adminId,
                'rejection_reason' => null,
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

        $package = MembershipPackage::query()->findOrFail($payment->package_id);
        $today = CarbonImmutable::today();
        $latestActiveEndDate = Membership::query()
            ->where('member_id', $payment->member_id)
            ->where('status', 'active')
            ->whereDate('end_date', '>=', $today)
            ->max('end_date');

        $startDate = $latestActiveEndDate
            ? CarbonImmutable::parse($latestActiveEndDate)->addDay()
            : $today;
        $endDate = $startDate->addDays($package->duration_in_days - 1);

        Membership::query()
            ->where('member_id', $payment->member_id)
            ->where('status', 'active')
            ->whereDate('end_date', '<', $startDate)
            ->update(['status' => 'expired']);

        Membership::query()->create([
            'member_id' => $payment->member_id,
            'package_id' => $package->id,
            'payment_id' => $payment->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'active',
        ]);
    }

    private function activatePtPackage(Payment $payment): void
    {
        $package = PtPackage::query()->findOrFail($payment->package_id);

        $memberPackage = PtSessionPackage::query()->create([
            'member_id' => $payment->member_id,
            'pt_package_id' => $package->id,
            'payment_id' => $payment->id,
            'sessions_total' => $package->sessions_count,
            'sessions_remaining' => $package->sessions_count,
            'status' => 'active',
        ]);

        $member = User::query()->findOrFail($payment->member_id);
        $member->notify(new ChoosePtScheduleNotification(
            $memberPackage->id,
            $package->name,
        ));
    }
}
