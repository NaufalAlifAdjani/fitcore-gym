<?php

namespace Tests\Feature;

use App\Console\Commands\SystemUpdateMembershipStatus;
use App\Mail\MemberMembershipExpiringMail;
use App\Models\Membership;
use App\Models\MembershipPackage;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SystemUpdateMembershipStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_updates_overdue_memberships_to_expired(): void
    {
        Mail::fake();

        $member = User::factory()->create(['role' => 'member']);
        $package = MembershipPackage::create([
            'name' => 'Monthly Pass',
            'tier' => 'Basic',
            'price' => 300000,
            'duration_in_days' => 30,
            'is_active' => true,
        ]);

        // Overdue membership (ended yesterday)
        $expiredMembership = Membership::create([
            'member_id' => $member->id,
            'membership_package_id' => $package->id,
            'start_date' => today()->subDays(31),
            'end_date' => today()->subDay(),
            'status' => 'active',
        ]);

        // Still active membership (ends tomorrow)
        $activeMembership = Membership::create([
            'member_id' => $member->id,
            'membership_package_id' => $package->id,
            'start_date' => today()->subDays(10),
            'end_date' => today()->addDay(),
            'status' => 'active',
        ]);

        $this->artisan(SystemUpdateMembershipStatus::class)
            ->assertSuccessful();

        $this->assertEquals('expired', $expiredMembership->fresh()->status);
        $this->assertEquals('active', $activeMembership->fresh()->status);
    }

    public function test_command_queues_reminder_email_for_memberships_expiring_in_three_days(): void
    {
        Mail::fake();

        $member = User::factory()->create(['role' => 'member', 'email' => 'member@example.com']);
        $package = MembershipPackage::create([
            'name' => 'Gold Pass',
            'tier' => 'Premium',
            'price' => 600000,
            'duration_in_days' => 30,
            'is_active' => true,
        ]);

        // Expiring in exactly 3 days
        $expiringMembership = Membership::create([
            'member_id' => $member->id,
            'membership_package_id' => $package->id,
            'start_date' => today()->subDays(27),
            'end_date' => today()->addDays(3),
            'status' => 'active',
        ]);

        // Expiring in 5 days (should NOT trigger email)
        $otherMembership = Membership::create([
            'member_id' => $member->id,
            'membership_package_id' => $package->id,
            'start_date' => today()->subDays(25),
            'end_date' => today()->addDays(5),
            'status' => 'active',
        ]);

        $this->artisan('system:update-membership-status')
            ->assertSuccessful();

        Mail::assertQueued(MemberMembershipExpiringMail::class, function (MemberMembershipExpiringMail $mail) use ($expiringMembership) {
            return $mail->hasTo('member@example.com') &&
                   $mail->membership->id === $expiringMembership->id;
        });

        Mail::assertQueuedCount(1);
    }

    public function test_expiring_mail_implements_should_queue(): void
    {
        $this->assertTrue(is_subclass_of(MemberMembershipExpiringMail::class, ShouldQueue::class));
    }
}
