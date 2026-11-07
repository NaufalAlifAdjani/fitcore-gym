<?php

namespace App\Http\Controllers\Trainer;

use App\Enums\PtSessionStatus;
use App\Http\Controllers\Controller;
use App\Models\PtSession;
use App\Models\TrainerAvailability;
use App\Models\TrainerDayOff;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TrainerAvailabilityController extends Controller
{
    /**
     * Menampilkan formulir master ketersediaan dan jadwal cuti PT.
     */
    public function index(Request $request): View
    {
        $trainer = $request->user();

        $configuredSlots = config('pt_booking.slots', [
            'morning' => ['07:00', '08:00', '09:00', '10:00'],
            'afternoon' => ['13:00', '14:00', '15:00', '16:00'],
            'evening' => ['18:00', '19:00', '20:00', '21:00'],
        ]);

        $allFlatSlots = [];
        foreach ($configuredSlots as $times) {
            foreach ($times as $time) {
                $allFlatSlots[] = substr($time, 0, 5);
            }
        }

        // Ambil jadwal mingguan yang sudah ada
        $availabilities = TrainerAvailability::where('trainer_id', $trainer->id)
            ->get()
            ->keyBy('day_of_week');

        // Definisi hari (Senin = 1 s/d Minggu = 0)
        $dayDefinitions = [
            1 => ['name' => 'Senin', 'default_active' => true],
            2 => ['name' => 'Selasa', 'default_active' => true],
            3 => ['name' => 'Rabu', 'default_active' => true],
            4 => ['name' => 'Kamis', 'default_active' => true],
            5 => ['name' => 'Jumat', 'default_active' => true],
            6 => ['name' => 'Sabtu', 'default_active' => true],
            0 => ['name' => 'Minggu', 'default_active' => false],
        ];

        $weeklySchedule = [];
        $hasCustomSchedule = $availabilities->isNotEmpty();

        foreach ($dayDefinitions as $dayNumber => $meta) {
            if ($availabilities->has($dayNumber)) {
                $record = $availabilities->get($dayNumber);
                $isActive = (bool) $record->is_active;
                $activeSlots = is_array($record->slots) ? $record->slots : [];
            } else {
                $isActive = $meta['default_active'];
                $activeSlots = $meta['default_active'] ? $allFlatSlots : [];
            }

            $weeklySchedule[$dayNumber] = [
                'day_number' => $dayNumber,
                'name' => $meta['name'],
                'is_active' => $isActive,
                'slots' => $activeSlots,
            ];
        }

        // Ambil daftar hari libur mendatang
        $upcomingDayOffs = TrainerDayOff::where('trainer_id', $trainer->id)
            ->whereDate('date', '>=', now()->toDateString())
            ->orderBy('date', 'asc')
            ->get();

        // Hitung sesi aktif per hari libur mendatang
        $dayOffDates = $upcomingDayOffs->pluck('date')->map(fn ($d) => $d->toDateString())->all();
        $conflictingSessionsCounts = [];

        if (! empty($dayOffDates)) {
            $conflictingSessionsCounts = PtSession::where('trainer_id', $trainer->id)
                ->whereIn('session_date', $dayOffDates)
                ->whereIn('status', [PtSessionStatus::Confirmed->value, PtSessionStatus::Pending->value])
                ->groupBy('session_date')
                ->selectRaw('session_date, count(*) as total')
                ->pluck('total', 'session_date')
                ->all();
        }

        return view('trainer.availability.index', [
            'weeklySchedule' => $weeklySchedule,
            'configuredSlots' => $configuredSlots,
            'allFlatSlots' => $allFlatSlots,
            'upcomingDayOffs' => $upcomingDayOffs,
            'conflictingSessionsCounts' => $conflictingSessionsCounts,
            'hasCustomSchedule' => $hasCustomSchedule,
        ]);
    }

    /**
     * Memperbarui jadwal kerja mingguan PT.
     */
    public function updateSchedule(Request $request): RedirectResponse
    {
        $trainer = $request->user();

        $validated = $request->validate([
            'days' => ['required', 'array'],
            'days.*.is_active' => ['nullable'],
            'days.*.slots' => ['nullable', 'array'],
            'days.*.slots.*' => ['string', 'regex:/^([01]\d|2[0-3]):[0-5]\d$/'],
        ]);

        DB::transaction(function () use ($trainer, $validated) {
            foreach (range(0, 6) as $dayNumber) {
                $dayData = $validated['days'][$dayNumber] ?? null;
                $isActive = isset($dayData['is_active']) && (bool) $dayData['is_active'];
                $slots = ($isActive && isset($dayData['slots'])) ? array_values($dayData['slots']) : [];

                TrainerAvailability::updateOrCreate(
                    [
                        'trainer_id' => $trainer->id,
                        'day_of_week' => $dayNumber,
                    ],
                    [
                        'is_active' => $isActive,
                        'slots' => $slots,
                    ]
                );
            }
        });

        return redirect()
            ->route('trainer.availability.index')
            ->with('success', 'Jadwal kerja mingguan berhasil disimpan.');
    }

    /**
     * Menambahkan tanggal libur / cuti khusus PT.
     */
    public function storeDayOff(Request $request): RedirectResponse
    {
        $trainer = $request->user();

        $validated = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'reason' => ['nullable', 'string', 'max:255'],
        ], [
            'date.required' => 'Tanggal hari libur wajib dipilih.',
            'date.after_or_equal' => 'Tanggal libur tidak boleh di masa lampau.',
        ]);

        $dateString = Carbon::parse($validated['date'])->toDateString();

        $existing = TrainerDayOff::where('trainer_id', $trainer->id)
            ->whereDate('date', $dateString)
            ->first();

        if ($existing) {
            return redirect()
                ->route('trainer.availability.index')
                ->with('error', 'Tanggal '.$dateString.' sudah terdaftar sebagai hari libur Anda.');
        }

        TrainerDayOff::create([
            'trainer_id' => $trainer->id,
            'date' => $dateString,
            'reason' => $validated['reason'] ?? 'Cuti / Libur Khusus',
        ]);

        // Periksa apakah ada sesi aktif yang terdampak
        $conflictingCount = PtSession::where('trainer_id', $trainer->id)
            ->whereDate('session_date', $dateString)
            ->whereIn('status', [PtSessionStatus::Confirmed->value, PtSessionStatus::Pending->value])
            ->count();

        $message = 'Hari libur berhasil ditambahkan.';
        if ($conflictingCount > 0) {
            $message .= " Perhatian: Terdapat {$conflictingCount} sesi aktif pada tanggal tersebut. Harap segera koordinasikan dengan member Anda.";
        }

        return redirect()
            ->route('trainer.availability.index')
            ->with($conflictingCount > 0 ? 'warning' : 'success', $message);
    }

    /**
     * Menghapus tanggal libur / cuti PT.
     */
    public function destroyDayOff(Request $request, TrainerDayOff $dayOff): RedirectResponse
    {
        $trainer = $request->user();

        if ((int) $dayOff->trainer_id !== (int) $trainer->id) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $dateFormatted = Carbon::parse($dayOff->date)->translatedFormat('d F Y');
        $dayOff->delete();

        return redirect()
            ->route('trainer.availability.index')
            ->with('success', "Hari libur tanggal {$dateFormatted} berhasil dihapus.");
    }
}
