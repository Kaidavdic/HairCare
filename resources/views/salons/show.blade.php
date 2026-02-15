<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <div>
                <h2 class="font-semibold text-xl md:text-2xl text-base-content leading-tight">
                    {{ $salon->name }}
                </h2>
                <p class="text-sm text-base-content/70">
                    {{ $salon->location }}
                </p>
            </div>
            <div class="flex items-center gap-3 text-sm">
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-primary/10 text-primary">
                    @switch($salon->type)
                        @case('male')
                            {{ __('Muški salon') }}
                            @break
                        @case('female')
                            {{ __('Ženski salon') }}
                            @break
                        @case('unisex')
                            {{ __('Unisex salon') }}
                            @break
                    @endswitch
                </span>
                <span class="inline-flex items-center text-base-content">
                    {{ number_format($salon->average_rating, 1) }} ★
                    <span class="ml-1 text-base-content/70 text-xs">
                        ({{ $salon->reviews_count }} {{ __('recenzija') }})
                    </span>
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
                

            <!-- Salon Images Gallery -->
            @if ($salon->images->count() > 0)
                <div class="bg-base-100 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-base-content mb-4">
                            {{ __('Slike salona') }}
                        </h3>
                        @if ($salon->images->count() > 1)
                            <div class="carousel rounded-lg w-full h-96 bg-base-300">
                                @foreach ($salon->images as $image)
                                    <div id="slide-{{ $image->id }}" class="carousel-item relative w-full">
                                        <img src="{{ $image->image_url }}" alt="{{ $image->alt_text ?? $salon->name }}"
                                            class="w-full h-full object-contain" />
                                        @if ($image->alt_text)
                                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4">
                                                <p class="text-sm text-white">{{ $image->alt_text }}</p>
                                            </div>
                                        @endif
                                        <div class="absolute left-5 right-5 top-1/2 flex -translate-y-1/2 transform justify-between">
                                            <a href="#slide-{{ $loop->index == 0 ? $salon->images->last()->id : $salon->images->slice($loop->index - 1, 1)->first()->id }}" class="btn btn-circle btn-sm">❮</a>
                                            <a href="#slide-{{ $loop->index == $loop->count - 1 ? $salon->images->first()->id : $salon->images->slice($loop->index + 1, 1)->first()->id }}" class="btn btn-circle btn-sm">❯</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="flex justify-center gap-2 pt-4">
                                @foreach ($salon->images as $image)
                                    <a href="#slide-{{ $image->id }}" class="btn btn-xs">{{ $loop->index + 1 }}</a>
                                @endforeach
                            </div>
                        @else
                            <div class="rounded-lg overflow-hidden shadow">
                                <img src="{{ $salon->images->first()->image_url }}" alt="{{ $salon->images->first()->alt_text ?? $salon->name }}"
                                    class="w-full h-96 object-contain bg-base-300" />
                            </div>
                        @endif
                    </div>
                </div>
            @endif
<div class="bg-base-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-base-content">
                    @if ($salon->description)
                        <p class="text-sm leading-relaxed">
                            {{ $salon->description }}
                        </p>
                    @else
                        <p class="text-sm text-base-content/70">
                            {{ __('Ovaj salon još uvek nema detaljan opis.') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-base-100 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-base-content">
                                {{ __('Usluge') }}
                            </h3>

                            @if ($salon->services->isEmpty())
                                <p class="mt-3 text-sm text-base-content/80">
                                    {{ __('Salon još uvek nije definisao usluge.') }}
                                </p>
                            @else
                                <div class="mt-4 space-y-2">
                                    @foreach ($salon->services as $service)
                                        <div class="flex items-center justify-between p-3 rounded-xl border border-base-200 hover:border-primary/30 hover:bg-base-200/50 transition-all group">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2">
                                                    <h4 class="font-bold text-base-content group-hover:text-primary transition-colors">
                                                        {{ $service->name }}
                                                    </h4>
                                                    @if($service->is_promoted && $service->discount_price)
                                                        <span class="badge badge-accent badge-sm uppercase font-bold tracking-tighter">{{ __('Promocija') }}</span>
                                                    @endif
                                                </div>
                                                <div class="flex items-center gap-3 mt-1">
                                                    <span class="text-xs text-base-content/60 flex items-center gap-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                        </svg>
                                                        {{ $service->duration_minutes }} min
                                                    </span>
                                                    @if($service->description)
                                                        <span class="text-[10px] text-base-content/40 italic truncate max-w-[200px]">
                                                            {{ $service->description }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="flex items-center gap-4">
                                                <div class="text-right">
                                                    @if($service->is_promoted && $service->discount_price)
                                                        <div class="flex flex-col">
                                                            <span class="text-[10px] text-base-content/40 line-through leading-none">{{ number_format($service->price, 0) }} RSD</span>
                                                            <span class="font-bold text-accent italic">{{ number_format($service->discount_price, 0) }} RSD</span>
                                                        </div>
                                                    @else
                                                        <span class="font-bold text-primary">{{ number_format($service->price, 0) }} RSD</span>
                                                    @endif
                                                </div>

                                                @auth
                                                    @if(auth()->user()->isClient())
                                                        <button onclick="booking_modal_{{ $service->id }}.showModal()" class="btn btn-primary btn-sm rounded-lg shadow-sm">
                                                            {{ __('Zakaži') }}
                                                        </button>
                                                        
                                                        <!-- Booking Modal -->
                                                        <dialog id="booking_modal_{{ $service->id }}" class="modal modal-bottom sm:modal-middle">
                                                            <div class="modal-box bg-base-100 border border-base-200 p-0 overflow-y-auto max-h-[90vh]">
                                                                <div class="bg-primary/5 p-6 border-b border-base-200">
                                                                    <h3 class="font-bold text-lg">{{ __('Zakazivanje:') }} {{ $service->name }}</h3>
                                                                    <p class="text-sm opacity-70">{{ $salon->name }} • {{ $service->duration_minutes }} min • {{ number_format($service->discount_price ?? $service->price, 0) }} RSD</p>
                                                                </div>
                                                                
                                                                <form method="POST" action="{{ route('salons.appointments.store', [$salon, $service]) }}" class="p-6 space-y-6">
                                                                    @csrf
                                                                    
                                                                    <div>
                                                                        <label class="label pt-0">
                                                                            <span class="label-text font-bold">{{ __('1. Odaberite datum') }}</span>
                                                                        </label>
                                                                        <input type="date" name="date" id="date-{{ $service->id }}" 
                                                                               class="input input-bordered w-full h-12 text-lg focus:input-primary" 
                                                                               min="{{ now()->format('Y-m-d') }}" required>
                                                                    </div>

                                                                    <div>
                                                                        <label class="label">
                                                                            <span class="label-text font-bold">{{ __('2. Odaberite vreme') }}</span>
                                                                        </label>
                                                                        <div id="slots-container-{{ $service->id }}" class="grid grid-cols-4 gap-2 min-h-[100px] content-start">
                                                                            <div class="col-span-4 py-8 text-center bg-base-200/50 rounded-xl border border-dashed border-base-300">
                                                                                <p class="text-xs opacity-50">{{ __('Prvo odaberite datum da vidite slobodne termine') }}</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <input type="hidden" name="scheduled_at" id="scheduled_at-{{ $service->id }}" required>
                                                                    
                                                                    <div id="selected-time-box-{{ $service->id }}" class="hidden animate-in fade-in zoom-in duration-300">
                                                                        <div class="alert alert-success bg-success/10 border-success/20 text-success py-3 flex items-center gap-3">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                                            </svg>
                                                                            <span class="text-sm font-bold uppercase tracking-wider">
                                                                                {{ __('Odabrano:') }} <span id="selected-time-text-{{ $service->id }}" class="underline decoration-2 underline-offset-4"></span>
                                                                            </span>
                                                                        </div>
                                                                    </div>

                                                                    <div>
                                                                        <label class="label">
                                                                            <span class="label-text font-bold">{{ __('3. Napomena (opciono)') }}</span>
                                                                        </label>
                                                                        <textarea name="note" rows="2" class="textarea textarea-bordered w-full focus:textarea-primary" placeholder="{{ __('Imate li neku posebnu želju?') }}">{{ old('note') }}</textarea>
                                                                    </div>
                                                                    
                                                                    <div class="modal-action mt-8 border-t border-base-200 pt-6">
                                                                        <button type="button" onclick="booking_modal_{{ $service->id }}.close()" class="btn btn-ghost">{{ __('Otkaži') }}</button>
                                                                        <button type="submit" class="btn btn-primary px-8" id="submit-{{ $service->id }}" disabled>
                                                                            {{ __('Potvrdi Rezervaciju') }}
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <form method="dialog" class="modal-backdrop">
                                                                <button>close</button>
                                                            </form>
                                                        </dialog>

                                                        <script>
                                                            (function() {
                                                                const dateInput = document.getElementById('date-{{ $service->id }}');
                                                                const slotsContainer = document.getElementById('slots-container-{{ $service->id }}');
                                                                const scheduledAtInput = document.getElementById('scheduled_at-{{ $service->id }}');
                                                                const selectedTimeBox = document.getElementById('selected-time-box-{{ $service->id }}');
                                                                const selectedTimeText = document.getElementById('selected-time-text-{{ $service->id }}');
                                                                const submitBtn = document.getElementById('submit-{{ $service->id }}');
                                                                
                                                                const salonId = {{ $salon->id }};
                                                                const duration = {{ $service->duration_minutes }};

                                                                dateInput.addEventListener('change', async function() {
                                                                    const date = this.value;
                                                                    
                                                                    if (!date) {
                                                                        slotsContainer.innerHTML = '<div class="col-span-4 py-8 text-center bg-base-200/50 rounded-xl border border-dashed border-base-300"><p class="text-xs opacity-50">{{ __('Prvo odaberite datum') }}</p></div>';
                                                                        selectedTimeBox.classList.add('hidden');
                                                                        submitBtn.disabled = true;
                                                                        return;
                                                                    }

                                                                    slotsContainer.innerHTML = '<div class="col-span-4 py-8 flex flex-col items-center gap-2"><span class="loading loading-spinner text-primary"></span><p class="text-xs opacity-50 uppercase tracking-widest">{{ __('Učitavanje...') }}</p></div>';

                                                                    try {
                                                                        const response = await fetch(`/api/salon/${salonId}/available-slots?date=${date}&duration=${duration}`);
                                                                        const slots = await response.json();
                                                                        
                                                                        slotsContainer.innerHTML = '';
                                                                        
                                                                        if (!slots || slots.length === 0) {
                                                                            slotsContainer.innerHTML = '<div class="col-span-4 alert alert-warning py-3 text-xs flex justify-center">{{ __('Nema slobodnih termina za ovaj dan') }}</div>';
                                                                            selectedTimeBox.classList.add('hidden');
                                                                            submitBtn.disabled = true;
                                                                            return;
                                                                        }
                                                                        
                                                                        slots.forEach(slot => {
                                                                            const btn = document.createElement('button');
                                                                            btn.type = 'button';
                                                                            btn.innerHTML = `<span class="text-sm font-bold">${slot.time}</span>`;
                                                                            
                                                                            if (slot.available) {
                                                                                btn.className = 'btn btn-outline btn-success btn-sm h-10 rounded-lg group hover:scale-105 transition-transform';
                                                                                btn.addEventListener('click', function(e) {
                                                                                    e.preventDefault();
                                                                                    scheduledAtInput.value = slot.datetime;
                                                                                    selectedTimeText.textContent = `${date} u ${slot.time}h`;
                                                                                    selectedTimeBox.classList.remove('hidden');
                                                                                    submitBtn.disabled = false;
                                                                                    
                                                                                    slotsContainer.querySelectorAll('button').forEach(b => {
                                                                                        b.classList.remove('btn-active', 'shadow-lg');
                                                                                        b.classList.add('btn-outline');
                                                                                    });
                                                                                    btn.classList.add('btn-active', 'shadow-lg');
                                                                                    btn.classList.remove('btn-outline');
                                                                                });
                                                                            } else {
                                                                                btn.className = 'btn btn-disabled btn-sm h-10 rounded-lg opacity-30';
                                                                                btn.disabled = true;
                                                                            }
                                                                            
                                                                            slotsContainer.appendChild(btn);
                                                                        });
                                                                    } catch (error) {
                                                                        slotsContainer.innerHTML = '<div class="col-span-4 alert alert-error py-3 text-xs flex justify-center">{{ __('Greška pri učitavanju termina') }}</div>';
                                                                    }
                                                                });
                                                            })();
                                                        </script>
                                                    @endif
                                                @else
                                                    <a href="{{ route('login') }}" class="btn btn-ghost btn-xs text-[10px] opacity-60">
                                                        {{ __('Prijavi se za rezervaciju') }}
                                                    </a>
                                                @endauth
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-base-100 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-base-content">
                                {{ __('Recenzije') }}
                            </h3>

                            @if ($salon->reviews->isEmpty())
                                <p class="mt-3 text-sm text-base-content/80">
                                    {{ __('Ovaj salon još uvek nema recenzije.') }}
                                </p>
                            @else
                                <ul class="mt-4 space-y-4">
                                    @foreach ($salon->reviews as $review)
                                        <li class="border border-base-200 rounded-lg p-4">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm font-medium text-base-content">
                                                    {{ $review->client->name ?? __('Klijent') }}
                                                </p>
                                                <p class="text-sm text-base-content">
                                                    {{ $review->rating }} ★
                                                </p>
                                            </div>
                                            @if ($review->comment)
                                                <p class="mt-2 text-sm text-base-content/80">
                                                    {{ $review->comment }}
                                                </p>
                                            @endif
                                            <p class="mt-1 text-xs text-base-content/70">
                                                {{ $review->created_at->format('d.m.Y H:i') }}
                                            </p>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-base-100 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-sm text-base-content">
                            <h3 class="text-lg font-semibold">
                                {{ __('Informacije o salonu') }}
                            </h3>
                            <dl class="mt-4 space-y-2">
                                <div>
                                    <dt class="text-xs text-base-content/70 uppercase tracking-wide">
                                        {{ __('Lokacija') }}
                                    </dt>
                                    <dd>{{ $salon->location }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-base-content/70 uppercase tracking-wide">
                                        {{ __('Prosečna ocena') }}
                                    </dt>
                                    <dd>{{ number_format($salon->average_rating, 1) }} / 5</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-base-content/70 uppercase tracking-wide">
                                        {{ __('Broj recenzija') }}
                                    </dt>
                                    <dd>{{ $salon->reviews_count }}</dd>
                                </div>
                            </dl>
                            @auth
                                @if(auth()->user()->isClient())
                                    <div class="mt-6 pt-6 border-t border-base-200">
                                        <a href="{{ route('messages.show', $salon->owner) }}" class="btn btn-outline btn-primary btn-block gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                            </svg>
                                            {{ __('Pošalji poruku') }}
                                        </a>
                                        <p class="text-[10px] text-center mt-2 opacity-50 uppercase tracking-tighter">
                                            {{ __('Direktan kontakt sa vlasnikom salona') }}
                                        </p>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

