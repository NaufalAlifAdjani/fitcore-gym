<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class ChoosePtScheduleNotification extends Notification
{
    public function __construct(
        public readonly int $memberPtQuotaId,
        public readonly string $packageName,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array{type: string, title: string, message: string, member_pt_quota_id: int}
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'choose_pt_schedule',
            'title' => 'Pilih jadwal Personal Trainer',
            'message' => "Paket {$this->packageName} sudah aktif. Silakan pilih jadwal sesi PT Anda.",
            'member_pt_quota_id' => $this->memberPtQuotaId,
        ];
    }
}
