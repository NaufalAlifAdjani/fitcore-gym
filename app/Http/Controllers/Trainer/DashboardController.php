<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
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

        $sessions = $trainer->trainerSessions()
            ->with(['member', 'quota.ptPackage'])
            ->latest('session_date')
            ->take(10)
            ->get();

        return view('trainer.dashboard', [
            'trainer' => $trainer,
            'trainerProfile' => $trainerProfile,
            'sessions' => $sessions,
        ]);
    }
}
