<x-guest-layout>
    <div class="flex justify-center items-center py-10">
        <div class="card w-full max-w-xl bg-base-100 shadow-xl">
            <div class="card-body">
                <h1 class="text-2xl font-bold text-center mb-2">
                    {{ __('Kreiraj HairCare nalog') }}
                </h1>
                <p class="text-sm text-base-content/70 text-center mb-4">
                    {{ __('Registruj se kao klijent ili vlasnik salona i iskoristi sve mogućnosti sistema.') }}
                </p>

                <form method="POST" action="{{ route('register') }}" class="space-y-4" enctype="multipart/form-data">
                    @csrf

                    <!-- Profile Picture -->
                    <div>
                        <x-input-label for="profile_picture" :value="__('Profilna slika (opciono)')" />
                        <input id="profile_picture" name="profile_picture" type="file" class="file-input file-input-bordered file-input-primary w-full mt-1" accept="image/*" />
                        <x-input-error :messages="$errors->get('profile_picture')" class="mt-2" />
                    </div>

                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Ime i prezime')" />
                        <x-text-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Phone -->
                    <div>
                        <x-input-label for="phone" :value="__('Telefon (opciono)')" />
                        <x-text-input id="phone" class="mt-1 block w-full" type="text" name="phone" :value="old('phone')" autocomplete="tel" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    <!-- Role -->
                    <div>
                        <x-input-label :value="__('Registrujem se kao')" />
                        <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label class="card border-base-300 border rounded-box cursor-pointer hover:border-primary transition-colors">
                                <div class="card-body p-3 flex flex-row items-center gap-3">
                                    <input
                                        type="radio"
                                        name="role"
                                        value="client"
                                        class="radio radio-primary"
                                        {{ old('role', 'client') === 'client' ? 'checked' : '' }}
                                    >
                                    <div>
                                        <p class="font-semibold text-sm">{{ __('Klijent') }}</p>
                                        <p class="text-xs text-base-content/70">
                                            {{ __('Pretražuj salone i zakazuj termine.') }}
                                        </p>
                                    </div>
                                </div>
                            </label>

                            <label class="card border-base-300 border rounded-box cursor-pointer hover:border-primary transition-colors">
                                <div class="card-body p-3 flex flex-row items-center gap-3">
                                    <input
                                        type="radio"
                                        name="role"
                                        value="salon_owner"
                                        class="radio radio-primary"
                                        {{ old('role') === 'salon_owner' ? 'checked' : '' }}
                                    >
                                    <div>
                                        <p class="font-semibold text-sm">{{ __('Vlasnik salona') }}</p>
                                        <p class="text-xs text-base-content/70">
                                            {{ __('Upravljaj svojim salonom i terminima.') }}
                                        </p>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>

                    <!-- Interests -->
                    <div class="space-y-2">
                        <x-input-label for="interests" :value="__('Interesovanja / Usluge')" />
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 mt-2">
                            @foreach(['Muški salon', 'Ženski salon', 'Farbanje', 'Šišanje', 'Feniranje', 'Masaža', 'Manikir', 'Pedikir', 'Brijanje'] as $interest)
                                <label class="flex items-center gap-2 cursor-pointer border border-base-200 p-2 rounded-lg hover:bg-base-200 transition-colors">
                                    <input type="checkbox" name="interests[]" value="{{ $interest }}" class="checkbox checkbox-primary checkbox-sm" />
                                    <span class="text-xs">{{ $interest }}</span>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('interests')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="password" :value="__('Lozinka')" />
                            <x-text-input id="password" class="mt-1 block w-full"
                                          type="password"
                                          name="password"
                                          required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <x-input-label for="password_confirmation" :value="__('Potvrdi lozinku')" />
                            <x-text-input id="password_confirmation" class="mt-1 block w-full"
                                          type="password"
                                          name="password_confirmation" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                    </div>

                    <div class="card-actions mt-2 flex flex-col sm:flex-row items-center justify-between gap-3">
                        <a class="link link-hover text-sm text-base-content/70 hover:text-primary" href="{{ route('login') }}">
                            {{ __('Već imate nalog? Prijavite se') }}
                        </a>

                        <button class="btn btn-primary">
                            {{ __('Registruj se') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
