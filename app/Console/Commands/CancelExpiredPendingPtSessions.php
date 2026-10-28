<?php

namespace App\Console\Commands;

use App\Services\PtSessionService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('pt:cancel-expired-pending')]
#[Description('Membatalkan otomatis sesi PT pending yang melewati batas konfirmasi (H-12 jam sebelum jadwal sesi).')]
class CancelExpiredPendingPtSessions extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(PtSessionService $ptSessionService): int
    {
        $count = $ptSessionService->cancelExpiredPendingSessions();

        $this->info("{$count} sesi PT pending yang telah melewati batas konfirmasi berhasil dibatalkan otomatis dan kuota dikembalikan.");

        return Command::SUCCESS;
    }
}
