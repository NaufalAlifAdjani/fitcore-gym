<?php

namespace App\Notifications;

use App\Models\Membership;
use App\Models\MembershipPackage;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class MembershipPaymentVerifiedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Membership $membership,
        public readonly MembershipPackage $package,
        public readonly ?Payment $payment = null,
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
        $formattedEndDate = $this->membership->end_date ? Carbon::parse($this->membership->end_date)->translatedFormat('d M Y') : 'Aktif';

        return (new WebPushMessage)
            ->title('Pembayaran Membership Terverifikasi 🎉')
            ->icon('/images/logo/logo-icon.png')
            ->body("Paket {$this->package->name} Anda telah aktif hingga {$formattedEndDate}. Selamat berlatih di FitCore!")
            ->action('Lihat Kartu Member', 'view_card')
            ->data(['url' => route('member.card')]);
    }

    /**
     * @return array{type: string, title: string, message: string, membership_id: int, package_name: string, url: string}
     */
    public function toDatabase(object $notifiable): array
    {
        $formattedEndDate = $this->membership->end_date ? Carbon::parse($this->membership->end_date)->translatedFormat('d M Y') : 'Aktif';

        return [
            'type' => 'membership_verified',
            'title' => 'Pembayaran Membership Terverifikasi 🎉',
            'message' => "Paket membership {$this->package->name} Anda telah aktif hingga {$formattedEndDate}. Selamat berlatih di FitCore Athletic!",
            'membership_id' => $this->membership->id,
            'package_name' => $this->package->name,
            'url' => route('member.card'),
        ];
    }
}
