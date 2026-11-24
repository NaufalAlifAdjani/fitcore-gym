<?php

namespace Tests\Feature;

use App\Models\Bank;
use App\Models\MemberPtQuota;
use App\Models\MembershipPackage;
use App\Models\Payment;
use App\Models\User;
use App\Services\PaymentVerificationService;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MembershipNoPtBonusTest extends TestCase
{
    use RefreshDatabase;

    private function createMember(): User
    {
        return User::factory()->completeProfile()->create([
            'role' => 'member',
        ]);
    }

    private function createPackage(): MembershipPackage
    {
        return MembershipPackage::create([
            'name' => 'FitCore Gold Pure Gym',
            'badge' => 'POPULAR',
            'tier' => 'Standard',
            'description' => 'Murni akses fasilitas kebugaran gym',
            'price' => 750000,
            'duration_value' => 1,
            'duration_unit' => 'Bulan',
            'duration_in_days' => 30,
            'facilities' => ['Akses Gym 24 Jam', 'Loker Privat'],
            'pt_sessions' => 0,
            'pt_session_count' => 0,
            'is_active' => true,
        ]);
    }

    public function test_approving_membership_payment_does_not_grant_pt_quota(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $member = $this->createMember();
        $package = $this->createPackage();

        $bank = Bank::create([
            'name' => 'BCA',
            'account_number' => '1234567890',
            'account_holder' => 'FitCore Gym',
            'status' => 'active',
        ]);

        $payment = Payment::create([
            'invoice_id' => 'INV-MEM-TEST-001',
            'member_id' => $member->id,
            'package_id' => $package->id,
            'package_type' => 'membership',
            'payment_type' => 'membership',
            'amount' => $package->price,
            'bank_id' => $bank->id,
            'bank_sender' => 'Pengirim Test',
            'status' => 'pending',
            'transfer_date' => CarbonImmutable::now(),
        ]);

        $service = app(PaymentVerificationService::class);
        $service->approvePayment($payment, $admin->id);

        $payment->refresh();
        $this->assertSame('verified', $payment->status);

        // Assert member has active membership
        $this->assertDatabaseHas('memberships', [
            'member_id' => $member->id,
            'membership_package_id' => $package->id,
            'status' => 'active',
        ]);

        // Assert NO MemberPtQuota was created for this member
        $this->assertDatabaseMissing('member_pt_quotas', [
            'member_id' => $member->id,
        ]);
        $this->assertSame(0, MemberPtQuota::where('member_id', $member->id)->count());
    }

    public function test_membership_package_catalog_views_do_not_contain_pt_session_bonus(): void
    {
        $member = $this->createMember();
        $this->createPackage();

        // 1. Landing Page
        $this->get(route('landing.index'))
            ->assertOk()
            ->assertDontSee('Sesi Personal Trainer Gratis')
            ->assertDontSee('PT Gratis');

        // 2. Landing Packages
        $this->get(route('landing.packages'))
            ->assertOk()
            ->assertDontSee('Sesi Personal Trainer Gratis')
            ->assertDontSee('PT Gratis');

        // 3. Web Member Packages
        $this->actingAs($member)
            ->get(route('member.membership.packages'))
            ->assertOk()
            ->assertDontSee('Personal Trainer Gratis')
            ->assertDontSee('PT Gratis');

        // 4. Mobile Member Packages
        $this->actingAs($member)
            ->get(route('mobile.packages.index'))
            ->assertOk()
            ->assertDontSee('PT Gratis')
            ->assertDontSee('Personal Trainer Gratis');
    }
}
