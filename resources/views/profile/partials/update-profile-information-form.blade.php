<section>
    <header>
        <h2 class="text-lg font-medium text-[#16151A]">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-xs text-[#565A66]">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Foto Profil -->
        <div x-data="{ photoName: null, photoPreview: null }">
            <x-input-label for="avatar" :value="__('Foto Profil (Wajib untuk Check-In)')" />

            <div class="mt-2 flex items-center gap-4">
                <!-- Current Avatar or Initial -->
                <div x-show="!photoPreview" class="relative">
                    @if ($user->avatar_url)
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="h-16 w-16 rounded-full object-cover border-2 border-orange-200 shadow-xs">
                    @else
                        <div class="h-16 w-16 rounded-full bg-orange-100 text-[#FF5722] font-poppins font-bold flex items-center justify-center text-lg border-2 border-orange-200">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    @endif
                </div>

                <!-- Live Preview -->
                <div x-show="photoPreview" style="display: none;">
                    <span class="block h-16 w-16 rounded-full bg-cover bg-no-repeat bg-center border-2 border-[#FF5722] shadow-sm"
                          :style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <div>
                    <input id="avatar" name="avatar" type="file" accept="image/*" class="hidden"
                           x-ref="photo"
                           x-on:change="
                                photoName = $refs.photo.files[0]?.name;
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    photoPreview = e.target.result;
                                };
                                reader.readAsDataURL($refs.photo.files[0]);
                           " />

                    <button type="button" x-on:click="$refs.photo.click()" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-[#E5E7EB] rounded-xl text-xs font-semibold text-[#16151A] hover:bg-slate-50 shadow-xs cursor-pointer transition">
                        <svg class="w-4 h-4 text-[#565A66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>{{ $user->avatar_url ? __('Ganti Foto') : __('Pilih Foto Profil') }}</span>
                    </button>
                    <p class="text-[11px] text-[#565A66] mt-1">Format: JPG, PNG, WEBP (Maks. 2MB). Foto ini akan dicocokkan oleh staf admin saat Anda check-in.</p>
                </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>

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
