<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kartu Member Digital') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ── Digital Member Card ──────────────────────────────────────── --}}
            <div class="relative overflow-hidden rounded-2xl shadow-xl"
                 style="background: linear-gradient(135deg, #1e3a5f 0%, #2d6a9f 50%, #1a2e4a 100%);">

                {{-- Decorative circles --}}
                <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full opacity-10"
                     style="background: white;"></div>
                <div class="absolute -bottom-8 -left-8 w-32 h-32 rounded-full opacity-10"
                     style="background: white;"></div>

                <div class="relative p-6">

                    {{-- Header gym name --}}
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-blue-200 text-xs font-semibold uppercase tracking-widest">
                                FitCore Gym
                            </p>
                            <p class="text-white text-lg font-bold leading-tight">
                                Digital Member Card
                            </p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Member info --}}
                    <div class="mb-5">
                        <p class="text-white text-xl font-semibold">{{ $user->name }}</p>
                        @if($profile)
                            <p class="text-blue-200 text-sm mt-0.5">
                                ID: {{ $profile->member_code }}
                            </p>
                        @endif
                    </div>

                    {{-- Membership status --}}
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            @if($activeMembership)
                                <span class="inline-flex items-center gap-1.5 bg-green-500/20 border border-green-400/30 text-green-300 text-xs font-semibold px-3 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span>
                                    AKTIF
                                </span>
                                <p class="text-blue-200 text-xs mt-2">
                                    {{ $activeMembership->package->name ?? 'Membership' }}
                                </p>
                                <p class="text-white text-xs">
                                    Berlaku s/d
                                    {{ $activeMembership->end_date->locale('id')->translatedFormat('d M Y') }}
                                </p>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-red-500/20 border border-red-400/30 text-red-300 text-xs font-semibold px-3 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                                    TIDAK AKTIF
                                </span>
                                <p class="text-blue-200 text-xs mt-2">Tidak ada membership aktif</p>
                            @endif
                        </div>

                        {{-- QR Code block --}}
                        @if($qrSvg)
                            <div class="bg-white rounded-xl p-2 shadow-md w-28 h-28 flex items-center justify-center shrink-0">
                                {!! $qrSvg !!}
                            </div>
                        @else
                            <div class="bg-white/10 border border-white/20 rounded-xl p-3 w-28 h-28 flex flex-col items-center justify-center shrink-0">
                                <svg class="w-8 h-8 text-white/50 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <p class="text-white/50 text-xs text-center">QR tidak tersedia</p>
                            </div>
                        @endif
                    </div>

                    {{-- Scan instruction --}}
                    <div class="border-t border-white/10 pt-4">
                        <p class="text-blue-200 text-xs text-center">
                            Tunjukkan QR Code ini kepada petugas gym saat check-in
                        </p>
                    </div>

                </div>
            </div>

            {{-- ── Info Panel ──────────────────────────────────────────────── --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-5">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Cara Menggunakan Kartu Digital</h3>
                    <ol class="list-decimal list-inside space-y-1.5 text-sm text-gray-600">
                        <li>Buka halaman ini di perangkat Anda saat datang ke gym.</li>
                        <li>Tunjukkan QR Code kepada Admin atau Petugas di meja resepsionis.</li>
                        <li>Petugas akan memindai QR Code menggunakan sistem.</li>
                        <li>Tunggu konfirmasi check-in berhasil dari petugas.</li>
                    </ol>

                    @if(!$profile)
                        <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                            <p class="text-xs text-amber-800">
                                <strong>Perhatian:</strong> Profil member Anda belum lengkap.
                                Hubungi Admin untuk mengaktifkan Digital Member Card.
                            </p>
                        </div>
                    @elseif(!$activeMembership)
                        <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-xs text-red-800">
                                <strong>Membership tidak aktif.</strong>
                                QR Code tidak dapat digunakan untuk check-in.
                                Perbarui membership Anda untuk mengaktifkan akses.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
