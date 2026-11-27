<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\PtSessionStatus;
use App\Models\MemberProfile;
use App\Models\MemberPtQuota;
use App\Models\MembershipPackage;
use App\Models\PtPackage;
use App\Models\PtSession;
use App\Models\TrainerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberPtAssignmentAndRetentionTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $trainer1;

    protected User $trainer2;

    protected User $member;

    protected MembershipPackage $membershipPackage;

    protected PtPackage $ptPackage;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->trainer1 = User::factory()->create([
            'role' => 'trainer',
            'status' => 'active',
            'name' => 'Coach Rama',
        ]);
        TrainerProfile::create([
            'user_id' => $this->trainer1->id,
            'specialization' => 'Hypertrophy Specialist',
            'bio' => 'Coach bio Rama',
        ]);

        $this->trainer2 = User::factory()->create([
            'role' => 'trainer',
            'status' => 'active',
            'name' => 'Coach Sarah',
        ]);
        TrainerProfile::create([
            'user_id' => $this->trainer2->id,
            'specialization' => 'Fat Loss Specialist',
            'bio' => 'Coach bio Sarah',
        ]);

        $this->member = User::factory()->create([
            'role' => 'member',
            'status' => 'active',
            'name' => 'Budi Santoso',
            'email' => 'budi@fitcore.test',
            'phone' => '081234567890',
        ]);
        MemberProfile::create([
            'user_id' => $this->member->id,
            'member_code' => '#FC-0001',
            'gender' => 'Laki-laki',
            'trainer_id' => $this->trainer1->id,
        ]);

        $this->membershipPackage = MembershipPackage::create([
            'name' => 'Paket Premium All Access',
            'description' => 'Akses gym penuh',
            'price' => 500000,
            'duration_days' => 30,
            'is_active' => true,
        ]);

        $this->ptPackage = PtPackage::create([
            'name' => 'Paket PT 12 Sesi',
            'description' => '12 sesi latihan',
            'price' => 3000000,
            'pt_session_count' => 12,
            'validity_days' => 60,
            'status' => 'active',
        ]);
    }

    public function test_admin_member_management_page_shows_personal_trainer_column(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.members.index'));

        $response->assertStatus(200);
        $response->assertSee('PERSONAL TRAINER');
        $response->assertSee('Coach Rama');
        $response->assertSee('Hypertrophy Specialist');
    }

    public function test_admin_member_management_detail_endpoint_returns_trainer_info(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.members.show', $this->member));

        $response->assertStatus(200);
        $response->assertJsonPath('trainer.id', $this->trainer1->id);
        $response->assertJsonPath('trainer.name', 'Coach Rama');
        $response->assertJsonPath('trainer_name', 'Coach Rama');
    }

    public function test_admin_can_update_member_personal_trainer(): void
    {
        // Member initially has active quota with trainer1
        $quota = MemberPtQuota::create([
            'member_id' => $this->member->id,
            'pt_package_id' => $this->ptPackage->id,
            'trainer_id' => $this->trainer1->id,
            'source' => 'purchase',
            'total_sessions' => 12,
            'used_sessions' => 2,
            'remaining_sessions' => 10,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(60)->toDateString(),
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.members.update', $this->member), [
            'name' => 'Budi Santoso Updated',
            'email' => 'budi@fitcore.test',
            'phone' => '081234567890',
            'gender' => 'Laki-laki',
            'status' => 'active',
            'trainer_id' => $this->trainer2->id,
        ]);

        $response->assertRedirect(route('admin.members.index'));

        $this->member->refresh();
        $this->assertEquals($this->trainer2->id, $this->member->memberProfile->trainer_id);

        $quota->refresh();
        $this->assertEquals($this->trainer2->id, $quota->trainer_id);
    }

    public function test_when_member_changes_pt_past_completed_sessions_retain_original_trainer(): void
    {
        // Member starts with trainer1
        $quota = MemberPtQuota::create([
            'member_id' => $this->member->id,
            'pt_package_id' => $this->ptPackage->id,
            'trainer_id' => $this->trainer1->id,
            'source' => 'purchase',
            'total_sessions' => 12,
            'used_sessions' => 2,
            'remaining_sessions' => 10,
            'start_date' => now()->subDays(10)->toDateString(),
            'end_date' => now()->addDays(50)->toDateString(),
            'status' => 'active',
        ]);

        // Sesi 1 dan 2 selesai bersama Coach Rama (trainer 1)
        $pastSession1 = PtSession::create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $this->member->id,
            'trainer_id' => $this->trainer1->id,
            'session_date' => now()->subDays(5)->toDateString(),
            'start_time' => '08:00',
            'end_time' => '09:00',
            'status' => PtSessionStatus::Completed,
            'trainer_revenue' => 60000,
        ]);

        $pastSession2 = PtSession::create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $this->member->id,
            'trainer_id' => $this->trainer1->id,
            'session_date' => now()->subDays(3)->toDateString(),
            'start_time' => '08:00',
            'end_time' => '09:00',
            'status' => PtSessionStatus::Completed,
            'trainer_revenue' => 60000,
        ]);

        // Sekarang Admin mengubah PT Budi menjadi Coach Sarah (trainer 2)
        $this->actingAs($this->admin)->put(route('admin.members.update', $this->member), [
            'name' => $this->member->name,
            'email' => $this->member->email,
            'phone' => $this->member->phone,
            'gender' => 'Laki-laki',
            'status' => 'active',
            'trainer_id' => $this->trainer2->id,
        ]);

        // VERIFIKASI 1: Sesi lampau TETAP milik Coach Rama (trainer 1)
        $pastSession1->refresh();
        $pastSession2->refresh();
        $this->assertEquals($this->trainer1->id, $pastSession1->trainer_id);
        $this->assertEquals($this->trainer1->id, $pastSession2->trainer_id);
        $this->assertEquals(60000, (float) $pastSession1->trainer_revenue);

        // VERIFIKASI 2: Coach Rama (trainer 1) TETAP melihat Budi di daftar klien dan detail riwayat
        $responseRamaClients = $this->actingAs($this->trainer1)->get(route('trainer.clients.index'));
        $responseRamaClients->assertStatus(200);
        $responseRamaClients->assertSee('Budi Santoso');

        $responseRamaShow = $this->actingAs($this->trainer1)->get(route('trainer.clients.show', $this->member));
        $responseRamaShow->assertStatus(200);
        $responseRamaShow->assertSee('Budi Santoso');

        // VERIFIKASI 3: Coach Sarah (trainer 2) langsung melihat Budi dan bisa mengakses detail klien
        $responseSarahClients = $this->actingAs($this->trainer2)->get(route('trainer.clients.index'));
        $responseSarahClients->assertStatus(200);
        $responseSarahClients->assertSee('Budi Santoso');

        $responseSarahShow = $this->actingAs($this->trainer2)->get(route('trainer.clients.show', $this->member));
        $responseSarahShow->assertStatus(200);
        $responseSarahShow->assertSee('Budi Santoso');
    }

    public function test_mobile_trainer_clients_index_displays_metrics_and_search(): void
    {
        $response = $this->actingAs($this->trainer1)->get(route('mobile.trainer.clients.index'));

        $response->assertStatus(200);
        $response->assertSee('Member &amp; Klien Bimbingan', false);
        $response->assertSee('Total');
        $response->assertSee('Menipis');
        $response->assertSee('Bulan Ini');
        $response->assertSee('Lihat Riwayat');
    }

    public function test_mobile_trainer_sessions_index_displays_calendar_navigation(): void
    {
        $response = $this->actingAs($this->trainer1)->get(route('mobile.trainer.sessions.index'));

        $response->assertStatus(200);
        $response->assertSee('Jadwal Sesi Klien');
        $response->assertSee('Today');
        $response->assertSee('Hari sebelumnya');
        $response->assertSee('Hari berikutnya');
    }
}
