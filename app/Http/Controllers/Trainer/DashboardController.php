<?php

namespace App\Http\Controllers\Trainer;

use App\Enums\PtSessionStatus;
use App\Http\Controllers\Controller;
use App\Models\MemberPtQuota;
use App\Models\PtSession;
use App\Models\User;
use App\Services\PtSessionService;
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
        app(PtSessionService::class)->completePastConfirmedSessions($trainer->id);

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

            if ($request->is('m/*') || str_contains($request->header('referer', ''), '/m/')) {
                return redirect()->back()->with('success', 'Sesi latihan berhasil diterima dan dikonfirmasi.');
            }

            return redirect()->route('trainer.dashboard')
                ->with('success', 'Sesi latihan berhasil diterima dan dikonfirmasi.');
        }

        if ($request->is('m/*') || str_contains($request->header('referer', ''), '/m/')) {
            return redirect()->back()->with('info', 'Status sesi latihan sudah tidak dalam posisi menunggu.');
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

            if ($request->is('m/*') || str_contains($request->header('referer', ''), '/m/')) {
                return redirect()->back()->with('success', 'Permintaan sesi ditolak dan kuota sesi telah dikembalikan ke member.');
            }

            return redirect()->route('trainer.dashboard')
                ->with('success', 'Permintaan sesi ditolak dan kuota sesi telah dikembalikan ke member.');
        }

        if ($request->is('m/*') || str_contains($request->header('referer', ''), '/m/')) {
            return redirect()->back()->with('info', 'Status sesi latihan sudah tidak dalam posisi menunggu.');
        }

        return redirect()->route('trainer.dashboard')
            ->with('info', 'Status sesi latihan sudah tidak dalam posisi menunggu.');
    }

    /**
     * Display the mobile trainer dashboard.
     */
    public function mobileDashboard(Request $request): View
    {
        $trainer = $request->user();
        $trainerProfile = $trainer->trainerProfile;

        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();
        $today = Carbon::today()->toDateString();

        $todaySessions = $trainer->trainerSessions()
            ->whereDate('session_date', $today)
            ->whereNotIn('status', [PtSessionStatus::Cancelled->value])
            ->with(['member.memberProfile', 'quota.ptPackage', 'memberPtQuota.ptPackage'])
            ->orderBy('start_time', 'asc')
            ->get();
        $todaySessionsCount = $todaySessions->count();

        $completedSessionsMonth = $trainer->trainerSessions()
            ->whereIn('status', [PtSessionStatus::Completed->value, PtSessionStatus::Done->value])
            ->whereBetween('session_date', [$startOfMonth, $endOfMonth])
            ->count();

        $totalCompletedSessions = $trainer->trainerSessions()
            ->whereIn('status', [PtSessionStatus::Completed->value, PtSessionStatus::Done->value])
            ->count();

        $commissionRate = (int) config('pt_booking.default_commission_rate', 60000);
        $estimatedCommission = $completedSessionsMonth * $commissionRate;

        $activeMembersCount = $trainer->trainerSessions()
            ->distinct('member_id')
            ->count('member_id');

        $pendingSessions = $trainer->trainerSessions()
            ->where('status', PtSessionStatus::Pending->value)
            ->whereDate('session_date', '>=', $today)
            ->with(['member.memberProfile', 'quota.ptPackage', 'memberPtQuota.ptPackage'])
            ->orderBy('session_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->take(5)
            ->get();

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

        $currentMonthLabel = Carbon::now()->translatedFormat('F Y');

        return view('mobile.trainer.dashboard', [
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
     * Display the mobile sessions list for the trainer.
     */
    public function mobileSessionsIndex(Request $request): View
    {
        $trainer = $request->user();
        $tab = $request->query('tab', 'all');
        $date = $request->query('date');
        $today = Carbon::today()->toDateString();

        $query = PtSession::with(['member.memberProfile', 'quota.ptPackage', 'memberPtQuota.ptPackage', 'rating'])
            ->where('trainer_id', $trainer->id);

        if ($tab === 'today') {
            $query->whereDate('session_date', $today);
        } elseif ($tab === 'pending') {
            $query->where('status', PtSessionStatus::Pending->value);
        } elseif ($tab === 'confirmed') {
            $query->where('status', PtSessionStatus::Confirmed->value);
        } elseif ($tab === 'completed') {
            $query->whereIn('status', [PtSessionStatus::Completed->value, PtSessionStatus::Done->value]);
        } elseif ($tab === 'cancelled') {
            $query->where('status', PtSessionStatus::Cancelled->value);
        }

        if ($date) {
            $query->whereDate('session_date', $date);
        }

        $sessions = $query->orderBy('session_date', 'desc')
            ->orderBy('start_time', 'asc')
            ->paginate(15)
            ->withQueryString();

        $counts = [
            'all' => PtSession::where('trainer_id', $trainer->id)->count(),
            'today' => PtSession::where('trainer_id', $trainer->id)->whereDate('session_date', $today)->count(),
            'pending' => PtSession::where('trainer_id', $trainer->id)->where('status', PtSessionStatus::Pending->value)->count(),
            'confirmed' => PtSession::where('trainer_id', $trainer->id)->where('status', PtSessionStatus::Confirmed->value)->count(),
            'completed' => PtSession::where('trainer_id', $trainer->id)->whereIn('status', [PtSessionStatus::Completed->value, PtSessionStatus::Done->value])->count(),
            'cancelled' => PtSession::where('trainer_id', $trainer->id)->where('status', PtSessionStatus::Cancelled->value)->count(),
        ];

        return view('mobile.trainer.sessions.index', [
            'trainer' => $trainer,
            'sessions' => $sessions,
            'counts' => $counts,
            'tab' => $tab,
            'selectedDate' => $date,
        ]);
    }

    /**
     * Display a specific session detail on mobile.
     */
    public function mobileSessionShow(Request $request, PtSession $ptSession): View
    {
        $trainer = $request->user();

        if ($ptSession->trainer_id !== $trainer->id && $trainer->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $ptSession->load(['member.memberProfile', 'quota.ptPackage', 'memberPtQuota.ptPackage', 'rating']);

        return view('mobile.trainer.sessions.show', [
            'trainer' => $trainer,
            'ptSession' => $ptSession,
        ]);
    }

    /**
     * Display trainer clients list on mobile.
     */
    public function mobileClientsIndex(Request $request): View
    {
        $trainer = $request->user();

        $clients = User::whereHas('memberSessions', function ($query) use ($trainer) {
            $query->where('trainer_id', $trainer->id);
        })
            ->with(['memberProfile', 'ptQuotas' => function ($query) {
                $query->where('status', 'active')->latest();
            }])
            ->paginate(15);

        return view('mobile.trainer.clients.index', [
            'trainer' => $trainer,
            'clients' => $clients,
        ]);
    }

    /**
     * Display trainer profile on mobile.
     */
    public function mobileProfileIndex(Request $request): View
    {
        $trainer = $request->user()->load('trainerProfile');
        $profile = $trainer->trainerProfile;

        $completedSessions = $trainer->trainerSessions()
            ->whereIn('status', [PtSessionStatus::Completed->value, PtSessionStatus::Done->value])
            ->count();

        $activeMembersCount = $trainer->trainerSessions()
            ->distinct('member_id')
            ->count('member_id');

        $commissionRate = (int) config('pt_booking.default_commission_rate', 60000);
        $estimatedCommission = $completedSessions * $commissionRate;

        return view('mobile.trainer.profile.index', [
            'trainer' => $trainer,
            'profile' => $profile,
            'completedSessions' => $completedSessions,
            'activeMembersCount' => $activeMembersCount,
            'commissionRate' => $commissionRate,
            'estimatedCommission' => $estimatedCommission,
        ]);
    }
}
