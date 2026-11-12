<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CheckIn;
use App\Models\Membership;
use App\Models\Payment;
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
        $now = now();
        $rangeInput = $request->query('range');
        $periodInput = $request->query('period');
        $period = (string) ($periodInput ?: $rangeInput ?: '');
        $startDateInput = $request->query('start_date');
        $endDateInput = $request->query('end_date');

        // Resolusi filter rentang tanggal
        if ($startDateInput && $endDateInput) {
            try {
                $filterStartDate = Carbon::parse($startDateInput)->startOfDay();
                $filterEndDate = Carbon::parse($endDateInput)->endOfDay();
                if ($filterStartDate->greaterThan($filterEndDate)) {
                    [$filterStartDate, $filterEndDate] = [$filterEndDate->copy()->startOfDay(), $filterStartDate->copy()->endOfDay()];
                }
                $filterPeriod = $period ?: 'custom';
            } catch (\Throwable) {
                $filterStartDate = $now->copy()->startOfMonth();
                $filterEndDate = $now->copy()->endOfMonth();
                $filterPeriod = 'this_month';
            }
        } elseif ($period) {
            switch ($period) {
                case 'today':
                    $filterStartDate = $now->copy()->startOfDay();
                    $filterEndDate = $now->copy()->endOfDay();
                    $filterPeriod = 'today';
                    break;
                case '7_days':
                    $filterStartDate = $now->copy()->subDays(6)->startOfDay();
                    $filterEndDate = $now->copy()->endOfDay();
                    $filterPeriod = '7_days';
                    break;
                case '30_days':
                    $filterStartDate = $now->copy()->subDays(29)->startOfDay();
                    $filterEndDate = $now->copy()->endOfDay();
                    $filterPeriod = '30_days';
                    break;
                case 'this_year':
                    $filterStartDate = $now->copy()->startOfYear();
                    $filterEndDate = $now->copy()->endOfYear();
                    $filterPeriod = 'this_year';
                    break;
                case 'this_month':
                    $filterStartDate = $now->copy()->startOfMonth();
                    $filterEndDate = $now->copy()->endOfMonth();
                    $filterPeriod = 'this_month';
                    break;
                default:
                    if ($rangeInput) {
                        $filterStartDate = $now->copy()->subDays(6)->startOfDay();
                        $filterEndDate = $now->copy()->endOfDay();
                        $filterPeriod = '7_days';
                    } else {
                        $filterStartDate = $now->copy()->startOfMonth();
                        $filterEndDate = $now->copy()->endOfMonth();
                        $filterPeriod = 'this_month';
                    }
                    break;
            }
        } else {
            $filterStartDate = $now->copy()->startOfMonth();
            $filterEndDate = $now->copy()->endOfMonth();
            $filterPeriod = 'this_month';
        }

        if ($filterStartDate->isSameDay($filterEndDate)) {
            $filterRangeLabel = 'Hari Ini ('.$filterStartDate->translatedFormat('d M Y').')';
        } elseif ($filterStartDate->isSameMonth($filterEndDate) && $filterStartDate->year === $filterEndDate->year) {
            $filterRangeLabel = $filterStartDate->translatedFormat('d').' - '.$filterEndDate->translatedFormat('d M Y');
        } else {
            $filterRangeLabel = $filterStartDate->translatedFormat('d M Y').' - '.$filterEndDate->translatedFormat('d M Y');
        }

        // 1. Stat Card 1: TOTAL MEMBER AKTIF
        $activeMembers = Membership::query()
            ->where('status', 'active')
            ->when($request->filled('start_date') || ($period && $period !== 'this_month'), function ($q) use ($filterStartDate, $filterEndDate) {
                $q->where(function ($sub) use ($filterStartDate, $filterEndDate) {
                    $sub->whereDate('start_date', '<=', $filterEndDate->toDateString())
                        ->whereDate('end_date', '>=', $filterStartDate->toDateString());
                });
            })
            ->count();
        $totalActiveMembers = number_format($activeMembers, 0, ',', '.');

        // 2. Stat Card 2: PENDAPATAN MEMBERSHIP
        $membershipRevenue = (float) Payment::query()
            ->where('status', 'verified')
            ->where(function ($query) {
                $query->where('package_type', 'membership')
                    ->orWhere('payment_type', 'membership')
                    ->orWhereNotNull('membership_id')
                    ->orWhere(function ($sub) {
                        $sub->whereNull('package_type')
                            ->whereNull('payment_type')
                            ->whereNull('pt_package_id');
                    });
            })
            ->where(function ($query) use ($filterStartDate, $filterEndDate) {
                $query->whereBetween('verified_at', [$filterStartDate, $filterEndDate])
                    ->orWhere(function ($q) use ($filterStartDate, $filterEndDate) {
                        $q->whereNull('verified_at')
                            ->whereBetween('created_at', [$filterStartDate, $filterEndDate]);
                    });
            })
            ->sum('amount');
        $formattedMembershipRevenue = $this->formatRupiah($membershipRevenue);

        // 3. Stat Card 3: PENDAPATAN SESI PT
        $ptRevenue = (float) Payment::query()
            ->where('status', 'verified')
            ->where(function ($query) {
                $query->where('package_type', 'pt_session')
                    ->orWhere('payment_type', 'pt_package')
                    ->orWhereNotNull('pt_package_id');
            })
            ->where(function ($query) use ($filterStartDate, $filterEndDate) {
                $query->whereBetween('verified_at', [$filterStartDate, $filterEndDate])
                    ->orWhere(function ($q) use ($filterStartDate, $filterEndDate) {
                        $q->whereNull('verified_at')
                            ->whereBetween('created_at', [$filterStartDate, $filterEndDate]);
                    });
            })
            ->sum('amount');
        $formattedPtRevenue = $this->formatRupiah($ptRevenue);

        // Total pendapatan gabungan (untuk kompatibilitas)
        $totalRevenue = $membershipRevenue + $ptRevenue;
        $formattedRevenue = $this->formatRupiah($totalRevenue);

        // 4. Stat Card 4: TOTAL CHECKIN
        $totalCheckIns = CheckIn::query()
            ->where(function ($query) use ($filterStartDate, $filterEndDate) {
                $query->whereBetween('checked_in_at', [$filterStartDate, $filterEndDate])
                    ->orWhere(function ($q) use ($filterStartDate, $filterEndDate) {
                        $q->whereNull('checked_in_at')
                            ->whereBetween('created_at', [$filterStartDate, $filterEndDate]);
                    });
            })
            ->count();
        $formattedTotalCheckIns = number_format($totalCheckIns, 0, ',', '.');
        $todayCheckIns = $totalCheckIns;
        $formattedTodayCheckIns = $formattedTotalCheckIns;

        // 5. Chart Data: Mengikuti filter tanggal yang aktif di dashboard (tersinkronisasi penuh dengan card)
        $intervals = [];

        if ($filterStartDate->isSameDay($filterEndDate)) {
            // Rentang 1 hari (misal: Hari Ini): dibagi per blok 2 jam (00:00, 02:00, s/d 22:00)
            for ($h = 0; $h < 24; $h += 2) {
                $intervals[] = [
                    'label' => sprintf('%02d:00', $h),
                    'start' => $filterStartDate->copy()->setTime($h, 0, 0),
                    'end' => $filterStartDate->copy()->setTime($h + 1, 59, 59),
                ];
            }
        } else {
            $diffDays = $filterStartDate->copy()->startOfDay()->diffInDays($filterEndDate->copy()->startOfDay()) + 1;

            if ($diffDays <= 31) {
                // Rentang hingga 31 hari (7 hari, 30 hari, Bulan Ini): interval per hari
                $cursor = $filterStartDate->copy()->startOfDay();
                $endCursor = $filterEndDate->copy()->startOfDay();
                while ($cursor->lte($endCursor)) {
                    $intervals[] = [
                        'label' => $cursor->format('d M'),
                        'start' => $cursor->copy()->startOfDay(),
                        'end' => $cursor->copy()->endOfDay(),
                    ];
                    $cursor->addDay();
                }
            } elseif ($diffDays <= 366) {
                // Rentang beberapa bulan hingga 1 tahun: interval per bulan
                $cursor = $filterStartDate->copy()->startOfMonth();
                $endCursor = $filterEndDate->copy()->startOfMonth();
                while ($cursor->lte($endCursor)) {
                    $intervals[] = [
                        'label' => $cursor->translatedFormat('M') ?: $cursor->format('M'),
                        'start' => $cursor->copy()->startOfMonth(),
                        'end' => $cursor->copy()->endOfMonth(),
                    ];
                    $cursor->addMonth();
                }
            } else {
                // Rentang lebih dari 1 tahun: interval per tahun
                $cursor = $filterStartDate->copy()->startOfYear();
                $endCursor = $filterEndDate->copy()->startOfYear();
                while ($cursor->lte($endCursor)) {
                    $intervals[] = [
                        'label' => (string) $cursor->year,
                        'start' => $cursor->copy()->startOfYear(),
                        'end' => $cursor->copy()->endOfYear(),
                    ];
                    $cursor->addYear();
                }
            }
        }

        if ($filterPeriod === '7_days') {
            $chartTitle = 'Pendapatan 7 Hari Terakhir';
        } elseif ($filterPeriod === '30_days') {
            $chartTitle = 'Pendapatan 30 Hari Terakhir';
        } elseif ($filterPeriod === 'this_year') {
            $chartTitle = 'Pendapatan Tahun Ini';
        } elseif ($filterPeriod === 'today') {
            $chartTitle = 'Pendapatan Hari Ini ('.$filterStartDate->translatedFormat('d M Y').')';
        } elseif ($filterPeriod === 'this_month') {
            $chartTitle = 'Pendapatan Bulan Ini ('.$filterStartDate->translatedFormat('F Y').')';
        } else {
            $chartTitle = 'Pendapatan Periode '.$filterRangeLabel;
        }

        $chartStartDate = $intervals[0]['start'];
        $chartEndDate = end($intervals)['end'];

        $payments = Payment::query()
            ->where('status', 'verified')
            ->where(function ($query) use ($chartStartDate, $chartEndDate) {
                $query->whereBetween('verified_at', [$chartStartDate, $chartEndDate])
                    ->orWhere(function ($q) use ($chartStartDate, $chartEndDate) {
                        $q->whereNull('verified_at')
                            ->whereBetween('created_at', [$chartStartDate, $chartEndDate]);
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

        $currentRange = $filterPeriod;

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

        $filterStartDateFormatted = $filterStartDate->format('Y-m-d');
        $filterEndDateFormatted = $filterEndDate->format('Y-m-d');

        return view('admin.dashboard.admin_dashboard', [
            'activeMembers' => $activeMembers,
            'totalActiveMembers' => $totalActiveMembers,
            'membershipRevenue' => $membershipRevenue,
            'formattedMembershipRevenue' => $formattedMembershipRevenue,
            'ptRevenue' => $ptRevenue,
            'formattedPtRevenue' => $formattedPtRevenue,
            'totalRevenue' => $totalRevenue,
            'formattedRevenue' => $formattedRevenue,
            'totalCheckIns' => $totalCheckIns,
            'formattedTotalCheckIns' => $formattedTotalCheckIns,
            'todayCheckIns' => $todayCheckIns,
            'formattedTodayCheckIns' => $formattedTodayCheckIns,
            'filterStartDate' => $filterStartDateFormatted,
            'filterEndDate' => $filterEndDateFormatted,
            'filterPeriod' => $filterPeriod,
            'filterRangeLabel' => $filterRangeLabel,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'chartTitle' => $chartTitle,
            'currentRange' => $currentRange,
            'pendingPayments' => $pendingPayments,
            'recentActivities' => $recentActivities,
            'pendingVerificationsCount' => $pendingVerificationsCount,
            'pendingPaymentsCount' => $pendingPaymentsCount,
            'dateRangeText' => $dateRangeText,
        ]);
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
