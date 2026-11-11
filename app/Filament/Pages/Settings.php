<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Tables\Actions\Action;
use App\Filament\Tables\Columns\BadgeColumn;
use App\Filament\Tables\Columns\TextColumn;
use App\Filament\Tables\Concerns\InteractsWithTable;
use App\Filament\Tables\Contracts\HasTable;
use App\Filament\Tables\Table;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class Settings extends Page implements HasTable
{
    use InteractsWithTable;
    use WithPagination;

    protected static ?string $navigationIcon = 'settings';

    protected static ?string $navigationLabel = 'Pengaturan';

    protected static ?string $slug = 'settings';

    protected static string $view = 'admin.settings.index';

    #[Url(as: 'search')]
    public string $search = '';

    #[Url(as: 'status')]
    public string $statusFilter = 'all';

    #[Url(as: 'trainer')]
    public string $trainerFilter = 'all';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedTrainerFilter(): void
    {
        $this->resetPage();
    }

    public function getTableQuery(): Builder
    {
        return Payment::query()
            ->with(['member', 'trainer', 'ptPackage'])
            ->where(function ($q): void {
                $q->where('package_type', 'pt_session')
                    ->orWhere('payment_type', 'pt_package')
                    ->orWhereNotNull('pt_package_id')
                    ->orWhere('trainer_revenue', '>', 0);
            })
            ->when(filled($this->search), function ($q): void {
                $search = trim($this->search);
                $q->where(function ($sq) use ($search): void {
                    $sq->where('invoice_id', 'like', "%{$search}%")
                        ->orWhereHas('member', fn ($mq) => $mq->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('trainer', fn ($tq) => $tq->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($this->statusFilter !== 'all', function ($q): void {
                $q->where('payout_status', $this->statusFilter);
            })
            ->when($this->trainerFilter !== 'all', function ($q): void {
                $q->where('trainer_id', $this->trainerFilter);
            })
            ->latest('id');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('trainer_name')
                    ->label('Nama Trainer')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('client_or_session')
                    ->label('Nama Klien / Sesi')
                    ->searchable(),

                TextColumn::make('amount')
                    ->label('Total Tarif')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('trainer_revenue')
                    ->label('Komisi PT (70%)')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('gym_revenue')
                    ->label('Pendapatan Gym (30%)')
                    ->money('IDR')
                    ->sortable(),

                BadgeColumn::make('payout_status')
                    ->label('Status Pencairan')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                    ]),
            ])
            ->actions([
                Action::make('payout')
                    ->label('Cairkan Komisi')
                    ->color('success')
                    ->visible(fn ($record) => ($record->payout_status ?? 'pending') === 'pending')
                    ->action(fn ($record) => $this->cairkanKomisi($record->id)),
            ]);
    }

    public function cairkanKomisi(int $id): void
    {
        $payment = Payment::findOrFail($id);

        if ($payment->payout_status === 'paid') {
            session()->flash('info', 'Komisi ini sudah dicairkan sebelumnya.');
            $this->dispatch('toast', type: 'info', message: 'Komisi ini sudah dicairkan sebelumnya.');

            return;
        }

        // Pastikan komisi 70/30 terisi jika belum
        $amount = (float) $payment->amount;
        $trainerRevenue = (float) $payment->trainer_revenue > 0 ? (float) $payment->trainer_revenue : round($amount * 0.70, 2);
        $gymRevenue = (float) $payment->gym_revenue > 0 ? (float) $payment->gym_revenue : round($amount * 0.30, 2);

        $payment->update([
            'trainer_revenue' => $trainerRevenue,
            'gym_revenue' => $gymRevenue,
            'payout_status' => 'paid',
            'payout_date' => now(),
        ]);

        $trainerName = $payment->trainer?->name ?? 'Personal Trainer';
        $formattedCommission = number_format($trainerRevenue, 0, ',', '.');
        $msg = "Komisi PT sebesar Rp {$formattedCommission} untuk {$trainerName} berhasil dicairkan (Mark as Paid).";

        session()->flash('success', $msg);
        $this->dispatch('toast', type: 'success', message: $msg);
    }

    /**
     * Fallback aksi untuk non-livewire HTTP POST request
     */
    public function payout(Request $request, int|Payment $payment): RedirectResponse
    {
        $id = $payment instanceof Payment ? $payment->id : (int) $payment;
        $this->cairkanKomisi($id);

        return back();
    }

    public function render()
    {
        $trainers = User::where('role', 'trainer')->orderBy('name')->get();

        // Rekapitulasi Statistik Komisi PT
        $allPtPayments = Payment::where(function ($q): void {
            $q->where('package_type', 'pt_session')
                ->orWhere('payment_type', 'pt_package')
                ->orWhereNotNull('pt_package_id')
                ->orWhere('trainer_revenue', '>', 0);
        })->get();

        $totalTarif = $allPtPayments->sum('amount');
        $totalTrainerCommission = $allPtPayments->sum(fn ($p) => (float) $p->trainer_revenue ?: round($p->amount * 0.70, 2));
        $totalGymRevenue = $allPtPayments->sum(fn ($p) => (float) $p->gym_revenue ?: round($p->amount * 0.30, 2));
        $pendingPayoutCount = $allPtPayments->where(fn ($p) => ($p->payout_status ?? 'pending') === 'pending')->count();
        $paidPayoutCount = $allPtPayments->where('payout_status', 'paid')->count();

        return view(static::$view, [
            'trainers' => $trainers,
            'totalTarif' => $totalTarif,
            'totalTrainerCommission' => $totalTrainerCommission,
            'totalGymRevenue' => $totalGymRevenue,
            'pendingPayoutCount' => $pendingPayoutCount,
            'paidPayoutCount' => $paidPayoutCount,
        ])->layout('layouts.app', ['title' => static::$navigationLabel]);
    }
}
