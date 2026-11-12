<?php

namespace App\Notifications;

use App\Models\PtSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SessionCancelledNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly PtSession $session,
        public readonly ?string $reason = null,
        public readonly ?User $cancelledBy = null,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $sessionDate = Carbon::parse($this->session->session_date)->locale('id')->isoFormat('D MMMM Y');
        $startTime = substr((string) $this->session->start_time, 0, 5);
        $endTime = substr((string) $this->session->end_time, 0, 5);
        $timeRange = "{$startTime} - {$endTime} WIB";

        $isRecipientTrainer = $notifiable->id === $this->session->trainer_id;
        $cancelledByName = $this->cancelledBy?->name ?? 'Sistem';
        $targetUrl = $isRecipientTrainer ? route('trainer.sessions.index') : route('pt-sessions.index');

        $mail = (new MailMessage)
            ->subject('Pemberitahuan Pembatalan Sesi PT - FitCore')
            ->greeting("Halo {$notifiable->name},");

        if ($isRecipientTrainer) {
            $memberName = $this->session->member?->name ?? 'Member';
            $mail->line("Sesi latihan dengan member **{$memberName}** pada tanggal **{$sessionDate}** ({$timeRange}) telah dibatalkan oleh {$cancelledByName}.");
        } else {
            $trainerName = $this->session->trainer?->name ?? 'Personal Trainer';
            $mail->line("Sesi latihan Anda dengan Coach **{$trainerName}** pada tanggal **{$sessionDate}** ({$timeRange}) telah dibatalkan.");
        }

        if ($this->reason) {
            $mail->line("**Alasan pembatalan:** {$this->reason}");
        }

        if (! $isRecipientTrainer) {
            $mail->line('Kuota sesi latihan Anda telah dikembalikan secara utuh ke akun Anda.');
        }

        return $mail
            ->action('Lihat Jadwal', $targetUrl)
            ->line('Jika Anda memiliki pertanyaan lebih lanjut, silakan hubungi tim dukungan FitCore.');
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
        $sessionDate = Carbon::parse($this->session->session_date)->locale('id')->isoFormat('D MMMM Y');
        $startTime = substr((string) $this->session->start_time, 0, 5);
        $endTime = substr((string) $this->session->end_time, 0, 5);
        $timeRange = "{$startTime} - {$endTime} WIB";

        $isRecipientTrainer = $notifiable->id === $this->session->trainer_id;
        $cancelledByName = $this->cancelledBy?->name ?? 'Sistem';
        $targetUrl = $isRecipientTrainer ? route('trainer.sessions.index') : route('pt-sessions.index');

        if ($isRecipientTrainer) {
            $memberName = $this->session->member?->name ?? 'Member';
            $message = "Sesi latihan dengan {$memberName} pada {$sessionDate} ({$timeRange}) dibatalkan oleh {$cancelledByName}.";
        } else {
            $trainerName = $this->session->trainer?->name ?? 'Personal Trainer';
            $message = "Sesi latihan dengan Coach {$trainerName} pada {$sessionDate} ({$timeRange}) telah dibatalkan.";
        }

        if ($this->reason) {
            $message .= " Alasan: {$this->reason}";
        }

        return [
            'type' => 'session_cancelled',
            'session_id' => $this->session->id,
            'cancelled_by_id' => $this->cancelledBy?->id,
            'cancelled_by_name' => $cancelledByName,
            'reason' => $this->reason,
            'session_date' => $this->session->session_date instanceof Carbon ? $this->session->session_date->toDateString() : (string) $this->session->session_date,
            'time_range' => $timeRange,
            'title' => 'Sesi PT Dibatalkan',
            'message' => $message,
            'url' => $targetUrl,
        ];
    }
}
