<?php

declare(strict_types=1);

namespace App\Http\Controllers\Trainer;

use App\Enums\PtSessionStatus;
use App\Http\Controllers\Controller;
use App\Models\PtSession;
use App\Models\User;
use App\Services\PtSessionService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function __construct(
        protected PtSessionService $ptSessionService
    ) {}

    /**
     * Tampilkan daftar seluruh member / klien bimbingan Personal Trainer.
     */
    public function index(Request $request): View
    {
        $trainer = $request->user();
        $this->ptSessionService->completePastConfirmedSessions($trainer->id);

        $search = trim((string) $request->query('search', ''));
        $statusFilter = (string) $request->query('status', 'all'); // 'all', 'active_quota', 'empty_quota'

        $today = Carbon::today()->toDateString();
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

        // Query member unik yang pernah memiliki sesi latihan dengan trainer ini
        $query = User::query()
            ->where('role', 'member')
            ->whereHas('memberSessions', function ($q) use ($trainer) {
                $q->where('trainer_id', $trainer->id);
            })
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

        // Filter pencarian teks: nama, email, phone, nomor member
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

        // Filter status kuota
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

        // Metrik Ringkasan untuk Header
        $totalClientsCount = User::query()
            ->where('role', 'member')
            ->whereHas('memberSessions', function ($q) use ($trainer) {
                $q->where('trainer_id', $trainer->id);
            })
            ->count();

        $activeClientsCount = User::query()
            ->where('role', 'member')
            ->whereHas('memberSessions', function ($q) use ($trainer) {
                $q->where('trainer_id', $trainer->id);
            })
            ->whereHas('ptQuotas', function ($q) use ($today) {
                $q->where('status', 'active')
                    ->where('remaining_sessions', '>', 0)
                    ->where('end_date', '>=', $today);
            })
            ->count();

        $lowQuotaClientsCount = User::query()
            ->where('role', 'member')
            ->whereHas('memberSessions', function ($q) use ($trainer) {
                $q->where('trainer_id', $trainer->id);
            })
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

        $completedSessionsMonth = $trainer->trainerSessions()
            ->whereIn('status', [PtSessionStatus::Completed->value, PtSessionStatus::Done->value])
            ->whereBetween('session_date', [$startOfMonth, $endOfMonth])
            ->count();

        return view('trainer.clients.index', [
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
     * Tampilkan detail member dan riwayat sesi latihannya bersama PT.
     */
    public function show(Request $request, User $member): View
    {
        $trainer = $request->user();

        // Otorisasi: Pastikan member pernah/sedang memiliki sesi latihan dengan trainer ini
        $hasTrained = PtSession::where('trainer_id', $trainer->id)
            ->where('member_id', $member->id)
            ->exists();

        if (! $hasTrained) {
            abort(403, 'Anda tidak memiliki akses ke data riwayat member ini.');
        }

        // Otomatis selesaikan sesi terkonfirmasi yang jadwalnya telah terlewati
        $this->ptSessionService->completePastConfirmedSessions($trainer->id, $member->id);

        $member->load([
            'memberProfile',
            'ptQuotas' => function ($q) {
                $q->with('ptPackage')->orderBy('end_date', 'desc');
            },
        ]);

        $sessionStatusFilter = (string) $request->query('session_status', 'all');

        $sessionsQuery = PtSession::with(['quota.ptPackage', 'rating'])
            ->where('trainer_id', $trainer->id)
            ->where('member_id', $member->id);

        if ($sessionStatusFilter !== 'all' && in_array($sessionStatusFilter, ['completed', 'confirmed', 'pending', 'cancelled'], true)) {
            if ($sessionStatusFilter === 'completed') {
                $sessionsQuery->whereIn('status', [PtSessionStatus::Completed->value, PtSessionStatus::Done->value]);
            } else {
                $sessionsQuery->where('status', $sessionStatusFilter);
            }
        }

        $sessions = $sessionsQuery
            ->orderBy('session_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Perhitungan Akumulasi Sesi & Kuota Klien Ini
        $completedSessionsWithTrainer = PtSession::where('trainer_id', $trainer->id)
            ->where('member_id', $member->id)
            ->whereIn('status', [PtSessionStatus::Completed->value, PtSessionStatus::Done->value])
            ->count();

        $activeQuotas = $member->ptQuotas
            ->where('status', 'active')
            ->where('end_date', '>=', Carbon::today());

        $activeQuota = $activeQuotas->first();

        $remainingActiveQuota = (int) $activeQuotas->sum('remaining_sessions');

        // Total sesi yang dibeli oleh member (dari kuota aktif atau histori seluruh kuota yang dibeli)
        $totalPurchasedSessions = (int) ($activeQuotas->isNotEmpty()
            ? $activeQuotas->sum('total_sessions')
            : $member->ptQuotas->sum('total_sessions'));

        return view('trainer.clients.show', [
            'trainer' => $trainer,
            'member' => $member,
            'sessions' => $sessions,
            'sessionStatusFilter' => $sessionStatusFilter,
            'totalSessionsWithTrainer' => $totalPurchasedSessions,
            'totalPurchasedSessions' => $totalPurchasedSessions,
            'completedSessionsWithTrainer' => $completedSessionsWithTrainer,
            'activeQuota' => $activeQuota,
            'remainingActiveQuota' => $remainingActiveQuota,
        ]);
    }

    /**
     * Tandai sesi latihan telah selesai dijalankan dan simpan catatan evaluasi latihan.
     */
    public function completeSession(Request $request, PtSession $ptSession): RedirectResponse
    {
        $trainer = $request->user();

        if ($ptSession->trainer_id !== $trainer->id) {
            abort(403, 'Anda tidak memiliki hak untuk menyelesaikan sesi ini.');
        }

        if ($ptSession->status === PtSessionStatus::Cancelled) {
            return back()->with('error', 'Sesi yang sudah dibatalkan tidak dapat diselesaikan.');
        }

        $validated = $request->validate([
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $ptSession->markAsCompleted($validated['notes'] ?? null);

        return back()->with('success', 'Sesi latihan berhasil diselesaikan dan catatan evaluasi telah disimpan.');
    }
}
