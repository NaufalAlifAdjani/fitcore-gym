@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full rounded-xl border border-[#E5E7EB] bg-white px-3.5 py-2.5 text-xs font-medium text-[#16151A] outline-none transition focus:border-[#ED1B45] focus:bg-white focus:ring-2 focus:ring-[#ED1B45]/20 disabled:opacity-50']) }}>
