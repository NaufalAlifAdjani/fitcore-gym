<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdminNewPaymentNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly Payment $payment,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
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
            'message' => "Pembayaran baru dengan invoice {$this->payment->invoice_id} membutuhkan verifikasi.",
        ];
    }
}
