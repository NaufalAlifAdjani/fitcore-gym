<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl sm:text-2xl text-[#16151A] leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-10" x-data="{}" x-init="
        if (window.location.hash === '#password' || new URLSearchParams(window.location.search).get('tab') === 'password') {
            $nextTick(() => {
                const el = document.getElementById('password');
                if (el) {
                    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    const input = document.getElementById('update_password_current_password');
                    if (input) input.focus();
                }
            });
        }
    ">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div id="profile-info" class="p-6 sm:p-8 bg-white border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] rounded-2xl scroll-mt-24">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div id="password" class="p-6 sm:p-8 bg-white border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] rounded-2xl scroll-mt-24 transition-all">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-white border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] rounded-2xl">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
