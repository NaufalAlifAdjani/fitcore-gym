@props([
    'theme' => 'light',
])

@php
    $user = Auth::user();
    $unreadCount = $user ? $user->unreadNotifications()->count() : 0;
    $notifications = $user ? $user->notifications()->take(10)->get() : collect();
    $isDark = $theme === 'dark';
@endphp

<div class="relative" x-data="{ notifOpen: false }">
    <!-- Bell Button -->
    <button
        type="button"
        @click="notifOpen = !notifOpen"
        @class([
            'relative inline-flex h-10 w-10 items-center justify-center rounded-full transition-colors focus:outline-none cursor-pointer',
            'text-slate-400 hover:text-white hover:bg-slate-800' => $isDark,
            'text-[#565A66] hover:text-[#16151A] hover:bg-gray-100' => ! $isDark,
        ])
        aria-label="Notifikasi"
        :aria-expanded="notifOpen.toString()"
    >
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
        </svg>

        @if($unreadCount > 0)
            <span class="absolute -top-0.5 -right-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-[#ED1B45] px-1 text-[10px] font-black text-white shadow-sm ring-2 {{ $isDark ? 'ring-[#0B0F17]' : 'ring-white' }}">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown Modal / Popover -->
    <div
        x-cloak
        x-show="notifOpen"
        @click.outside="notifOpen = false"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        @class([
            'absolute right-0 mt-2 w-80 sm:w-96 rounded-2xl shadow-2xl z-50 overflow-hidden border',
            'bg-[#111827] border-slate-800 text-slate-100' => $isDark,
            'bg-white border-[#E5E7EB] text-[#16151A]' => ! $isDark,
        ])
    >
        <!-- Dropdown Header -->
        <div @class([
            'flex items-center justify-between px-4 py-3 border-b',
            'border-slate-800 bg-[#0E1522]' => $isDark,
            'border-[#E5E7EB] bg-gray-50/70' => ! $isDark,
        ])>
            <div class="flex items-center gap-2">
                <span class="font-heading font-bold text-sm">Notifikasi</span>
                @if($unreadCount > 0)
                    <span class="text-[11px] font-extrabold px-2 py-0.5 rounded-full bg-rose-500/15 text-[#ED1B45]">
                        {{ $unreadCount }} baru
                    </span>
                @endif
            </div>

            @if($unreadCount > 0)
                <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                    @csrf
                    <button
                        type="submit"
                        class="text-xs font-semibold text-[#ED1B45] hover:underline focus:outline-none cursor-pointer"
                    >
                        Tandai semua dibaca
                    </button>
                </form>
            @endif
        </div>

        <!-- Notification List -->
        <div class="max-h-88 overflow-y-auto divide-y {{ $isDark ? 'divide-slate-800' : 'divide-gray-100' }}">
            @forelse($notifications as $notification)
                @php
                    $isUnread = $notification->unread();
                    $type = $notification->data['type'] ?? '';
                    $title = $notification->data['title'] ?? 'Pemberitahuan';
                    $message = $notification->data['message'] ?? '';
                @endphp

                <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="m-0 p-0">
                    @csrf
                    <button
                        type="submit"
                        @class([
                            'w-full text-left p-3.5 flex items-start gap-3 transition-colors cursor-pointer group',
                            $isDark
                                ? ($isUnread ? 'bg-slate-800/40 hover:bg-slate-800/70' : 'hover:bg-slate-800/30 opacity-80 hover:opacity-100')
                                : ($isUnread ? 'bg-rose-50/40 hover:bg-rose-50/70' : 'hover:bg-gray-50 opacity-75 hover:opacity-100')
                        ])
                    >
                        <!-- Type Icon -->
                        <div class="shrink-0 mt-0.5">
                            @if($type === 'session_confirmed')
                                <div class="w-8 h-8 rounded-full bg-emerald-500/15 text-emerald-500 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>
                                </div>
                            @elseif($type === 'session_cancelled')
                                <div class="w-8 h-8 rounded-full bg-rose-500/15 text-rose-500 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                </div>
                            @elseif($type === 'new_session_requested')
                                <div class="w-8 h-8 rounded-full bg-indigo-500/15 text-indigo-500 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                    </svg>
                                </div>
                            @else
                                <div class="w-8 h-8 rounded-full bg-[#ED1B45]/15 text-[#ED1B45] flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Text Details -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <p @class([
                                    'text-xs font-bold truncate',
                                    'text-white' => $isDark,
                                    'text-[#16151A]' => ! $isDark,
                                ])>
                                    {{ $title }}
                                </p>
                                <span class="text-[10px] text-gray-400 shrink-0">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p @class([
                                'text-[11px] leading-relaxed mt-0.5 line-clamp-2',
                                'text-slate-300' => $isDark,
                                'text-[#565A66]' => ! $isDark,
                            ])>
                                {{ $message }}
                            </p>
                        </div>

                        <!-- Unread Dot Indicator -->
                        @if($isUnread)
                            <div class="shrink-0 self-center">
                                <span class="block w-2 h-2 rounded-full bg-[#ED1B45]"></span>
                            </div>
                        @endif
                    </button>
                </form>
            @empty
                <div class="px-4 py-8 text-center">
                    <div class="mx-auto w-12 h-12 rounded-full flex items-center justify-center {{ $isDark ? 'bg-slate-800 text-slate-500' : 'bg-gray-100 text-gray-400' }} mb-2.5">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                    </div>
                    <p @class([
                        'text-xs font-bold',
                        'text-white' => $isDark,
                        'text-[#16151A]' => ! $isDark,
                    ])>
                        Belum ada notifikasi
                    </p>
                    <p class="text-[11px] text-gray-400 mt-0.5">
                        Pemberitahuan sesi dan jadwal Anda akan muncul di sini.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</div>
