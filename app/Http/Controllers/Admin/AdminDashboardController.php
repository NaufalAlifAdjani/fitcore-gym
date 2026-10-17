<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CheckIn;
use App\Models\Membership;
use App\Models\Payment;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the main Admin Dashboard with summary metrics and weekly revenue chart.
     */
    public function index(): View
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        // 1. Metric: Active memberships count
        $activeMembers = Membership::query()
            ->where('status', 'active')
            ->count();

        // 2. Metric: Total verified revenue this month
        $totalRevenue = (float) Payment::query()
            ->where('status', 'verified')
            ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('verified_at', [$startOfMonth, $endOfMonth])
                    ->orWhere(function ($q) use ($startOfMonth, $endOfMonth) {
                        $q->whereNull('verified_at')
                            ->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
                    });
            })
            ->sum('amount');

        // 3. Metric: Total gym check-ins this month
        $totalCheckIns = CheckIn::query()
            ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('checked_in_at', [$startOfMonth, $endOfMonth])
                    ->orWhere(function ($q) use ($startOfMonth, $endOfMonth) {
                        $q->whereNull('checked_in_at')
                            ->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
                    });
            })
            ->count();

        // 4. Chart Data: 7-day revenue aggregation
        $chartLabels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $startOfDay = $date->copy()->startOfDay();
            $endOfDay = $date->copy()->endOfDay();

            $dailyRevenue = (float) Payment::query()
                ->where('status', 'verified')
                ->where(function ($query) use ($startOfDay, $endOfDay) {
                    $query->whereBetween('verified_at', [$startOfDay, $endOfDay])
                        ->orWhere(function ($q) use ($startOfDay, $endOfDay) {
                            $q->whereNull('verified_at')
                                ->whereBetween('created_at', [$startOfDay, $endOfDay]);
                        });
                })
                ->sum('amount');

            $chartLabels[] = $date->format('d M');
            $chartData[] = $dailyRevenue;
        }

        // Optional quick stats: pending payment count for admin promptness
        $pendingPaymentsCount = Payment::query()
            ->where('status', 'pending')
            ->count();

        return view('admin.dashboard.admin_dashboard', compact(
            'activeMembers',
            'totalRevenue',
            'totalCheckIns',
            'chartLabels',
            'chartData',
            'pendingPaymentsCount'
        ));
    }
}
