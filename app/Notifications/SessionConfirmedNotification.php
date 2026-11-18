<?php

namespace App\Notifications;

use App\Models\PtSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class SessionConfirmedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly PtSession $session,
        public readonly ?User $confirmedBy = null,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, class-string|string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail', WebPushChannel::class];
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
        $trainerName = $this->session->trainer?->name ?? 'Personal Trainer';

        return (new MailMessage)
            ->subject('Jadwal Sesi PT Dikonfirmasi - FitCore')
            ->greeting("Halo {$notifiable->name},")
            ->line('Kabar gembira! Jadwal sesi latihan Personal Trainer Anda telah disetujui dan dikonfirmasi.')
            ->line("**Pelatih:** Coach {$trainerName}")
            ->line("**Tanggal:** {$sessionDate}")
            ->line("**Waktu:** {$timeRange}")
            ->action('Lihat Sesi Latihan', route('pt-sessions.index'))
            ->line('Harap hadir 10 menit sebelum waktu latihan dimulai. Tetap semangat!');
    }

    /**
     * Get the web push representation of the notification.
     */
    public function toWebPush(object $notifiable, mixed $notification = null): WebPushMessage
    {
        $sessionDate = Carbon::parse($this->session->session_date)->locale('id')->isoFormat('D MMMM Y');
        $startTime = substr((string) $this->session->start_time, 0, 5);
        $trainerName = $this->session->trainer?->name ?? 'Personal Trainer';

        return (new WebPushMessage)
            ->title('Sesi PT Disetujui! ✅')
            ->icon('/images/logo/logo-icon.png')
            ->body("Sesi latihan dengan Coach {$trainerName} pada {$sessionDate} jam {$startTime} WIB telah dikonfirmasi.")
            ->action('Lihat Sesi', 'view_session')
            ->data(['url' => route('pt-sessions.index')]);
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
        $trainerName = $this->session->trainer?->name ?? 'Personal Trainer';

        return [
            'type' => 'session_confirmed',
            'session_id' => $this->session->id,
            'trainer_id' => $this->session->trainer_id,
            'trainer_name' => $trainerName,
            'session_date' => $this->session->session_date instanceof Carbon ? $this->session->session_date->toDateString() : (string) $this->session->session_date,
            'time_range' => $timeRange,
            'title' => 'Sesi PT Dikonfirmasi',
            'message' => "Sesi latihan dengan Coach {$trainerName} pada {$sessionDate} ({$timeRange}) telah dikonfirmasi.",
            'url' => route('pt-sessions.index'),
        ];
    }
}
