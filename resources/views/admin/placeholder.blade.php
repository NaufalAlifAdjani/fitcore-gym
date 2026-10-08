<x-app-layout>
    <x-slot name="header">
        <h1 class="font-heading text-2xl font-bold text-[#16151A]">{{ $title }}</h1>
    </x-slot>

    <section class="px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl rounded-[20px] bg-white p-8 shadow-[0px_1px_2px_rgba(0,0,0,0.05)]">
            <p class="text-sm leading-6 text-[#565A66]">
                Halaman {{ $title }} akan tersedia di sini.
            </p>
        </div>
    </section>
</x-app-layout>
