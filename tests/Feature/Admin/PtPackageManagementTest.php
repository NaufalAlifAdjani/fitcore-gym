<?php

namespace Tests\Feature\Admin;

use App\Models\Bank;
use App\Models\Membership;
use App\Models\MembershipPackage;
use App\Models\PtPackage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PtPackageManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_pt_packages_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $package = PtPackage::factory()->create([
            'name' => 'PT Test 12 Sesi',
            'pt_session_count' => 12,
            'price' => 3000000,
            'min_membership_days' => 14,
            'validity_days' => 60,
            'status' => 'active',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.pt-packages.index'))
            ->assertOk()
            ->assertSee('PT Test 12 Sesi')
            ->assertSee('12 Sesi')
            ->assertSee('Min. 14 Hari')
            ->assertSee('60 Hari');

        // Test alias route
        $this->actingAs($admin)
            ->get(route('admin.pt-sessions.index'))
            ->assertOk()
            ->assertSee('PT Test 12 Sesi');
    }

    public function test_guest_is_redirected_to_login_when_accessing_admin_pt_packages(): void
    {
        $this->get(route('admin.pt-packages.index'))
            ->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_or_manage_pt_packages(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $trainer = User::factory()->create(['role' => 'trainer']);
        $package = PtPackage::factory()->create(['name' => 'PT Existing']);

        // Member
        $this->actingAs($member)->get(route('admin.pt-packages.index'))->assertForbidden();
        $this->actingAs($member)->post(route('admin.pt-packages.store'), $this->validPackageData())->assertForbidden();
        $this->actingAs($member)->put(route('admin.pt-packages.update', $package), $this->validPackageData())->assertForbidden();
        $this->actingAs($member)->patch(route('admin.pt-packages.toggle-status', $package))->assertForbidden();

        // Trainer
        $this->actingAs($trainer)->get(route('admin.pt-packages.index'))->assertForbidden();
        $this->actingAs($trainer)->post(route('admin.pt-packages.store'), $this->validPackageData())->assertForbidden();
        $this->actingAs($trainer)->put(route('admin.pt-packages.update', $package), $this->validPackageData())->assertForbidden();
        $this->actingAs($trainer)->patch(route('admin.pt-packages.toggle-status', $package))->assertForbidden();
    }

    public function test_admin_can_create_a_pt_package(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->post(route('admin.pt-packages.store'), [
                'name' => 'PT Performance Gold 20 Sesi',
                'pt_session_count' => 20,
                'price' => 5000000,
                'min_membership_days' => 30,
                'validity_days' => 90,
                'is_active' => true,
            ]);

        $response->assertRedirect(route('admin.pt-packages.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('pt_packages', [
            'name' => 'PT Performance Gold 20 Sesi',
            'pt_session_count' => 20,
            'price' => 5000000,
            'min_membership_days' => 30,
            'validity_days' => 90,
            'status' => 'active',
        ]);
    }

    public function test_admin_can_update_a_pt_package(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $package = PtPackage::factory()->create([
            'name' => 'PT Old Name',
            'pt_session_count' => 8,
            'price' => 2000000,
            'min_membership_days' => 0,
            'validity_days' => 30,
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)
            ->put(route('admin.pt-packages.update', $package), [
                'name' => 'PT Updated Elite 10 Sesi',
                'pt_session_count' => 10,
                'price' => 2500000,
                'min_membership_days' => 15,
                'validity_days' => 45,
                'is_active' => true,
            ]);

        $response->assertRedirect(route('admin.pt-packages.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('pt_packages', [
            'id' => $package->id,
            'name' => 'PT Updated Elite 10 Sesi',
            'pt_session_count' => 10,
            'price' => 2500000,
            'min_membership_days' => 15,
            'validity_days' => 45,
        ]);
    }

    public function test_admin_can_toggle_package_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $package = PtPackage::factory()->create([
            'status' => 'active',
        ]);

        // Toggle to inactive via JSON
        $jsonResponse = $this->actingAs($admin)
            ->patchJson(route('admin.pt-packages.toggle-status', $package));

        $jsonResponse->assertOk()
            ->assertJsonPath('status', 'inactive')
            ->assertJsonPath('is_active', false);

        $this->assertDatabaseHas('pt_packages', [
            'id' => $package->id,
            'status' => 'inactive',
        ]);

        // Toggle back to active via standard request
        $response = $this->actingAs($admin)
            ->patch(route('admin.pt-packages.toggle-status', $package));

        $response->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('pt_packages', [
            'id' => $package->id,
            'status' => 'active',
        ]);
    }

    public function test_validation_rejects_negative_or_invalid_values(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Missing fields
        $this->actingAs($admin)
            ->post(route('admin.pt-packages.store'), [])
            ->assertSessionHasErrors(['name', 'pt_session_count', 'price', 'min_membership_days', 'validity_days']);

        // Negative values & zero sessions
        $this->actingAs($admin)
            ->post(route('admin.pt-packages.store'), [
                'name' => 'Invalid Package',
                'pt_session_count' => 0,
                'price' => -50000,
                'min_membership_days' => -5,
                'validity_days' => 0,
            ])
            ->assertSessionHasErrors([
                'pt_session_count',
                'price',
                'min_membership_days',
                'validity_days',
            ]);
    }

    public function test_admin_can_filter_pt_packages_by_search_and_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        PtPackage::factory()->create(['name' => 'PT Alpha Active', 'status' => 'active']);
        PtPackage::factory()->create(['name' => 'PT Beta Active', 'status' => 'active']);
        PtPackage::factory()->create(['name' => 'PT Alpha Inactive', 'status' => 'inactive']);

        // Filter search
        $this->actingAs($admin)
            ->get(route('admin.pt-packages.index', ['search' => 'Alpha']))
            ->assertOk()
            ->assertSee('PT Alpha Active')
            ->assertSee('PT Alpha Inactive')
            ->assertDontSee('PT Beta Active');

        // Filter status inactive
        $this->actingAs($admin)
            ->get(route('admin.pt-packages.index', ['status' => 'inactive']))
            ->assertOk()
            ->assertSee('PT Alpha Inactive')
            ->assertDontSee('PT Beta Active');
    }

    public function test_inactive_package_cannot_be_selected_or_purchased_by_member(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $membershipPackage = MembershipPackage::create([
            'name' => 'FitCore Annual',
            'badge' => 'GOLD',
            'tier' => 'Standard',
            'price' => 3000000,
            'duration_value' => 12,
            'duration_unit' => 'Bulan',
            'duration_in_days' => 365,
            'facilities' => ['Akses Gym'],
            'is_active' => true,
        ]);

        $membership = Membership::create([
            'member_id' => $member->id,
            'membership_package_id' => $membershipPackage->id,
            'start_date' => now()->startOfDay()->toDateString(),
            'end_date' => now()->addDays(90)->toDateString(),
            'status' => 'active',
        ]);

        $bank = Bank::create([
            'name' => 'BCA',
            'account_number' => '123456789',
            'account_holder' => 'FitCore Gym',
            'status' => 'active',
        ]);

        $inactivePackage = PtPackage::factory()->create([
            'name' => 'PT Khusus Nonaktif',
            'pt_session_count' => 10,
            'price' => 2000000,
            'min_membership_days' => 0,
            'validity_days' => 30,
            'status' => 'inactive',
        ]);

        // 1. Not in member packages list
        $this->actingAs($member)
            ->get(route('member.pt-packages.index'))
            ->assertOk()
            ->assertDontSee('PT Khusus Nonaktif');

        // 2. Select trainer with inactive package redirects with error
        $this->actingAs($member)
            ->get(route('member.pt-packages.select-trainer', ['package_id' => $inactivePackage->id]))
            ->assertRedirect(route('member.pt-packages.index'))
            ->assertSessionHas('error');

        // 3. Checkout with inactive package redirects with error
        $this->actingAs($member)
            ->get(route('member.pt-packages.checkout', ['package_id' => $inactivePackage->id]))
            ->assertRedirect(route('member.pt-packages.index'))
            ->assertSessionHas('error');

        // 4. Process checkout with inactive package redirects with error
        $this->actingAs($member)
            ->post(route('member.pt-packages.process-checkout'), [
                'package_id' => $inactivePackage->id,
                'bank_id' => $bank->id,
            ])
            ->assertRedirect(route('member.pt-packages.index'))
            ->assertSessionHas('error');

        // 5. Buy with inactive package fails
        $this->actingAs($member)
            ->postJson(route('member.pt-packages.buy'), [
                'pt_package_id' => $inactivePackage->id,
                'bank_id' => $bank->id,
            ])
            ->assertStatus(404);
    }

    private function validPackageData(array $overrides = []): array
    {
        return array_merge([
            'name' => 'PT Paket Standard 8 Sesi',
            'pt_session_count' => 8,
            'price' => 2000000,
            'min_membership_days' => 0,
            'validity_days' => 45,
            'is_active' => true,
        ], $overrides);
    }
}
