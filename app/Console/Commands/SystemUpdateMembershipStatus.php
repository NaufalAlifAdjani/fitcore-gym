<?php

namespace App\Console\Commands;

use App\Mail\MemberMembershipExpiringMail;
use App\Models\Membership;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

#[Signature('system:update-membership-status')]
#[Description('Update expired memberships status and send reminder emails for memberships expiring in 3 days.')]
class SystemUpdateMembershipStatus extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $today = today();

        // 1. Update active memberships where end_date < today to expired
        $expiredCount = Membership::query()
            ->where('status', 'active')
            ->whereDate('end_date', '<', $today)
            ->update(['status' => 'expired']);

        $this->info("Memperbarui status {$expiredCount} membership yang kedaluwarsa menjadi 'expired'.");

        // 2. Find active memberships where end_date == today()->addDays(3)
        $reminderDate = $today->copy()->addDays(3);
        $expiringMemberships = Membership::query()
            ->with(['member', 'package'])
            ->where('status', 'active')
            ->whereDate('end_date', $reminderDate)
            ->get();

        $emailCount = 0;
        foreach ($expiringMemberships as $membership) {
            if ($membership->member?->email) {
                Mail::to($membership->member->email)->queue(new MemberMembershipExpiringMail($membership));
                $emailCount++;
            }
        }

        $this->info("Mengirim {$emailCount} email pengingat membership (H-3) via antrean (Queue).");

        return self::SUCCESS;
    }
}
