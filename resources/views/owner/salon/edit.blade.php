<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ __('Moj salon') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-base-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-base-content">
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

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-1">
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

                            <div class="md:col-span-2">
                                <x-input-label for="description" :value="__('Opis')" />
                                <textarea id="description" name="description" rows="2"
                                    class="mt-1 block w-full rounded-md border-base-200 bg-base-100 text-sm text-base-content">{{ old('description', $salon->description ?? '') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
                        </div>

                        <div class="border-t border-base-300 pt-4" x-data="{ closedDays: {{ json_encode(json_decode($salon->closed_days ?? '[]', true)) }} }">
                            <h4 class="font-semibold mb-3 text-base-content text-sm uppercase tracking-wider">Radno vreme</h4>

                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4 max-w-sm">
                                    <div>
                                        <x-input-label for="opening_hour" :value="__('Vreme otvaranja')" />
                                        <input type="number" id="opening_hour" name="opening_hour" min="0" max="23"
                                            class="mt-1 block w-full rounded-md border-base-200 bg-base-100 text-sm text-base-content"
                                            value="{{ old('opening_hour', $salon->opening_hour ?? 9) }}" />
                                    </div>
                                    <div>
                                        <x-input-label for="closing_hour" :value="__('Vreme zatvaranja')" />
                                        <input type="number" id="closing_hour" name="closing_hour" min="0" max="23"
                                            class="mt-1 block w-full rounded-md border-base-200 bg-base-100 text-sm text-base-content"
                                            value="{{ old('closing_hour', $salon->closing_hour ?? 18) }}" />
                                    </div>
                                </div>

                                <div>
                                    <x-input-label :value="__('Neradni dani')" class="mb-2" />
                                    <div class="flex flex-wrap gap-2">
                                        @php
                                            $daysOfWeek = [
                                                1 => 'Pon',
                                                2 => 'Uto',
                                                3 => 'Sre',
                                                4 => 'Čet',
                                                5 => 'Pet',
                                                6 => 'Sub',
                                                0 => 'Ned'
                                            ];
                                        @endphp
                                        @foreach ($daysOfWeek as $dayNum => $dayName)
                                            <label class="relative flex-1 min-w-[60px] cursor-pointer group">
                                                <input type="checkbox" name="closed_days[]" value="{{ $dayNum }}"
                                                    class="sr-only" 
                                                    x-model="closedDays"
                                                    :value="'{{ $dayNum }}'" />
                                                <div class="h-10 border rounded-lg flex items-center justify-center transition-all duration-200"
                                                    :class="closedDays.map(String).includes('{{ $dayNum }}')
                                                        ? 'bg-error border-error text-error-content font-bold' 
                                                        : 'bg-base-100 border-base-200 text-base-content hover:border-primary'">
                                                    <span class="text-xs uppercase tracking-tight">{{ $dayName }}</span>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                    <p class="mt-2 text-[10px] text-base-content/60 italic">
                                        {{ __('Kliknite na dane kada je vaš salon zatvoren (neradni dani postaju crveni).') }}
                                    </p>
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

    <!-- Status Information -->
    @if ($salon && $salon->status !== 'approved')
        <div class="pb-4">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-warning/10 border border-warning rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-warning flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <h4 class="font-semibold text-warning mb-1">Salon čeka odobrenje</h4>
                            <p class="text-sm text-base-content/70">
                                Vaš salon je trenutno na čekanju za odobrenje od strane administratora. 
                                Nakon što bude odobren, moći ćete da dodajete slike i usluge.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Salon Images Section -->
    @if ($salon && $salon->status === 'approved')
        <div class="pb-8">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-base-100 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-6 text-base-content">
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