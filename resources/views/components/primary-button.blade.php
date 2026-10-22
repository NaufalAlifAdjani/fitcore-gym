<button {{ $attributes->merge(['type' => 'submit', 'class' => 'w-full bg-[#be0a32] hover:bg-[#a1082a] text-white font-bold py-3.5 px-6 rounded-full flex items-center justify-center gap-2 text-sm shadow-md hover:shadow-lg transition duration-150 focus:outline-none focus:ring-2 focus:ring-[#be0a32] focus:ring-offset-2']) }}>
    <span>{{ $slot }}</span>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
    </svg>
</button>
