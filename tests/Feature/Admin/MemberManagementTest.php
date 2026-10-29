<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\CheckIn;
use App\Models\MemberProfile;
use App\Models\Membership;
use App\Models\MembershipPackage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private MembershipPackage $package;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->package = MembershipPackage::create([
            'name' => 'Gold Annual Pass',
            'badge' => 'GOLD',
            'tier' => 'Premium & VIP',
            'description' => 'Akses 1 tahun',
            'price' => 3000000,
            'duration_days' => 365,
            'duration_in_days' => 365,
            'is_active' => true,
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('admin.members.index'));
        $response->assertRedirect('/login');
    }

    public function test_member_and_trainer_cannot_access_member_management(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $trainer = User::factory()->create(['role' => 'trainer']);

        $this->actingAs($member)->get(route('admin.members.index'))->assertForbidden();
        $this->actingAs($trainer)->get(route('admin.members.index'))->assertForbidden();
    }

    public function test_admin_can_view_member_management_page_with_auto_update_and_stats(): void
    {
        // Member 1: Active
        $activeUser = User::factory()->create(['name' => 'Budi Active', 'role' => 'member', 'status' => 'active']);
        MemberProfile::create(['user_id' => $activeUser->id, 'member_code' => '#FC-1001']);
        Membership::create([
            'member_id' => $activeUser->id,
            'membership_package_id' => $this->package->id,
            'start_date' => now()->subMonths(1)->toDateString(),
            'end_date' => now()->addMonths(5)->toDateString(),
            'status' => 'active',
        ]);

        // Member 2: Expired membership that needs auto-update
        $expiredUser = User::factory()->create(['name' => 'Siti Expired', 'role' => 'member', 'status' => 'active']);
        MemberProfile::create(['user_id' => $expiredUser->id, 'member_code' => '#FC-1002']);
        $expiredMembership = Membership::create([
            'member_id' => $expiredUser->id,
            'membership_package_id' => $this->package->id,
            'start_date' => now()->subMonths(6)->toDateString(),
            'end_date' => now()->subDays(5)->toDateString(),
            'status' => 'active', // Will be auto-updated to 'expired'
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.members.index'));

        $response->assertOk()
            ->assertViewIs('admin.member-management.index')
            ->assertSee('Manajemen Data Member')
            ->assertSee('Budi Active')
            ->assertSee('Siti Expired');

        // Verify Auto-Update occurred
        $this->assertEquals('expired', $expiredMembership->fresh()->status);
    }

    public function test_search_and_filtering_members(): void
    {
        $member1 = User::factory()->create(['name' => 'Amanda Putri', 'email' => 'amanda@test.com', 'role' => 'member']);
        MemberProfile::create(['user_id' => $member1->id, 'member_code' => '#FC-8921']);

        $member2 = User::factory()->create(['name' => 'Dimas Arya', 'email' => 'dimas@test.com', 'role' => 'member']);
        MemberProfile::create(['user_id' => $member2->id, 'member_code' => '#FC-5520']);

        // Search by name
        $response = $this->actingAs($this->admin)->get(route('admin.members.index', ['search' => 'Amanda']));
        $response->assertSee('Amanda Putri')->assertDontSee('Dimas Arya');

        // Search by member code
        $responseCode = $this->actingAs($this->admin)->get(route('admin.members.index', ['search' => '#FC-5520']));
        $responseCode->assertSee('Dimas Arya')->assertDontSee('Amanda Putri');
    }

    public function test_search_with_filters_provides_clear_options_and_sorting(): void
    {
        $package2 = MembershipPackage::create([
            'name' => 'Basic Monthly Pass',
            'badge' => 'BASIC',
            'tier' => 'Standard',
            'description' => 'Akses 1 bulan',
            'price' => 350000,
            'duration_days' => 30,
            'duration_in_days' => 30,
            'is_active' => true,
        ]);

        $memberFatma = User::factory()->create(['name' => 'Fatmawati', 'email' => 'fatma@test.com', 'role' => 'member']);
        MemberProfile::create(['user_id' => $memberFatma->id, 'member_code' => '#FC-OOCO']);
        Membership::create([
            'member_id' => $memberFatma->id,
            'membership_package_id' => $package2->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(20)->toDateString(),
            'status' => 'active',
        ]);

        $memberUtama = User::factory()->create(['name' => 'Member Utama', 'email' => 'utama@test.com', 'role' => 'member']);
        MemberProfile::create(['user_id' => $memberUtama->id, 'member_code' => '#FC-0001']);
        Membership::create([
            'member_id' => $memberUtama->id,
            'membership_package_id' => $this->package->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'status' => 'active',
        ]);

        // 1. Direct search finds Fatmawati across packages
        $resSearch = $this->actingAs($this->admin)->get(route('admin.members.index', ['search' => 'Fatmawati']));
        $resSearch->assertOk()->assertSee('Fatmawati')->assertDontSee('Member Utama');

        // 2. Search Fatmawati while filtered to another package shows empty state with reset & broaden action
        $resMismatch = $this->actingAs($this->admin)->get(route('admin.members.index', [
            'search' => 'Fatmawati',
            'package' => $this->package->id,
        ]));
        $resMismatch->assertOk()
            ->assertSee('Tidak ada data anggota ditemukan')
            ->assertSee('Cari "Fatmawati" di Semua Paket', false)
            ->assertSee('Reset Semua Filter');

        // 3. Sorting by expiring_soon works cleanly
        $resSort = $this->actingAs($this->admin)->get(route('admin.members.index', ['sort' => 'expiring_soon']));
        $resSort->assertOk();
    }

    public function test_admin_can_view_member_detail_json(): void
    {
        $member = User::factory()->create(['name' => 'Amanda Putri', 'role' => 'member']);
        MemberProfile::create(['user_id' => $member->id, 'member_code' => '#FC-8921', 'gender' => 'Perempuan']);
        $membership = Membership::create([
            'member_id' => $member->id,
            'membership_package_id' => $this->package->id,
            'start_date' => now()->subDays(30)->toDateString(),
            'end_date' => now()->addDays(4)->toDateString(),
            'status' => 'active',
        ]);

        CheckIn::create([
            'member_id' => $member->id,
            'membership_id' => $membership->id,
            'checked_in_at' => now()->subDay(),
            'method' => 'qr',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.members.show', $member));

        $response->assertOk()
            ->assertJsonPath('name', 'Amanda Putri')
            ->assertJsonPath('member_code', '#FC-8921')
            ->assertJsonPath('gender', 'Perempuan')
            ->assertJsonPath('package.name', 'Gold Annual Pass');
    }

    public function test_admin_can_store_new_member_with_validation(): void
    {
        // 1. Invalid data
        $invalidResponse = $this->actingAs($this->admin)->post(route('admin.members.store'), [
            'name' => '',
            'email' => 'not-an-email',
            'phone' => '',
        ]);
        $invalidResponse->assertSessionHasErrors(['name', 'email', 'phone']);

        // 2. Valid data
        $validResponse = $this->actingAs($this->admin)->post(route('admin.members.store'), [
            'name' => 'Farhan Maulana',
            'email' => 'farhan@test.com',
            'phone' => '081277778888',
            'gender' => 'Laki-laki',
            'address' => 'Jakarta Selatan',
            'membership_package_id' => $this->package->id,
            'start_date' => now()->toDateString(),
        ]);

        $validResponse->assertRedirect(route('admin.members.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'Farhan Maulana',
            'email' => 'farhan@test.com',
            'role' => 'member',
            'status' => 'active',
        ]);

        $newUser = User::where('email', 'farhan@test.com')->first();
        $this->assertNotNull($newUser->memberProfile);
        $this->assertDatabaseHas('memberships', [
            'member_id' => $newUser->id,
            'membership_package_id' => $this->package->id,
            'status' => 'active',
        ]);
    }

    public function test_admin_can_update_member_with_validation(): void
    {
        $member = User::factory()->create(['name' => 'Old Name', 'email' => 'old@test.com', 'role' => 'member']);
        MemberProfile::create(['user_id' => $member->id, 'member_code' => '#FC-1234']);

        // 1. Validation error on existing duplicate email
        $otherMember = User::factory()->create(['email' => 'existing@test.com', 'role' => 'member']);
        $errorResponse = $this->actingAs($this->admin)->put(route('admin.members.update', $member), [
            'name' => 'New Name',
            'email' => 'existing@test.com',
            'phone' => '08123456789',
            'status' => 'active',
        ]);
        $errorResponse->assertSessionHasErrors(['email']);

        // 2. Valid update
        $validResponse = $this->actingAs($this->admin)->put(route('admin.members.update', $member), [
            'name' => 'Updated Name',
            'email' => 'updated@test.com',
            'phone' => '081299998888',
            'gender' => 'Laki-laki',
            'status' => 'active',
            'membership_package_id' => $this->package->id,
            'end_date' => now()->addMonths(6)->toDateString(),
        ]);

        $validResponse->assertRedirect(route('admin.members.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $member->id,
            'name' => 'Updated Name',
            'email' => 'updated@test.com',
        ]);
    }

    public function test_admin_can_soft_delete_member_account(): void
    {
        $member = User::factory()->create(['status' => 'active', 'role' => 'member']);
        MemberProfile::create(['user_id' => $member->id, 'member_code' => '#FC-9999']);
        $membership = Membership::create([
            'member_id' => $member->id,
            'membership_package_id' => $this->package->id,
            'start_date' => now()->subMonth()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.members.deactivate', $member));

        $response->assertRedirect(route('admin.members.index'))
            ->assertSessionHas('success');

        // Record still exists in database, but status is inactive
        $this->assertDatabaseHas('users', [
            'id' => $member->id,
            'status' => 'inactive',
        ]);
        $this->assertEquals('inactive', $membership->fresh()->status);
    }

    public function test_admin_can_hard_delete_member_permanently(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        MemberProfile::create(['user_id' => $member->id, 'member_code' => '#FC-8888']);
        Membership::create([
            'member_id' => $member->id,
            'membership_package_id' => $this->package->id,
            'start_date' => now()->subMonth()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.members.destroy', $member));

        $response->assertRedirect(route('admin.members.index'))
            ->assertSessionHas('success');

        // Record completely removed from database
        $this->assertDatabaseMissing('users', ['id' => $member->id]);
        $this->assertDatabaseMissing('member_profiles', ['member_code' => '#FC-8888']);
    }

    public function test_admin_can_export_members_csv(): void
    {
        $member = User::factory()->create(['name' => 'Export Member', 'role' => 'member']);
        MemberProfile::create(['user_id' => $member->id, 'member_code' => '#FC-7777']);

        $response = $this->actingAs($this->admin)->get(route('admin.members.export'));

        $response->assertOk();
        $this->assertTrue(str_contains((string) $response->headers->get('content-type'), 'text/csv'));
    }
}
