<?php

namespace Tests\Feature;

use App\Enums\PtSessionStatus;
use App\Livewire\Trainer\Sessions\Index;
use App\Models\MemberPtQuota;
use App\Models\PtSession;
use App\Models\TrainerProfile;
use App\Models\User;
use App\Notifications\NewSessionRequestedNotification;
use App\Notifications\SessionCancelledNotification;
use App\Notifications\SessionConfirmedNotification;
use App\Services\PtSessionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class PtSessionNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_trainer_receives_notification_when_member_books_new_session(): void
    {
        Notification::fake();

        $member = User::factory()->member()->create();
        $trainer = User::factory()->trainer()->create();
        TrainerProfile::factory()->create(['user_id' => $trainer->id]);

        MemberPtQuota::factory()->create([
            'member_id' => $member->id,
            'total_sessions' => 10,
            'used_sessions' => 0,
            'remaining_sessions' => 10,
            'end_date' => now()->addMonth(),
        ]);

        $sessionDate = now()->addDays(2)->toDateString();

        $response = $this->actingAs($member)->post(route('pt-sessions.store'), [
            'trainer_id' => $trainer->id,
            'session_date' => $sessionDate,
            'start_time' => '10:00',
        ]);

        $response->assertRedirect(route('pt-sessions.index'));

        Notification::assertSentTo(
            $trainer,
            NewSessionRequestedNotification::class,
            function (NewSessionRequestedNotification $notification) use ($member, $trainer, $sessionDate) {
                $this->assertEquals($member->id, $notification->session->member_id);
                $this->assertEquals($trainer->id, $notification->session->trainer_id);
                $this->assertEquals($sessionDate, $notification->session->session_date->toDateString());
                $this->assertContains('database', $notification->via($trainer));
                $this->assertContains('mail', $notification->via($trainer));

                $dbData = $notification->toDatabase($trainer);
                $this->assertEquals('new_session_requested', $dbData['type']);
                $this->assertEquals($member->name, $dbData['member_name']);

                return true;
            }
        );
    }

    public function test_member_receives_notification_when_trainer_confirms_session(): void
    {
        Notification::fake();

        $trainer = User::factory()->trainer()->create();
        $member = User::factory()->member()->create();
        TrainerProfile::factory()->create(['user_id' => $trainer->id]);

        $quota = MemberPtQuota::factory()->create([
            'member_id' => $member->id,
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addMonths(2),
        ]);

        $session = PtSession::factory()->create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'session_date' => now()->addDays(2)->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => PtSessionStatus::Pending,
        ]);

        $this->actingAs($trainer);

        Livewire::test(Index::class)
            ->call('confirmSession', $session->id);

        $session->refresh();
        $this->assertEquals(PtSessionStatus::Confirmed, $session->status);

        Notification::assertSentTo(
            $member,
            SessionConfirmedNotification::class,
            function (SessionConfirmedNotification $notification) use ($session, $trainer, $member) {
                $this->assertEquals($session->id, $notification->session->id);
                $this->assertEquals($trainer->id, $notification->confirmedBy?->id);
                $this->assertContains('database', $notification->via($member));
                $this->assertContains('mail', $notification->via($member));

                $dbData = $notification->toDatabase($member);
                $this->assertEquals('session_confirmed', $dbData['type']);
                $this->assertEquals($trainer->name, $dbData['trainer_name']);

                return true;
            }
        );
    }

    public function test_trainer_receives_notification_when_member_cancels_session(): void
    {
        Notification::fake();

        $member = User::factory()->member()->create();
        $trainer = User::factory()->trainer()->create();

        $quota = MemberPtQuota::factory()->create([
            'member_id' => $member->id,
            'total_sessions' => 10,
            'used_sessions' => 3,
            'remaining_sessions' => 7,
            'end_date' => now()->addMonth(),
        ]);

        $session = PtSession::factory()->create([
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'member_pt_quota_id' => $quota->id,
            'session_date' => now()->addDays(2)->toDateString(),
            'start_time' => '14:00:00',
            'end_time' => '15:00:00',
            'status' => PtSessionStatus::Confirmed,
        ]);

        $reason = 'Ada keperluan mendadak di kantor';

        $response = $this->actingAs($member)->patch(route('pt-sessions.cancel', $session), [
            'reason' => $reason,
        ]);

        $response->assertRedirect(route('pt-sessions.index'));

        Notification::assertSentTo(
            $trainer,
            SessionCancelledNotification::class,
            function (SessionCancelledNotification $notification) use ($session, $member, $reason, $trainer) {
                $this->assertEquals($session->id, $notification->session->id);
                $this->assertEquals($reason, $notification->reason);
                $this->assertEquals($member->id, $notification->cancelledBy?->id);
                $this->assertContains('database', $notification->via($trainer));
                $this->assertContains('mail', $notification->via($trainer));

                $dbData = $notification->toDatabase($trainer);
                $this->assertEquals('session_cancelled', $dbData['type']);
                $this->assertEquals($member->name, $dbData['cancelled_by_name']);
                $this->assertEquals($reason, $dbData['reason']);

                return true;
            }
        );
    }

    public function test_member_receives_notification_when_trainer_rejects_session(): void
    {
        Notification::fake();

        $trainer = User::factory()->trainer()->create();
        $member = User::factory()->member()->create();
        TrainerProfile::factory()->create(['user_id' => $trainer->id]);

        $quota = MemberPtQuota::factory()->create([
            'member_id' => $member->id,
            'total_sessions' => 10,
            'used_sessions' => 1,
            'remaining_sessions' => 9,
            'status' => 'active',
            'end_date' => now()->addMonth(),
        ]);

        $session = PtSession::factory()->create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'session_date' => now()->addDays(2)->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => PtSessionStatus::Pending,
        ]);

        $this->actingAs($trainer);

        Livewire::test(Index::class)
            ->call('rejectSession', $session->id);

        $session->refresh();
        $this->assertEquals(PtSessionStatus::Cancelled, $session->status);

        Notification::assertSentTo(
            $member,
            SessionCancelledNotification::class,
            function (SessionCancelledNotification $notification) use ($session, $trainer, $member) {
                $this->assertEquals($session->id, $notification->session->id);
                $this->assertEquals($trainer->id, $notification->cancelledBy?->id);
                $this->assertEquals('Sesi ditolak oleh pelatih', $notification->reason);
                $this->assertContains('database', $notification->via($member));

                $dbData = $notification->toDatabase($member);
                $this->assertEquals('session_cancelled', $dbData['type']);
                $this->assertEquals($trainer->name, $dbData['cancelled_by_name']);

                return true;
            }
        );
    }

    public function test_member_receives_notification_when_pending_session_is_auto_cancelled(): void
    {
        Notification::fake();

        $member = User::factory()->member()->create();
        $trainer = User::factory()->trainer()->create();

        $quota = MemberPtQuota::factory()->create([
            'member_id' => $member->id,
            'total_sessions' => 10,
            'used_sessions' => 1,
            'remaining_sessions' => 9,
            'end_date' => now()->addMonth(),
        ]);

        // Session starting in 6 hours (cutoff is 12 hours before)
        $session = PtSession::factory()->create([
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'member_pt_quota_id' => $quota->id,
            'session_date' => now()->toDateString(),
            'start_time' => now()->addHours(6)->format('H:i:00'),
            'end_time' => now()->addHours(7)->format('H:i:00'),
            'status' => PtSessionStatus::Pending,
        ]);

        $service = app(PtSessionService::class);
        $cancelledCount = $service->cancelExpiredPendingSessions($member->id);

        $this->assertEquals(1, $cancelledCount);

        Notification::assertSentTo(
            $member,
            SessionCancelledNotification::class,
            function (SessionCancelledNotification $notification) use ($session) {
                return $notification->session->id === $session->id
                    && $notification->reason === 'Batas waktu konfirmasi sesi telah berakhir';
            }
        );
    }

    public function test_user_can_mark_notification_as_read(): void
    {
        $member = User::factory()->member()->create();
        $trainer = User::factory()->trainer()->create();

        $session = PtSession::factory()->create([
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
        ]);

        $notification = new SessionConfirmedNotification($session, $trainer);
        $member->notify($notification);

        $dbNotification = $member->unreadNotifications()->first();
        $this->assertNotNull($dbNotification);

        $response = $this->actingAs($member)->post(route('notifications.read', $dbNotification->id));

        $response->assertRedirect(route('pt-sessions.index'));
        $this->assertEquals(0, $member->unreadNotifications()->count());
    }

    public function test_user_can_mark_all_notifications_as_read(): void
    {
        $member = User::factory()->member()->create();
        $trainer = User::factory()->trainer()->create();

        $session1 = PtSession::factory()->create(['member_id' => $member->id, 'trainer_id' => $trainer->id]);
        $session2 = PtSession::factory()->create(['member_id' => $member->id, 'trainer_id' => $trainer->id]);

        $member->notify(new SessionConfirmedNotification($session1, $trainer));
        $member->notify(new SessionCancelledNotification($session2, 'Alasan tes', $trainer));

        $this->assertEquals(2, $member->unreadNotifications()->count());

        $response = $this->actingAs($member)->post(route('notifications.mark-all-read'));

        $response->assertRedirect();
        $this->assertEquals(0, $member->unreadNotifications()->count());
    }

    public function test_navbar_displays_notifications_dropdown(): void
    {
        $member = User::factory()->member()->create();
        $trainer = User::factory()->trainer()->create();

        $session = PtSession::factory()->create([
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
        ]);

        $member->notify(new SessionConfirmedNotification($session, $trainer));

        $response = $this->actingAs($member)->get(route('member.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Notifikasi');
        $response->assertSee('Sesi PT Dikonfirmasi');
        $response->assertSee('1 baru');
    }
}
