<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the trainer dashboard.
     */
    public function index(Request $request): View
    {
        $trainer = $request->user();
        $trainerProfile = $trainer->trainerProfile;

        // Metric 1: Volume Latihan (Total Sesi)
        $completedSessions = $trainer->trainerSessions()
            ->where('status', 'completed')
            ->count();
        $sessionTarget = 80;

        // Metric 2: Kepuasan Klien (Rating Rata-rata)
        $ratingsQuery = $trainer->receivedRatings();
        $totalReviewsCount = (clone $ratingsQuery)->count();
        $avgRating = $totalReviewsCount > 0
            ? round((float) (clone $ratingsQuery)->avg('rating'), 2)
            : 5.00;

        $fiveStarCount = (clone $ratingsQuery)->where('rating', 5)->count();
        $fourStarCount = (clone $ratingsQuery)->where('rating', 4)->count();

        // Metric 3: Manajemen Klien (Total Member Aktif)
        $activeMembersCount = $trainer->trainerSessions()
            ->distinct('member_id')
            ->count('member_id');
        $memberTarget = 20;

        // Active Star Filter
        $starFilter = $request->query('star');

        // Review list
        $reviewsQuery = $trainer->receivedRatings()
            ->with(['member.memberProfile', 'ptSession.quota.ptPackage', 'ptSession.memberPtQuota.ptPackage'])
            ->latest();

        if ($starFilter && in_array($starFilter, ['5', '4', '3', '2', '1'], true)) {
            $reviewsQuery->where('rating', (int) $starFilter);
        }

        $reviews = $reviewsQuery->get();

        // Current period label matching mockup
        $currentMonthLabel = 'Bulan Ini ('.Carbon::now()->translatedFormat('F Y').')';

        return view('trainer.dashboard', [
            'trainer' => $trainer,
            'trainerProfile' => $trainerProfile,
            'completedSessions' => $completedSessions,
            'sessionTarget' => $sessionTarget,
            'avgRating' => number_format($avgRating, 2),
            'totalReviewsCount' => $totalReviewsCount,
            'fiveStarCount' => $fiveStarCount,
            'fourStarCount' => $fourStarCount,
            'activeMembersCount' => $activeMembersCount,
            'memberTarget' => $memberTarget,
            'currentMonthLabel' => $currentMonthLabel,
            'selectedStar' => $starFilter,
            'reviews' => $reviews,
        ]);
    }
}
