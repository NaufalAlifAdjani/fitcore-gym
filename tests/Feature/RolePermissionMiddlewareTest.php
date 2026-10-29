<?php

namespace Tests\Feature;

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
        $this->assertFalse($admin->hasRole('member'));
        $this->assertTrue($admin->hasAnyRole(['admin', 'member']));

        $this->assertTrue($trainer->isTrainer());
        $this->assertFalse($trainer->isAdmin());
        $this->assertFalse($trainer->isMember());
        $this->assertTrue($trainer->hasRole('trainer'));
        $this->assertFalse($trainer->hasRole('admin'));

        $this->assertTrue($member->isMember());
        $this->assertFalse($member->isAdmin());
        $this->assertFalse($member->isTrainer());
        $this->assertTrue($member->hasRole('member'));
        $this->assertFalse($member->hasRole('admin'));
    }

    public function test_gates_defined_correctly_for_roles(): void
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

    public function test_non_admin_cannot_access_admin_dashboard_and_receives_403_page(): void
    {
        $trainer = User::factory()->create(['role' => 'trainer']);
        $member = User::factory()->create(['role' => 'member']);

        $this->actingAs($trainer)
            ->get(route('admin.dashboard'))
            ->assertForbidden()
            ->assertSee('HTTP 403')
            ->assertSee('Akses Ditolak')
            ->assertSee('Beranda')
            ->assertSee(route('trainer.sessions.index'));

        $this->actingAs($member)
            ->get(route('admin.dashboard'))
            ->assertForbidden()
            ->assertSee('HTTP 403')
            ->assertSee('Akses Ditolak');
    }

    public function test_non_member_cannot_access_member_dashboard_and_receives_403_page(): void
    {
        $trainer = User::factory()->create(['role' => 'trainer']);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($trainer)
            ->get(route('member.dashboard'))
            ->assertForbidden()
            ->assertSee('HTTP 403')
            ->assertSee('Akses Ditolak');

        $this->actingAs($admin)
            ->get(route('member.dashboard'))
            ->assertForbidden()
            ->assertSee('HTTP 403')
            ->assertSee('Akses Ditolak');
    }

    public function test_non_trainer_cannot_access_trainer_sessions_and_receives_403_page(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($member)
            ->get(route('trainer.sessions.index'))
            ->assertForbidden()
            ->assertSee('HTTP 403')
            ->assertSee('Akses Ditolak');

        $this->actingAs($admin)
            ->get(route('trainer.sessions.index'))
            ->assertForbidden()
            ->assertSee('HTTP 403')
            ->assertSee('Akses Ditolak');
    }

    public function test_guest_is_redirected_to_login_by_middleware(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('member.dashboard'))->assertRedirect(route('login'));
        $this->get(route('trainer.sessions.index'))->assertRedirect(route('login'));
    }

    public function test_authorized_users_can_access_their_respective_pages(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk();

        $member = User::factory()->create(['role' => 'member']);
        $this->actingAs($member)
            ->get(route('member.dashboard'))
            ->assertOk();

        $trainer = User::factory()->create(['role' => 'trainer']);
        $this->actingAs($trainer)
            ->get(route('trainer.sessions.index'))
            ->assertOk();
    }
}
