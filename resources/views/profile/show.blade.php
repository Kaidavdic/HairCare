<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ __('Profil korisnika') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-base-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-base-content relative">
                    
                    <!-- Header / Avatar Section -->
                    <div class="flex flex-col md:flex-row gap-8 items-start">
                        <!-- Avatar -->
                        <div class="flex-shrink-0">
                            <div class="avatar @if(!$user->profile_picture) placeholder @endif">
                                @if($user->profile_picture)
                                    <div class="w-32 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}" />
                                    </div>
                                @else
                                    <div class="bg-neutral text-neutral-content rounded-full w-32">
                                        <span class="text-3xl font-bold">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="flex-grow space-y-4">
                            <div>
                                <h1 class="text-3xl font-bold">{{ $user->name }}</h1>
                                <p class="text-sm opacity-60">
                                    {{ __('Član od') }}: {{ $user->created_at->format('d.m.Y.') }}
                                </p>
                                @if($user->average_rating && $user->reviews_count > 0)
                                    <div class="flex items-center gap-2 mt-2">
                                        <div class="rating rating-sm rating-disabled">
                                            @for($i=1; $i<=5; $i++)
                                                <input type="radio" class="mask mask-star-2 bg-orange-400" @checked($i <= round($user->average_rating)) />
                                            @endfor
                                        </div>
                                        <span class="text-sm font-semibold">{{ number_format($user->average_rating, 1) }}</span>
                                        <span class="text-xs opacity-60">({{ $user->reviews_count }} {{ __('recenzija') }})</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Role Badge -->
                            <div>
                                @if($user->isSalonOwner())
                                    <span class="badge badge-primary">{{ __('Vlasnik salona') }}</span>
                                    @if($user->salon)
                                        <a href="{{ route('salons.show', $user->salon) }}" class="btn btn-link btn-xs no-underline hover:underline">
                                            {{ $user->salon->name }}
                                        </a>
                                    @endif
                                @elseif($user->isAdmin())
                                    <span class="badge badge-error text-white">{{ __('Admin') }}</span>
                                @else
                                    <span class="badge badge-ghost">{{ __('Klijent') }}</span>
                                @endif
                            </div>

                            <!-- Initial Bio / Interests Section -->
                            @if($user->interests && count($user->interests) > 0)
                                <div class="mt-4">
                                    <h3 class="font-semibold text-lg mb-2">{{ __('Interesovanja') }}</h3>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($user->interests as $interest)
                                            <span class="badge badge-outline">{{ $interest }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex-shrink-0">
                            @auth
                                @if(auth()->id() === $user->id)
                                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline">
                                        {{ __('Uredi moj profil') }}
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>

                    <div class="divider"></div>

                    <!-- Reviews Tabs Section -->
                    @if($writtenReviews->count() > 0 || $receivedReviews->count() > 0)
                        <div class="mt-8">
                            <!-- Tab Navigation -->
                            <div role="tablist" class="tabs tabs-lifted">
                                @if($writtenReviews->count() > 0)
                                    <input type="radio" name="review_tabs" role="tab" class="tab" aria-label="{{ __('Napisane recenzije') }}" checked />
                                    <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-6">
                                        <h3 class="text-xl font-bold mb-4">{{ __('Napisane recenzije') }}</h3>
                                        <div class="space-y-4">
                                            @foreach($writtenReviews as $review)
                                                <div class="card bg-base-200 shadow-sm">
                                                    <div class="card-body p-4">
                                                        <div class="flex justify-between items-start">
                                                            <div>
                                                                <h4 class="font-bold">
                                                                    @if($review->salon)
                                                                        <a href="{{ route('salons.show', $review->salon) }}" class="link link-hover">
                                                                            {{ $review->salon->name }}
                                                                        </a>
                                                                    @endif
                                                                </h4>
                                                                @if($review->service)
                                                                    <p class="text-xs opacity-60">{{ __('Usluga') }}: {{ $review->service->name }}</p>
                                                                @elseif($review->service_id)
                                                                    <p class="text-xs opacity-60">{{ __('Usluga') }}: {{ __('Obrisana usluga') }}</p>
                                                                @endif
                                                                <p class="text-xs opacity-60">{{ $review->created_at->diffForHumans() }}</p>
                                                            </div>
                                                            <div class="flex flex-col items-end gap-1">
                                                                @if($review->salon_rating)
                                                                    <div class="flex items-center gap-1">
                                                                        <span class="text-[9px] uppercase opacity-40">{{ __('Salon') }}:</span>
                                                                        <div class="rating rating-sm rating-disabled">
                                                                            @for($i=1; $i<=5; $i++)
                                                                                <input type="radio" class="mask mask-star-2 bg-orange-400" @checked($i <= $review->salon_rating) />
                                                                            @endfor
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if($review->service_rating)
                                                                    <div class="flex items-center gap-1">
                                                                        <span class="text-[9px] uppercase opacity-40">{{ __('Usluga') }}:</span>
                                                                        <div class="rating rating-sm rating-disabled">
                                                                            @for($i=1; $i<=5; $i++)
                                                                                <input type="radio" class="mask mask-star-2 bg-green-400" @checked($i <= $review->service_rating) />
                                                                            @endfor
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @if($review->comment)
                                                            <p class="mt-2 text-sm italic">"{{ $review->comment }}"</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if($receivedReviews->count() > 0)
                                    <input type="radio" name="review_tabs" role="tab" class="tab" aria-label="{{ __('Recenzije o korisniku') }}" @if($writtenReviews->count() == 0) checked @endif />
                                    <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-6">
                                        <h3 class="text-xl font-bold mb-4">{{ __('Recenzije o korisniku') }}</h3>
                                        <div class="space-y-4">
                                            @foreach($receivedReviews as $review)
                                                <div class="card bg-base-200 shadow-sm">
                                                    <div class="card-body p-4">
                                                        <div class="flex justify-between items-start">
                                                            <div>
                                                                <h4 class="font-bold">
                                                                    @if($review->client)
                                                                        <a href="{{ route('profile.show', $review->client) }}" class="link link-hover">
                                                                            {{ $review->client->name }}
                                                                        </a>
                                                                    @else
                                                                        {{ __('Anonimno') }}
                                                                    @endif
                                                                </h4>
                                                                @if($review->appointment && $review->appointment->service)
                                                                    <p class="text-xs opacity-60">{{ __('Usluga') }}: {{ $review->appointment->service->name }}</p>
                                                                @endif
                                                                <p class="text-xs opacity-60">{{ $review->created_at->diffForHumans() }}</p>
                                                            </div>
                                                            <div class="flex flex-col items-end">
                                                                <div class="rating rating-sm rating-disabled">
                                                                    @for($i=1; $i<=5; $i++)
                                                                        <input type="radio" class="mask mask-star-2 bg-orange-400" @checked($i <= $review->rating) />
                                                                    @endfor
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if($review->comment)
                                                            <p class="mt-2 text-sm italic">"{{ $review->comment }}"</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="mt-8 text-center py-8 opacity-50">
                            <p>{{ __('Ovaj korisnik još uvek nema recenzije.') }}</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
