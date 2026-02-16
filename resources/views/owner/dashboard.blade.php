<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ __('Kontrolna tabla salona') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(!$salon)
                <div class="alert alert-warning shadow-lg mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    <span>{{ __('Još uvek niste kreirali profil salona.') }} 
                        <a href="{{ route('owner.salon.edit') }}" class="link font-bold">{{ __('Kreirajte profil ovde') }}</a>
                    </span>
                </div>
            @endif

            <!-- Statistics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="stats shadow bg-base-100 border border-base-200">
                    <div class="stat">
                        <div class="stat-title text-base-content/70">{{ __('Ukupan prihod') }}</div>
                        <div class="stat-value text-primary">{{ number_format($totalRevenue, 2) }}</div>
                        <div class="stat-desc">RSD</div>
                    </div>
                </div>

                <div class="stats shadow bg-base-100 border border-base-200">
                    <div class="stat">
                        <div class="stat-title text-base-content/70">{{ __('Prihod (ovaj mesec)') }}</div>
                        <div class="stat-value text-secondary">{{ number_format($monthlyRevenue, 2) }}</div>
                        <div class="stat-desc">RSD</div>
                    </div>
                </div>

                <div class="stats shadow bg-base-100 border border-base-200">
                    <div class="stat">
                        <div class="stat-title text-base-content/70">{{ __('Termini (ovaj mesec)') }}</div>
                        <div class="stat-value text-accent">{{ $monthlyAppointments }}</div>
                        <div class="stat-desc text-success">↗︎ {{ $monthlyTrend }}%</div>
                    </div>
                </div>

                <div class="stats shadow bg-base-100 border border-base-200">
                    <div class="stat">
                        <div class="stat-title text-base-content/70">{{ __('Prosečna ocena') }}</div>
                        <div class="stat-value text-warning">{{ number_format($averageRating, 1) }}</div>
                        <div class="stat-desc">{{ $totalReviews }} {{ __('recenzija') }}</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Appointments -->
                <div class="card bg-base-100 shadow border border-base-200">
                    <div class="card-body">
                        <h3 class="card-title mb-4">{{ __('Predstojeći termini') }}</h3>
                        <div class="overflow-x-auto">
                            <table class="table w-full">
                                <thead>
                                    <tr>
                                        <th>{{ __('Klijent') }}</th>
                                        <th>{{ __('Usluga') }}</th>
                                        <th>{{ __('Vreme') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentAppointments as $app)
                                        <tr>
                                            <td>{{ $app->client->name }}</td>
                                            <td>{{ $app->service->name }}</td>
                                            <td>{{ $app->scheduled_at->format('d.m. H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4">{{ __('Nema predstojećih termina.') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-actions justify-end mt-4">
                            <a href="{{ route('owner.appointments.index') }}" class="btn btn-ghost btn-sm">{{ __('Vidi sve') }}</a>
                        </div>
                    </div>
                </div>

                <!-- Popular Services -->
                <div class="card bg-base-100 shadow border border-base-200">
                    <div class="card-body">
                        <h3 class="card-title mb-4">{{ __('Najpopularnije usluge') }}</h3>
                        <div class="space-y-4">
                            @forelse($popularServices as $service)
                                <div class="flex items-center justify-between">
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ $service->name }}</span>
                                        <span class="text-xs text-base-content/50">{{ $service->appointments_count }} {{ __('zakazivanja') }}</span>
                                    </div>
                                    <div class="badge badge-outline">{{ number_format($service->price, 2) }} RSD</div>
                                </div>
                                @if(!$loop->last) <div class="divider my-0"></div> @endif
                            @empty
                                <p class="text-center py-4 text-base-content/50">{{ __('Još uvek nemate zakazanih usluga.') }}</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Statistical Analysis Section -->
            <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Rating Analysis -->
                <div class="card bg-base-100 shadow border border-base-200">
                    <div class="card-body">
                        <h3 class="card-title text-sm uppercase tracking-widest text-base-content/50 mb-4">{{ __('Analiza ocena salona') }}</h3>
                        
                        <div class="space-y-3">
                            @for($i = 5; $i >= 1; $i--)
                                @php
                                    $count = $ratingDistribution[$i] ?? 0;
                                    $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                                @endphp
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center gap-1 w-12 shrink-0">
                                        <span class="text-sm font-bold">{{ $i }}</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-warning fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    </div>
                                    <div class="flex-1">
                                        <progress class="progress progress-warning w-full" value="{{ $percentage }}" max="100"></progress>
                                    </div>
                                    <div class="w-10 text-right">
                                        <span class="text-xs text-base-content/60">{{ $count }}</span>
                                    </div>
                                </div>
                            @endfor
                        </div>

                        <div class="mt-6 p-4 bg-base-200/50 rounded-xl border border-base-200">
                            <div class="text-xs uppercase font-bold text-base-content/40 mb-2">{{ __('Mesečni trend') }}</div>
                            <div class="flex items-center justify-between">
                                @php
                                    $currentMonth = date('Y-m');
                                    $lastMonth = date('Y-m', strtotime('-1 month'));
                                    $currentAvg = $monthlyTrends[$currentMonth] ?? 0;
                                    $lastAvg = $monthlyTrends[$lastMonth] ?? 0;
                                    $diff = $currentAvg - $lastAvg;
                                @endphp
                                <div class="text-2xl font-black">
                                    {{ number_format($currentAvg, 1) }}
                                    <span class="text-xs font-normal opacity-50">/ 5.0</span>
                                </div>
                                @if($lastAvg > 0)
                                    <div @class([
                                        'text-xs font-bold px-2 py-1 rounded-lg',
                                        'bg-success/20 text-success' => $diff >= 0,
                                        'bg-error/20 text-error' => $diff < 0,
                                    ])>
                                        {{ $diff >= 0 ? '+' : '' }}{{ number_format($diff, 1) }} {{ __('ovaj mesec') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Service Performance -->
                <div class="card bg-base-100 shadow border border-base-200 lg:col-span-2">
                    <div class="card-body">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="card-title text-sm uppercase tracking-widest text-base-content/50">{{ __('Kvalitet usluga (Najbolje ocenjene)') }}</h3>
                            <div class="badge badge-primary badge-sm">{{ __('Bazirano na recenzijama') }}</div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($servicePerformance as $service)
                                <div class="p-4 bg-base-200/30 rounded-2xl border border-base-200 flex items-center justify-between group hover:bg-base-200/50 transition-all">
                                    <div>
                                        <div class="font-bold text-base-content">{{ $service->name }}</div>
                                        <div class="text-[10px] text-base-content/50 uppercase font-black">{{ $service->reviews_count }} {{ __('recenzija') }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="flex items-center gap-1 text-warning">
                                            <span class="text-lg font-black">{{ number_format($service->average_rating, 1) }}</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        </div>
                                        <div class="w-24 h-1 bg-base-300 rounded-full mt-1 overflow-hidden">
                                            <div class="h-full bg-warning" style="width: {{ ($service->average_rating / 5) * 100 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-2 py-8 text-center text-base-content/40 italic">
                                    {{ __('Nema dovoljno podataka za analizu usluga.') }}
                                </div>
                            @endforelse
                        </div>
                        
                        @if($servicePerformance->count() > 0)
                            <div class="mt-6 flex items-center gap-3 p-3 bg-primary/5 rounded-xl border border-primary/10">
                                <div class="bg-primary/20 p-2 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <p class="text-[11px] text-base-content/70">
                                    <strong>{{ __('Savet:') }}</strong> {{ __('Usluge sa nižom ocenom zahtevaju dodatnu pažnju ili korekciju protokola rada kako biste poboljšali zadovoljstvo klijenta.') }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
