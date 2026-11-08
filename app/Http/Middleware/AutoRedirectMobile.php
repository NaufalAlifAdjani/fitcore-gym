<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AutoRedirectMobile
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Hanya evaluasi untuk request GET non-JSON/non-Livewire
        if (! $request->isMethod('GET') || $request->expectsJson() || $request->is('livewire/*')) {
            return $next($request);
        }

        // 2. Deteksi apakah perangkat adalah smartphone menggunakan Native Regex bawaan (PHP + Laravel)
        // Mengecualikan tablet agar perangkat berlayar besar tetap mendapatkan tampilan desktop
        $deviceQuery = $request->query('device');
        $userAgent = $request->userAgent() ?? '';

        $isPhone = (bool) preg_match('/(iphone|ipod|android.*mobile|mobile|blackberry|bb10|iemobile|opera mini)/i', $userAgent);
        $isTablet = (bool) preg_match('/(ipad|tablet|(android(?!.*mobile))|silk|playbook)/i', $userAgent);

        $isMobile = $deviceQuery === 'mobile'
            || ($deviceQuery !== 'desktop' && $isPhone && ! $isTablet);

        $isDesktop = $deviceQuery === 'desktop'
            || ($deviceQuery !== 'mobile' && ($isTablet || preg_match('/(windows nt|macintosh|x11|cros)/i', $userAgent) || (! $isMobile && ! app()->runningUnitTests())));

        // 3. Jika mobile dan URL belum berada di bawah prefix /m/* (Forward Redirect: Desktop -> Mobile)
        if ($isMobile && ! $request->is('m') && ! $request->is('m/*')) {
            if ($request->hasSession()) {
                $request->session()->reflash();
            }

            $query = $request->except('device');

            if ($request->is('/')) {
                if ($request->user()) {
                    return match ($request->user()->role) {
                        'trainer' => redirect()->route('mobile.trainer.dashboard'),
                        'admin' => redirect()->route('admin.dashboard'),
                        default => redirect()->route('mobile.member.dashboard'),
                    };
                }

                return redirect()->route('mobile.landing.index');
            }

            if ($request->is('login')) {
                return redirect()->route('mobile.login', $query);
            }

            if ($request->is('register')) {
                return redirect()->route('mobile.register', $query);
            }

            if ($request->is('packages')) {
                if ($request->user()?->role === 'member') {
                    return redirect()->route('mobile.member.packages');
                }

                return redirect()->route('mobile.landing.packages');
            }

            if ($request->is('trainers')) {
                return redirect()->route('mobile.landing.trainers');
            }

            if ($request->is('about')) {
                return redirect()->route('mobile.landing.about');
            }

            if ($request->is('member/dashboard')) {
                return redirect()->route('mobile.member.dashboard');
            }

            if ($request->is('trainer/dashboard')) {
                return redirect()->route('mobile.trainer.dashboard');
            }

            if ($request->is('dashboard') && $request->user()?->role === 'trainer') {
                return redirect()->route('mobile.trainer.dashboard');
            }

            if ($request->is('trainer/jadwal-sesi') || $request->is('trainer/sessions*')) {
                return redirect()->route('mobile.trainer.sessions.index');
            }

            if ($request->is('trainer/klien-saya') || $request->is('trainer/clients*')) {
                return redirect()->route('mobile.trainer.clients.index');
            }

            if ($request->is('trainer/profile')) {
                return redirect()->route('mobile.trainer.profile.index');
            }

            if ($request->is('member/card')) {
                return redirect()->route('mobile.member.card');
            }

            if ($request->is('membership/packages') || $request->is('member/pt-packages')) {
                return redirect()->route('mobile.member.packages');
            }

            if ($request->is('membership/checkout/*')) {
                $packageId = $request->segment(3);

                return redirect()->route('mobile.member.packages.checkout', $packageId);
            }

            if ($request->is('membership/payment-status/*')) {
                $paymentId = $request->segment(3);

                return redirect()->route('mobile.member.packages.payment-status', $paymentId);
            }

            if ($request->is('pt-sessions')) {
                return redirect()->route('mobile.member.pt-sessions.index');
            }

            if ($request->is('pt-sessions/create')) {
                return redirect()->route('mobile.member.pt-sessions.create', $query);
            }

            if ($request->is('pt-sessions/*') && ! $request->is('pt-sessions/slots') && ! $request->is('pt-sessions/availability')) {
                $sessionId = $request->segment(2);
                if (is_numeric($sessionId)) {
                    return redirect()->route('mobile.member.pt-sessions.show', $sessionId);
                }
            }

            if ($request->is('member/riwayat-transaksi') || $request->is('member/riwayat-transaksi/*')) {
                return redirect()->route('mobile.member.riwayat-transaksi.index');
            }

            if ($request->is('profile') || $request->is('profile/*')) {
                if ($request->user()?->role === 'trainer') {
                    return redirect()->route('mobile.trainer.profile.index');
                }

                return redirect()->route('mobile.member.profile.index');
            }
        }

        // 4. Jika desktop dan URL berada di bawah prefix /m/* (Reverse Redirect: Mobile -> Desktop)
        if ($isDesktop && ($request->is('m') || $request->is('m/*'))) {
            if ($request->hasSession()) {
                $request->session()->reflash();
            }

            $query = $request->except('device');

            // --- A. TAMU / GUEST / LANDING / AUTH ---
            if ($request->is('m')) {
                if ($request->user()) {
                    return match ($request->user()->role) {
                        'trainer' => redirect()->route('trainer.dashboard', $query),
                        'admin' => redirect()->route('admin.dashboard', $query),
                        default => redirect()->route('member.dashboard', $query),
                    };
                }

                return redirect()->route('landing.index', $query);
            }

            if ($request->is('m/packages-info')) {
                return redirect()->route('landing.packages', $query);
            }

            if ($request->is('m/trainers')) {
                return redirect()->route('landing.trainers', $query);
            }

            if ($request->is('m/about')) {
                return redirect()->route('landing.about', $query);
            }

            if ($request->is('m/login')) {
                return redirect()->route('login', $query);
            }

            if ($request->is('m/register')) {
                return redirect()->route('register', $query);
            }

            // --- B. TRAINER ROUTES (/m/trainer/...) ---
            if ($request->is('m/trainer/dashboard') || $request->is('m/trainer')) {
                return redirect()->route('trainer.dashboard', $query);
            }

            if ($request->is('m/trainer/sessions') || $request->is('m/trainer/sessions/*')) {
                return redirect()->route('trainer.sessions.index', $query);
            }

            if ($request->is('m/trainer/clients') || $request->is('m/trainer/clients/*')) {
                return redirect()->route('trainer.clients.index', $query);
            }

            if ($request->is('m/trainer/profile') || $request->is('m/trainer/profile/*')) {
                return redirect()->route('profile.edit', $query);
            }

            if ($request->is('m/trainer/*')) {
                return redirect()->route('trainer.dashboard', $query);
            }

            // --- C. MEMBER ROUTES (/m/... atau /m/member/...) ---
            if ($request->is('m/dashboard') || $request->is('m/member/dashboard') || $request->is('m/member')) {
                return redirect()->route('member.dashboard', $query);
            }

            if ($request->is('m/card') || $request->is('m/member/card')) {
                return redirect()->route('member.card', $query);
            }

            if ($request->is('m/packages') || $request->is('m/packages-catalog') || $request->is('m/member/packages')) {
                if ($request->user()?->role === 'member') {
                    return redirect()->route('member.membership.packages', $query);
                }

                return redirect()->route('landing.packages', $query);
            }

            if (preg_match('#^m/(?:member/)?packages/([^/]+)/checkout#', $request->path(), $matches)) {
                return redirect()->route('member.membership.checkout', array_merge(['package' => $matches[1]], $query));
            }

            if (preg_match('#^m/(?:member/)?payment-status/([^/]+)#', $request->path(), $matches)) {
                return redirect()->route('member.membership.payment-status', array_merge(['payment' => $matches[1]], $query));
            }

            if ($request->is('m/pt-sessions/booking') || $request->is('m/member/pt-sessions/booking')) {
                return redirect()->route('pt-sessions.create', $query);
            }

            if (preg_match('#^m/(?:member/)?pt-sessions/(\d+)#', $request->path(), $matches)) {
                return redirect()->route('pt-sessions.show', array_merge(['ptSession' => $matches[1]], $query));
            }

            if ($request->is('m/pt-sessions') || $request->is('m/member/pt-sessions')) {
                return redirect()->route('pt-sessions.index', $query);
            }

            if ($request->is('m/riwayat-transaksi') || $request->is('m/riwayat-transaksi/*') || $request->is('m/member/riwayat-transaksi*')) {
                return redirect()->route('member.riwayat-transaksi.index', $query);
            }

            if ($request->is('m/profile') || $request->is('m/profile/*') || $request->is('m/member/profile*')) {
                return redirect()->route('profile.edit', $query);
            }

            if ($request->is('m/member/*')) {
                return redirect()->route('member.dashboard', $query);
            }

            // --- D. FALLBACK PENGALIHAN ---
            if ($request->user()) {
                return match ($request->user()->role) {
                    'trainer' => redirect()->route('trainer.dashboard', $query),
                    'admin' => redirect()->route('admin.dashboard', $query),
                    default => redirect()->route('member.dashboard', $query),
                };
            }

            return redirect()->route('landing.index', $query);
        }

        return $next($request);
    }
}
