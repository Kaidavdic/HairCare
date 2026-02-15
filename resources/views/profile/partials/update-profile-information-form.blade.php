<section>
    <header>
        <h2 class="text-lg font-medium text-base-content">
            {{ __('Informacije o profilu') }}
        </h2>

        <p class="mt-1 text-sm text-base-content/80">
            {{ __("Ažurirajte informacije o vašem profilu i email adresu.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Ime i prezime')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="username" :value="__('Korisničko ime')" />
            <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username', $user->username)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('username')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-base-content">
                        {{ __('Vaša email adresa nije potvrđena.') }}

                        <button form="send-verification"
                            class="underline text-sm text-base-content/80 hover:text-base-content rounded-md focus:outline-none focus:ring-2 focus:ring-primary/30 focus:ring-offset-2">
                            {{ __('Kliknite ovde da ponovo pošaljete email za potvrdu.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-success">
                            {{ __('Novi link za potvrdu je poslat na vašu email adresu.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="interests" :value="__('Interesi')" />
            <x-text-input id="interests" name="interests" type="text" class="mt-1 block w-full" 
                :value="old('interests', is_array($user->interests) ? implode(', ', $user->interests) : $user->interests)" 
                placeholder="{{ __('npr. šišanje, bojenje kose, brijanje') }}" />
            <p class="mt-1 text-xs text-base-content/60">{{ __('Odvojite interese zarezima.') }}</p>
            <x-input-error class="mt-2" :messages="$errors->get('interests')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Sačuvaj') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-base-content/70">{{ __('Sačuvano.') }}</p>
            @endif
        </div>
    </form>
</section>