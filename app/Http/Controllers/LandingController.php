<?php

namespace App\Http\Controllers;

use App\Models\MembershipPackage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LandingController extends Controller
{
    /**
     * Menampilkan Landing Page publik versi desktop.
     * Pengguna yang sudah login akan diarahkan ke dashboard masing-masing.
     */
    public function index(Request $request): View|RedirectResponse
    {
        if ($request->user()) {
            return match ($request->user()->role) {
                'trainer' => redirect()->route('trainer.dashboard'),
                'admin' => redirect()->route('admin.dashboard'),
                default => redirect()->route('member.dashboard'),
            };
        }

        $packages = MembershipPackage::query()
            ->where('is_active', true)
            ->where('status', 'active')
            ->orderBy('price', 'asc')
            ->take(3)
            ->get();

        $trainers = User::query()
            ->where('role', 'trainer')
            ->whereHas('trainerProfile')
            ->with('trainerProfile')
            ->take(3)
            ->get();

        return view('landing.index', compact('packages', 'trainers'));
    }

    /**
     * Menampilkan Katalog Paket Membership publik versi desktop.
     */
    public function packages(Request $request): View
    {
        $packages = MembershipPackage::query()
            ->where('is_active', true)
            ->where('status', 'active')
            ->orderBy('price', 'asc')
            ->get();

        return view('landing.packages', compact('packages'));
    }

    /**
     * Menampilkan Daftar Trainer publik versi desktop.
     */
    public function trainers(Request $request): View
    {
        $trainers = User::query()
            ->where('role', 'trainer')
            ->whereHas('trainerProfile')
            ->with('trainerProfile')
            ->get();

        return view('landing.trainers', compact('trainers'));
    }

    /**
     * Menampilkan Halaman Tentang Kami publik versi desktop.
     */
    public function about(Request $request): View
    {
        return view('landing.about');
    }

    /**
     * Menampilkan Landing Page publik versi mobile.
     * Pengguna yang sudah login akan diarahkan ke dashboard mobile masing-masing.
     */
    public function mobileIndex(Request $request): View|RedirectResponse
    {
        if ($request->user()) {
            return match ($request->user()->role) {
                'trainer' => redirect()->route('mobile.trainer.dashboard'),
                'admin' => redirect()->route('admin.dashboard'),
                default => redirect()->route('mobile.member.dashboard'),
            };
        }

        $packages = MembershipPackage::query()
            ->where('is_active', true)
            ->where('status', 'active')
            ->orderBy('price', 'asc')
            ->take(3)
            ->get();

        $trainers = User::query()
            ->where('role', 'trainer')
            ->whereHas('trainerProfile')
            ->with('trainerProfile')
            ->take(4)
            ->get();

        return view('mobile.landing.index', compact('packages', 'trainers'));
    }

    /**
     * Menampilkan Katalog Paket Membership publik versi mobile.
     */
    public function mobilePackages(Request $request): View
    {
        $packages = MembershipPackage::query()
            ->where('is_active', true)
            ->where('status', 'active')
            ->orderBy('price', 'asc')
            ->get();

        return view('mobile.landing.packages', compact('packages'));
    }

    /**
     * Menampilkan Daftar Trainer publik versi mobile.
     */
    public function mobileTrainers(Request $request): View
    {
        $trainers = User::query()
            ->where('role', 'trainer')
            ->whereHas('trainerProfile')
            ->with('trainerProfile')
            ->get();

        return view('mobile.landing.trainers', compact('trainers'));
    }

    /**
     * Menampilkan Halaman Tentang Kami publik versi mobile.
     */
    public function mobileAbout(Request $request): View
    {
        return view('mobile.landing.about');
    }
}
