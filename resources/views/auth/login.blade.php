<x-guest-layout>
    <div class="flex justify-center items-center py-10">
        <div class="card w-full max-w-md bg-base-100 shadow-xl">
            <div class="card-body">
                <h1 class="text-2xl font-bold text-center mb-2">
                    {{ __('Dobrodošli u HairCare') }}
                </h1>
                <p class="text-sm text-base-content/70 text-center mb-4">
                    {{ __('Prijavite se da biste zakazali termine i upravljali svojim nalogom.') }}
                </p>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    {{-- Store the previous URL to redirect after login --}}
                    <input type="hidden" name="previous_url"
                        value="{{ request()->query('redirect', request()->header('referer', route('home'))) }}" />

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="mt-1 block w-full" type="email" name="email"
                            :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Lozinka')" />
                        <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required
                            autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="label cursor-pointer gap-2">
                            <input id="remember_me" type="checkbox" class="checkbox checkbox-sm" name="remember">
                            <span class="label-text text-sm">{{ __('Zapamti me') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="link text-sm" href="{{ route('password.request') }}">
                                {{ __('Zaboravili ste lozinku?') }}
                            </a>
                        @endif
                    </div>

                    <div class="card-actions mt-2">
                        <button class="btn btn-primary w-full">
                            {{ __('Prijavi se') }}
                        </button>
                    </div>

                    @if (Route::has('register'))
                        <p class="text-center text-xs text-base-content/70 mt-2">
                            {{ __('Nemate nalog?') }}
                            <a href="{{ route('register') }}" class="link link-primary hover:text-primary-focus">
                                {{ __('Registrujte se') }}
                            </a>
                        </p>
                    @endif
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>