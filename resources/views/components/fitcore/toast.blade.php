@if (session('success') || session('error') || $errors->any())
<div x-data="{ show: true }"
     x-show="show"
     x-init="setTimeout(() => show = false, 6000)"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
     x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
     class="fixed top-6 left-1/2 -translate-x-1/2 z-50 w-[calc(100%-2rem)] max-w-md shadow-2xl rounded-2xl p-4 border backdrop-blur-md transition-all
            {{ session('success') ? 'bg-zinc-900/95 border-emerald-500/40 text-zinc-100' : 'bg-zinc-900/95 border-red-500/40 text-zinc-100' }}">

    <div class="flex items-start gap-3">
        @if (session('success'))
            <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div class="flex-1">
                <h5 class="text-xs font-bold uppercase tracking-wider text-emerald-400">Berhasil</h5>
                <p class="text-xs text-zinc-200 mt-0.5 leading-relaxed">{{ session('success') }}</p>
            </div>
        @else
            <div class="w-8 h-8 rounded-xl bg-red-500/20 text-red-400 flex items-center justify-center shrink-0 border border-red-500/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h5 class="text-xs font-bold uppercase tracking-wider text-red-400">Peringatan / Error</h5>
                @if (session('error'))
                    <p class="text-xs text-zinc-200 mt-0.5 leading-relaxed">{{ session('error') }}</p>
                @endif
                @if ($errors->any())
                    <ul class="text-xs text-zinc-200 mt-0.5 space-y-0.5 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif

        <button @click="show = false" class="text-zinc-500 hover:text-zinc-300 p-1 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>
@endif
