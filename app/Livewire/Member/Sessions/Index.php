<?php

declare(strict_types=1);

namespace App\Livewire\Member\Sessions;

use App\Enums\PtSessionStatus;
use App\Models\PtSession;
use App\Services\PtSessionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;

class Index extends Component
{
    #[Url(as: 'd')]
    public string $date = '';

    #[Url(as: 'v')]
    public string $view = 'month';

    #[Url(as: 'm')]
    public string $mode = 'calendar';

    #[Url(as: 'status')]
    public string $taskStatusFilter = 'all';

    public bool $viewDropdownOpen = false;

    public bool $cancelModalOpen = false;

    public ?int $targetSessionId = null;

    public string $targetDate = '';

    public string $targetTime = '';

    public string $cancelReason = '';

    public function mount(): void
    {
        $this->date = $this->date ?: now()->toDateString();

        if ($this->view === 'daily') {
            $this->view = 'day';
        } elseif ($this->view === 'weekly') {
            $this->view = 'week';
        } elseif (! in_array($this->view, ['day', 'week', 'month'], true)) {
            $this->view = 'month';
        }

        if (! in_array($this->mode, ['calendar', 'tasks'], true)) {
            $this->mode = 'calendar';
        }

        if (! in_array($this->taskStatusFilter, ['all', 'pending', 'confirmed', 'cancelled'], true)) {
            $this->taskStatusFilter = 'all';
        }
    }

    public function updatedDate(string $value): void
    {
        try {
            Carbon::parse($value);
        } catch (\Exception $e) {
            $this->date = now()->toDateString();
        }
    }

    public function setView(string $newView): void
    {
        if (in_array($newView, ['day', 'week', 'month'], true)) {
            $this->view = $newView;
        }
        $this->viewDropdownOpen = false;
    }

    public function setMode(string $newMode): void
    {
        if (in_array($newMode, ['calendar', 'tasks'], true)) {
            $this->mode = $newMode;
        }
    }

    public function setTaskStatusFilter(string $status): void
    {
        if (in_array($status, ['all', 'pending', 'confirmed', 'cancelled'], true)) {
            $this->taskStatusFilter = $status;
        }
    }

    public function toggleViewDropdown(): void
    {
        $this->viewDropdownOpen = ! $this->viewDropdownOpen;
    }

    public function closeViewDropdown(): void
    {
        $this->viewDropdownOpen = false;
    }

    public function goToToday(): void
    {
        $this->date = now()->toDateString();
    }

    public function goToDate(string $dateString): void
    {
        try {
            $this->date = Carbon::parse($dateString)->toDateString();
        } catch (\Exception $e) {
            // keep current date
        }
    }

    public function previousPeriod(): void
    {
        $carbon = Carbon::parse($this->date);

        if ($this->view === 'day') {
            $this->date = $carbon->subDay()->toDateString();
        } elseif ($this->view === 'week') {
            $this->date = $carbon->subWeek()->toDateString();
        } else {
            $this->date = $carbon->subMonth()->toDateString();
        }
    }

    public function nextPeriod(): void
    {
        $carbon = Carbon::parse($this->date);

        if ($this->view === 'day') {
            $this->date = $carbon->addDay()->toDateString();
        } elseif ($this->view === 'week') {
            $this->date = $carbon->addWeek()->toDateString();
        } else {
            $this->date = $carbon->addMonth()->toDateString();
        }
    }

    public function openCancelModal(int $sessionId): void
    {
        $session = PtSession::where('id', $sessionId)
            ->where('member_id', Auth::id())
            ->first();

        if ($session) {
            $this->targetSessionId = $session->id;
            $this->targetDate = Carbon::parse($session->session_date)->locale('id')->translatedFormat('l, d F Y');
            $this->targetTime = $session->time_range;
            $this->cancelReason = '';
            $this->cancelModalOpen = true;
        }
    }

    public function closeCancelModal(): void
    {
        $this->cancelModalOpen = false;
        $this->targetSessionId = null;
        $this->cancelReason = '';
    }

    public function confirmCancel(PtSessionService $ptSessionService): void
    {
        if (! $this->targetSessionId) {
            return;
        }

        $session = PtSession::where('id', $this->targetSessionId)
            ->where('member_id', Auth::id())
            ->firstOrFail();

        try {
            $ptSessionService->cancel($session, $this->cancelReason ?: null);
            session()->flash('success', 'Sesi berhasil dibatalkan dan 1 kuota telah dikembalikan ke akun Anda.');
            $this->closeCancelModal();
            unset($this->sessions, $this->tasksList, $this->monthSessions, $this->taskStatusCounts);
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage() ?: 'Gagal membatalkan sesi.');
        }
    }

    #[Computed]
    public function weekDays(): array
    {
        $carbon = Carbon::parse($this->date);
        $start = $carbon->copy()->startOfWeek(Carbon::SUNDAY);

        $dates = [];
        for ($i = 0; $i < 7; $i++) {
            $dates[] = $start->copy()->addDays($i);
        }

        return $dates;
    }

    #[Computed]
    public function monthGrid(): array
    {
        $carbon = Carbon::parse($this->date);
        $startOfMonth = $carbon->copy()->startOfMonth();
        $endOfMonth = $carbon->copy()->endOfMonth();

        $gridStart = $startOfMonth->copy()->startOfWeek(Carbon::SUNDAY);
        $gridEnd = $endOfMonth->copy()->endOfWeek(Carbon::SATURDAY);

        $days = [];
        $curr = $gridStart->copy();
        while ($curr->lte($gridEnd)) {
            $days[] = $curr->copy();
            $curr->addDay();
        }

        return $days;
    }

    #[Computed]
    public function monthSessions()
    {
        $carbon = Carbon::parse($this->date);
        $startOfMonth = $carbon->copy()->startOfMonth()->startOfWeek(Carbon::SUNDAY);
        $endOfMonth = $carbon->copy()->endOfMonth()->endOfWeek(Carbon::SATURDAY);

        $startOfWeek = $carbon->copy()->startOfWeek(Carbon::SUNDAY);
        $endOfWeek = $carbon->copy()->endOfWeek(Carbon::SATURDAY);

        $from = $startOfMonth->min($startOfWeek)->toDateString();
        $to = $endOfMonth->max($endOfWeek)->toDateString();

        return PtSession::with(['trainer.trainerProfile', 'quota'])
            ->where('member_id', Auth::id() ?? 1)
            ->whereBetween('session_date', [$from, $to])
            ->orderBy('start_time', 'asc')
            ->get()
            ->groupBy(fn ($session) => Carbon::parse($session->session_date)->toDateString());
    }

    #[Computed]
    public function sessions()
    {
        return PtSession::with(['trainer.trainerProfile', 'quota'])
            ->where('member_id', Auth::id() ?? 1)
            ->whereDate('session_date', Carbon::parse($this->date)->toDateString())
            ->orderBy('start_time', 'asc')
            ->get();
    }

    #[Computed]
    public function tasksList()
    {
        $carbon = Carbon::parse($this->date);

        if ($this->view === 'day') {
            $startDate = $carbon->toDateString();
            $endDate = $carbon->toDateString();
        } elseif ($this->view === 'week') {
            $startDate = $carbon->copy()->startOfWeek(Carbon::SUNDAY)->toDateString();
            $endDate = $carbon->copy()->endOfWeek(Carbon::SATURDAY)->toDateString();
        } else {
            $startDate = $carbon->copy()->startOfMonth()->toDateString();
            $endDate = $carbon->copy()->endOfMonth()->toDateString();
        }

        $query = PtSession::with(['trainer.trainerProfile', 'quota'])
            ->where('member_id', Auth::id() ?? 1)
            ->whereBetween('session_date', [$startDate, $endDate]);

        if (in_array($this->taskStatusFilter, ['pending', 'confirmed', 'cancelled'], true)) {
            $query->where('status', $this->taskStatusFilter);
        }

        return $query
            ->orderBy('session_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get()
            ->groupBy(fn ($session) => Carbon::parse($session->session_date)->locale('id')->translatedFormat('D, d M'));
    }

    #[Computed]
    public function taskStatusCounts(): array
    {
        $carbon = Carbon::parse($this->date);

        if ($this->view === 'day') {
            $startDate = $carbon->toDateString();
            $endDate = $carbon->toDateString();
        } elseif ($this->view === 'week') {
            $startDate = $carbon->copy()->startOfWeek(Carbon::SUNDAY)->toDateString();
            $endDate = $carbon->copy()->endOfWeek(Carbon::SATURDAY)->toDateString();
        } else {
            $startDate = $carbon->copy()->startOfMonth()->toDateString();
            $endDate = $carbon->copy()->endOfMonth()->toDateString();
        }

        $base = PtSession::where('member_id', Auth::id() ?? 1)
            ->whereBetween('session_date', [$startDate, $endDate]);

        $counts = (clone $base)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'all' => (clone $base)->count(),
            'pending' => (int) ($counts[PtSessionStatus::Pending->value] ?? 0),
            'confirmed' => (int) ($counts[PtSessionStatus::Confirmed->value] ?? 0),
            'cancelled' => (int) ($counts[PtSessionStatus::Cancelled->value] ?? 0),
        ];
    }

    public function render()
    {
        return view('member.pt-sessions.calendar');
    }
}
