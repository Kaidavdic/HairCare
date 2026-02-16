<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="haircare">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'HairCare') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-base-100 font-sans antialiased text-base-content min-h-screen flex flex-col">

    <!-- Navbar -->
    @include('layouts.navigation')

    <!-- Hero -->
    @php
        $heroBg = isset($settings['hero_bg_image']) ? asset('storage/' . $settings['hero_bg_image']) : null;
        $heroTitle = $settings['hero_title'] ?? 'Ponađi savršen salon';
        $heroContent = $settings['hero_content'] ?? 'Rezerviši termin za frizuru, šminku ili masažu u najboljim salonima u gradu. Brzo, lako i pouzdano.';
    @endphp
    
    <div class="hero min-h-[50vh] transition-all duration-500" 
         style="{{ $heroBg ? "background-image: url('{$heroBg}'); background-size: cover; background-position: center;" : "background-color: var(--p);" }}">
        <div class="hero-overlay {{ $heroBg ? 'bg-black/40' : 'hidden' }}"></div>
        <div class="hero-content text-center {{ $heroBg ? 'text-white' : '' }}">
            <div class="max-w-xl">
                <h1 class="text-5xl font-bold drop-shadow-lg">{{ $heroTitle }}</h1>
                <p class="py-6 text-lg font-medium drop-shadow-md">
                    {{ $heroContent }}
                </p>
                <div class="flex justify-center gap-4">
                    <a href="{{ route('salons.index') }}" class="btn btn-primary {{ $heroBg ? 'border-white' : '' }}">Pretraži Salone</a>
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-outline {{ $heroBg ? 'text-white border-white hover:bg-white hover:text-black' : '' }}">Postani Partner</a>
                    @endguest
                </div>
            </div>
        </div>
    </div>

    <!-- Content Container -->
    <div class="container mx-auto px-4 py-12 flex-grow">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-16">
            
            <!-- News Section -->
            <div>
                <h2 class="text-3xl font-bold mb-6 text-primary flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Aktuelnosti
                </h2>
                @if($news->count() > 0)
                    <div class="space-y-4">
                        @foreach($news as $item)
                            <div class="card bg-base-100 shadow border border-base-200">
                                <div class="card-body p-5">
                                    <h3 class="card-title text-lg">{{ $item->title }}</h3>
                                    <p class="text-base-content/80">{{ $item->content }}</p>
                                    <div class="card-actions justify-end mt-2">
                                        <span class="text-xs text-base-content/50">{{ $item->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert">
                        <span>Trenutno nema novih obaveštenja.</span>
                    </div>
                @endif
            </div>

            <!-- Popular Services Section -->
            <div>
                <h2 class="text-3xl font-bold mb-6 text-secondary flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.046 8.268 8.268 0 0 0 9 9.603a5.851 5.851 0 0 1 3-4.389Zm3 1.389a6.002 6.002 0 0 1 1.637 4.195 6.001 6.001 0 0 1-4.013 5.539 6.096 6.096 0 0 0-1.923-1.761 4.386 4.386 0 0 0 1.957-3.953 4.438 4.438 0 0 0-2.079-3.79 6.004 6.004 0 0 1 4.421-1.23Z" />
                    </svg>
                    Popularne Usluge
                </h2>
                @if($popularServices->count() > 0)
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($popularServices as $service)
                            <div class="card bg-base-100 shadow border border-base-200">
                                <div class="card-body p-5 flex flex-row items-center justify-between">
                                    <div>
                                        <h3 class="font-bold text-lg">{{ $service->name }}</h3>
                                        @if($service->salon)
                                            <p class="text-sm text-base-content/70">Salon: 
                                                <a href="{{ route('salons.show', $service->salon) }}" class="link link-hover text-primary">
                                                    {{ $service->salon->name }}
                                                </a>
                                            </p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <div class="badge badge-secondary badge-outline mb-1">{{ $service->price }} RSD</div>
                                        <div class="text-xs text-base-content/50">{{ $service->appointments_count }} zakazivanja</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert">
                        <span>Nema dovoljno podataka za prikaz popularnih usluga.</span>
                    </div>
                @endif
            </div>

        </div>

        <!-- Promotions Section -->
        @if($promotions->count() > 0)
            <div class="mb-16">
                <h2 class="text-3xl font-bold mb-6 text-accent flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                    </svg>
                    Promocije
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($promotions as $service)
                        <div class="card bg-base-100 shadow-lg border-2 border-accent hover:shadow-xl transition-shadow">
                            <div class="card-body">
                                <div class="badge badge-accent mb-2">PROMOCIJA</div>
                                <h3 class="card-title text-lg">{{ $service->name }}</h3>
                                @if($service->salon)
                                    <p class="text-sm text-base-content/70 mb-2">
                                        <a href="{{ route('salons.show', $service->salon) }}" class="link link-hover text-primary">
                                            {{ $service->salon->name }}
                                        </a>
                                    </p>
                                @endif
                                @if($service->description)
                                    <p class="text-sm text-base-content/60 mb-3">{{ Str::limit($service->description, 80) }}</p>
                                @endif
                                <div class="flex items-center gap-3 mt-auto">
                                    <div class="text-base-content/50 line-through text-sm">{{ $service->price }} RSD</div>
                                    <div class="text-2xl font-bold text-accent">{{ $service->discount_price }} RSD</div>
                                </div>
                                @if($service->salon)
                                    <a href="{{ route('salons.show', $service->salon) }}" class="btn btn-accent btn-sm mt-4">
                                        Zakaži
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="divider"></div>

        <!-- Popular Salons Section (Last Month) -->
        @if($popularSalons->isNotEmpty())
            <div class="mb-12">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-3xl font-bold text-base-content">{{ __('Popularni saloni') }}</h2>
                        <p class="text-base-content/60">{{ __('Najčešće posećeni saloni u poslednjih mesec dana') }}</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($popularSalons as $salon)
                        <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-all border border-base-200">
                            <figure class="px-4 pt-4">
                                @if($salon->images->isNotEmpty())
                                    <img src="{{ $salon->images->first()->image_url }}" 
                                         alt="{{ $salon->name }}" 
                                         class="rounded-xl h-48 w-full object-cover" />
                                @else
                                    <div class="h-48 w-full bg-base-200 rounded-xl flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 opacity-20">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21V10.5m0 10.5h2.25A2.25 2.25 0 0 0 18 18.75V10.5m-4.5 10.5H9m-1.5-10.5H3.75a1.125 1.125 0 0 1-1.125-1.125V3.75a1.125 1.125 0 0 1 1.125-1.125h16.5a1.125 1.125 0 0 1 1.125 1.125v4.5A1.125 1.125 0 0 1 20.25 10.5H18M9 10.5V21a2.25 2.25 0 0 1-2.25 2.25H4.5A2.25 2.25 0 0 1 2.25 21V10.5m6 0H18m-9 0h7.5" />
                                        </svg>
                                    </div>
                                @endif
                            </figure>
                            <div class="card-body">
                                <h2 class="card-title text-lg">{{ $salon->name }}</h2>
                                <p class="text-xs text-base-content/60 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                    </svg>
                                    {{ $salon->location }}
                                </p>
                                <div class="flex items-center justify-between mt-4">
                                    <div class="flex items-center gap-1">
                                        <div class="rating rating-xs">
                                            @for($i = 1; $i <= 5; $i++)
                                                <input type="radio" class="mask mask-star-2 bg-orange-400" disabled @checked($i <= round($salon->average_rating)) />
                                            @endfor
                                        </div>
                                        <span class="text-xs font-semibold">{{ number_format($salon->average_rating, 1) }}</span>
                                    </div>
                                    <a href="{{ route('salons.show', $salon) }}" class="btn btn-primary btn-xs">{{ __('Pogledaj') }}</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="divider"></div>

        <!-- Salon Search & List Section -->
        <div id="salons-search">
             <div class="card bg-base-100 shadow-md mb-8">
                <div class="card-body">
                    <h2 class="card-title text-2xl mb-4">
                        {{ __('Pronađi frizerski salon') }}
                    </h2>
                    <form method="GET" action="{{ route('home') }}#salons-search" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        <div>
                            <label for="q" class="label text-sm font-semibold">{{ __('Pretraga') }}</label>
                            <input
                                id="q"
                                name="q"
                                type="text"
                                class="input input-bordered w-full"
                                value="{{ $filters['q'] ?? '' }}"
                                placeholder="{{ __('Naziv salona ili lokacija') }}"
                            />
                        </div>

                        <div>
                            <label for="type" class="label text-sm font-semibold">{{ __('Tip salona') }}</label>
                            <select
                                id="type"
                                name="type"
                                class="select select-bordered w-full"
                            >
                                <option value="">{{ __('Svi') }}</option>
                                <option value="male" @selected(($filters['type'] ?? '') === 'male')>{{ __('Muški') }}</option>
                                <option value="female" @selected(($filters['type'] ?? '') === 'female')>{{ __('Ženski') }}</option>
                                <option value="unisex" @selected(($filters['type'] ?? '') === 'unisex')>{{ __('Unisex') }}</option>
                            </select>
                        </div>

                        <div>
                            <label for="min_rating" class="label text-sm font-semibold">{{ __('Minimalna ocena') }}</label>
                            <select
                                id="min_rating"
                                name="min_rating"
                                class="select select-bordered w-full"
                            >
                                <option value="">{{ __('Sve ocene') }}</option>
                                @for ($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" @selected((int) ($filters['min_rating'] ?? 0) === $i)>{{ $i }}+</option>
                                @endfor
                            </select>
                        </div>

                        <div>
                            <label for="sort" class="label text-sm font-semibold">{{ __('Sortiraj po') }}</label>
                            <select
                                id="sort"
                                name="sort"
                                class="select select-bordered w-full"
                            >
                                <option value="rating_desc" @selected(($filters['sort'] ?? '') === 'rating_desc')>{{ __('Najbolje ocenjeni') }}</option>
                                <option value="rating_asc" @selected(($filters['sort'] ?? '') === 'rating_asc')>{{ __('Najslabije ocenjeni') }}</option>
                                <option value="newest" @selected(($filters['sort'] ?? '') === 'newest')>{{ __('Najnoviji') }}</option>
                                <option value="oldest" @selected(($filters['sort'] ?? '') === 'oldest')>{{ __('Najstariji') }}</option>
                            </select>
                        </div>

                        <div class="md:col-span-4 mt-2">
                            <button class="btn btn-primary w-full md:w-auto">
                                {{ __('Pretraži') }}
                            </button>
                             <a href="{{ route('home') }}#salons-search" class="btn btn-ghost ml-2">
                                {{ __('Poništi filtere') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($salons as $salon)
                    <a href="{{ route('salons.show', $salon) }}" class="card bg-base-100 shadow-xl hover:shadow-2xl transition duration-300">
                        @if ($salon->images->count() > 0)
                            <figure>
                                <img src="{{ $salon->images->first()->image_url }}" alt="{{ $salon->images->first()->alt_text ?? $salon->name }}" class="h-48 w-full object-cover" />
                            </figure>
                        @else
                            <figure class="h-48 bg-base-300 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 opacity-20">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21V10.5m0 10.5h2.25A2.25 2.25 0 0 0 18 18.75V10.5m-4.5 10.5H9m-1.5-10.5H3.75a1.125 1.125 0 0 1-1.125-1.125V3.75a1.125 1.125 0 0 1 1.125-1.125h16.5a1.125 1.125 0 0 1 1.125 1.125v4.5A1.125 1.125 0 0 1 20.25 10.5H18M9 10.5V21a2.25 2.25 0 0 1-2.25 2.25H4.5A2.25 2.25 0 0 1 2.25 21V10.5m6 0H18m-9 0h7.5" />
                                </svg>
                            </figure>
                        @endif
                        <div class="card-body">
                            <h2 class="card-title">
                                {{ $salon->name }}
                                <div class="badge badge-secondary">
                                    {{ number_format($salon->average_rating, 1) }} ★
                                </div>
                            </h2>
                            <p class="text-sm text-base-content/70 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                </svg>
                                {{ $salon->location }}
                            </p>
                            <div class="mt-2">
                                @switch($salon->type)
                                    @case('male')
                                        <div class="badge badge-outline">{{ __('Muški') }}</div>
                                        @break
                                    @case('female')
                                         <div class="badge badge-outline">{{ __('Ženski') }}</div>
                                        @break
                                    @case('unisex')
                                         <div class="badge badge-outline">{{ __('Unisex') }}</div>
                                        @break
                                @endswitch
                            </div>
                            
                            @if ($salon->description)
                                <p class="mt-2 text-sm text-base-content/80 line-clamp-2">
                                    {{ $salon->description }}
                                </p>
                            @endif
                            <div class="card-actions justify-between items-center mt-3 pt-3 border-t border-base-200 text-xs text-base-content/60">
                                <span>
                                    {{ $salon->reviews_count }} {{ __('recenzija') }}
                                </span>
                                <span class="text-primary font-semibold">
                                    {{ __('Pogledaj detalje') }} &rarr;
                                </span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full alert alert-info">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                        <span>{{ __('Trenutno nema salona koji odgovaraju zadatim filterima.') }}</span>
                    </div>
                @endforelse
            </div>

            <div class="mt-10">
                {{ $salons->links() }}
            </div>
        </div>

    </div>

    <!-- Footer -->
    <x-footer />

</body>
</html>
