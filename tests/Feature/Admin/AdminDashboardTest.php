<?php

namespace Tests\Feature\Admin;

use App\Models\Bank;
use App\Models\CheckIn;
use App\Models\Membership;
use App\Models\MembershipPackage;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_dashboard_with_metrics_and_chart(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $member = User::factory()->create(['role' => 'member']);
        $bank = Bank::create([
            'name' => 'BCA',
            'account_number' => '1234567890',
            'account_holder' => 'FitCore Gym',
            'status' => 'active',
        ]);

        $package = MembershipPackage::create([
            'name' => 'Gold Membership',
            'tier' => 'Premium',
            'price' => 500000,
            'duration_in_days' => 30,
            'facilities' => ['Gym access'],
            'is_active' => true,
        ]);

        // 1. Active membership
        $membership = Membership::create([
            'member_id' => $member->id,
            'membership_package_id' => $package->id,
            'start_date' => today(),
            'end_date' => today()->addDays(30),
            'status' => 'active',
        ]);

        // 2. Verified payment this month
        Payment::create([
            'invoice_id' => 'INV-TEST-001',
            'member_id' => $member->id,
            'bank_id' => $bank->id,
            'package_id' => $package->id,
            'package_type' => 'membership',
            'payment_type' => 'membership',
            'amount' => 500000,
            'status' => 'verified',
            'verified_at' => now(),
            'created_at' => now(),
        ]);

        // Pending payment (should not be counted in totalRevenue)
        Payment::create([
            'invoice_id' => 'INV-TEST-002',
            'member_id' => $member->id,
            'bank_id' => $bank->id,
            'package_id' => $package->id,
            'package_type' => 'membership',
            'payment_type' => 'membership',
            'amount' => 200000,
            'status' => 'pending',
            'created_at' => now(),
        ]);

        // 3. Check-in this month
        CheckIn::create([
            'member_id' => $member->id,
            'membership_id' => $membership->id,
            'checked_in_at' => now(),
            'method' => 'manual',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk()
            ->assertViewIs('admin.dashboard.admin_dashboard')
            ->assertViewHas('activeMembers', 1)
            ->assertViewHas('totalRevenue', 500000.0)
            ->assertViewHas('totalCheckIns', 1)
            ->assertViewHas('chartLabels')
            ->assertViewHas('chartData')
            ->assertSee('Dashboard Utama')
            ->assertSee('adminRevenueChart')
            ->assertSee('https://cdn.jsdelivr.net/npm/chart.js', false);
    }

    public function test_non_admin_cannot_access_admin_dashboard(): void
    {
        $member = User::factory()->create(['role' => 'member']);

        $this->actingAs($member)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
    }
}
