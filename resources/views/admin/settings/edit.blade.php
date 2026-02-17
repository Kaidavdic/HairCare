<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ __('Podešavanja aplikacije / Kontakt podaci') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-base-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-base-content">
                    @if (session('status'))
                        <div class="alert alert-success mb-4">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-4" enctype="multipart/form-data">
                        @csrf
                        <div class="form-control">
                            <label class="label"><span class="label-text">{{ __('Naslov na početnoj stranici') }}</span></label>
                            <input type="text" name="hero_title" value="{{ old('hero_title', $settings['hero_title'] ?? 'Pronađi savršen salon') }}" class="input input-bordered w-full" required />
                            <x-input-error :messages="$errors->get('hero_title')" class="mt-2" />
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text">{{ __('Sadržaj na početnoj stranici') }}</span></label>
                            <textarea name="hero_content" class="textarea textarea-bordered h-24 w-full" required>{{ old('hero_content', $settings['hero_content'] ?? 'Rezerviši termin za frizuru, šminku ili masažu u najboljim salonima u gradu. Brzo, lako i pouzdano.') }}</textarea>
                            <x-input-error :messages="$errors->get('hero_content')" class="mt-2" />
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text">{{ __('Pozadinska slika početne stranice') }}</span></label>
                            @if(isset($settings['hero_bg_image']))
                                <div class="mb-2">
                                    <img src="{{ \Illuminate\Support\Str::startsWith($settings['hero_bg_image'], 'http') ? $settings['hero_bg_image'] : asset('storage/' . $settings['hero_bg_image']) }}" alt="Hero Background" class="h-20 w-auto rounded border border-base-300">
                                </div>
                            @endif
                            <input type="file" name="hero_bg_image" class="file-input file-input-bordered w-full" accept="image/*" />
                            <x-input-error :messages="$errors->get('hero_bg_image')" class="mt-2" />
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">{{ __('Broj prethodnih lozinki za čuvanje (Istorija)') }}</span>
                            </label>
                            <input type="number" name="password_history_count" value="{{ old('password_history_count', $settings['password_history_count'] ?? 3) }}" class="input input-bordered w-full" min="0" max="10" required />
                            <x-input-error :messages="$errors->get('password_history_count')" class="mt-2" />
                            <label class="label">
                                <span class="label-text-alt text-base-content/60">{{ __('Onemogućava ponovno korišćenje poslednjih N lozinki.') }}</span>
                            </label>
                        </div>

                        <div class="divider">{{ __('Kontakt podaci') }}</div>

                        <div class="form-control">
                            <label class="label"><span class="label-text">{{ __('Email za kontakt stranicu') }}</span></label>
                            <input type="email" name="email" value="{{ old('email', $settings['email'] ?? 'info@haircare.com') }}" class="input input-bordered w-full" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text">{{ __('Telefon za kontakt stranicu') }}</span></label>
                            <input type="text" name="phone" value="{{ old('phone', $settings['phone'] ?? '+381 11 123 4567') }}" class="input input-bordered w-full" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text">{{ __('Adresa za kontakt stranicu') }}</span></label>
                            <input type="text" name="address" value="{{ old('address', $settings['address'] ?? 'Bulevar kralja Aleksandra 123, Beograd') }}" class="input input-bordered w-full" />
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="btn btn-primary">{{ __('Sačuvaj podešavanja') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
