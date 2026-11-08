<button {{ $attributes->merge(['type' => 'submit', 'class' => 'w-full bg-[#FF5722] hover:bg-[#E64A19] text-white font-bold py-3.5 px-6 rounded-full flex items-center justify-center gap-2 text-sm shadow-md hover:shadow-lg transition duration-150 focus:outline-none focus:ring-2 focus:ring-[#FF5722] focus:ring-offset-2']) }}>
    <span>{{ $slot }}</span>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
    </svg>
</button>
