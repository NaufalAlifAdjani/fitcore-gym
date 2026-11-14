<?php

namespace Tests\Feature;

use App\Enums\PtSessionStatus;
use App\Livewire\Trainer\Sessions\Index as TrainerSessionsIndex;
use App\Models\MemberProfile;
use App\Models\MemberPtQuota;
use App\Models\PtPackage;
use App\Models\PtSession;
use App\Models\TrainerProfile;
use App\Models\User;
use App\Notifications\NewSessionRequestedNotification;
use App\Notifications\SessionConfirmedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class PtBookingEndToEndFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_end_to_end_pt_booking_workflow(): void
    {
        Notification::fake();

        // ── 1. Setup Master Data (Trainer & Member with Quota) ────────────────
        $trainer = User::factory()->create([
            'role' => 'trainer',
            'name' => 'Coach Rama Prasetya',
            'email' => 'rama@gym.test',
            'shift' => 'pagi', // 06:00 - 14:00
        ]);
        TrainerProfile::factory()->create([
            'user_id' => $trainer->id,
            'commission_rate' => 75000,
            'specialization' => 'Hypertrophy & Strength Conditioning',
        ]);

        $member = User::factory()->create([
            'role' => 'member',
            'name' => 'Budi Santoso',
            'email' => 'budi@gym.test',
            'phone' => '081234567890',
        ]);
        MemberProfile::factory()->create([
            'user_id' => $member->id,
            'member_code' => 'MBR-0001',
        ]);

        $package = PtPackage::factory()->create([
            'name' => 'Paket PT 10 Sesi',
            'pt_session_count' => 10,
        ]);

        $quota = MemberPtQuota::factory()->create([
            'member_id' => $member->id,
            'pt_package_id' => $package->id,
            'total_sessions' => 10,
            'used_sessions' => 0,
            'remaining_sessions' => 10,
            'status' => 'active',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonths(3)->toDateString(),
        ]);

        // ── 2. Member Mengakses Form Booking & Memilih Jadwal ──────────────────
        $responseCreate = $this->actingAs($member)->get(route('pt-sessions.create', ['trainer_id' => $trainer->id]));
        $responseCreate->assertStatus(200);
        $responseCreate->assertSee('Pilih Jadwal');
        $responseCreate->assertSee('Coach Rama Prasetya');
        $responseCreate->assertSee('10 Sesi');

        // Target tanggal 2 hari ke depan jam 08:00 (dalam shift pagi coach rama)
        $targetDate = now()->addDays(2)->toDateString();

        // ── 3. Member Submit Booking Sesi PT ──────────────────────────────────
        $responseStore = $this->actingAs($member)->post(route('pt-sessions.store'), [
            'trainer_id' => $trainer->id,
            'session_date' => $targetDate,
            'start_time' => '08:00',
        ]);

        $responseStore->assertRedirect(route('pt-sessions.index'));
        $responseStore->assertSessionHas('success');

        // Verifikasi kuota member terpotong tepat 1 sesi
        $this->assertDatabaseHas('member_pt_quotas', [
            'id' => $quota->id,
            'used_sessions' => 1,
            'remaining_sessions' => 9,
        ]);

        // Verifikasi record sesi dibuat dengan status Pending
        $createdSession = PtSession::where('member_id', $member->id)
            ->where('trainer_id', $trainer->id)
            ->first();

        $this->assertNotNull($createdSession);
        $this->assertEquals(PtSessionStatus::Pending, $createdSession->status);
        $this->assertEquals($targetDate, $createdSession->session_date->toDateString());
        $this->assertEquals('08:00:00', $createdSession->start_time);
        $this->assertEquals('09:00:00', $createdSession->end_time);

        // ── 4. Verifikasi Notifikasi Terkirim ke Trainer ───────────────────────
        Notification::assertSentTo(
            $trainer,
            NewSessionRequestedNotification::class,
            function (NewSessionRequestedNotification $notif) use ($member, $createdSession) {
                return $notif->session->id === $createdSession->id &&
                    $notif->session->member_id === $member->id;
            }
        );

        // ── 5. Sisi Trainer: Menu Jadwal Sesi PT (/trainer/jadwal-sesi) ─────────
        $this->actingAs($trainer);

        // Akses livewire Jadwal Sesi
        Livewire::test(TrainerSessionsIndex::class)
            ->set('date', $targetDate)
            ->set('view', 'day')
            ->set('mode', 'tasks')
            ->assertStatus(200)
            ->assertSee('Budi Santoso')
            ->assertSee('Menunggu')
            ->assertSee('Terima')
            ->assertSee('Tolak')
            // Test filter status tasks
            ->call('setTaskStatusFilter', 'pending')
            ->assertSee('Budi Santoso')
            ->call('setTaskStatusFilter', 'confirmed')
            ->assertDontSee('Budi Santoso') // Belum confirmed
            // Trainer Mengonfirmasi Sesi
            ->call('confirmSession', $createdSession->id)
            ->assertDispatched('toast');

        // Status sesi berubah menjadi Confirmed di database
        $this->assertEquals(PtSessionStatus::Confirmed, $createdSession->fresh()->status);

        // Notifikasi konfirmasi terkirim ke member
        Notification::assertSentTo(
            $member,
            SessionConfirmedNotification::class,
            function (SessionConfirmedNotification $notif) use ($createdSession) {
                return $notif->session->id === $createdSession->id;
            }
        );

        // ── 6. Sisi Trainer: Menu Klien Saya (/trainer/klien-saya) ────────────
        // 6a. Daftar Klien
        $responseClients = $this->actingAs($trainer)->get(route('trainer.clients.index'));
        $responseClients->assertStatus(200);
        $responseClients->assertSee('Klien Bimbingan Saya');
        $responseClients->assertSee('Budi Santoso');
        $responseClients->assertSee('MBR-0001');

        // 6b. Test Filter Klien Saya (Search by name & status filter)
        $responseSearch = $this->actingAs($trainer)->get(route('trainer.clients.index', ['search' => 'Budi']));
        $responseSearch->assertStatus(200);
        $responseSearch->assertSee('Budi Santoso');

        $responseSearchEmpty = $this->actingAs($trainer)->get(route('trainer.clients.index', ['search' => 'NonExistent']));
        $responseSearchEmpty->assertStatus(200);
        $responseSearchEmpty->assertDontSee('Budi Santoso');

        $responseFilterActive = $this->actingAs($trainer)->get(route('trainer.clients.index', ['status' => 'active_quota']));
        $responseFilterActive->assertStatus(200);
        $responseFilterActive->assertSee('Budi Santoso');

        // 6c. Detail Klien (/trainer/klien-saya/{member})
        $responseClientShow = $this->actingAs($trainer)->get(route('trainer.clients.show', ['member' => $member->id]));
        $responseClientShow->assertStatus(200);
        $responseClientShow->assertSee('Budi Santoso');
        $responseClientShow->assertSee('MBR-0001');
        $responseClientShow->assertSee('Paket PT 10 Sesi');
        $responseClientShow->assertSee('Terkonfirmasi');

        // 6d. Trainer Menyelesaikan Sesi (Complete Session)
        $responseComplete = $this->actingAs($trainer)->post(route('trainer.sessions.complete', ['ptSession' => $createdSession->id]), [
            'notes' => 'Member menyelesaikan program latihan deadlift & squat dengan performa sangat baik.',
        ]);

        $responseComplete->assertRedirect();
        $responseComplete->assertSessionHas('success');

        // Verifikasi status sesi menjadi Completed di DB
        $completedSession = $createdSession->fresh();
        $this->assertEquals(PtSessionStatus::Completed, $completedSession->status);
        $this->assertEquals('Member menyelesaikan program latihan deadlift & squat dengan performa sangat baik.', $completedSession->notes);
        $this->assertEquals(75000, $completedSession->trainer_earnings);
    }
}
