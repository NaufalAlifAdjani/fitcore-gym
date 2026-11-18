<?php

namespace App\Console\Commands;

use App\Mail\MemberMembershipExpiringMail;
use App\Models\Membership;
use App\Notifications\MembershipExpiringNotification;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

#[Signature('system:update-membership-status')]
#[Description('Update expired memberships status and send reminder notifications for memberships expiring in 3 days (H-3) and 1 day (H-1).')]
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

        // 2. Find active memberships where end_date == today()->addDays(3) (H-3)
        $reminderDateH3 = $today->copy()->addDays(3);
        $expiringH3 = Membership::query()
            ->with(['member', 'package'])
            ->where('status', 'active')
            ->whereDate('end_date', $reminderDateH3)
            ->get();

        $h3Count = 0;
        foreach ($expiringH3 as $membership) {
            if ($membership->member) {
                if ($membership->member->email) {
                    Mail::to($membership->member->email)->queue(new MemberMembershipExpiringMail($membership));
                }
                $membership->member->notify(new MembershipExpiringNotification($membership, 3));
                $h3Count++;
            }
        }

        $this->info("Mengirim {$h3Count} pengingat membership (H-3) via Push Notification & Email.");

        // 3. Find active memberships where end_date == today()->addDays(1) (H-1)
        $reminderDateH1 = $today->copy()->addDays(1);
        $expiringH1 = Membership::query()
            ->with(['member', 'package'])
            ->where('status', 'active')
            ->whereDate('end_date', $reminderDateH1)
            ->get();

        $h1Count = 0;
        foreach ($expiringH1 as $membership) {
            if ($membership->member) {
                $membership->member->notify(new MembershipExpiringNotification($membership, 1));
                $h1Count++;
            }
        }

        $this->info("Mengirim {$h1Count} pengingat membership (H-1) via Push Notification.");

        return self::SUCCESS;
    }
}
