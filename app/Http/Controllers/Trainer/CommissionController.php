<?php

namespace App\Http\Controllers\Trainer;

use App\Enums\PtSessionStatus;
use App\Http\Controllers\Controller;
use App\Models\PtSession;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommissionController extends Controller
{
    /**
     * Menampilkan riwayat pendapatan dan komisi bagi Personal Trainer yang login.
     */
    public function index(Request $request): View
    {
        $trainer = $request->user();
        $trainerProfile = $trainer->trainerProfile;

        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));
        $startOfMonth = Carbon::parse($selectedMonth.'-01')->startOfMonth()->toDateString();
        $endOfMonth = Carbon::parse($selectedMonth.'-01')->endOfMonth()->toDateString();

        $defaultRate = (int) config('pt_booking.default_commission_rate', 60000);
        $currentRate = $trainerProfile?->commission_rate ?? $defaultRate;

        // Query sesi selesai untuk pelatih
        $baseSessionsQuery = PtSession::where('trainer_id', $trainer->id)
            ->whereIn('status', [PtSessionStatus::Completed->value, PtSessionStatus::Done->value])
            ->with(['member.memberProfile', 'quota.ptPackage', 'memberPtQuota.ptPackage']);

        // Riwayat sesi untuk bulan terpilih
        $monthlySessions = (clone $baseSessionsQuery)
            ->whereBetween('session_date', [$startOfMonth, $endOfMonth])
            ->orderBy('session_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Metrik Bulan Terpilih
        $monthCount = (clone $baseSessionsQuery)
            ->whereBetween('session_date', [$startOfMonth, $endOfMonth])
            ->count();

        $monthEarnings = (clone $baseSessionsQuery)
            ->whereBetween('session_date', [$startOfMonth, $endOfMonth])
            ->get()
            ->sum(fn ($s) => $s->trainer_earnings ?: $currentRate);

        // Metrik Sepanjang Waktu
        $allTimeCount = (clone $baseSessionsQuery)->count();
        $allTimeEarnings = (clone $baseSessionsQuery)
            ->get()
            ->sum(fn ($s) => $s->trainer_earnings ?: $currentRate);

        return view('trainer.commissions.index', [
            'trainer' => $trainer,
            'trainerProfile' => $trainerProfile,
            'currentRate' => $currentRate,
            'selectedMonth' => $selectedMonth,
            'monthlySessions' => $monthlySessions,
            'monthCount' => $monthCount,
            'monthEarnings' => $monthEarnings,
            'allTimeCount' => $allTimeCount,
            'allTimeEarnings' => $allTimeEarnings,
        ]);
    }
}
