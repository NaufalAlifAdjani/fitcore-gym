<?php

declare(strict_types=1);

namespace App\Livewire\Trainer\Sessions;

use App\Actions\GetTrainerSchedule;
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
    public string $view = 'daily';

    public function mount(): void
    {
        $this->date = $this->date ?: now()->toDateString();

        if (! in_array($this->view, ['daily', 'weekly'])) {
            $this->view = 'daily';
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

    public function setView(string $view): void
    {
        if (in_array($view, ['daily', 'weekly'])) {
            $this->view = $view;
        }
    }

    public function goToToday(): void
    {
        $this->date = now()->toDateString();
    }

    public function previousPeriod(): void
    {
        $carbon = Carbon::parse($this->date);
        $this->date = $carbon->subWeek()->toDateString();
    }

    public function nextPeriod(): void
    {
        $carbon = Carbon::parse($this->date);
        $this->date = $carbon->addWeek()->toDateString();
    }

    public function goToDate(string $date): void
    {
        $this->date = $date;
    }

    #[Computed]
    public function sessions()
    {
        return app(GetTrainerSchedule::class)->execute(
            Auth::id() ?? 1, // fallback for testing if no auth
            $this->date,
            $this->view
        );
    }

    #[Computed]
    public function weekDates(): array
    {
        $carbon = Carbon::parse($this->date);
        $start = $carbon->copy()->startOfWeek(Carbon::MONDAY);

        $dates = [];
        for ($i = 0; $i < 7; $i++) {
            $dates[] = $start->copy()->addDays($i);
        }

        return $dates;
    }

    public function render()
    {
        return view('livewire.trainer.sessions.index')->layout('layouts.app');
    }
}
