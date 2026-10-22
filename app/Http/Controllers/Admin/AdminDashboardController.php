<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CheckIn;
use App\Models\Membership;
use App\Models\Payment;
use App\Models\PtSession;
use Carbon\Carbon;
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

        // 5. Chart Data: Contextual verified revenue aggregation
        $currentRange = (string) $request->query('range', '7_days');
        if (! in_array($currentRange, ['7_days', '30_days', 'this_year'], true)) {
            $currentRange = '7_days';
        }

        $intervals = [];

        switch ($currentRange) {
            case '30_days':
                $chartTitle = 'Pendapatan 30 Hari Terakhir';
                for ($i = 29; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $intervals[] = [
                        'label' => $date->format('d M'),
                        'start' => $date->copy()->startOfDay(),
                        'end' => $date->copy()->endOfDay(),
                    ];
                }
                break;

            case 'this_year':
                $chartTitle = 'Pendapatan Tahun Ini';
                $currentYear = now()->year;
                for ($month = 1; $month <= 12; $month++) {
                    $monthCarbon = Carbon::create($currentYear, $month, 1);
                    $intervals[] = [
                        'label' => $monthCarbon->translatedFormat('M') ?: $monthCarbon->format('M'),
                        'start' => $monthCarbon->copy()->startOfMonth(),
                        'end' => $monthCarbon->copy()->endOfMonth(),
                    ];
                }
                break;

            case '7_days':
            default:
                $currentRange = '7_days';
                $chartTitle = 'Pendapatan 7 Hari Terakhir';
                for ($i = 6; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $intervals[] = [
                        'label' => $date->format('d M'),
                        'start' => $date->copy()->startOfDay(),
                        'end' => $date->copy()->endOfDay(),
                    ];
                }
                break;
        }

        $overallStartDate = $intervals[0]['start'];
        $overallEndDate = end($intervals)['end'];

        $payments = Payment::query()
            ->where('status', 'verified')
            ->where(function ($query) use ($overallStartDate, $overallEndDate) {
                $query->whereBetween('verified_at', [$overallStartDate, $overallEndDate])
                    ->orWhere(function ($q) use ($overallStartDate, $overallEndDate) {
                        $q->whereNull('verified_at')
                            ->whereBetween('created_at', [$overallStartDate, $overallEndDate]);
                    });
            })
            ->get(['amount', 'verified_at', 'created_at']);

        $chartLabels = [];
        $chartData = [];

        foreach ($intervals as $interval) {
            $chartLabels[] = $interval['label'];
            $chartData[] = (float) $payments->filter(function ($payment) use ($interval) {
                $effectiveDate = $payment->verified_at ? Carbon::parse($payment->verified_at) : ($payment->created_at ? Carbon::parse($payment->created_at) : null);

                return $effectiveDate && $effectiveDate->between($interval['start'], $interval['end']);
            })->sum('amount');
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
            'chartTitle',
            'currentRange',
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
