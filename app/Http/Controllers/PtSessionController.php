<?php

namespace App\Http\Controllers;

use App\Enums\PtSessionStatus;
use App\Exceptions\PtBookingException;
use App\Http\Requests\StorePtSessionRequest;
use App\Http\Requests\UpdatePtSessionRequest;
use App\Models\MemberPtQuota;
use App\Models\PtSession;
use App\Models\User;
use App\Services\PtSessionService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PtSessionController extends Controller
{
    public function __construct(
        protected PtSessionService $ptSessionService
    ) {}

    /**
     * Menampilkan daftar riwayat dan jadwal sesi PT pengguna.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $tab = $request->query('tab', 'all');
        $month = $request->query('month');

        $query = PtSession::with(['trainer.trainerProfile', 'quota'])
            ->where('member_id', $user->id);

        if ($month) {
            $monthCarbon = Carbon::parse($month);
            $query->whereYear('session_date', $monthCarbon->year)
                ->whereMonth('session_date', $monthCarbon->month);
        }

        if ($tab === 'scheduled') {
            $query->where('status', PtSessionStatus::Scheduled->value)
                ->orderBy('session_date', 'asc')
                ->orderBy('start_time', 'asc');
        } elseif ($tab === 'done') {
            $query->where('status', PtSessionStatus::Done->value)
                ->orderBy('session_date', 'desc')
                ->orderBy('start_time', 'desc');
        } elseif ($tab === 'cancelled') {
            $query->where('status', PtSessionStatus::Cancelled->value)
                ->orderBy('session_date', 'desc')
                ->orderBy('start_time', 'desc');
        } else {
            // Urutkan jadwal mendatang dahulu, lalu riwayat lampau
            $query->orderByRaw("CASE WHEN status = 'scheduled' THEN 0 ELSE 1 END")
                ->orderBy('session_date', 'desc')
                ->orderBy('start_time', 'desc');
        }

        $sessions = $query->paginate(10)->withQueryString();

        // Data Kuota Aktif
        $quota = MemberPtQuota::where('member_id', $user->id)
            ->where('status', 'active')
            ->orderBy('end_date', 'desc')
            ->first();

        // Counter untuk Tabs
        $counts = [
            'all' => PtSession::where('member_id', $user->id)->count(),
            'scheduled' => PtSession::where('member_id', $user->id)->where('status', PtSessionStatus::Scheduled->value)->count(),
            'done' => PtSession::where('member_id', $user->id)->where('status', PtSessionStatus::Done->value)->count(),
            'cancelled' => PtSession::where('member_id', $user->id)->where('status', PtSessionStatus::Cancelled->value)->count(),
        ];

        return view('pt-sessions.index', [
            'sessions' => $sessions,
            'quota' => $quota,
            'counts' => $counts,
            'currentTab' => $tab,
            'selectedMonth' => $month,
        ]);
    }

    /**
     * Form pemilihan tanggal & jam booking sesi baru.
     */
    public function create(Request $request): View
    {
        $user = $request->user();

        $quota = MemberPtQuota::where('member_id', $user->id)
            ->where('status', 'active')
            ->where('end_date', '>=', now()->toDateString())
            ->where('remaining_sessions', '>', 0)
            ->orderBy('end_date', 'asc')
            ->first();

        // Default trainer (Coach Rama jika ada, atau trainer aktif pertama)
        $trainer = User::where('role', 'trainer')
            ->whereHas('trainerProfile')
            ->with('trainerProfile')
            ->where('email', 'rama@gym.test')
            ->first()
            ?? User::where('role', 'trainer')
                ->whereHas('trainerProfile')
                ->with('trainerProfile')
                ->first();

        $trainers = User::where('role', 'trainer')
            ->whereHas('trainerProfile')
            ->with('trainerProfile')
            ->get();

        return view('pt-sessions.create', [
            'quota' => $quota,
            'trainer' => $trainer,
            'trainers' => $trainers,
        ]);
    }

    /**
     * Memproses penyimpanan booking sesi baru.
     */
    public function store(StorePtSessionRequest $request): RedirectResponse
    {
        try {
            $session = $this->ptSessionService->createBooking(
                $request->user(),
                $request->validated()
            );

            return redirect()
                ->route('pt-sessions.index')
                ->with('success', __('pt_booking.booking_success'));
        } catch (PtBookingException $e) {
            return back()
                ->withInput()
                ->withErrors(['booking_error' => $e->getMessage()]);
        }
    }

    /**
     * Form ubah jadwal sesi (reschedule).
     */
    public function edit(PtSession $ptSession): View|RedirectResponse
    {
        Gate::authorize('update', $ptSession);

        if (! $ptSession->can_be_changed) {
            return redirect()
                ->route('pt-sessions.index')
                ->withErrors(['error' => __('pt_booking.deadline_passed')]);
        }

        $ptSession->load(['trainer.trainerProfile', 'quota']);

        return view('pt-sessions.edit', [
            'session' => $ptSession,
            'trainer' => $ptSession->trainer,
            'quota' => $ptSession->quota,
        ]);
    }

    /**
     * Memproses pengubahan jadwal sesi.
     */
    public function update(UpdatePtSessionRequest $request, PtSession $ptSession): RedirectResponse
    {
        Gate::authorize('update', $ptSession);

        try {
            $this->ptSessionService->reschedule(
                $ptSession,
                $request->validated()
            );

            return redirect()
                ->route('pt-sessions.index')
                ->with('success', __('pt_booking.reschedule_success'));
        } catch (PtBookingException $e) {
            return back()
                ->withInput()
                ->withErrors(['reschedule_error' => $e->getMessage()]);
        }
    }

    /**
     * Membatalkan sesi PT.
     */
    public function cancel(Request $request, PtSession $ptSession): RedirectResponse
    {
        Gate::authorize('cancel', $ptSession);

        try {
            $this->ptSessionService->cancel(
                $ptSession,
                $request->input('reason')
            );

            return redirect()
                ->route('pt-sessions.index')
                ->with('success', __('pt_booking.cancel_success'));
        } catch (PtBookingException $e) {
            return back()
                ->withErrors(['cancel_error' => $e->getMessage()]);
        }
    }
}
