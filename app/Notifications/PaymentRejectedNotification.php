<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class PaymentRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Payment $payment,
        public readonly string $reason,
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
        $targetUrl = route('member.riwayat-transaksi.index');

        return (new WebPushMessage)
            ->title('Pembayaran Ditolak ❌')
            ->icon('/images/logo/logo-icon.png')
            ->body("Pembayaran invoice {$this->payment->invoice_id} ditolak: {$this->reason}. Silakan periksa detailnya.")
            ->action('Lihat Transaksi', 'view_transaction')
            ->data(['url' => $targetUrl]);
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
        return [
            'type' => 'payment_rejected',
            'payment_id' => $this->payment->id,
            'invoice_id' => $this->payment->invoice_id,
            'reason' => $this->reason,
            'title' => 'Pembayaran Ditolak',
            'message' => "Pembayaran invoice {$this->payment->invoice_id} ditolak: {$this->reason}.",
            'url' => route('member.riwayat-transaksi.index'),
        ];
    }
}
