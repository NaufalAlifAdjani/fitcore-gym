<?php

namespace Tests\Feature;

use App\Models\Bank;
use App\Models\MembershipPackage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnsureProfileIsCompleteTest extends TestCase
{
    use RefreshDatabase;

    private function createPackage(): MembershipPackage
    {
        return MembershipPackage::create([
            'name' => 'FitCore Starter Pass',
            'badge' => 'SILVER',
            'tier' => 'Basic',
            'description' => 'Akses pemula hemat',
            'price' => 450000,
            'duration_value' => 1,
            'duration_unit' => 'Bulan',
            'duration_in_days' => 30,
            'facilities' => ['Akses Gym'],
            'pt_sessions' => 0,
            'is_active' => true,
        ]);
    }

    private function createBank(): Bank
    {
        return Bank::create([
            'name' => 'BCA',
            'account_number' => '1234567890',
            'account_holder' => 'PT FitCore Gym',
            'status' => 'active',
        ]);
    }

    public function test_guest_is_redirected_to_login_when_accessing_checkout(): void
    {
        $package = $this->createPackage();

        $response = $this->get(route('member.membership.checkout', $package));

        $response->assertRedirect(route('login'));
    }

    public function test_member_with_incomplete_profile_is_redirected_from_desktop_checkout_to_profile_edit(): void
    {
        $package = $this->createPackage();
        $this->createBank();
        $member = User::factory()->incompleteProfile()->create(['role' => 'member']);

        $this->assertFalse($member->isProfileComplete());

        $response = $this->actingAs($member)->get(route('member.membership.checkout', $package));

        $response->assertRedirect(route('profile.edit'))
            ->assertSessionHas('warning', 'Mohon lengkapi biodata (Nomor Telepon, Alamat, dll) Anda terlebih dahulu sebelum melakukan pembelian paket.')
            ->assertSessionHas('profile_incomplete', true);
    }

    public function test_member_with_incomplete_profile_is_redirected_from_mobile_checkout_to_mobile_profile(): void
    {
        $package = $this->createPackage();
        $this->createBank();
        $member = User::factory()->incompleteProfile()->create(['role' => 'member']);

        $response = $this->actingAs($member)->get(route('mobile.member.packages.checkout', $package));

        $response->assertRedirect(route('mobile.member.profile.index'))
            ->assertSessionHas('warning', 'Mohon lengkapi biodata (Nomor Telepon, Alamat, dll) Anda terlebih dahulu sebelum melakukan pembelian paket.')
            ->assertSessionHas('profile_incomplete', true);
    }

    public function test_member_can_complete_profile_information_via_profile_update(): void
    {
        $member = User::factory()->incompleteProfile()->create([
            'role' => 'member',
            'phone' => null,
        ]);

        $this->assertFalse($member->isProfileComplete());

        $response = $this->actingAs($member)->patch(route('profile.update'), [
            'name' => $member->name,
            'email' => $member->email,
            'phone' => '081234567890',
            'address' => 'Jl. Sudirman No. 45, Jakarta Pusat',
            'gender' => 'male',
        ]);

        $response->assertRedirect(route('profile.edit'))
            ->assertSessionHas('status', 'profile-updated');

        $member->refresh();

        $this->assertSame('081234567890', $member->phone);
        $this->assertSame('Jl. Sudirman No. 45, Jakarta Pusat', $member->memberProfile->address);
        $this->assertSame('male', $member->memberProfile->gender);
        $this->assertTrue($member->isProfileComplete());
    }

    public function test_member_with_complete_profile_can_access_desktop_checkout(): void
    {
        $package = $this->createPackage();
        $this->createBank();
        $member = User::factory()->completeProfile()->create(['role' => 'member']);

        $this->assertTrue($member->isProfileComplete());

        $response = $this->actingAs($member)->get(route('member.membership.checkout', $package));

        $response->assertOk()
            ->assertSee($package->name);
    }

    public function test_member_with_complete_profile_can_access_mobile_checkout(): void
    {
        $package = $this->createPackage();
        $this->createBank();
        $member = User::factory()->completeProfile()->create(['role' => 'member']);

        $this->assertTrue($member->isProfileComplete());

        $response = $this->actingAs($member)->get(route('mobile.member.packages.checkout', $package));

        $response->assertOk()
            ->assertSee($package->name);
    }
}
