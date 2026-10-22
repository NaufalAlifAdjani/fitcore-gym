<?php

declare(strict_types=1);

namespace App\Livewire\Trainer\Sessions;

use App\Actions\GetTrainerSchedule;
use App\Enums\PtSessionStatus;
use App\Models\MemberPtQuota;
use App\Models\PtSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public function mount(): void
    {
        $this->date = $this->date ?: now()->toDateString();

        if ($this->view === 'daily') {
            $this->view = 'day';
        } elseif ($this->view === 'weekly') {
            $this->view = 'week';
        } elseif (! in_array($this->view, ['day', 'week', 'month'])) {
            $this->view = 'month';
        }

        if (! in_array($this->mode, ['calendar', 'tasks'])) {
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

    public function toggleViewDropdown(): void
    {
        $this->viewDropdownOpen = ! $this->viewDropdownOpen;
    }

    public function closeViewDropdown(): void
    {
        $this->viewDropdownOpen = false;
    }

    public function setView(string $view): void
    {
        $v = match ($view) {
            'daily' => 'day',
            'weekly' => 'week',
            default => $view,
        };

        if (in_array($v, ['day', 'week', 'month'])) {
            $this->view = $v;
            $this->viewDropdownOpen = false;
        }
    }

    public function setMode(string $mode): void
    {
        if (in_array($mode, ['calendar', 'tasks'])) {
            $this->mode = $mode;
            $this->viewDropdownOpen = false;
        }
    }

    public function setTaskStatusFilter(string $status): void
    {
        if (in_array($status, ['all', 'pending', 'confirmed', 'cancelled'], true)) {
            $this->taskStatusFilter = $status;
            unset($this->tasksList);
        }
    }

    public function goToToday(): void
    {
        $this->date = now()->toDateString();
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

    public function previousMonth(): void
    {
        $this->previousPeriod();
    }

    public function nextMonth(): void
    {
        $this->nextPeriod();
    }

    public function goToDate(string $date): void
    {
        $this->date = $date;
    }

    #[Computed]
    public function sessions()
    {
        $viewType = $this->view === 'week' ? 'weekly' : 'daily';

        return app(GetTrainerSchedule::class)->execute(
            Auth::id() ?? 1, // fallback for testing if no auth
            $this->date,
            $viewType
        );
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
            // Month
            $startDate = $carbon->copy()->startOfMonth()->toDateString();
            $endDate = $carbon->copy()->endOfMonth()->toDateString();
        }

        $query = PtSession::with(['member.memberProfile', 'quota', 'rating'])
            ->where('trainer_id', Auth::id() ?? 1)
            ->whereBetween('session_date', [$startDate, $endDate]);

        if (in_array($this->taskStatusFilter, ['pending', 'confirmed', 'cancelled'], true)) {
            $query->where('status', $this->taskStatusFilter);
        }

        return $query
            ->orderBy('session_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get()
            ->groupBy(fn ($session) => Carbon::parse($session->session_date)->format('D, M j'));
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

        $base = PtSession::where('trainer_id', Auth::id() ?? 1)
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

    #[Computed]
    public function monthGrid(): array
    {
        $carbon = Carbon::parse($this->date);
        $startOfMonth = $carbon->copy()->startOfMonth();
        $endOfMonth = $carbon->copy()->endOfMonth();

        // Google Calendar starts on Sunday
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

        return PtSession::with(['member.memberProfile', 'quota', 'rating'])
            ->where('trainer_id', Auth::id() ?? 1)
            ->whereBetween('session_date', [$from, $to])
            ->orderBy('start_time', 'asc')
            ->get()
            ->groupBy(fn ($session) => Carbon::parse($session->session_date)->toDateString());
    }

    public function confirmSession(int $sessionId): void
    {
        $trainerId = Auth::id();
        $query = PtSession::where('id', $sessionId);
        if ($trainerId) {
            $query->where('trainer_id', $trainerId);
        }

        $session = $query->firstOrFail();

        if ($session->status === PtSessionStatus::Pending) {
            $session->update([
                'status' => PtSessionStatus::Confirmed,
            ]);

            unset($this->sessions, $this->tasksList, $this->monthSessions, $this->taskStatusCounts);

            session()->flash('success', 'Sesi berhasil diterima dan status diubah menjadi terkonfirmasi.');
            $this->dispatch('toast', type: 'success', message: 'Sesi berhasil diterima dan status diubah menjadi terkonfirmasi.');
        }
    }

    public function rejectSession(int $sessionId): void
    {
        $trainerId = Auth::id();
        $query = PtSession::where('id', $sessionId);
        if ($trainerId) {
            $query->where('trainer_id', $trainerId);
        }

        $session = $query->firstOrFail();

        if ($session->status === PtSessionStatus::Pending) {
            DB::transaction(function () use ($session) {
                $session->update([
                    'status' => PtSessionStatus::Cancelled,
                ]);

                $quota = MemberPtQuota::where('id', $session->member_pt_quota_id)
                    ->lockForUpdate()
                    ->first();

                if ($quota) {
                    $quota->decrement('used_sessions');
                    $quota->increment('remaining_sessions');
                }
            });

            unset($this->sessions, $this->tasksList, $this->monthSessions, $this->taskStatusCounts);

            session()->flash('success', 'Sesi ditolak dan status diubah menjadi dibatalkan.');
            $this->dispatch('toast', type: 'success', message: 'Sesi ditolak dan status diubah menjadi dibatalkan.');
        }
    }

    public function render()
    {
        return view('livewire.trainer.sessions.index')->layout('layouts.trainer');
    }
}
