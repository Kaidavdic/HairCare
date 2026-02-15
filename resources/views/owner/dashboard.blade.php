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

        </div>
    </div>
</x-app-layout>
