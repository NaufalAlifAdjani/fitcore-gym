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
            ->assertViewHas('pendingPayments')
            ->assertViewHas('recentActivities')
            ->assertViewHas('pendingVerificationsCount', 1)
            ->assertSee('Dashboard Performa Gym')
            ->assertSee('TOTAL MEMBER AKTIF')
            ->assertSee('Antrean Menunggu Verifikasi')
            ->assertSee('INV-TEST-002')
            ->assertSee('Perhatian: Ada 1 pembayaran baru menunggu verifikasi!')
            ->assertSee('adminRevenueChart')
            ->assertSee('https://cdn.jsdelivr.net/npm/chart.js', false);
    }

    public function test_dashboard_pending_payments_queue_only_contains_pending_payments(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $member = User::factory()->create(['role' => 'member', 'name' => 'John Doe', 'email' => 'john@example.com']);
        $bank = Bank::create([
            'name' => 'BCA',
            'account_number' => '1234567890',
            'account_holder' => 'FitCore Gym',
            'status' => 'active',
        ]);

        $package = MembershipPackage::create([
            'name' => 'Platinum Pass',
            'tier' => 'Platinum',
            'price' => 750000,
            'duration_in_days' => 60,
            'facilities' => ['All access'],
            'is_active' => true,
        ]);

        $pendingPayment = Payment::create([
            'invoice_id' => 'INV-PENDING-999',
            'member_id' => $member->id,
            'bank_id' => $bank->id,
            'package_id' => $package->id,
            'package_type' => 'membership',
            'payment_type' => 'membership',
            'amount' => 750000,
            'bank_sender' => 'BNI',
            'bank_destination' => 'Mandiri FitCore',
            'status' => 'pending',
            'created_at' => now(),
        ]);

        $verifiedPayment = Payment::create([
            'invoice_id' => 'INV-VERIFIED-888',
            'member_id' => $member->id,
            'bank_id' => $bank->id,
            'package_id' => $package->id,
            'package_type' => 'membership',
            'payment_type' => 'membership',
            'amount' => 500000,
            'status' => 'verified',
            'verified_at' => now(),
            'created_at' => now()->subDay(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk()
            ->assertSee('Antrean Menunggu Verifikasi')
            ->assertSee('INV-PENDING-999')
            ->assertSee('John Doe')
            ->assertSee('john@example.com')
            ->assertSee('Platinum Pass')
            ->assertSee('MEMBERSHIP')
            ->assertSee('Rp 750.000')
            ->assertSee('BNI')
            ->assertSee('ke Mandiri FitCore')
            ->assertSee('PENDING')
            ->assertDontSee('INV-VERIFIED-888');
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

    public function test_revenue_chart_supports_contextual_ranges_7_days_30_days_and_this_year(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // 1. Default / 7_days
        $response7 = $this->actingAs($admin)->get(route('admin.dashboard', ['range' => '7_days']));
        $response7->assertOk()
            ->assertViewHas('currentRange', '7_days')
            ->assertViewHas('chartTitle', 'Pendapatan 7 Hari Terakhir')
            ->assertSee('Pendapatan 7 Hari Terakhir')
            ->assertSee('onchange="window.location.href=\'?range=\' + this.value"', false);

        $labels7 = $response7->viewData('chartLabels');
        $data7 = $response7->viewData('chartData');
        $this->assertCount(7, $labels7);
        $this->assertCount(7, $data7);

        // 2. 30_days
        $response30 = $this->actingAs($admin)->get(route('admin.dashboard', ['range' => '30_days']));
        $response30->assertOk()
            ->assertViewHas('currentRange', '30_days')
            ->assertViewHas('chartTitle', 'Pendapatan 30 Hari Terakhir')
            ->assertSee('Pendapatan 30 Hari Terakhir');

        $labels30 = $response30->viewData('chartLabels');
        $data30 = $response30->viewData('chartData');
        $this->assertCount(30, $labels30);
        $this->assertCount(30, $data30);

        // 3. this_year
        $responseYear = $this->actingAs($admin)->get(route('admin.dashboard', ['range' => 'this_year']));
        $responseYear->assertOk()
            ->assertViewHas('currentRange', 'this_year')
            ->assertViewHas('chartTitle', 'Pendapatan Tahun Ini')
            ->assertSee('Pendapatan Tahun Ini');

        $labelsYear = $responseYear->viewData('chartLabels');
        $dataYear = $responseYear->viewData('chartData');
        $this->assertCount(12, $labelsYear);
        $this->assertCount(12, $dataYear);

        // 4. Fallback on invalid range
        $responseInvalid = $this->actingAs($admin)->get(route('admin.dashboard', ['range' => 'invalid_range']));
        $responseInvalid->assertOk()
            ->assertViewHas('currentRange', '7_days')
            ->assertViewHas('chartTitle', 'Pendapatan 7 Hari Terakhir');
    }
}
