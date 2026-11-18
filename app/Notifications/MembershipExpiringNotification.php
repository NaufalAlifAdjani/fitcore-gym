<?php

namespace App\Notifications;

use App\Models\Membership;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class MembershipExpiringNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Membership $membership,
        public readonly int $daysRemaining,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
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
        $packageName = $this->membership->package?->name ?? 'Membership';
        $endDate = Carbon::parse($this->membership->end_date)->locale('id')->isoFormat('D MMMM Y');

        if ($this->daysRemaining === 1) {
            $title = 'Perhatian: Membership Berakhir Besok! 🚨';
            $body = "Hari ini hari terakhir paket {$packageName}. Perpanjang sekarang untuk menjaga streak latihan Anda!";
        } else {
            $title = "Membership Berakhir {$this->daysRemaining} Hari Lagi ⏳";
            $body = "Paket {$packageName} Anda akan berakhir pada {$endDate}. Perpanjang sekarang agar akses gym tetap aktif!";
        }

        return (new WebPushMessage)
            ->title($title)
            ->icon('/images/logo/logo-icon.png')
            ->body($body)
            ->action('Perpanjang Paket', 'renew_package')
            ->data(['url' => route('member.membership.packages')]);
    }

    /**
     * Get the database representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return $this->toArray($notifiable);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $packageName = $this->membership->package?->name ?? 'Membership';
        $endDate = Carbon::parse($this->membership->end_date)->locale('id')->isoFormat('D MMMM Y');

        if ($this->daysRemaining === 1) {
            $title = 'Perhatian: Membership Berakhir Besok!';
            $message = "Hari ini hari terakhir paket {$packageName}. Perpanjang sekarang untuk menjaga streak latihan Anda!";
        } else {
            $title = "Membership Berakhir {$this->daysRemaining} Hari Lagi";
            $message = "Paket {$packageName} Anda akan berakhir pada {$endDate}. Perpanjang sekarang agar akses gym tetap aktif!";
        }

        return [
            'type' => 'membership_expiring',
            'membership_id' => $this->membership->id,
            'days_remaining' => $this->daysRemaining,
            'end_date' => (string) $this->membership->end_date,
            'title' => $title,
            'message' => $message,
            'url' => route('member.membership.packages'),
        ];
    }
}
