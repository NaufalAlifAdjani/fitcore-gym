<?php

namespace Tests\Feature\Admin;

use App\Models\MembershipPackage;
use App\Models\Payment;
use App\Models\User;
use App\Models\VerificationLog;
use Database\Seeders\PaymentSeeder;
use Database\Seeders\PtPackageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_seeder_creates_five_repeatable_records_with_both_package_types(): void
    {
        User::factory()->create(['role' => 'member']);
        User::factory()->create(['role' => 'admin']);
        $this->createMembershipPackage();
        app(PtPackageSeeder::class)->run();

        $seeder = app(PaymentSeeder::class);
        $seeder->run();
        $seeder->run();

        $this->assertDatabaseCount('payments', 5);
        $this->assertSame(2, Payment::query()->where('status', 'pending')->count());
        $this->assertSame(2, Payment::query()->where('status', 'verified')->count());
        $this->assertSame(1, Payment::query()->where('status', 'rejected')->count());
        $this->assertSame(3, Payment::query()->where('package_type', 'membership')->count());
        $this->assertSame(2, Payment::query()->where('package_type', 'pt_session')->count());
    }

    public function test_payment_and_verification_log_relationships_resolve_member_and_admin(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $admin = User::factory()->create(['role' => 'admin']);
        $this->createMembershipPackage();
        app(PtPackageSeeder::class)->run();

        app(PaymentSeeder::class)->run();

        $payment = Payment::query()->where('invoice_id', 'INV-GYM-2026-0002')->firstOrFail();
        $this->assertTrue($payment->member->is($member));
        $this->assertTrue($payment->admin->is($admin));

        $log = VerificationLog::create([
            'payment_id' => $payment->id,
            'admin_id' => $admin->id,
            'action' => 'approve',
            'notes' => 'Bukti pembayaran sesuai.',
        ]);

        $this->assertTrue($log->payment->is($payment));
        $this->assertTrue($log->admin->is($admin));
        $this->assertTrue($payment->fresh()->verificationLogs->contains($log));
    }

    private function createMembershipPackage(): MembershipPackage
    {
        return MembershipPackage::create([
            'name' => 'Monthly Membership',
            'badge' => 'SILVER',
            'tier' => 'Basic',
            'description' => 'Monthly gym access',
            'price' => 450000,
            'promo_price' => null,
            'duration_value' => 1,
            'duration_unit' => 'Bulan',
            'duration_in_days' => 30,
            'facilities' => ['Gym access'],
            'pt_sessions' => 0,
            'is_active' => true,
        ]);
    }
}
