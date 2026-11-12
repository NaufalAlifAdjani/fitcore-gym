<?php

declare(strict_types=1);

namespace Tests\Feature\Trainer;

use App\Enums\PtSessionStatus;
use App\Models\MemberProfile;
use App\Models\MemberPtQuota;
use App\Models\PtPackage;
use App\Models\PtSession;
use App\Models\Rating;
use App\Models\TrainerProfile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $trainer;

    private User $otherTrainer;

    private User $member;

    private MemberPtQuota $quota;

    protected function setUp(): void
    {
        parent::setUp();

        $this->trainer = User::factory()->create([
            'name' => 'Coach Budi',
            'email' => 'coach.budi@fitcore.test',
            'role' => 'trainer',
            'email_verified_at' => now(),
        ]);

        TrainerProfile::create([
            'user_id' => $this->trainer->id,
            'specialization' => 'Strength & Conditioning',
            'bio' => 'Certified Trainer',
            'experience' => 5,
            'status' => 'active',
        ]);

        $this->otherTrainer = User::factory()->create([
            'name' => 'Coach Siti',
            'email' => 'coach.siti@fitcore.test',
            'role' => 'trainer',
            'email_verified_at' => now(),
        ]);

        TrainerProfile::create([
            'user_id' => $this->otherTrainer->id,
            'specialization' => 'Yoga & Mobility',
            'bio' => 'Yoga Instructor',
            'experience' => 4,
            'status' => 'active',
        ]);

        $this->member = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.test',
            'phone' => '081234567890',
            'role' => 'member',
            'email_verified_at' => now(),
        ]);

        MemberProfile::create([
            'user_id' => $this->member->id,
            'member_code' => 'MBR-0001',
            'gender' => 'male',
        ]);

        $package = PtPackage::create([
            'name' => '10 Sesi PT',
            'pt_session_count' => 10,
            'price' => 1500000,
            'validity_days' => 60,
        ]);

        $this->quota = MemberPtQuota::create([
            'member_id' => $this->member->id,
            'pt_package_id' => $package->id,
            'source' => 'purchase',
            'total_sessions' => 10,
            'used_sessions' => 2,
            'remaining_sessions' => 8,
            'start_date' => Carbon::today()->subDays(5)->toDateString(),
            'end_date' => Carbon::today()->addDays(55)->toDateString(),
            'status' => 'active',
        ]);
    }

    public function test_guest_is_redirected_to_login_when_accessing_clients(): void
    {
        $response = $this->get('/trainer/klien-saya');

        $response->assertRedirect('/login');
    }

    public function test_member_gets_forbidden_when_accessing_trainer_clients(): void
    {
        $response = $this->actingAs($this->member)->get('/trainer/klien-saya');

        $response->assertStatus(403);
    }

    public function test_trainer_can_view_clients_list_with_metrics_and_client_card(): void
    {
        // Hubungkan member ke trainer ini via sesi
        PtSession::create([
            'member_pt_quota_id' => $this->quota->id,
            'member_id' => $this->member->id,
            'trainer_id' => $this->trainer->id,
            'session_date' => Carbon::today()->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'status' => PtSessionStatus::Confirmed,
        ]);

        $response = $this->actingAs($this->trainer)->get('/trainer/klien-saya');

        $response->assertOk();
        $response->assertViewIs('trainer.clients.index');
        $response->assertSee('Klien Bimbingan Saya');
        $response->assertSee('John Doe');
        $response->assertSee('MBR-0001');
        $response->assertSee('Kuota Aktif');
        $response->assertSee('Sesi Selesai');
        $response->assertSee('Total Sesi');
        $response->assertSee(route('trainer.clients.show', $this->member->id));
    }

    public function test_trainer_can_search_and_filter_clients(): void
    {
        // Member 2 yang kuotanya habis
        $member2 = User::factory()->create([
            'name' => 'Alice Smith',
            'email' => 'alice@example.test',
            'phone' => '089876543210',
            'role' => 'member',
            'email_verified_at' => now(),
        ]);

        MemberProfile::create([
            'user_id' => $member2->id,
            'member_code' => 'MBR-0002',
            'gender' => 'female',
        ]);

        $quota2 = MemberPtQuota::create([
            'member_id' => $member2->id,
            'pt_package_id' => $this->quota->pt_package_id,
            'source' => 'purchase',
            'total_sessions' => 5,
            'used_sessions' => 5,
            'remaining_sessions' => 0,
            'start_date' => Carbon::today()->subDays(30)->toDateString(),
            'end_date' => Carbon::today()->subDays(1)->toDateString(),
            'status' => 'expired',
        ]);

        PtSession::create([
            'member_pt_quota_id' => $this->quota->id,
            'member_id' => $this->member->id,
            'trainer_id' => $this->trainer->id,
            'session_date' => Carbon::today()->subDays(2)->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => PtSessionStatus::Completed,
        ]);

        PtSession::create([
            'member_pt_quota_id' => $quota2->id,
            'member_id' => $member2->id,
            'trainer_id' => $this->trainer->id,
            'session_date' => Carbon::today()->subDays(5)->toDateString(),
            'start_time' => '14:00:00',
            'end_time' => '15:00:00',
            'status' => PtSessionStatus::Completed,
        ]);

        // Search name
        $searchResponse = $this->actingAs($this->trainer)->get('/trainer/klien-saya?search=Alice');
        $searchResponse->assertOk();
        $searchResponse->assertSee('Alice Smith');
        $searchResponse->assertDontSee('John Doe');

        // Filter active quota
        $activeFilterResponse = $this->actingAs($this->trainer)->get('/trainer/klien-saya?status=active_quota');
        $activeFilterResponse->assertOk();
        $activeFilterResponse->assertSee('John Doe');
        $activeFilterResponse->assertDontSee('Alice Smith');

        // Filter empty quota
        $emptyFilterResponse = $this->actingAs($this->trainer)->get('/trainer/klien-saya?status=empty_quota');
        $emptyFilterResponse->assertOk();
        $emptyFilterResponse->assertSee('Alice Smith');
        $emptyFilterResponse->assertDontSee('John Doe');

        // Filter low quota (Alice kuotanya habis <= 2 sesi)
        $lowFilterResponse = $this->actingAs($this->trainer)->get('/trainer/klien-saya?status=low_quota');
        $lowFilterResponse->assertOk();
        $lowFilterResponse->assertSee('Alice Smith');
        $lowFilterResponse->assertDontSee('John Doe');

        // Badge sisa sesi
        $allClientsResponse = $this->actingAs($this->trainer)->get('/trainer/klien-saya');
        $allClientsResponse->assertSee('Sisa 8 Sesi');
        $allClientsResponse->assertSee('Perlu Perpanjangan');
    }

    public function test_trainer_can_view_member_session_history_and_profile(): void
    {
        $sessionCompleted = PtSession::create([
            'member_pt_quota_id' => $this->quota->id,
            'member_id' => $this->member->id,
            'trainer_id' => $this->trainer->id,
            'session_date' => Carbon::today()->subDays(3)->toDateString(),
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'status' => PtSessionStatus::Completed,
            'notes' => 'Latihan punggung & bisep. Beban lat pull down 45kg.',
            'completed_at' => Carbon::today()->subDays(3)->setTime(9, 5),
        ]);

        Rating::create([
            'pt_session_id' => $sessionCompleted->id,
            'member_id' => $this->member->id,
            'trainer_id' => $this->trainer->id,
            'rating' => 5,
            'comment' => 'Pelayanan coach sangat mantap dan teliti!',
        ]);

        $sessionConfirmed = PtSession::create([
            'member_pt_quota_id' => $this->quota->id,
            'member_id' => $this->member->id,
            'trainer_id' => $this->trainer->id,
            'session_date' => Carbon::today()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => PtSessionStatus::Confirmed,
        ]);

        $response = $this->actingAs($this->trainer)->get("/trainer/klien-saya/{$this->member->id}");

        $response->assertOk();
        $response->assertViewIs('trainer.clients.show');
        $response->assertSee('John Doe');
        $response->assertSee('MBR-0001');
        $response->assertSee('10 Sesi PT');
        $response->assertSee('Latihan punggung & bisep');
        $response->assertSee('Pelayanan coach sangat mantap dan teliti!');
        $response->assertDontSee('Selesaikan Sesi');
    }

    public function test_trainer_cannot_view_unrelated_member_details(): void
    {
        // Member ini hanya pernah latihan dengan otherTrainer
        $unrelatedMember = User::factory()->create([
            'name' => 'Stranger Member',
            'email' => 'stranger@example.test',
            'role' => 'member',
        ]);

        PtSession::create([
            'member_pt_quota_id' => $this->quota->id,
            'member_id' => $unrelatedMember->id,
            'trainer_id' => $this->otherTrainer->id,
            'session_date' => Carbon::today()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => PtSessionStatus::Confirmed,
        ]);

        $response = $this->actingAs($this->trainer)->get("/trainer/klien-saya/{$unrelatedMember->id}");

        $response->assertStatus(403);
    }

    public function test_trainer_can_complete_session_with_notes(): void
    {
        $session = PtSession::create([
            'member_pt_quota_id' => $this->quota->id,
            'member_id' => $this->member->id,
            'trainer_id' => $this->trainer->id,
            'session_date' => Carbon::today()->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'status' => PtSessionStatus::Confirmed,
        ]);

        $response = $this->actingAs($this->trainer)->post("/trainer/sessions/{$session->id}/complete", [
            'notes' => 'Sesi kardio & HIIT 45 menit. Detak jantung stabil di 140 bpm.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $session->refresh();
        $this->assertEquals(PtSessionStatus::Completed, $session->status);
        $this->assertEquals('Sesi kardio & HIIT 45 menit. Detak jantung stabil di 140 bpm.', $session->notes);
        $this->assertNotNull($session->completed_at);
    }

    public function test_trainer_cannot_complete_other_trainers_session(): void
    {
        $session = PtSession::create([
            'member_pt_quota_id' => $this->quota->id,
            'member_id' => $this->member->id,
            'trainer_id' => $this->otherTrainer->id,
            'session_date' => Carbon::today()->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'status' => PtSessionStatus::Confirmed,
        ]);

        $response = $this->actingAs($this->trainer)->post("/trainer/sessions/{$session->id}/complete", [
            'notes' => 'Catatan tidak sah',
        ]);

        $response->assertStatus(403);
    }

    public function test_trainer_cannot_complete_cancelled_session(): void
    {
        $session = PtSession::create([
            'member_pt_quota_id' => $this->quota->id,
            'member_id' => $this->member->id,
            'trainer_id' => $this->trainer->id,
            'session_date' => Carbon::today()->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'status' => PtSessionStatus::Cancelled,
        ]);

        $response = $this->actingAs($this->trainer)->post("/trainer/sessions/{$session->id}/complete", [
            'notes' => 'Catatan sesi batal',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $session->refresh();
        $this->assertEquals(PtSessionStatus::Cancelled, $session->status);
    }

    public function test_total_sesi_displays_purchased_sessions_not_booking_requests_count(): void
    {
        // Member memiliki kuota 10 sesi yang dibeli ($this->quota->total_sessions = 10)
        // Dan hanya memiliki 2 pengajuan sesi (booking)
        PtSession::create([
            'member_pt_quota_id' => $this->quota->id,
            'member_id' => $this->member->id,
            'trainer_id' => $this->trainer->id,
            'session_date' => Carbon::today()->subDay()->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'status' => PtSessionStatus::Completed,
        ]);

        PtSession::create([
            'member_pt_quota_id' => $this->quota->id,
            'member_id' => $this->member->id,
            'trainer_id' => $this->trainer->id,
            'session_date' => Carbon::today()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => PtSessionStatus::Pending,
        ]);

        // Cek halaman index klien
        $responseIndex = $this->actingAs($this->trainer)->get('/trainer/klien-saya');
        $responseIndex->assertOk();
        // Kartu member harus menampilkan total sesi yang dibeli (10), bukan total pengajuan sesi (2)
        $responseIndex->assertSee('Total Sesi');
        $responseIndex->assertSee('10');

        // Cek halaman detail member
        $responseShow = $this->actingAs($this->trainer)->get("/trainer/klien-saya/{$this->member->id}");
        $responseShow->assertOk();
        $responseShow->assertViewHas('totalPurchasedSessions', 10);
    }

    public function test_past_confirmed_sessions_are_automatically_completed(): void
    {
        // Buat sesi terkonfirmasi di masa lampau (kemarin)
        $pastSession = PtSession::create([
            'member_pt_quota_id' => $this->quota->id,
            'member_id' => $this->member->id,
            'trainer_id' => $this->trainer->id,
            'session_date' => Carbon::today()->subDay()->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'status' => PtSessionStatus::Confirmed,
        ]);

        $this->assertEquals(PtSessionStatus::Confirmed, $pastSession->status);

        // Saat mengakses halaman riwayat member, fungsi otomatisasi berjalan
        $response = $this->actingAs($this->trainer)->get("/trainer/klien-saya/{$this->member->id}");
        $response->assertOk();

        $pastSession->refresh();
        $this->assertEquals(PtSessionStatus::Completed, $pastSession->status);
        $this->assertNotNull($pastSession->completed_at);

        // Pastikan tidak ada tombol 'Selesaikan Sesi' pada UI
        $response->assertDontSee('Selesaikan Sesi');
    }

    public function test_artisan_command_completes_past_confirmed_sessions(): void
    {
        $pastSession = PtSession::create([
            'member_pt_quota_id' => $this->quota->id,
            'member_id' => $this->member->id,
            'trainer_id' => $this->trainer->id,
            'session_date' => Carbon::today()->subDays(2)->toDateString(),
            'start_time' => '14:00:00',
            'end_time' => '15:00:00',
            'status' => PtSessionStatus::Confirmed,
        ]);

        $this->artisan('pt:complete-past-confirmed')
            ->expectsOutputToContain('sesi PT terkonfirmasi yang telah melewati jam jadwal berhasil diselesaikan otomatis.')
            ->assertSuccessful();

        $pastSession->refresh();
        $this->assertEquals(PtSessionStatus::Completed, $pastSession->status);
        $this->assertNotNull($pastSession->completed_at);
    }
}
