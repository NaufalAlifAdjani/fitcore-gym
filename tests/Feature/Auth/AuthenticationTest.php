<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('member.dashboard', absolute: false));
    }

    public function test_members_are_redirected_to_the_member_dashboard_after_login(): void
    {
        $member = User::factory()->create(['role' => 'member']);

        $this->post('/login', [
            'email' => $member->email,
            'password' => 'password',
        ])->assertRedirect(route('member.dashboard', absolute: false));

        $this->assertAuthenticatedAs($member);
    }

    public function test_admins_are_redirected_to_the_admin_dashboard_after_login(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard', absolute: false));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_members_accessing_root_dashboard_are_redirected_to_member_dashboard(): void
    {
        $member = User::factory()->create(['role' => 'member']);

        $this->actingAs($member)
            ->get('/dashboard')
            ->assertRedirect(route('member.dashboard'));
    }

    public function test_admins_accessing_root_dashboard_are_redirected_to_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get('/dashboard')
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_members_can_view_member_dashboard(): void
    {
        $member = User::factory()->create(['role' => 'member']);

        $this->actingAs($member)
            ->get(route('member.dashboard'))
            ->assertOk()
            ->assertSee('FITCORE')
            ->assertSee('ATHLETIC')
            ->assertSee('Konten Beranda Member');
    }

    public function test_non_members_cannot_view_member_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('member.dashboard'))
            ->assertForbidden();
    }

    public function test_guests_cannot_view_member_dashboard(): void
    {
        $this->get(route('member.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_users_see_logout_confirmation_before_logout(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Konfirmasi Keluar')
            ->assertSee('Apakah Anda yakin ingin keluar dari akun ini? Sesi Anda saat ini akan diakhiri.')
            ->assertSee('Batal')
            ->assertSee('Ya, Keluar')
            ->assertSee('showLogoutModal = true', false)
            ->assertSee('showLogoutModal = false', false)
            ->assertSee('action="'.route('logout').'"', false);
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
