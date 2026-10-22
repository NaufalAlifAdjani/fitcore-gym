<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CheckIn;
use App\Models\Membership;
use App\Models\Payment;
use App\Models\PtSession;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the main Admin Dashboard with dynamic metrics, revenue chart, and recent activities.
     */
    public function index(Request $request): View
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        // 1. Stat Card 1: Total active members
        $activeMembers = Membership::query()
            ->where('status', 'active')
            ->count();
        $totalActiveMembers = number_format($activeMembers, 0, ',', '.');

        // 2. Stat Card 2: Total verified revenue this month
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
        $formattedRevenue = $this->formatRupiah($totalRevenue);

        // 3. Stat Card 3: Total check-ins today
        $todayCheckIns = CheckIn::query()
            ->where(function ($query) {
                $today = today();
                $query->whereDate('checked_in_at', $today)
                    ->orWhere(function ($q) use ($today) {
                        $q->whereNull('checked_in_at')
                            ->whereDate('created_at', $today);
                    });
            })
            ->count();
        $totalCheckIns = $todayCheckIns;
        $formattedTodayCheckIns = number_format($todayCheckIns, 0, ',', '.');

        // 4. Stat Card 4: PT Sessions in progress or scheduled at current hour
        $now = now();
        $today = $now->toDateString();
        $currentTime = $now->format('H:i:s');

        $activePtSessions = PtSession::query()
            ->where(function ($query) use ($today, $currentTime) {
                $query->where('status', 'in_progress')
                    ->orWhere(function ($q) use ($today, $currentTime) {
                        $q->whereDate('session_date', $today)
                            ->where('status', 'scheduled')
                            ->where('start_time', '<=', $currentTime)
                            ->where('end_time', '>=', $currentTime);
                    });
            })
            ->count();
        $formattedPtSessions = $activePtSessions.' Sesi';

        // 5. Chart Data: 7-day verified revenue aggregation
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

        // 6. Pending payments verification queue (paginate(5))
        $pendingPayments = Payment::query()
            ->with(['member', 'membershipPackage', 'ptPackage', 'bank'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(5);

        $recentActivities = $pendingPayments;

        // Pending payments for verification banner
        $pendingVerificationsCount = Payment::query()
            ->where('status', 'pending')
            ->count();
        $pendingPaymentsCount = $pendingVerificationsCount;

        $dateRangeText = now()->startOfMonth()->format('j F').' - '.now()->endOfMonth()->format('j F Y');

        return view('admin.dashboard.admin_dashboard', compact(
            'activeMembers',
            'totalActiveMembers',
            'totalRevenue',
            'formattedRevenue',
            'totalCheckIns',
            'todayCheckIns',
            'formattedTodayCheckIns',
            'activePtSessions',
            'formattedPtSessions',
            'chartLabels',
            'chartData',
            'pendingPayments',
            'recentActivities',
            'pendingVerificationsCount',
            'pendingPaymentsCount',
            'dateRangeText'
        ));
    }

    /**
     * Format currency to compact Rupiah string or standard IDR representation.
     */
    private function formatRupiah(float $amount): string
    {
        if ($amount >= 1_000_000_000) {
            return 'Rp '.number_format($amount / 1_000_000_000, 1, ',', '.').'M';
        }

        if ($amount >= 1_000_000) {
            return 'Rp '.number_format($amount / 1_000_000, 1, ',', '.').' Jt';
        }

        return 'Rp '.number_format($amount, 0, ',', '.');
    }
}
