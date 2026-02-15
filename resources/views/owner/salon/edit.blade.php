<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ __('Moj salon') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-base-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-base-content">
                    @if (session('status'))
                        <div class="mb-4 text-sm text-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ $salon ? route('owner.salon.update') : route('owner.salon.store') }}"
                        class="space-y-4">
                        @csrf
                        @if ($salon)
                            @method('PUT')
                        @endif

                        <div>
                            <x-input-label for="name" :value="__('Naziv salona')" />
                            <x-text-input id="name" name="name" type="text" class="block mt-1 w-full"
                                :value="old('name', $salon->name ?? '')" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="location" :value="__('Lokacija')" />
                            <x-text-input id="location" name="location" type="text" class="block mt-1 w-full"
                                :value="old('location', $salon->location ?? '')" required />
                            <x-input-error :messages="$errors->get('location')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="type" :value="__('Tip salona')" />
                            <select id="type" name="type"
                                class="mt-1 block w-full rounded-md border-base-200 bg-base-100 text-sm text-base-content"
                                required>
                                @php
                                    $type = old('type', $salon->type ?? '');
                                @endphp
                                <option value="">{{ __('Izaberite tip') }}</option>
                                <option value="male" @selected($type === 'male')>{{ __('Muški') }}</option>
                                <option value="female" @selected($type === 'female')>{{ __('Ženski') }}</option>
                                <option value="unisex" @selected($type === 'unisex')>{{ __('Unisex') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Opis')" />
                            <textarea id="description" name="description" rows="4"
                                class="mt-1 block w-full rounded-md border-base-200 bg-base-100 text-sm text-base-content">{{ old('description', $salon->description ?? '') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="border-t border-base-300 pt-6">
                            <h4 class="font-semibold mb-4 text-base-content">Radno vrijeme</h4>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="opening_hour" :value="__('Vrijeme otvaranja')" />
                                    <input type="number" id="opening_hour" name="opening_hour" min="0" max="23"
                                        class="mt-1 block w-full rounded-md border-base-200 bg-base-100 text-sm text-base-content"
                                        value="{{ old('opening_hour', $salon->opening_hour ?? 9) }}" />
                                </div>
                                <div>
                                    <x-input-label for="closing_hour" :value="__('Vrijeme zatvaranja')" />
                                    <input type="number" id="closing_hour" name="closing_hour" min="0" max="23"
                                        class="mt-1 block w-full rounded-md border-base-200 bg-base-100 text-sm text-base-content"
                                        value="{{ old('closing_hour', $salon->closing_hour ?? 18) }}" />
                                </div>
                            </div>

                            <div class="mt-4">
                                <x-input-label :value="__('Slobodni dani (odaberite dane kada je salon zatvoren)')" />
                                <div class="mt-2 space-y-2">
                                    @php
                                        $daysOfWeek = [
                                            0 => 'Nedjelja',
                                            1 => 'Ponedjeljak',
                                            2 => 'Utorak',
                                            3 => 'Srijeda',
                                            4 => 'Četvrtak',
                                            5 => 'Petak',
                                            6 => 'Subota'
                                        ];
                                        $closedDays = json_decode($salon->closed_days ?? '[]', true);
                                    @endphp
                                    @foreach ($daysOfWeek as $dayNum => $dayName)
                                        <label class="flex items-center gap-2">
                                            <input type="checkbox" name="closed_days[]" value="{{ $dayNum }}"
                                                class="checkbox checkbox-sm" @checked(in_array($dayNum, $closedDays)) />
                                            <span>{{ $dayName }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="pt-4">
                            <x-primary-button>
                                {{ __('Sačuvaj salon') }}
                            </x-primary-button>
                        </div>
                    </form>
                    @if ($salon && $salon->status === 'approved')
                        <div class="mt-6">
                            <h3 class="font-semibold mb-2">{{ __('Upravljanje uslugama') }}</h3>
                            <a href="{{ route('owner.services.index') }}"
                                class="btn btn-outline btn-sm">{{ __('Pogledaj / Dodaj usluge') }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Salon Images Section -->
    @if ($salon)
        <div class="py-8">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-base-100 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-base-content">
                        <h3 class="text-lg font-semibold mb-4 text-base-content">Slike vašeg salona</h3>

                        <!-- Upload Form -->
                        <form action="{{ route('owner.images.store') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-4 mb-6 p-4 bg-base-200 rounded-lg">
                            @csrf
                            <div>
                                <x-input-label for="image" :value="__('Dodaj novu sliku')" />
                                <input type="file" name="image" id="image" accept="image/*" required
                                    class="file-input file-input-bordered file-input-sm w-full mt-1 @error('image') file-input-error @enderror" />
                                <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="alt_text" :value="__('Opis slike (opcionalno)')" />
                                <x-text-input id="alt_text" name="alt_text" type="text"
                                    placeholder="npr. Frizer sa zaposlenim" class="block mt-1 w-full" />
                            </div>
                            <x-primary-button>{{ __('Dodaj sliku') }}</x-primary-button>
                        </form>

                        @if (session('success'))
                            <div class="alert alert-success shadow-lg mb-4">
                                <span>{{ session('success') }}</span>
                            </div>
                        @endif

                        <!-- Images Gallery -->
                        @if ($salon->images->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach ($salon->images as $image)
                                    <div class="bg-base-200 rounded-lg overflow-hidden shadow">
                                        <div class="relative h-40 bg-base-300">
                                            <img src="{{ $image->image_url }}" alt="{{ $image->alt_text ?? 'Slika salona' }}"
                                                class="w-full h-full object-cover" />
                                        </div>
                                        <div class="p-3">
                                            @if ($image->alt_text)
                                                <p class="text-sm text-base-content/70 mb-2">{{ $image->alt_text }}</p>
                                            @endif
                                            <form action="{{ route('owner.images.destroy', $image) }}" method="POST"
                                                onsubmit="return confirm('Sigurno želite da obriješete ovu sliku?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-error btn-sm btn-outline w-full">
                                                    Obriši
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-base-200 rounded-lg p-8 text-center text-base-content/60">
                                Nema slika. Počnite sa dodavanjem slika vašeg salona.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>