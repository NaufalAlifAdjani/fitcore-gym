<?php

namespace Tests\Feature;

use App\Models\Membership;
use App\Models\MembershipPackage;
use App\Models\Payment;
use App\Models\PtSession;
use App\Models\User;
use App\Notifications\AdminNewPaymentNotification;
use App\Notifications\ChoosePtScheduleNotification;
use App\Notifications\MembershipExpiringNotification;
use App\Notifications\NewSessionRequestedNotification;
use App\Notifications\PaymentRejectedNotification;
use App\Notifications\SessionCancelledNotification;
use App\Notifications\SessionConfirmedNotification;
use App\Services\PaymentVerificationService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use Tests\TestCase;

class PushNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_push_subscription_endpoints(): void
    {
        $this->getJson(route('push-subscriptions.vapid-public-key'))
            ->assertUnauthorized();

        $this->postJson(route('push-subscriptions.store'), [
            'endpoint' => 'https://fcm.googleapis.com/fcm/send/sample-token',
        ])->assertUnauthorized();

        $this->deleteJson(route('push-subscriptions.destroy'), [
            'endpoint' => 'https://fcm.googleapis.com/fcm/send/sample-token',
        ])->assertUnauthorized();
    }

    public function test_user_can_get_vapid_public_key(): void
    {
        $user = User::factory()->member()->create();

        $response = $this->actingAs($user)->getJson(route('push-subscriptions.vapid-public-key'));

        $response->assertOk()
            ->assertJsonStructure(['publicKey']);

        $this->assertNotEmpty($response->json('publicKey'));
    }

    public function test_user_can_store_and_delete_push_subscription(): void
    {
        $user = User::factory()->member()->create();
        $endpoint = 'https://fcm.googleapis.com/fcm/send/sample-unique-token-123';

        // 1. Store
        $response = $this->actingAs($user)->postJson(route('push-subscriptions.store'), [
            'endpoint' => $endpoint,
            'keys' => [
                'p256dh' => 'mock-p256dh-key',
                'auth' => 'mock-auth-token',
            ],
            'content_encoding' => 'aes128gcm',
        ]);

        $response->assertCreated()
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('push_subscriptions', [
            'subscribable_type' => User::class,
            'subscribable_id' => $user->id,
            'endpoint' => $endpoint,
        ]);

        // 2. Delete
        $deleteResponse = $this->actingAs($user)->deleteJson(route('push-subscriptions.destroy'), [
            'endpoint' => $endpoint,
        ]);

        $deleteResponse->assertOk()
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('push_subscriptions', [
            'endpoint' => $endpoint,
        ]);
    }

    public function test_notifications_contain_webpush_channel_and_valid_target_url(): void
    {
        $member = User::factory()->member()->create();
        $trainer = User::factory()->trainer()->create();
        $admin = User::factory()->admin()->create();

        $session = PtSession::factory()->create([
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'session_date' => Carbon::tomorrow()->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
        ]);

        $package = MembershipPackage::factory()->create(['name' => 'Gold Package']);
        $membership = Membership::factory()->create([
            'member_id' => $member->id,
            'membership_package_id' => $package->id,
            'end_date' => Carbon::tomorrow()->toDateString(),
            'status' => 'active',
        ]);

        $payment = Payment::factory()->create([
            'member_id' => $member->id,
            'status' => 'pending',
            'amount' => 500000,
        ]);

        // 1. SessionConfirmedNotification
        $confirmedNotif = new SessionConfirmedNotification($session, $trainer);
        $this->assertContains(WebPushChannel::class, $confirmedNotif->via($member));
        $pushData1 = $confirmedNotif->toWebPush($member)->toArray()['data'];
        $this->assertEquals(route('pt-sessions.index'), $pushData1['url']);
        $this->assertEquals(route('pt-sessions.index'), $confirmedNotif->toDatabase($member)['url']);

        // 2. SessionCancelledNotification
        $cancelledNotif = new SessionCancelledNotification($session, 'Coach sakit', $trainer);
        $this->assertContains(WebPushChannel::class, $cancelledNotif->via($member));
        $pushData2 = $cancelledNotif->toWebPush($member)->toArray()['data'];
        $this->assertEquals(route('pt-sessions.index'), $pushData2['url']);

        // 3. NewSessionRequestedNotification
        $newSessionNotif = new NewSessionRequestedNotification($session);
        $this->assertContains(WebPushChannel::class, $newSessionNotif->via($trainer));
        $pushData3 = $newSessionNotif->toWebPush($trainer)->toArray()['data'];
        $this->assertEquals(route('trainer.sessions.index', ['status' => 'pending']), $pushData3['url']);

        // 4. AdminNewPaymentNotification
        $adminPaymentNotif = new AdminNewPaymentNotification($payment);
        $this->assertContains(WebPushChannel::class, $adminPaymentNotif->via($admin));
        $pushData4 = $adminPaymentNotif->toWebPush($admin)->toArray()['data'];
        $this->assertEquals(route('admin.payments.index'), $pushData4['url']);
        $this->assertEquals(route('admin.payments.index'), $adminPaymentNotif->toDatabase($admin)['url']);

        // 5. ChoosePtScheduleNotification
        $choosePtNotif = new ChoosePtScheduleNotification(1, 'Paket PT 10 Sesi');
        $this->assertContains(WebPushChannel::class, $choosePtNotif->via($member));
        $pushData5 = $choosePtNotif->toWebPush($member)->toArray()['data'];
        $this->assertEquals(route('pt-sessions.create'), $pushData5['url']);
        $this->assertEquals(route('pt-sessions.create'), $choosePtNotif->toDatabase($member)['url']);

        // 6. PaymentRejectedNotification
        $rejectNotif = new PaymentRejectedNotification($payment, 'Bukti transfer buram');
        $this->assertContains(WebPushChannel::class, $rejectNotif->via($member));
        $pushData6 = $rejectNotif->toWebPush($member)->toArray()['data'];
        $this->assertEquals(route('member.riwayat-transaksi.index'), $pushData6['url']);
        $this->assertEquals(route('member.riwayat-transaksi.index'), $rejectNotif->toDatabase($member)['url']);

        // 7. MembershipExpiringNotification H-1
        $expiringH1 = new MembershipExpiringNotification($membership, 1);
        $this->assertContains(WebPushChannel::class, $expiringH1->via($member));
        $pushData7 = $expiringH1->toWebPush($member)->toArray()['data'];
        $this->assertEquals(route('member.membership.packages'), $pushData7['url']);
        $this->assertEquals(route('member.membership.packages'), $expiringH1->toDatabase($member)['url']);
    }

    public function test_payment_rejection_triggers_notification_to_member(): void
    {
        Notification::fake();

        $admin = User::factory()->admin()->create();
        $member = User::factory()->member()->create();
        $payment = Payment::factory()->create([
            'member_id' => $member->id,
            'status' => 'pending',
        ]);

        $service = app(PaymentVerificationService::class);
        $service->rejectPayment($payment, 'Bukti transfer tidak sesuai', $admin->id);

        Notification::assertSentTo(
            $member,
            PaymentRejectedNotification::class,
            function (PaymentRejectedNotification $notification) use ($payment) {
                return $notification->payment->id === $payment->id
                    && $notification->reason === 'Bukti transfer tidak sesuai';
            }
        );
    }

    public function test_system_update_membership_status_sends_h3_and_h1_notifications(): void
    {
        Notification::fake();

        $memberH3 = User::factory()->member()->create();
        $memberH1 = User::factory()->member()->create();

        $package = MembershipPackage::factory()->create();

        Membership::factory()->create([
            'member_id' => $memberH3->id,
            'membership_package_id' => $package->id,
            'start_date' => today()->subMonth(),
            'end_date' => today()->addDays(3),
            'status' => 'active',
        ]);

        Membership::factory()->create([
            'member_id' => $memberH1->id,
            'membership_package_id' => $package->id,
            'start_date' => today()->subMonth(),
            'end_date' => today()->addDays(1),
            'status' => 'active',
        ]);

        $this->artisan('system:update-membership-status')->assertSuccessful();

        Notification::assertSentTo(
            $memberH3,
            MembershipExpiringNotification::class,
            fn (MembershipExpiringNotification $n) => $n->daysRemaining === 3
        );

        Notification::assertSentTo(
            $memberH1,
            MembershipExpiringNotification::class,
            fn (MembershipExpiringNotification $n) => $n->daysRemaining === 1
        );
    }

    public function test_clicking_bell_notification_marks_as_read_and_redirects_to_target_url(): void
    {
        $member = User::factory()->member()->create();
        $trainer = User::factory()->trainer()->create();

        $session = PtSession::factory()->create([
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
        ]);

        $member->notify(new SessionConfirmedNotification($session, $trainer));

        $dbNotification = $member->unreadNotifications()->first();
        $this->assertNotNull($dbNotification);

        $response = $this->actingAs($member)->post(route('notifications.read', $dbNotification->id));

        $response->assertRedirect(route('pt-sessions.index'));
        $this->assertEquals(0, $member->unreadNotifications()->count());
    }
}
