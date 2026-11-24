<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class ChoosePtScheduleNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly int $memberPtQuotaId,
        public readonly string $packageName,
    ) {}

    /**
     * @return array<int, class-string|string>
     */
    public function via(object $notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    /**
     * Get the web push representation of the notification.
     */
    public function toWebPush(object $notifiable, mixed $notification = null): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Pilih Jadwal Personal Trainer 📅')
            ->icon('/images/logo/logo-icon.png')
            ->body("Paket {$this->packageName} sudah aktif. Silakan tentukan jadwal latihan sesi PT Anda!")
            ->action('Booking Sesi', 'book_session')
            ->data(['url' => route('pt-sessions.create')]);
    }

    /**
     * @return array{type: string, title: string, message: string, member_pt_quota_id: int, url: string}
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'choose_pt_schedule',
            'title' => 'Pilih jadwal Personal Trainer',
            'message' => "Paket {$this->packageName} sudah aktif. Silakan pilih jadwal sesi PT Anda.",
            'member_pt_quota_id' => $this->memberPtQuotaId,
            'url' => route('pt-sessions.create'),
        ];
    }
}
