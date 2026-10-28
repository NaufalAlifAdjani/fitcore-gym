<?php

namespace App\Services;

use App\Models\Payment;
use Carbon\Carbon;

class InvoicePdfService
{
    /**
     * Generate standard PDF 1.4 invoice document binary.
     */
    public function generate(Payment $payment): string
    {
        $invoiceId = (string) ($payment->invoice_id ?? 'INV-'.str_pad((string) $payment->id, 6, '0', STR_PAD_LEFT));
        $memberName = (string) ($payment->member?->name ?? 'Member FitCore');
        $memberEmail = (string) ($payment->member?->email ?? '-');
        $memberId = 'MEM-'.str_pad((string) $payment->member_id, 5, '0', STR_PAD_LEFT);

        $packageName = $this->resolvePackageName($payment);
        $packageType = ($payment->package_type === 'pt_session' || $payment->payment_type === 'pt_package')
            ? 'Personal Trainer'
            : 'Paket Membership';

        $transferDate = $payment->transfer_date ?? $payment->created_at ?? now();
        $formattedDate = $transferDate->format('d/m/Y H:i').' WIB';

        $statusLabel = match ($payment->status) {
            'verified' => 'BERHASIL / LUNAS',
            'pending' => 'MENUNGGU VERIFIKASI',
            'rejected' => 'DITOLAK',
            default => strtoupper((string) $payment->status),
        };

        $amountFormatted = 'Rp '.number_format((float) $payment->amount, 0, ',', '.');
        $bankSender = (string) ($payment->bank_sender ?? '-');
        $bankDestination = (string) ($payment->bank_destination ?? ($payment->bank?->name ? $payment->bank->name.' ('.$payment->bank->account_number.')' : 'FitCore Gym Account'));

        // Resolve active period
        $activePeriod = $this->resolveActivePeriod($payment);

        // Build PDF graphics stream
        $stream = [];

        // Header Background Banner (Dark Charcoal: #141414)
        $stream[] = '0.078 0.078 0.078 rg'; // #141414
        $stream[] = '40 730 515 80 re f';

        // Red Accent Stripe (#ED1B45)
        $stream[] = '0.929 0.106 0.271 rg'; // #ED1B45
        $stream[] = '40 726 515 4 re f';

        // Brand Logo & Title (White text)
        $stream[] = '1 1 1 rg'; // White
        $stream[] = 'BT /F2 18 Tf 55 778 Td (FITCORE ATHLETIC) Tj ET';
        $stream[] = 'BT /F1 9 Tf 55 762 Td (PREMIUM GYM & PERFORMANCE CENTER) Tj ET';
        $stream[] = 'BT /F1 8 Tf 55 746 Td (SCBD District 8, Jakarta Selatan | cs@fitcoreathletic.id) Tj ET';

        // INVOICE Badge on the right
        $stream[] = 'BT /F2 16 Tf 420 778 Td (OFFICIAL INVOICE) Tj ET';
        $stream[] = 'BT /F1 9 Tf 420 762 Td (No: '.$this->escapePdf($invoiceId).') Tj ET';
        $stream[] = 'BT /F1 8 Tf 420 746 Td (Tgl: '.$this->escapePdf($formattedDate).') Tj ET';

        // Information Grid Box (Light Gray Card: #F6F6F7)
        $stream[] = '0.965 0.965 0.969 rg'; // #F6F6F7
        $stream[] = '40 605 515 105 re f';
        $stream[] = '0.898 0.906 0.922 RG'; // Border #E5E7EB
        $stream[] = '1 w';
        $stream[] = '40 605 515 105 re S';

        // Section Headers inside Info Grid
        $stream[] = '0.929 0.106 0.271 rg'; // #ED1B45
        $stream[] = 'BT /F2 10 Tf 55 690 Td (DATA PEMESAN) Tj ET';
        $stream[] = 'BT /F2 10 Tf 315 690 Td (DETAIL PEMBAYARAN) Tj ET';

        // Member Data Text
        $stream[] = '0.086 0.082 0.102 rg'; // #16151A
        $stream[] = 'BT /F2 9 Tf 55 672 Td (Nama:) Tj /F1 9 Tf 35 0 Td ('.$this->escapePdf($memberName).') Tj ET';
        $stream[] = 'BT /F2 9 Tf 55 656 Td (Email:) Tj /F1 9 Tf 35 0 Td ('.$this->escapePdf($memberEmail).') Tj ET';
        $stream[] = 'BT /F2 9 Tf 55 640 Td (ID:) Tj /F1 9 Tf 35 0 Td ('.$this->escapePdf($memberId).') Tj ET';

        // Payment Details Text
        $stream[] = 'BT /F2 9 Tf 315 672 Td (Metode:) Tj /F1 9 Tf 45 0 Td (Transfer Bank) Tj ET';
        $stream[] = 'BT /F2 9 Tf 315 656 Td (Pengirim:) Tj /F1 9 Tf 45 0 Td ('.$this->escapePdf($bankSender).') Tj ET';
        $stream[] = 'BT /F2 9 Tf 315 640 Td (Tujuan:) Tj /F1 9 Tf 45 0 Td ('.$this->escapePdf($bankDestination).') Tj ET';

        // Status Badge Row
        $stream[] = 'BT /F2 9 Tf 315 622 Td (Status:) Tj ET';
        if ($payment->status === 'verified') {
            $stream[] = '0.063 0.612 0.345 rg'; // Green
        } elseif ($payment->status === 'pending') {
            $stream[] = '0.851 0.525 0.051 rg'; // Amber
        } else {
            $stream[] = '0.886 0.149 0.247 rg'; // Red
        }
        $stream[] = 'BT /F2 9 Tf 360 622 Td ('.$this->escapePdf($statusLabel).') Tj ET';

        // Table Header
        $stream[] = '0.086 0.082 0.102 rg'; // Dark table header
        $stream[] = '40 560 515 26 re f';
        $stream[] = '1 1 1 rg'; // White text
        $stream[] = 'BT /F2 9 Tf 55 570 Td (DESKRIPSI ITEM / LAYANAN) Tj ET';
        $stream[] = 'BT /F2 9 Tf 280 570 Td (KATEGORI) Tj ET';
        $stream[] = 'BT /F2 9 Tf 380 570 Td (PERIODE AKTIF) Tj ET';
        $stream[] = 'BT /F2 9 Tf 480 570 Td (TOTAL) Tj ET';

        // Table Row (White with border)
        $stream[] = '1 1 1 rg';
        $stream[] = '40 500 515 60 re f';
        $stream[] = '0.898 0.906 0.922 RG';
        $stream[] = '40 500 515 60 re S';

        $stream[] = '0.086 0.082 0.102 rg';
        $stream[] = 'BT /F2 10 Tf 55 536 Td ('.$this->escapePdf($packageName).') Tj ET';
        $stream[] = 'BT /F1 8 Tf 55 520 Td (FitCore Athletic Gym Membership Access) Tj ET';

        $stream[] = 'BT /F1 9 Tf 280 536 Td ('.$this->escapePdf($packageType).') Tj ET';
        $stream[] = 'BT /F1 9 Tf 380 536 Td ('.$this->escapePdf($activePeriod).') Tj ET';

        $stream[] = '0.929 0.106 0.271 rg'; // Red total
        $stream[] = 'BT /F2 10 Tf 460 536 Td ('.$this->escapePdf($amountFormatted).') Tj ET';

        // Summary Breakdown Box
        $stream[] = '0.965 0.965 0.969 rg';
        $stream[] = '315 410 240 75 re f';
        $stream[] = '0.898 0.906 0.922 RG';
        $stream[] = '315 410 240 75 re S';

        $stream[] = '0.337 0.353 0.400 rg'; // #565A66 Muted
        $stream[] = 'BT /F1 9 Tf 330 462 Td (Subtotal Tagihan:) Tj ET';
        $stream[] = 'BT /F1 9 Tf 445 462 Td ('.$this->escapePdf($amountFormatted).') Tj ET';

        $stream[] = 'BT /F1 9 Tf 330 446 Td (Pajak & Administrasi:) Tj ET';
        $stream[] = 'BT /F1 9 Tf 445 446 Td (Rp 0 (Termasuk)) Tj ET';

        // Divider
        $stream[] = '0.898 0.906 0.922 RG';
        $stream[] = '325 438 m 545 438 l S';

        // Grand Total Row
        $stream[] = '0.086 0.082 0.102 rg';
        $stream[] = 'BT /F2 10 Tf 330 422 Td (TOTAL PEMBAYARAN:) Tj ET';
        $stream[] = '0.929 0.106 0.271 rg';
        $stream[] = 'BT /F2 11 Tf 445 422 Td ('.$this->escapePdf($amountFormatted).') Tj ET';

        // Security / Verification Stamp Notice
        $stream[] = '0.898 0.906 0.922 RG';
        $stream[] = '40 310 515 80 re S';
        $stream[] = '0.965 0.965 0.969 rg';
        $stream[] = '40 310 515 80 re f';

        $stream[] = '0.929 0.106 0.271 rg';
        $stream[] = 'BT /F2 9 Tf 55 368 Td (CATATAN RESMI FITCORE ATHLETIC) Tj ET';
        $stream[] = '0.337 0.353 0.400 rg';
        $stream[] = 'BT /F1 8 Tf 55 352 Td (1. Invoice ini diterbitkan secara otomatis melalui sistem digital FitCore Gym.) Tj ET';
        $stream[] = 'BT /F1 8 Tf 55 338 Td (2. Berlaku sebagai bukti pembayaran sah dan dokumen konfirmasi keanggotaan gym.) Tj ET';
        $stream[] = 'BT /F1 8 Tf 55 324 Td (3. Untuk pertanyaan dan bantuan, hubungi Customer Care kami di WhatsApp +62 812-3456-7890.) Tj ET';

        // Footer Note
        $stream[] = '0.55 0.55 0.58 rg';
        $stream[] = 'BT /F1 8 Tf 180 80 Td (Copyright FitCore Athletic Gym & Performance Center. All rights reserved.) Tj ET';

        $content = implode("\n", $stream);
        $contentLength = strlen($content);

        // Compile PDF Objects with accurate byte offsets
        $objects = [];
        $objects[1] = '<< /Type /Catalog /Pages 2 0 R >>';
        $objects[2] = '<< /Type /Pages /Kids [3 0 R] /Count 1 >>';
        $objects[3] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595.28 841.89] /Contents 4 0 R /Resources << /Font << /F1 5 0 R /F2 6 0 R >> >> >>';
        $objects[4] = "<< /Length {$contentLength} >>\nstream\n".$content."\nendstream";
        $objects[5] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>';
        $objects[6] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>';

        $output = "%PDF-1.4\n";
        $offsets = [];

        foreach ($objects as $num => $obj) {
            $offsets[$num] = strlen($output);
            $output .= "{$num} 0 obj\n{$obj}\nendobj\n";
        }

        $xrefOffset = strlen($output);
        $output .= "xref\n";
        $output .= '0 '.(count($objects) + 1)."\n";
        $output .= "0000000000 65535 f \n";

        for ($i = 1; $i <= count($objects); $i++) {
            $output .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }

        $output .= "trailer\n";
        $output .= '<< /Size '.(count($objects) + 1)." /Root 1 0 R >>\n";
        $output .= "startxref\n{$xrefOffset}\n%%EOF";

        return $output;
    }

    private function resolvePackageName(Payment $payment): string
    {
        if ($payment->package_type === 'pt_session' || $payment->payment_type === 'pt_package') {
            return $payment->ptPackage?->name ?? 'Personal Trainer - Sesi Latihan';
        }

        return $payment->membershipPackage?->name
            ?? $payment->activatedMembership?->package?->name
            ?? $payment->membership?->package?->name
            ?? 'FitCore Membership Pass';
    }

    private function resolveActivePeriod(Payment $payment): string
    {
        if ($payment->activatedMembership?->start_date && $payment->activatedMembership?->end_date) {
            return $payment->activatedMembership->start_date->format('d/m/y').' - '.$payment->activatedMembership->end_date->format('d/m/y');
        }

        if ($payment->membership?->start_date && $payment->membership?->end_date) {
            return $payment->membership->start_date->format('d/m/y').' - '.$payment->membership->end_date->format('d/m/y');
        }

        $baseDate = $payment->transfer_date ?? $payment->created_at ?? now();
        $duration = $payment->membershipPackage?->duration_in_days
            ?? $payment->membershipPackage?->duration_days
            ?? 30;

        $end = Carbon::parse($baseDate)->addDays((int) $duration);

        return Carbon::parse($baseDate)->format('d/m/y').' - '.$end->format('d/m/y');
    }

    private function escapePdf(string $text): string
    {
        // Only ASCII-safe representation for standard Type 1 Helvetica font
        $text = iconv('UTF-8', 'Windows-1252//TRANSLIT', $text) ?: $text;

        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
    }
}
