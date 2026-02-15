<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">{{ __('Uredi uslugu') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-base-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-base-content">
                    <form method="POST" action="{{ route('owner.services.update', $service) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="name" :value="__('Naziv usluge')" />
                            <x-text-input id="name" name="name" type="text" class="block mt-1 w-full"
                                value="{{ old('name', $service->name) }}" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Opis')" />
                            <textarea id="description" name="description" rows="3"
                                class="mt-1 block w-full rounded-md border-base-200 bg-base-100 text-sm text-base-content">{{ old('description', $service->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="duration_minutes" :value="__('Trajanje (min)')" />
                            <x-text-input id="duration_minutes" name="duration_minutes" type="number"
                                class="block mt-1 w-full"
                                value="{{ old('duration_minutes', $service->duration_minutes) }}" required />
                            <x-input-error :messages="$errors->get('duration_minutes')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="price" :value="__('Cena (RSD)')" />
                            <x-text-input id="price" name="price" type="number" step="0.01" class="block mt-1 w-full"
                                value="{{ old('price', $service->price) }}" required />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>

                        <div class="form-control">
                            <label class="label cursor-pointer justify-start gap-4">
                                <span class="label-text">{{ __('Promocija?') }}</span>
                                <input type="checkbox" name="is_promoted" value="1" class="checkbox checkbox-primary" 
                                    {{ old('is_promoted', $service->is_promoted) ? 'checked' : '' }}
                                    onchange="document.getElementById('discount_container').classList.toggle('hidden', !this.checked)" />
                            </label>
                        </div>

                        <div id="discount_container" class="{{ old('is_promoted', $service->is_promoted) ? '' : 'hidden' }}">
                            <x-input-label for="discount_price" :value="__('Akcijska cena (RSD)')" />
                            <x-text-input id="discount_price" name="discount_price" type="number" step="0.01" class="block mt-1 w-full"
                                value="{{ old('discount_price', $service->discount_price) }}" />
                            <x-input-error :messages="$errors->get('discount_price')" class="mt-2" />
                        </div>

                        <div class="pt-4">
                            <x-primary-button>{{ __('Sačuvaj') }}</x-primary-button>
                            <a href="{{ route('owner.services.index') }}"
                                class="btn btn-ghost btn-sm">{{ __('Otkaži') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>