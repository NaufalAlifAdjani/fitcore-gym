<?php

namespace App\Http\Controllers\Trainer;

use App\Enums\PtSessionStatus;
use App\Http\Controllers\Controller;
use App\Models\MemberPtQuota;
use App\Models\PtSession;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the trainer dashboard.
     */
    public function index(Request $request): View
    {
        $trainer = $request->user();
        $trainerProfile = $trainer->trainerProfile;

        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();
        $today = Carbon::today()->toDateString();

        // 1. Sesi Hari Ini
        $todaySessions = $trainer->trainerSessions()
            ->whereDate('session_date', $today)
            ->whereNotIn('status', [PtSessionStatus::Cancelled->value])
            ->with(['member.memberProfile', 'quota.ptPackage', 'memberPtQuota.ptPackage'])
            ->orderBy('start_time', 'asc')
            ->get();
        $todaySessionsCount = $todaySessions->count();

        // 2. Sesi Selesai (Murni akumulasi tanpa target)
        $completedSessionsMonth = $trainer->trainerSessions()
            ->whereIn('status', [PtSessionStatus::Completed->value, PtSessionStatus::Done->value])
            ->whereBetween('session_date', [$startOfMonth, $endOfMonth])
            ->count();

        $totalCompletedSessions = $trainer->trainerSessions()
            ->whereIn('status', [PtSessionStatus::Completed->value, PtSessionStatus::Done->value])
            ->count();

        // 3. Estimasi Komisi (Default per sesi selesai)
        $commissionRate = (int) config('pt_booking.default_commission_rate', 60000);
        $estimatedCommission = $completedSessionsMonth * $commissionRate;

        // 4. Total Member Aktif (Murni akumulasi tanpa target)
        $activeMembersCount = $trainer->trainerSessions()
            ->distinct('member_id')
            ->count('member_id');

        // 5. Permintaan Booking Baru (Pending yang butuh konfirmasi trainer)
        $pendingSessions = $trainer->trainerSessions()
            ->where('status', PtSessionStatus::Pending->value)
            ->whereDate('session_date', '>=', $today)
            ->with(['member.memberProfile', 'quota.ptPackage', 'memberPtQuota.ptPackage'])
            ->orderBy('session_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->take(5)
            ->get();

        // 6. Peringatan Sisa Kuota Klien (Member bimbingan dengan kuota tersisa <= 2 sesi)
        $lowQuotaClients = MemberPtQuota::query()
            ->whereHas('sessions', function ($query) use ($trainer) {
                $query->where('trainer_id', $trainer->id);
            })
            ->where('status', 'active')
            ->where('remaining_sessions', '<=', 2)
            ->with(['member.memberProfile', 'ptPackage'])
            ->distinct()
            ->take(5)
            ->get();

        // Current period label
        $currentMonthLabel = Carbon::now()->translatedFormat('F Y');

        return view('trainer.dashboard', [
            'trainer' => $trainer,
            'trainerProfile' => $trainerProfile,
            'todaySessions' => $todaySessions,
            'todaySessionsCount' => $todaySessionsCount,
            'completedSessions' => $completedSessionsMonth,
            'totalCompletedSessions' => $totalCompletedSessions,
            'commissionRate' => $commissionRate,
            'estimatedCommission' => $estimatedCommission,
            'activeMembersCount' => $activeMembersCount,
            'pendingSessions' => $pendingSessions,
            'lowQuotaClients' => $lowQuotaClients,
            'currentMonthLabel' => $currentMonthLabel,
        ]);
    }

    /**
     * Konfirmasi permintaan sesi dari dashboard.
     */
    public function confirmSession(Request $request, PtSession $ptSession): RedirectResponse
    {
        $trainer = $request->user();

        if ($ptSession->trainer_id !== $trainer->id) {
            abort(403, 'Anda tidak memiliki hak untuk mengonfirmasi sesi ini.');
        }

        if ($ptSession->status === PtSessionStatus::Pending) {
            $ptSession->update([
                'status' => PtSessionStatus::Confirmed,
            ]);

            return redirect()->route('trainer.dashboard')
                ->with('success', 'Sesi latihan berhasil diterima dan dikonfirmasi.');
        }

        return redirect()->route('trainer.dashboard')
            ->with('info', 'Status sesi latihan sudah tidak dalam posisi menunggu.');
    }

    /**
     * Tolak permintaan sesi dari dashboard dan kembalikan kuota member.
     */
    public function rejectSession(Request $request, PtSession $ptSession): RedirectResponse
    {
        $trainer = $request->user();

        if ($ptSession->trainer_id !== $trainer->id) {
            abort(403, 'Anda tidak memiliki hak untuk menolak sesi ini.');
        }

        if ($ptSession->status === PtSessionStatus::Pending) {
            DB::transaction(function () use ($ptSession) {
                $ptSession->update([
                    'status' => PtSessionStatus::Cancelled,
                ]);

                $quota = MemberPtQuota::where('id', $ptSession->member_pt_quota_id)
                    ->lockForUpdate()
                    ->first();

                if ($quota) {
                    $quota->decrement('used_sessions');
                    $quota->increment('remaining_sessions');
                }
            });

            return redirect()->route('trainer.dashboard')
                ->with('success', 'Permintaan sesi ditolak dan kuota sesi telah dikembalikan ke member.');
        }

        return redirect()->route('trainer.dashboard')
            ->with('info', 'Status sesi latihan sudah tidak dalam posisi menunggu.');
    }
}
