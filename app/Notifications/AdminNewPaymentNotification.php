<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class AdminNewPaymentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Payment $payment,
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
        $memberName = $this->payment->member?->name ?? 'Member';
        $formattedAmount = 'Rp '.number_format((float) $this->payment->amount, 0, ',', '.');

        return (new WebPushMessage)
            ->title('Pembayaran Baru Masuk 💳')
            ->icon('/images/logo/logo-icon.png')
            ->body("Invoice {$this->payment->invoice_id} ({$formattedAmount}) dari {$memberName} menunggu verifikasi.")
            ->action('Verifikasi', 'view_payment')
            ->data(['url' => route('admin.payments.index')]);
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
            'type' => 'new_payment',
            'payment_id' => $this->payment->id,
            'invoice_id' => $this->payment->invoice_id,
            'member_id' => $this->payment->member_id,
            'member_name' => $this->payment->member?->name ?? 'Member',
            'amount' => $this->payment->amount,
            'title' => 'Pembayaran Baru Masuk',
            'message' => "Pembayaran baru dengan invoice {$this->payment->invoice_id} membutuhkan verifikasi.",
            'url' => route('admin.payments.index'),
        ];
    }
}
