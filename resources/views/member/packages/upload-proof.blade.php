<x-member-layout title="Upload Bukti Pembayaran - FitCore Athletic" active-nav="packages">
    <div class="bg-slate-50 min-h-screen py-12 px-4 sm:px-6 lg:px-8 text-slate-900 border-b border-slate-200">
        <div class="mx-auto max-w-6xl">
            <!-- Breadcrumb Navigation -->
            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <a href="{{ route('member.membership.checkout', $package->id) }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-[#BA0030] transition">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"><path d="m15 18-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    Kembali ke Detail Pembayaran
                </a>
                <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                    <span class="text-slate-400">Langkah 1: Konfirmasi Pembayaran</span>
                    <span>•</span>
                    <span class="text-[#BA0030]">Langkah 2: Form Bukti Transfer</span>
                </div>
            </div>

            <!-- Header Section -->
            <div class="mb-10">
                <h1 class="font-heading text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Upload Bukti Pembayaran
                </h1>
                <p class="mt-2 text-xs sm:text-sm text-slate-600 leading-relaxed font-medium">
                    Selesaikan transfer sesuai nominal tepat dan unggah struk pembayaran resmi Anda untuk verifikasi instan tim admin.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-8 rounded-2xl bg-rose-50 border border-rose-200 p-5 text-xs text-rose-800">
                    <p class="font-bold mb-2">Mohon periksa kembali formulir Anda:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-12 items-start">
                <!-- Kolom Kiri: Ringkasan Pesanan & Rekening Tujuan Transfer -->
                <div class="lg:col-span-6 space-y-6">
                    <!-- 1. Ringkasan Pesanan Card -->
                    <x-card :padding="false" class="!rounded-3xl border border-slate-200/90 p-6 sm:p-7 shadow-sm bg-white">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">
                            Ringkasan Pesanan
                        </h2>

                        <div class="flex items-center gap-4">
                            <div class="relative h-20 w-24 shrink-0 overflow-hidden rounded-2xl bg-slate-900 flex items-center justify-center border border-slate-800 shadow-inner">
                                <svg class="h-8 w-8 text-[#BA0030]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6.5 6.5h11M6.5 17.5h11M4 9.5h16M4 14.5h16M8.5 4.5v15M15.5 4.5v15" stroke-linecap="round"/>
                                </svg>
                                <span class="absolute bottom-1 right-1.5 text-[8px] font-black text-rose-300">FITCORE</span>
                            </div>

                            <div class="flex-1 min-w-0">
                                <span class="inline-block px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider bg-rose-100 text-[#BA0030] mb-1">
                                    PAKET MEMBERSHIP
                                </span>
                                <h3 class="font-heading text-lg font-extrabold text-slate-900 truncate">
                                    {{ $package->name }}
                                </h3>
                                <p class="text-xs text-slate-500 font-medium">
                                    Masa Aktif: {{ $package->duration_value }} {{ $package->duration_unit }} ({{ $package->duration_in_days }} Hari)
                                </p>
                            </div>
                        </div>

                        <!-- Box Hitam Total Pembayaran -->
                        <div class="mt-5 rounded-2xl bg-[#0B0F17] p-5 text-white shadow-md">
                            <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400">
                                TOTAL PEMBAYARAN :
                            </span>
                            <div class="mt-1 flex items-baseline justify-between">
                                <span class="font-heading text-2xl sm:text-3xl font-black text-white tracking-tight">
                                    Rp {{ number_format($totalAmount, 0, ',', '.') }}
                                </span>
                                <span class="text-[11px] text-rose-400 font-semibold">Termasuk kode unik: +{{ $uniqueCode }}</span>
                            </div>
                        </div>
                    </x-card>

                    <!-- 2. Rekening Tujuan Transfer Card -->
                    <x-payment.bank-account-card :bank="$selectedBank" />

                    <!-- 3. Card Bantuan CS -->
                    <x-payment.support-card />
                </div>

                <!-- Kolom Kanan: Formulir Upload Bukti Transfer -->
                <div class="lg:col-span-6 space-y-6">
                    <x-payment.upload-proof-form
                        :action="route('member.membership.upload-proof.store', $package->id)"
                        :default-sender-bank="$selectedBank->name"
                    >
                        <input type="hidden" name="bank_id" value="{{ $selectedBank->id }}" />
                        <input type="hidden" name="unique_code" value="{{ $uniqueCode }}" />
                    </x-payment.upload-proof-form>
                </div>
            </div>
        </div>
    </div>
</x-member-layout>
