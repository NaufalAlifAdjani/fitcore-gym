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

        // 3. Estimasi Komisi (Berdasarkan tarif pelatih / sesi)
        $commissionRate = $trainerProfile?->commission_rate
            ?? (int) config('pt_booking.default_commission_rate', 60000);

        $monthEarnings = $trainer->trainerSessions()
            ->whereIn('status', [PtSessionStatus::Completed->value, PtSessionStatus::Done->value])
            ->whereBetween('session_date', [$startOfMonth, $endOfMonth])
            ->sum('trainer_earnings');

        $estimatedCommission = $monthEarnings > 0 ? $monthEarnings : ($completedSessionsMonth * $commissionRate);

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
        $mode = $request->query('mode', 'calendar');
        $tab = $request->query('tab', 'all');
        $date = $request->query('date');
        $today = Carbon::today()->toDateString();
        $currentDate = $date ? Carbon::parse($date) : Carbon::today();

        // 1. Grid Kalender Bulanan (Google Calendar Style)
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();
        $gridStart = $startOfMonth->copy()->startOfWeek(Carbon::SUNDAY);
        $gridEnd = $endOfMonth->copy()->endOfWeek(Carbon::SATURDAY);

        $monthGrid = [];
        $curr = $gridStart->copy();
        while ($curr->lte($gridEnd)) {
            $monthGrid[] = $curr->copy();
            $curr->addDay();
        }

        // Ambil sesi pada rentang kalender untuk penanda titik event per tanggal
        $calendarSessions = PtSession::where('trainer_id', $trainer->id)
            ->whereBetween('session_date', [$gridStart->toDateString(), $gridEnd->toDateString()])
            ->get()
            ->groupBy(fn ($s) => Carbon::parse($s->session_date)->toDateString());

        // 2. Query Sesi
        $query = PtSession::with(['member.memberProfile', 'quota.ptPackage', 'memberPtQuota.ptPackage'])
            ->where('trainer_id', $trainer->id);

        if ($mode === 'calendar') {
            // Mode Kalender memfilter sesi pada tanggal aktif yang dipilih
            $query->whereDate('session_date', $currentDate->toDateString());
        } else {
            // Mode Tasks (Daftar Sesi) memfilter berdasarkan status tab
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
            'mode' => $mode,
            'selectedDate' => $date,
            'currentDate' => $currentDate,
            'monthGrid' => $monthGrid,
            'calendarSessions' => $calendarSessions,
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

        $ptSession->load(['member.memberProfile', 'quota.ptPackage', 'memberPtQuota.ptPackage']);

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
        $search = trim((string) $request->query('search', ''));
        $statusFilter = (string) $request->query('status', 'all');
        $today = Carbon::today()->toDateString();

        $belongsToTrainer = function ($q) use ($trainer) {
            $q->whereHas('memberSessions', function ($sq) use ($trainer) {
                $sq->where('trainer_id', $trainer->id);
            })
                ->orWhereHas('ptQuotas', function ($sq) use ($trainer) {
                    $sq->where('trainer_id', $trainer->id)->where('status', 'active');
                })
                ->orWhereHas('memberProfile', function ($sq) use ($trainer) {
                    $sq->where('trainer_id', $trainer->id);
                });
        };

        $query = User::query()
            ->where('role', 'member')
            ->where($belongsToTrainer)
            ->with([
                'memberProfile',
                'ptQuotas' => function ($q) {
                    $q->with('ptPackage')->orderBy('end_date', 'desc');
                },
                'memberSessions' => function ($q) use ($trainer) {
                    $q->where('trainer_id', $trainer->id)
                        ->orderBy('session_date', 'desc')
                        ->orderBy('start_time', 'desc');
                },
            ]);

        if ($search !== '') {
            $query->where(function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhereHas('memberProfile', function ($pQuery) use ($search) {
                        $pQuery->where('member_code', 'like', "%{$search}%");
                    });
            });
        }

        if ($statusFilter === 'active_quota') {
            $query->whereHas('ptQuotas', function ($q) use ($today) {
                $q->where('status', 'active')
                    ->where('remaining_sessions', '>', 0)
                    ->where('end_date', '>=', $today);
            });
        } elseif ($statusFilter === 'empty_quota') {
            $query->whereDoesntHave('ptQuotas', function ($q) use ($today) {
                $q->where('status', 'active')
                    ->where('remaining_sessions', '>', 0)
                    ->where('end_date', '>=', $today);
            });
        } elseif ($statusFilter === 'low_quota') {
            $query->where(function ($sub) use ($today) {
                $sub->whereHas('ptQuotas', function ($q) use ($today) {
                    $q->where('status', 'active')
                        ->where('end_date', '>=', $today)
                        ->where('remaining_sessions', '<=', 2);
                })->orWhereDoesntHave('ptQuotas', function ($q) use ($today) {
                    $q->where('status', 'active')
                        ->where('remaining_sessions', '>', 0)
                        ->where('end_date', '>=', $today);
                });
            });
        }

        $clients = $query->orderBy('name', 'asc')->paginate(12)->withQueryString();

        $totalClientsCount = User::query()
            ->where('role', 'member')
            ->where($belongsToTrainer)
            ->count();

        $activeClientsCount = User::query()
            ->where('role', 'member')
            ->where($belongsToTrainer)
            ->whereHas('ptQuotas', function ($q) use ($today) {
                $q->where('status', 'active')
                    ->where('remaining_sessions', '>', 0)
                    ->where('end_date', '>=', $today);
            })
            ->count();

        $lowQuotaClientsCount = User::query()
            ->where('role', 'member')
            ->where($belongsToTrainer)
            ->where(function ($sub) use ($today) {
                $sub->whereHas('ptQuotas', function ($q) use ($today) {
                    $q->where('status', 'active')
                        ->where('end_date', '>=', $today)
                        ->where('remaining_sessions', '<=', 2);
                })->orWhereDoesntHave('ptQuotas', function ($q) use ($today) {
                    $q->where('status', 'active')
                        ->where('remaining_sessions', '>', 0)
                        ->where('end_date', '>=', $today);
                });
            })
            ->count();

        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();
        $completedSessionsMonth = $trainer->trainerSessions()
            ->whereIn('status', [PtSessionStatus::Completed->value, PtSessionStatus::Done->value])
            ->whereBetween('session_date', [$startOfMonth, $endOfMonth])
            ->count();

        return view('mobile.trainer.clients.index', [
            'trainer' => $trainer,
            'clients' => $clients,
            'search' => $search,
            'statusFilter' => $statusFilter,
            'totalClientsCount' => $totalClientsCount,
            'activeClientsCount' => $activeClientsCount,
            'lowQuotaClientsCount' => $lowQuotaClientsCount,
            'completedSessionsMonth' => $completedSessionsMonth,
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
