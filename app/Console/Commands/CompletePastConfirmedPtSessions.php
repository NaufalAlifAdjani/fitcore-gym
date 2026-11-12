<?php

namespace App\Console\Commands;

use App\Services\PtSessionService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('pt:complete-past-confirmed')]
#[Description('Menandai otomatis sesi PT terkonfirmasi yang jadwalnya telah terlewati menjadi berstatus selesai (completed).')]
class CompletePastConfirmedPtSessions extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(PtSessionService $ptSessionService): int
    {
        $count = $ptSessionService->completePastConfirmedSessions();

        $this->info("{$count} sesi PT terkonfirmasi yang telah melewati jam jadwal berhasil diselesaikan otomatis.");

        return Command::SUCCESS;
    }
}
