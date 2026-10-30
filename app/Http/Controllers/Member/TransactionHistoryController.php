<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\InvoicePdfService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class TransactionHistoryController extends Controller
{
    /**
     * Menampilkan riwayat transaksi member yang dikelompokkan berdasarkan bulan dan tahun.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Siapkan pilihan tahun yang tersedia dari data transaksi member
        $availableYears = Payment::query()
            ->where('member_id', $user->id)
            ->get(['transfer_date', 'created_at'])
            ->map(function (Payment $payment): ?string {
                $date = $payment->transfer_date ?? $payment->created_at;

                return $date?->format('Y');
            })
            ->filter()
            ->unique()
            ->sortDesc()
            ->values();

        if ($availableYears->isEmpty()) {
            $availableYears = collect([(string) now()->year]);
        }

        // Siapkan query dengan eager loading lengkap untuk menghindari N+1 query
        $query = Payment::query()
            ->where('member_id', $user->id)
            ->with([
                'member:id,name,email',
                'bank',
                'membershipPackage',
                'ptPackage',
                'activatedMembership.package',
                'membership.package',
            ]);

        // Filter pencarian: nomor invoice atau nama paket
        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));

            $query->where(function ($subQuery) use ($search): void {
                $subQuery->where('invoice_id', 'like', "%{$search}%")
                    ->orWhereHas('membershipPackage', function ($q) use ($search): void {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('ptPackage', function ($q) use ($search): void {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('activatedMembership.package', function ($q) use ($search): void {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('membership.package', function ($q) use ($search): void {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter status transaksi (Semua, Menunggu Verifikasi, Berhasil)
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $status = (string) $request->input('status');

            if (in_array($status, ['pending', 'verified', 'rejected'], true)) {
                $query->where('status', $status);
            }
        }

        // Filter tahun
        if ($request->filled('year') && $request->input('year') !== 'all') {
            $year = (int) $request->input('year');

            $query->where(function ($yearQuery) use ($year): void {
                $yearQuery->whereYear('transfer_date', $year)
                    ->orWhere(function ($fallbackQuery) use ($year): void {
                        $fallbackQuery->whereNull('transfer_date')
                            ->whereYear('created_at', $year);
                    });
            });
        }

        // Eksekusi query dengan urutan transaksi terbaru
        $payments = $query
            ->latest('transfer_date')
            ->latest('created_at')
            ->get();

        $totalCount = $payments->count();

        // Kelompokkan data transaksi berdasarkan Bulan dan Tahun menggunakan Collection groupBy()
        $groupedPayments = $payments->groupBy(function (Payment $payment): string {
            $date = $payment->transfer_date ?? $payment->created_at ?? now();

            return strtoupper($date->copy()->locale('id')->translatedFormat('F Y'));
        });

        return view('member.riwayat-transaksi.index', [
            'groupedPayments' => $groupedPayments,
            'availableYears' => $availableYears,
            'totalCount' => $totalCount,
            'currentSearch' => (string) $request->input('search', ''),
            'currentStatus' => (string) $request->input('status', 'all'),
            'currentYear' => (string) $request->input('year', 'all'),
        ]);
    }

    /**
     * Mengunduh faktur/invoice resmi transaksi dalam format PDF.
     */
    public function invoice(Request $request, Payment $payment, InvoicePdfService $pdfService): Response
    {
        // Pastikan hanya pemilik transaksi atau admin yang berhak mengunduh invoice
        if ($payment->member_id !== $request->user()->id && $request->user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki hak akses untuk mengunduh invoice transaksi ini.');
        }

        // Load relasi yang diperlukan untuk konten faktur
        $payment->loadMissing([
            'member',
            'bank',
            'membershipPackage',
            'ptPackage',
            'activatedMembership.package',
            'membership.package',
        ]);

        $pdfContent = $pdfService->generate($payment);
        $filename = 'Invoice-'.($payment->invoice_id ?? 'INV-'.$payment->id).'.pdf';

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Cache-Control' => 'private, max-age=0, must-revalidate',
            'Pragma' => 'public',
        ]);
    }
}
