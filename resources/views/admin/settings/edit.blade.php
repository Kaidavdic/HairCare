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

                    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-4">
                        @csrf
                        <div class="form-control">
                            <label class="label"><span class="label-text">{{ __('Email adresa') }}</span></label>
                            <input type="email" name="email" value="{{ old('email', $settings['email'] ?? '') }}" class="input input-bordered w-full" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text">{{ __('Telefon') }}</span></label>
                            <input type="text" name="phone" value="{{ old('phone', $settings['phone'] ?? '') }}" class="input input-bordered w-full" required />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text">{{ __('Adresa') }}</span></label>
                            <input type="text" name="address" value="{{ old('address', $settings['address'] ?? '') }}" class="input input-bordered w-full" required />
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
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

                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="btn btn-primary">{{ __('Sačuvaj podešavanja') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
