<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

#[Signature('mail:test {email? : Alamat email tujuan untuk uji coba kirim}')]
#[Description('Menguji konfigurasi koneksi SMTP dan mengirim email verifikasi percobaan.')]
class SendTestMailCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $mailer = config('mail.default');
        $smtpConfig = config('mail.mailers.smtp', []);
        $fromAddress = config('mail.from.address');
        $fromName = config('mail.from.name');

        $this->info('=== INFORMASI KONFIGURASI EMAIL (SMTP) ===');
        $this->table(
            ['Pengaturan', 'Nilai Saat Ini'],
            [
                ['MAIL_MAILER', (string) $mailer],
                ['MAIL_HOST', (string) ($smtpConfig['host'] ?? '-')],
                ['MAIL_PORT', (string) ($smtpConfig['port'] ?? '-')],
                ['MAIL_ENCRYPTION', (string) ($smtpConfig['encryption'] ?? ($smtpConfig['scheme'] ?? 'none'))],
                ['MAIL_USERNAME', (string) ($smtpConfig['username'] ?? '-')],
                ['MAIL_PASSWORD', ! empty($smtpConfig['password']) ? '******** (Tersedia)' : '(Kosong/Null)'],
                ['MAIL_FROM_ADDRESS', (string) $fromAddress],
                ['MAIL_FROM_NAME', (string) $fromName],
            ]
        );

        if ($mailer === 'log') {
            $this->warn('Peringatan: MAIL_MAILER masih diatur ke "log". Email hanya akan dicatat di storage/logs/laravel.log dan tidak dikirim ke internet.');
            $this->warn('Ubah MAIL_MAILER=smtp di berkas .env untuk mengirim melalui server SMTP.');
        }

        $recipient = $this->argument('email') ?: $fromAddress;

        if (empty($recipient) || ! filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            $this->error('Alamat email penerima tidak valid atau kosong.');

            return Command::FAILURE;
        }

        $this->info("Sedang mencoba mengirim email percobaan ke: {$recipient}...");

        try {
            Mail::raw(
                "Halo!\n\nIni adalah email uji coba dari sistem FitCore Gym.\nKoneksi SMTP Anda telah berhasil terhubung dan dapat mengirim email dengan baik.\n\nWaktu pengiriman: ".now()->translatedFormat('l, d F Y H:i:s'),
                function ($message) use ($recipient) {
                    $message->to($recipient)
                        ->subject('Uji Coba Koneksi SMTP - FitCore Gym');
                }
            );

            $this->newLine();
            $this->info('========================================================');
            $this->info('  [BERHASIL] Email uji coba berhasil dikirim ke: '.$recipient);
            $this->info('========================================================');

            return Command::SUCCESS;
        } catch (TransportExceptionInterface|\Throwable $e) {
            $this->newLine();
            $this->error('========================================================');
            $this->error('  [GAGAL] Gagal terhubung atau mengirim via SMTP:');
            $this->error('  '.$e->getMessage());
            $this->error('========================================================');

            $this->newLine();
            $this->warn('Petunjuk Perbaikan:');

            $host = strtolower((string) ($smtpConfig['host'] ?? ''));
            if (str_contains($host, 'gmail')) {
                $this->line('1. Gmail mewajibkan penggunaan "Sandi Aplikasi" (App Password 16 karakter), bukan password login biasa.');
                $this->line('2. Pastikan 2-Step Verification aktif di Google Account Anda.');
                $this->line('3. Gunakan MAIL_PORT=587 dengan MAIL_ENCRYPTION=tls, atau MAIL_PORT=465 dengan MAIL_SCHEME=smtps.');
                $this->line('4. Pastikan MAIL_FROM_ADDRESS sama persis dengan MAIL_USERNAME.');
            } else {
                $this->line('1. Periksa kembali MAIL_HOST dan MAIL_PORT.');
                $this->line('2. Pastikan kredensial MAIL_USERNAME dan MAIL_PASSWORD sudah sesuai.');
                $this->line('3. Pastikan port SMTP tidak diblokir oleh ISP / firewall lokal.');
            }

            return Command::FAILURE;
        }
    }
}
