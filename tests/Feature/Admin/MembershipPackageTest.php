<?php

namespace Tests\Feature\Admin;

use App\Models\MembershipPackage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MembershipPackageTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_membership_package(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $this->post(route('admin.packages.store'), $this->packageData())
            ->assertRedirect(route('admin.packages.index'));

        $this->assertDatabaseHas('membership_packages', [
            'name' => 'FitCore Basic',
            'tier' => 'Basic',
            'price' => 1_000_000,
        ]);
    }

    public function test_admin_can_update_a_membership_package(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $package = MembershipPackage::create($this->packageData());
        $payload = array_merge($this->packageData(), [
            'name' => 'FitCore Premium',
            'tier' => 'Premium & VIP',
            'price' => 2_000_000,
            'promo_price' => 1_500_000,
        ]);

        $this->put(route('admin.packages.update', $package), $payload)
            ->assertRedirect(route('admin.packages.index'));

        $this->assertDatabaseHas('membership_packages', [
            'id' => $package->id,
            'name' => 'FitCore Premium',
            'tier' => 'Premium & VIP',
            'price' => 2_000_000,
            'promo_price' => 1_500_000,
        ]);
    }

    public function test_admin_can_filter_packages_by_tier_status_and_name(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        MembershipPackage::create($this->packageData());
        MembershipPackage::create($this->packageData([
            'name' => 'FitCore Premium',
            'tier' => 'Premium & VIP',
            'is_active' => false,
        ]));

        $this->get(route('admin.packages.index', [
            'tier' => 'Premium & VIP',
            'status' => 'inactive',
            'search' => 'Premium',
        ]))
            ->assertOk()
            ->assertSee('FitCore Premium')
            ->assertDontSee('FitCore Basic');
    }

    public function test_admin_dashboard_and_navigation_pages_render(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $adminPages = [
            route('admin.dashboard'),
            route('admin.pt-sessions.index'),
            route('admin.payments.index'),
            route('admin.check-in.index'),
            route('admin.members.index'),
            route('admin.settings.index'),
            route('admin.help.index'),
        ];

        foreach ($adminPages as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_package_text_is_html_escaped_in_the_membership_list(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        MembershipPackage::create($this->packageData([
            'name' => '<script>alert("xss")</script>',
            'description' => '<script>alert("xss")</script>',
        ]));

        $this->get(route('admin.packages.index'))
            ->assertOk()
            ->assertSee('&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;', false)
            ->assertDontSee('<script>alert("xss")</script>', false);
    }

    public function test_status_toggle_returns_the_new_state_as_json(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $package = MembershipPackage::create($this->packageData());

        $this->patchJson(route('admin.packages.toggle-status', $package))
            ->assertOk()
            ->assertJsonPath('is_active', false);

        $this->assertDatabaseHas('membership_packages', [
            'id' => $package->id,
            'is_active' => false,
        ]);
    }

    public function test_guest_is_redirected_to_login_when_requesting_packages(): void
    {
        $this->get(route('admin.packages.index'))
            ->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_view_or_change_membership_packages(): void
    {
        $package = MembershipPackage::create($this->packageData());
        $this->actingAs(User::factory()->create(['role' => 'member']));

        $this->get(route('admin.packages.index'))
            ->assertForbidden()
            ->assertDontSee('FitCore Basic');

        $this->post(route('admin.packages.store'), $this->packageData())
            ->assertForbidden();

        $this->put(route('admin.packages.update', $package), $this->packageData([
            'name' => 'Unauthorized change',
        ]))
            ->assertForbidden();

        $this->patchJson(route('admin.packages.toggle-status', $package))
            ->assertForbidden();

        $this->assertDatabaseHas('membership_packages', [
            'id' => $package->id,
            'name' => 'FitCore Basic',
            'is_active' => true,
        ]);
        $this->assertDatabaseCount('membership_packages', 1);
    }

    public function test_promo_price_must_be_lower_than_regular_price(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $this->post(route('admin.packages.store'), $this->packageData([
            'promo_price' => 1_000_000,
        ]))
            ->assertSessionHasErrors([
                'promo_price' => 'The promo price field must be less than 1000000.',
            ]);

        $this->assertDatabaseCount('membership_packages', 0);
    }

    private function packageData(array $overrides = []): array
    {
        return array_merge([
            'name' => 'FitCore Basic',
            'badge' => 'SILVER',
            'tier' => 'Basic',
            'description' => 'Akses untuk pemula',
            'price' => 1_000_000,
            'promo_price' => null,
            'duration_value' => 1,
            'duration_unit' => 'Bulan',
            'duration_in_days' => 30,
            'facilities' => ['Akses Gym'],
            'pt_sessions' => 0,
            'is_active' => 1,
        ], $overrides);
    }
}
