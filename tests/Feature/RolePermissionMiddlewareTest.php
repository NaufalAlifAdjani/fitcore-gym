<?php

namespace Tests\Feature;

use App\Models\TrainerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class RolePermissionMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_model_role_helper_methods(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $trainer = User::factory()->create(['role' => 'trainer']);
        $member = User::factory()->create(['role' => 'member']);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($admin->isTrainer());
        $this->assertFalse($admin->isMember());
        $this->assertTrue($admin->hasRole('admin'));
        $this->assertTrue($admin->hasRole(['admin', 'trainer']));
        $this->assertTrue($admin->hasAnyRole(['admin', 'trainer']));

        $this->assertTrue($trainer->isTrainer());
        $this->assertFalse($trainer->isAdmin());
        $this->assertFalse($trainer->isMember());
        $this->assertTrue($trainer->hasRole('trainer'));
        $this->assertTrue($trainer->hasAnyRole(['trainer', 'member']));

        $this->assertTrue($member->isMember());
        $this->assertFalse($member->isAdmin());
        $this->assertFalse($member->isTrainer());
        $this->assertTrue($member->hasRole('member'));
        $this->assertFalse($member->hasRole(['admin', 'trainer']));
    }

    public function test_gate_definitions_for_all_roles(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $trainer = User::factory()->create(['role' => 'trainer']);
        $member = User::factory()->create(['role' => 'member']);

        // Admin Gates
        $this->assertTrue(Gate::forUser($admin)->allows('admin'));
        $this->assertTrue(Gate::forUser($admin)->allows('access-admin-dashboard'));
        $this->assertTrue(Gate::forUser($admin)->allows('manage-membership-packages'));
        $this->assertTrue(Gate::forUser($admin)->allows('verify-payments'));
        $this->assertTrue(Gate::forUser($admin)->allows('manage-members'));
        $this->assertTrue(Gate::forUser($admin)->allows('manage-pt-sessions'));
        $this->assertFalse(Gate::forUser($admin)->allows('trainer'));
        $this->assertFalse(Gate::forUser($admin)->allows('member'));

        // Trainer Gates
        $this->assertTrue(Gate::forUser($trainer)->allows('trainer'));
        $this->assertTrue(Gate::forUser($trainer)->allows('access-trainer-dashboard'));
        $this->assertTrue(Gate::forUser($trainer)->allows('manage-pt-sessions'));
        $this->assertFalse(Gate::forUser($trainer)->allows('admin'));
        $this->assertFalse(Gate::forUser($trainer)->allows('access-admin-dashboard'));
        $this->assertFalse(Gate::forUser($trainer)->allows('access-member-dashboard'));

        // Member Gates
        $this->assertTrue(Gate::forUser($member)->allows('member'));
        $this->assertTrue(Gate::forUser($member)->allows('access-member-dashboard'));
        $this->assertTrue(Gate::forUser($member)->allows('book-pt-session'));
        $this->assertFalse(Gate::forUser($member)->allows('admin'));
        $this->assertFalse(Gate::forUser($member)->allows('trainer'));
    }

    public function test_trainer_can_access_trainer_dashboard(): void
    {
        $trainer = User::factory()->create(['role' => 'trainer']);
        TrainerProfile::factory()->create(['user_id' => $trainer->id]);

        $response = $this->actingAs($trainer)->get(route('trainer.dashboard'));

        $response->assertOk()
            ->assertViewIs('trainer.dashboard')
            ->assertSee('Dashboard Personal Trainer')
            ->assertSee($trainer->name);
    }

    public function test_member_and_admin_cannot_access_trainer_dashboard_and_receive_custom_403_page(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $admin = User::factory()->create(['role' => 'admin']);

        // Member access
        $memberResponse = $this->actingAs($member)->get(route('trainer.dashboard'));
        $memberResponse->assertForbidden()
            ->assertSee('HTTP 403')
            ->assertSee('Akses Ditolak')
            ->assertSee('Area Khusus');

        // Admin access
        $adminResponse = $this->actingAs($admin)->get(route('trainer.dashboard'));
        $adminResponse->assertForbidden()
            ->assertSee('HTTP 403')
            ->assertSee('Akses Ditolak');
    }

    public function test_non_admin_cannot_access_admin_dashboard_and_receives_403(): void
    {
        $trainer = User::factory()->create(['role' => 'trainer']);
        $member = User::factory()->create(['role' => 'member']);

        $this->actingAs($trainer)
            ->get(route('admin.dashboard'))
            ->assertForbidden()
            ->assertSee('HTTP 403')
            ->assertSee('Akses Ditolak');

        $this->actingAs($member)
            ->get(route('admin.dashboard'))
            ->assertForbidden()
            ->assertSee('HTTP 403')
            ->assertSee('Akses Ditolak');
    }

    public function test_non_member_cannot_access_member_dashboard(): void
    {
        $trainer = User::factory()->create(['role' => 'trainer']);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($trainer)
            ->get(route('member.dashboard'))
            ->assertForbidden()
            ->assertSee('HTTP 403');

        $this->actingAs($admin)
            ->get(route('member.dashboard'))
            ->assertForbidden()
            ->assertSee('HTTP 403');
    }

    public function test_guest_is_redirected_to_login_by_middleware(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('trainer.dashboard'))->assertRedirect(route('login'));
        $this->get(route('member.dashboard'))->assertRedirect(route('login'));
    }

    public function test_trainer_can_view_admin_login_form(): void
    {
        $trainer = User::factory()->create(['role' => 'trainer']);

        $response = $this->actingAs($trainer)->get(route('admin.login'));

        $response->assertOk()
            ->assertViewIs('auth.admin-login')
            ->assertSee('Masuk Akun');
    }

    public function test_trainer_logging_in_via_admin_login_form_receives_403_page(): void
    {
        $trainer = User::factory()->create([
            'email' => 'trainer@fitcore.test',
            'role' => 'trainer',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'trainer@fitcore.test',
            'password' => 'password123',
            'role' => 'admin',
        ]);

        $response->assertForbidden()
            ->assertSee('HTTP 403')
            ->assertSee('Akses Ditolak')
            ->assertSee('Role: trainer');
    }

    public function test_member_logging_in_via_admin_login_form_receives_403_page(): void
    {
        $member = User::factory()->create([
            'email' => 'member@fitcore.test',
            'role' => 'member',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'member@fitcore.test',
            'password' => 'password123',
            'role' => 'admin',
        ]);

        $response->assertForbidden()
            ->assertSee('HTTP 403')
            ->assertSee('Akses Ditolak')
            ->assertSee('Role: member');
    }

    public function test_active_trainer_session_is_replaced_and_receives_403_when_submitting_member_on_admin_login(): void
    {
        $trainer = User::factory()->create(['role' => 'trainer']);
        $member = User::factory()->create([
            'email' => 'member@fitcore.test',
            'role' => 'member',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($trainer)->post(route('login'), [
            'email' => 'member@fitcore.test',
            'password' => 'password123',
            'role' => 'admin',
        ]);

        $response->assertForbidden()
            ->assertSee('HTTP 403')
            ->assertSee('Akses Ditolak')
            ->assertSee('Role: member');
    }

    public function test_admin_logging_in_via_admin_login_form_is_redirected_to_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@fitcore.test',
            'role' => 'admin',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'admin@fitcore.test',
            'password' => 'password123',
            'role' => 'admin',
        ]);

        $response->assertRedirect(route('admin.dashboard', absolute: false));
    }

    public function test_member_logging_in_via_trainer_portal_receives_403_page(): void
    {
        $member = User::factory()->create([
            'email' => 'member@fitcore.test',
            'role' => 'member',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'member@fitcore.test',
            'password' => 'password123',
            'role' => 'trainer',
        ]);

        $response->assertForbidden()
            ->assertSee('HTTP 403')
            ->assertSee('Akses Ditolak')
            ->assertSee('Role: member');
    }

    public function test_trainer_logging_in_via_member_portal_receives_403_page(): void
    {
        $trainer = User::factory()->create([
            'email' => 'trainer@fitcore.test',
            'role' => 'trainer',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'trainer@fitcore.test',
            'password' => 'password123',
            'role' => 'member',
        ]);

        $response->assertForbidden()
            ->assertSee('HTTP 403')
            ->assertSee('Akses Ditolak')
            ->assertSee('Role: trainer');
    }
}
