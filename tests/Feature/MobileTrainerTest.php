<?php

namespace Tests\Feature;

use App\Enums\PtSessionStatus;
use App\Models\MemberPtQuota;
use App\Models\PtSession;
use App\Models\TrainerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MobileTrainerTest extends TestCase
{
    use RefreshDatabase;

    private const IPHONE_USER_AGENT = 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1';

    public function test_guest_is_redirected_to_login_when_accessing_mobile_trainer_dashboard(): void
    {
        $response = $this->get('/m/trainer/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_member_is_forbidden_when_accessing_mobile_trainer_dashboard(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
        ]);

        $response = $this->actingAs($member)->get('/m/trainer/dashboard');

        $response->assertStatus(403);
    }

    public function test_trainer_can_access_mobile_trainer_dashboard_in_light_theme(): void
    {
        $trainer = User::factory()->create([
            'role' => 'trainer',
            'name' => 'Coach Bima',
        ]);

        TrainerProfile::create([
            'user_id' => $trainer->id,
            'specialization' => 'Hypertrophy Specialist',
        ]);

        $response = $this->actingAs($trainer)->get('/m/trainer/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Selamat Bertugas');
        $response->assertSee('Coach Bima');
        $response->assertSee('Sesi Hari Ini');
        $response->assertSee('Butuh Respon');
        $response->assertSee('Permintaan Masuk');
        $response->assertSee('PT Aktif');
        $response->assertSee('bg-[#F6F6F7]', false);
    }

    public function test_trainer_can_confirm_pending_session_from_mobile(): void
    {
        $trainer = User::factory()->create([
            'role' => 'trainer',
        ]);

        $member = User::factory()->create([
            'role' => 'member',
        ]);

        $quota = MemberPtQuota::factory()->create([
            'member_id' => $member->id,
            'total_sessions' => 5,
            'used_sessions' => 1,
            'remaining_sessions' => 4,
        ]);

        $session = PtSession::create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'session_date' => now()->addDays(2)->toDateString(),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'status' => PtSessionStatus::Pending,
        ]);

        $response = $this->actingAs($trainer)
            ->from(route('mobile.trainer.dashboard'))
            ->post(route('mobile.trainer.sessions.confirm', $session));

        $response->assertRedirect(route('mobile.trainer.dashboard'));
        $response->assertSessionHas('success');

        $this->assertEquals(PtSessionStatus::Confirmed, $session->fresh()->status);
    }

    public function test_trainer_can_reject_pending_session_and_refunds_quota_from_mobile(): void
    {
        $trainer = User::factory()->create([
            'role' => 'trainer',
        ]);

        $member = User::factory()->create([
            'role' => 'member',
        ]);

        $quota = MemberPtQuota::factory()->create([
            'member_id' => $member->id,
            'total_sessions' => 5,
            'used_sessions' => 1,
            'remaining_sessions' => 4,
        ]);

        $session = PtSession::create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'session_date' => now()->addDays(2)->toDateString(),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'status' => PtSessionStatus::Pending,
        ]);

        $response = $this->actingAs($trainer)
            ->from(route('mobile.trainer.dashboard'))
            ->post(route('mobile.trainer.sessions.reject', $session), [
                'reason' => 'Sedang ada pelatihan di luar kota.',
            ]);

        $response->assertRedirect(route('mobile.trainer.dashboard'));
        $response->assertSessionHas('success');

        $this->assertEquals(PtSessionStatus::Cancelled, $session->fresh()->status);

        // Kuota dikembalikan
        $freshQuota = $quota->fresh();
        $this->assertEquals(0, $freshQuota->used_sessions);
        $this->assertEquals(5, $freshQuota->remaining_sessions);
    }

    public function test_trainer_cannot_confirm_another_trainers_session(): void
    {
        $trainer1 = User::factory()->create(['role' => 'trainer']);
        $trainer2 = User::factory()->create(['role' => 'trainer']);

        $member = User::factory()->create(['role' => 'member']);
        $quota = MemberPtQuota::factory()->create(['member_id' => $member->id]);

        $session = PtSession::create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer1->id,
            'session_date' => now()->addDays(2)->toDateString(),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'status' => PtSessionStatus::Pending,
        ]);

        $response = $this->actingAs($trainer2)
            ->post(route('mobile.trainer.sessions.confirm', $session));

        $response->assertStatus(403);
    }

    public function test_trainer_can_access_mobile_sessions_index(): void
    {
        $trainer = User::factory()->create([
            'role' => 'trainer',
            'name' => 'Coach Bima',
        ]);

        $response = $this->actingAs($trainer)->get(route('mobile.trainer.sessions.index'));

        $response->assertStatus(200);
        $response->assertSee('Jadwal Sesi Klien');
        $response->assertSee('Semua');
        $response->assertSee('Hari Ini');
        $response->assertSee('Menunggu');
        $response->assertSee('Terkonfirmasi');
    }

    public function test_trainer_can_access_mobile_session_detail(): void
    {
        $trainer = User::factory()->create(['role' => 'trainer']);
        $member = User::factory()->create(['role' => 'member', 'name' => 'Klien Rudi']);
        $quota = MemberPtQuota::factory()->create(['member_id' => $member->id]);

        $session = PtSession::create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'session_date' => now()->addDays(1)->toDateString(),
            'start_time' => '14:00',
            'end_time' => '15:00',
            'status' => PtSessionStatus::Confirmed,
        ]);

        $response = $this->actingAs($trainer)->get(route('mobile.trainer.sessions.show', $session));

        $response->assertStatus(200);
        $response->assertSee('Detail Sesi Latihan');
        $response->assertSee('Klien Rudi');
        $response->assertSee('Terkonfirmasi');
        $response->assertSee('60 Menit');
    }

    public function test_trainer_can_access_mobile_clients_index(): void
    {
        $trainer = User::factory()->create(['role' => 'trainer']);

        $response = $this->actingAs($trainer)->get(route('mobile.trainer.clients.index'));

        $response->assertStatus(200);
        $response->assertSee('Member &amp; Klien Bimbingan', false);
    }

    public function test_trainer_can_access_mobile_profile_index(): void
    {
        $trainer = User::factory()->create([
            'role' => 'trainer',
            'name' => 'Coach Sarah',
            'email' => 'sarah@fitcore.test',
        ]);

        TrainerProfile::create([
            'user_id' => $trainer->id,
            'specialization' => 'Fat Loss Specialist',
            'experience' => 5,
        ]);

        $response = $this->actingAs($trainer)->get(route('mobile.trainer.profile.index'));

        $response->assertStatus(200);
        $response->assertSee('Profil Pelatih');
        $response->assertSee('Coach Sarah');
        $response->assertSee('sarah@fitcore.test');
        $response->assertSee('Fat Loss Specialist');
        $response->assertSee('Keluar dari Akun');
    }

    public function test_trainer_on_mobile_is_auto_redirected_from_desktop_routes(): void
    {
        $trainer = User::factory()->create([
            'role' => 'trainer',
        ]);

        // 1. Desktop dashboard -> mobile dashboard
        $response = $this->actingAs($trainer)->withHeaders([
            'User-Agent' => self::IPHONE_USER_AGENT,
        ])->get('/trainer/dashboard');

        $response->assertRedirect('/m/trainer/dashboard');

        // 2. Desktop generic /dashboard for trainer -> mobile dashboard
        $response = $this->actingAs($trainer)->withHeaders([
            'User-Agent' => self::IPHONE_USER_AGENT,
        ])->get('/dashboard');

        $response->assertRedirect('/m/trainer/dashboard');

        // 3. Desktop jadwal sesi -> mobile sessions index
        $response = $this->actingAs($trainer)->withHeaders([
            'User-Agent' => self::IPHONE_USER_AGENT,
        ])->get('/trainer/jadwal-sesi');

        $response->assertRedirect('/m/trainer/sessions');

        // 4. Desktop profile for trainer -> mobile trainer profile
        $response = $this->actingAs($trainer)->withHeaders([
            'User-Agent' => self::IPHONE_USER_AGENT,
        ])->get('/profile');

        $response->assertRedirect('/m/trainer/profile');

        // 5. Desktop commissions for trainer -> mobile trainer commissions
        $response = $this->actingAs($trainer)->withHeaders([
            'User-Agent' => self::IPHONE_USER_AGENT,
        ])->get('/trainer/pendapatan-komisi');

        $response->assertRedirect('/m/trainer/commissions');
    }

    public function test_trainer_can_access_mobile_commissions_page(): void
    {
        $trainer = User::factory()->create([
            'role' => 'trainer',
            'name' => 'Coach Bima',
            'shift' => 'pagi',
        ]);

        TrainerProfile::create([
            'user_id' => $trainer->id,
            'specialization' => 'Hypertrophy Specialist',
            'commission_rate' => 75000,
        ]);

        $response = $this->actingAs($trainer)->get('/m/trainer/commissions');

        $response->assertStatus(200);
        $response->assertSee('Pendapatan &amp; Komisi', false);
        $response->assertSee('Shift: Pagi');
        $response->assertSee('75.000');
        $response->assertSee('Komisi Bulan Ini');
        $response->assertSee('Total Seluruh Waktu');
    }
}
