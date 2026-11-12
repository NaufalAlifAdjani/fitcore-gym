<?php

namespace App\Notifications;

use App\Models\PtSession;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewSessionRequestedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly PtSession $session,
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
        $memberName = $this->session->member?->name ?? 'Member FitCore';

        return (new MailMessage)
            ->subject('Pengajuan Jadwal Sesi PT Baru - FitCore')
            ->greeting("Halo Coach {$notifiable->name},")
            ->line("Member **{$memberName}** telah mengajukan jadwal sesi latihan Personal Trainer baru.")
            ->line("**Tanggal:** {$sessionDate}")
            ->line("**Waktu:** {$timeRange}")
            ->action('Periksa Jadwal Sesi', route('trainer.sessions.index'))
            ->line('Silakan lakukan konfirmasi pada portal trainer untuk menyetujui sesi latihan.');
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
        $memberName = $this->session->member?->name ?? 'Member FitCore';

        return [
            'type' => 'new_session_requested',
            'session_id' => $this->session->id,
            'member_id' => $this->session->member_id,
            'member_name' => $memberName,
            'session_date' => $this->session->session_date instanceof Carbon ? $this->session->session_date->toDateString() : (string) $this->session->session_date,
            'time_range' => $timeRange,
            'title' => 'Pengajuan Sesi Baru',
            'message' => "Member {$memberName} telah mengajukan jadwal sesi PT pada {$sessionDate} ({$timeRange}).",
            'url' => route('trainer.sessions.index'),
        ];
    }
}
