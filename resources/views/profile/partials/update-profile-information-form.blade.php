<section>
    <header>
        <h2 class="text-lg font-medium text-[#16151A]">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-xs text-[#565A66]">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    @if (session('warning'))
        <div class="mt-4 p-4 rounded-xl bg-amber-50 border border-amber-300 flex items-start gap-3">
            <svg class="w-5 h-5 text-amber-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div class="text-xs sm:text-sm text-amber-900 leading-relaxed font-medium">
                {{ session('warning') }}
            </div>
        </div>
    @endif

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-xs mt-2 text-[#16151A]">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-xs text-[#565A66] hover:text-[#FF5722] rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FF5722]">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-xs text-emerald-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="phone" :value="__('Nomor Telepon / WhatsApp')" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" placeholder="Contoh: 081234567890" autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        @if ($user->role === 'member')
            <div>
                <x-input-label for="address" :value="__('Alamat Domisili')" />
                <textarea id="address" name="address" rows="3" class="mt-1 block w-full rounded-xl border-[#E5E7EB] bg-white text-[#16151A] text-sm focus:border-[#FF5722] focus:ring-[#FF5722] shadow-xs transition" placeholder="Masukkan alamat lengkap Anda">{{ old('address', $user->memberProfile?->address) }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('address')" />
            </div>

            <div>
                <x-input-label for="gender" :value="__('Jenis Kelamin')" />
                <select id="gender" name="gender" class="mt-1 block w-full rounded-xl border-[#E5E7EB] bg-white text-[#16151A] text-sm focus:border-[#FF5722] focus:ring-[#FF5722] shadow-xs transition">
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="male" {{ old('gender', $user->memberProfile?->gender) === 'male' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="female" {{ old('gender', $user->memberProfile?->gender) === 'female' ? 'selected' : '' }}>Perempuan</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('gender')" />
            </div>
        @endif

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs text-[#565A66]"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
