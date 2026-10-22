<?php

namespace App\Http\Controllers;

use App\Http\Resources\PtSessionDateResource;
use App\Http\Resources\PtSessionSlotResource;
use App\Models\PtSession;
use App\Models\User;
use App\Services\PtSessionService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PtSessionSlotController extends Controller
{
    public function __construct(
        protected PtSessionService $ptSessionService
    ) {}

    /**
     * Mendapatkan daftar slot harian pelatih.
     */
    public function slots(Request $request): PtSessionSlotResource
    {
        $validated = $request->validate([
            'trainer_id' => ['required', 'integer', 'exists:users,id'],
            'date' => ['nullable', 'date'],
            'ignore_session_id' => ['nullable', 'integer', 'exists:pt_sessions,id'],
        ]);

        $trainer = User::findOrFail($validated['trainer_id']);
        $date = isset($validated['date']) ? Carbon::parse($validated['date']) : now();
        $ignoreSession = isset($validated['ignore_session_id']) ? PtSession::find($validated['ignore_session_id']) : null;

        $groups = $this->ptSessionService->getAvailableSlots($trainer, $date, $ignoreSession);

        return new PtSessionSlotResource([
            'date' => $date->toDateString(),
            'trainer' => $trainer,
            'groups' => $groups,
        ]);
    }

    /**
     * Mendapatkan ringkasan ketersediaan kalender (7 hari).
     */
    public function availability(Request $request): PtSessionDateResource
    {
        $validated = $request->validate([
            'trainer_id' => ['required', 'integer', 'exists:users,id'],
            'start_date' => ['nullable', 'date'],
            'days' => ['nullable', 'integer', 'min:1', 'max:30'],
        ]);

        $trainer = User::findOrFail($validated['trainer_id']);
        $startDate = isset($validated['start_date']) ? Carbon::parse($validated['start_date']) : now();
        $days = (int) ($validated['days'] ?? 7);

        $daysList = $this->ptSessionService->getDateAvailability($trainer, $startDate, $days);

        return new PtSessionDateResource([
            'days' => $daysList,
        ]);
    }
}
