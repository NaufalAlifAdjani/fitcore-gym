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

        // 3. Jika mobile dan URL belum berada di bawah prefix /m/*
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

        return $next($request);
    }
}
