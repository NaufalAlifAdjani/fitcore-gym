<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Beli Paket Sesi Personal Trainer') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Pilih paket sesi latihan bersama pelatih profesional FitCore Gym sesuai kebutuhan Anda.
                </p>
            </div>
            @if($activeMembership)
                <div class="inline-flex items-center gap-2 bg-blue-50 border border-blue-200 text-blue-800 px-4 py-2 rounded-xl text-sm font-medium shadow-sm">
                    <span class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse"></span>
                    <span>Membership: <strong>{{ $activeMembership->package->name ?? 'Aktif' }}</strong> (Sisa <strong>{{ $remainingDays }}</strong> hari)</span>
                </div>
            @else
                <div class="inline-flex items-center gap-2 bg-amber-50 border border-amber-200 text-amber-800 px-4 py-2 rounded-xl text-sm font-medium shadow-sm">
                    <svg class="w-4 h-4 text-amber-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span>Belum ada membership aktif</span>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-8" x-data="{
        showModal: false,
        selectedPackage: null,
        selectedBankId: '{{ $banks->first()->id ?? '' }}',
        openBuyModal(pkg) {
            this.selectedPackage = pkg;
            this.showModal = true;
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- ── Alert Notifications ───────────────────────────────────────── --}}
            @if(session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm flex items-start gap-3">
                    <svg class="w-6 h-6 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h4 class="text-emerald-900 font-semibold text-sm">Berhasil!</h4>
                        <p class="text-emerald-800 text-sm mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-xl shadow-sm flex items-start gap-3">
                    <svg class="w-6 h-6 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <h4 class="text-rose-900 font-semibold text-sm">Gagal Melakukan Pembelian</h4>
                        <p class="text-rose-800 text-sm mt-0.5">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-xl shadow-sm">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h4 class="text-rose-900 font-semibold text-sm">Terjadi Kesalahan Validasi</h4>
                            <ul class="list-disc list-inside text-rose-800 text-sm mt-1 space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ── Status Membership & Syarat Minimum Hari Info ────────────── --}}
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-blue-900 text-white rounded-2xl p-6 sm:p-8 shadow-xl relative overflow-hidden">
                <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-blue-500/10 rounded-full blur-2xl"></div>
                <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div class="space-y-2">
                        <span class="inline-block bg-blue-500/30 text-blue-200 border border-blue-400/30 text-xs font-semibold uppercase tracking-wider px-3 py-1 rounded-full">
                            Ketentuan Pembelian Paket PT
                        </span>
                        <h3 class="text-xl sm:text-2xl font-bold tracking-tight">
                            Aturan Validasi Syarat Minimum Hari
                        </h3>
                        <p class="text-blue-100/90 text-sm max-w-2xl leading-relaxed">
                            Setiap paket Personal Trainer memiliki syarat sisa masa aktif membership minimal. Member hanya dapat membeli paket jika sisa hari aktif membership saat ini memenuhi atau melebihi syarat yang ditentukan.
                        </p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-xl p-5 min-w-[260px]">
                        <p class="text-xs font-semibold text-blue-200 uppercase tracking-wider">Status Membership Anda</p>
                        @if($activeMembership)
                            <div class="mt-2 flex items-baseline gap-2">
                                <span class="text-3xl font-extrabold text-white">{{ $remainingDays }}</span>
                                <span class="text-sm text-blue-200 font-medium">Hari Tersisa</span>
                            </div>
                            <p class="text-xs text-blue-200/80 mt-1">
                                Berlaku s/d {{ \Carbon\Carbon::parse($activeMembership->end_date)->locale('id')->translatedFormat('d M Y') }}
                            </p>
                        @else
                            <div class="mt-2 text-rose-300 font-semibold text-sm">
                                Tidak Memiliki Membership Aktif
                            </div>
                            <p class="text-xs text-blue-200/80 mt-1">
                                Sisa masa aktif: 0 hari
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ── Daftar Paket PT Tersedia ─────────────────────────────────── --}}
            <div>
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Pilihan Paket Sesi Personal Trainer</h3>
                        <p class="text-sm text-gray-500">Pilih paket yang sesuai dengan target kebugaran Anda.</p>
                    </div>
                </div>

                @if($packages->isEmpty())
                    <div class="bg-white rounded-2xl p-12 text-center border border-gray-200 shadow-sm">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <h4 class="text-base font-semibold text-gray-800">Belum Ada Paket PT Tersedia</h4>
                        <p class="text-sm text-gray-500 mt-1">Silakan cek kembali beberapa saat lagi.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($packages as $package)
                            @php
                                $minDays = (int) $package->min_membership_days;
                                $isEligible = ($minDays === 0) || ($activeMembership && $remainingDays >= $minDays);
                            @endphp

                            <div class="bg-white rounded-2xl border transition duration-300 flex flex-col justify-between shadow-sm hover:shadow-md {{ $isEligible ? 'border-gray-200 hover:border-blue-400' : 'border-gray-200 bg-gray-50/50 opacity-90' }}">
                                <div class="p-6">
                                    {{-- Badges --}}
                                    <div class="flex items-center justify-between gap-2 mb-4">
                                        <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1 rounded-lg">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                            {{ $package->pt_session_count }} Sesi Latihan
                                        </span>

                                        @if($isEligible)
                                            <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Memenuhi Syarat
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                Syarat Belum Cukup
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Package Name & Price --}}
                                    <h4 class="text-xl font-bold text-gray-900 mb-2">{{ $package->name }}</h4>
                                    <div class="mb-4">
                                        <span class="text-2xl sm:text-3xl font-extrabold text-gray-900">
                                            Rp {{ number_format($package->price, 0, ',', '.') }}
                                        </span>
                                        <span class="text-xs text-gray-500 block mt-0.5">Masa berlaku {{ $package->validity_days }} hari setelah pembelian</span>
                                    </div>

                                    <hr class="border-gray-100 my-4" />

                                    {{-- Validation Requirements Details --}}
                                    <div class="space-y-3 text-sm">
                                        <div class="flex items-start justify-between gap-2">
                                            <span class="text-gray-500 text-xs">Syarat Min. Membership:</span>
                                            @if($minDays > 0)
                                                <span class="font-semibold text-xs {{ $isEligible ? 'text-gray-800' : 'text-rose-600' }}">
                                                    Minimal {{ $minDays }} hari
                                                </span>
                                            @else
                                                <span class="font-semibold text-xs text-emerald-600">
                                                    Tanpa syarat hari
                                                </span>
                                            @endif
                                        </div>

                                        <div class="flex items-start justify-between gap-2">
                                            <span class="text-gray-500 text-xs">Sisa Hari Anda:</span>
                                            <span class="font-semibold text-xs {{ $isEligible ? 'text-emerald-700' : 'text-rose-700' }}">
                                                {{ $remainingDays }} hari
                                            </span>
                                        </div>

                                        {{-- Alert box on card if not eligible --}}
                                        @if(!$isEligible)
                                            <div class="bg-rose-50 border border-rose-200 rounded-xl p-3 text-xs text-rose-700 mt-2">
                                                @if(!$activeMembership)
                                                    Memerlukan membership aktif dengan sisa minimal <strong>{{ $minDays }} hari</strong>.
                                                @else
                                                    Kurang <strong>{{ $minDays - $remainingDays }} hari</strong> lagi untuk dapat membeli paket ini.
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Card Footer Action --}}
                                <div class="p-6 pt-0">
                                    @if($isEligible)
                                        <button type="button"
                                                @click="openBuyModal({{ json_encode($package) }})"
                                                class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-3 rounded-xl shadow-sm transition ease-in-out duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            Beli Paket Ini
                                        </button>
                                    @else
                                        <button type="button"
                                                disabled
                                                class="w-full inline-flex items-center justify-center gap-2 bg-gray-200 text-gray-500 font-semibold px-4 py-3 rounded-xl cursor-not-allowed">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                            Syarat Belum Terpenuhi
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ── Riwayat Kuota Sesi PT Member ────────────────────────────── --}}
            @if($myQuotas->isNotEmpty())
                <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-bold text-base text-gray-900">Kuota Sesi PT yang Anda Miliki</h4>
                        <span class="text-xs text-gray-500">{{ $myQuotas->count() }} Paket Terdaftar</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-semibold border-b border-gray-200">
                                <tr>
                                    <th class="py-3 px-4">Paket PT</th>
                                    <th class="py-3 px-4">Sumber</th>
                                    <th class="py-3 px-4 text-center">Total Sesi</th>
                                    <th class="py-3 px-4 text-center">Terpakai</th>
                                    <th class="py-3 px-4 text-center">Sisa Sesi</th>
                                    <th class="py-3 px-4">Masa Berlaku</th>
                                    <th class="py-3 px-4 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($myQuotas as $q)
                                    <tr class="hover:bg-gray-50/50">
                                        <td class="py-3 px-4 font-semibold text-gray-900">
                                            {{ $q->ptPackage->name ?? 'Paket PT' }}
                                        </td>
                                        <td class="py-3 px-4 text-gray-500 capitalize">
                                            {{ str_replace('_', ' ', $q->source) }}
                                        </td>
                                        <td class="py-3 px-4 text-center font-medium">{{ $q->total_sessions }}</td>
                                        <td class="py-3 px-4 text-center text-gray-500">{{ $q->used_sessions }}</td>
                                        <td class="py-3 px-4 text-center font-bold text-blue-600">{{ $q->remaining_sessions }}</td>
                                        <td class="py-3 px-4 text-gray-600 text-xs">
                                            {{ \Carbon\Carbon::parse($q->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($q->end_date)->format('d/m/Y') }}
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $q->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($q->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>

        {{-- ── Modal Konfirmasi & Pembelian Paket PT ───────────────────────── --}}
        <div x-show="showModal"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             style="display: none;"
             aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                     @click="showModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">

                    <form action="{{ route('member.pt-packages.buy') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="pt_package_id" :value="selectedPackage ? selectedPackage.id : ''" />

                        <div class="bg-gradient-to-r from-blue-700 to-indigo-800 px-6 py-4 text-white">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold" id="modal-title">Konfirmasi Pembelian Paket PT</h3>
                                <button type="button" @click="showModal = false" class="text-white/80 hover:text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="p-6 space-y-5">
                            {{-- Ringkasan Paket Terpilih --}}
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                <p class="text-xs font-semibold text-blue-700 uppercase tracking-wider">Paket Terpilih</p>
                                <div class="mt-1 flex items-baseline justify-between">
                                    <h4 class="text-base font-bold text-blue-950" x-text="selectedPackage ? selectedPackage.name : ''"></h4>
                                    <span class="text-base font-extrabold text-blue-700" x-text="selectedPackage ? 'Rp ' + Number(selectedPackage.price).toLocaleString('id-ID') : ''"></span>
                                </div>
                                <div class="mt-2 text-xs text-blue-800 flex items-center gap-3">
                                    <span>• <strong x-text="selectedPackage ? selectedPackage.pt_session_count : ''"></strong> Sesi Personal Trainer</span>
                                    <span>• Masa berlaku <strong x-text="selectedPackage ? selectedPackage.validity_days : ''"></strong> hari</span>
                                </div>
                            </div>

                            {{-- Pilihan Bank Transfer --}}
                            <div>
                                <label for="bank_id" class="block text-sm font-semibold text-gray-800 mb-1">
                                    Metode Pembayaran (Transfer Bank) <span class="text-rose-500">*</span>
                                </label>
                                <select id="bank_id" name="bank_id" required
                                        class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm text-sm">
                                    @foreach($banks as $b)
                                        <option value="{{ $b->id }}">
                                            Bank {{ $b->name }} - {{ $b->account_number }} (a.n. {{ $b->account_holder }})
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Silakan transfer sesuai nominal ke rekening bank yang dipilih.</p>
                            </div>

                            {{-- Upload Bukti Pembayaran (Opsional) --}}
                            <div>
                                <label for="proof_image" class="block text-sm font-semibold text-gray-800 mb-1">
                                    Unggah Bukti Transfer <span class="text-xs font-normal text-gray-500">(Opsional)</span>
                                </label>
                                <input type="file" id="proof_image" name="proof_image" accept="image/jpeg,image/png,image/webp,image/jpg"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-xl" />
                                <p class="text-xs text-gray-400 mt-1">Format: JPG, JPEG, PNG, WEBP (Maks. 2MB).</p>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-3 rounded-b-2xl border-t border-gray-100">
                            <button type="button" @click="showModal = false"
                                    class="w-full sm:w-auto inline-flex justify-center px-4 py-2.5 border border-gray-300 shadow-sm text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                                Batal
                            </button>
                            <button type="submit"
                                    class="w-full sm:w-auto inline-flex justify-center px-5 py-2.5 border border-transparent shadow-sm text-sm font-semibold rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Konfirmasi & Beli Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
