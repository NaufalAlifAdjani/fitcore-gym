<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pilih Akun Google - FITCORE GYM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4 antialiased text-gray-900">
    <div class="w-full max-w-md bg-white border border-gray-200 rounded-3xl shadow-xl overflow-hidden" 
         x-data="{ showCustom: false, customName: '', customEmail: '' }">
        
        <!-- Header Google -->
        <div class="px-8 pt-8 pb-4 text-center">
            <!-- Google "G" Logo -->
            <div class="inline-flex items-center justify-center mb-4">
                <svg class="w-10 h-10" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-900">Pilih akun</h1>
            <p class="text-sm text-gray-500 mt-1">
                untuk melanjutkan ke <span class="font-bold text-gray-800">FITCORE GYM</span>
            </p>
        </div>

        <!-- Daftar Pilihan Akun Google -->
        <div class="px-6 pb-6 space-y-2">
            <!-- Form untuk submit akun yang diklik -->
            <form id="googleForm" method="POST" action="{{ route('auth.google.mock-login') }}">
                @csrf
                <input type="hidden" name="email" id="selectedEmail" value="">
                <input type="hidden" name="name" id="selectedName" value="">

                <!-- Akun-Akun Tersedia -->
                <div class="divide-y divide-gray-100 border-y border-gray-100 my-2">
                    @forelse ($existingAccounts as $acc)
                        <button type="button" 
                                onclick="submitAccount('{{ $acc->email }}', '{{ $acc->name }}')"
                                class="w-full flex items-center gap-3.5 py-3 px-3 text-left hover:bg-gray-50 rounded-xl transition group">
                            <!-- Avatar Akun -->
                            <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center font-bold text-base shadow-sm">
                                {{ strtoupper(substr($acc->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold text-gray-900 truncate group-hover:text-blue-600 transition">
                                    {{ $acc->name }}
                                </div>
                                <div class="text-xs text-gray-500 truncate">
                                    {{ $acc->email }}
                                </div>
                            </div>
                        </button>
                    @empty
                        <button type="button" 
                                onclick="submitAccount('member.google@gmail.com', 'Member Google Demo')"
                                class="w-full flex items-center gap-3.5 py-3 px-3 text-left hover:bg-gray-50 rounded-xl transition group">
                            <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center font-bold text-base shadow-sm">
                                M
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold text-gray-900 truncate group-hover:text-blue-600 transition">
                                    Member Google Demo
                                </div>
                                <div class="text-xs text-gray-500 truncate">
                                    member.google@gmail.com
                                </div>
                            </div>
                        </button>
                    @endforelse

                    <!-- Tombol Gunakan Akun Lain -->
                    <button type="button" 
                            @click="showCustom = !showCustom"
                            class="w-full flex items-center gap-3.5 py-3 px-3 text-left hover:bg-gray-50 rounded-xl transition group">
                        <div class="w-10 h-10 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center group-hover:bg-blue-50 group-hover:text-blue-600 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div class="flex-1 text-sm font-semibold text-gray-700 group-hover:text-blue-600 transition">
                            Gunakan akun lain
                        </div>
                    </button>
                </div>
            </form>

            <!-- Form Input Akun Baru (Jika pilih Gunakan Akun Lain) -->
            <div x-show="showCustom" x-cloak class="mt-4 pt-4 border-t border-gray-200">
                <form method="POST" action="{{ route('auth.google.mock-login') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" placeholder="Nama Anda" required
                               class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Alamat Gmail</label>
                        <input type="email" name="email" placeholder="contoh@gmail.com" required
                               class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>
                    <button type="submit" 
                            class="w-full bg-[#be0a32] hover:bg-[#a1082a] text-white font-bold py-2.5 px-4 rounded-xl text-sm shadow transition">
                        Lanjutkan dengan Akun Ini
                    </button>
                </form>
            </div>

            <!-- Catatan Edukasi Pengembang -->
            <div class="mt-6 pt-4 text-center border-t border-gray-100">
                <p class="text-[11px] text-gray-400 leading-relaxed">
                    Untuk membuka pop-up pemilih akun Google resmi langsung dari server Google, masukkan <code class="text-gray-600 font-mono bg-gray-100 px-1 py-0.5 rounded">GOOGLE_CLIENT_ID</code> dan <code class="text-gray-600 font-mono bg-gray-100 px-1 py-0.5 rounded">GOOGLE_CLIENT_SECRET</code> di file <code class="text-gray-600 font-mono bg-gray-100 px-1 py-0.5 rounded">.env</code>.
                </p>
                <a href="{{ route('login') }}" class="inline-block mt-3 text-xs font-semibold text-gray-600 hover:text-gray-900 hover:underline">
                    ← Batal dan kembali ke halaman login
                </a>
            </div>
        </div>
    </div>

    <script>
        function submitAccount(email, name) {
            document.getElementById('selectedEmail').value = email;
            document.getElementById('selectedName').value = name;
            document.getElementById('googleForm').submit();
        }
    </script>
</body>
</html>
