<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ __('Admin kontrolna tabla') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="stats shadow bg-base-100 border border-base-200">
                    <div class="stat">
                        <div class="stat-title text-base-content/70">{{ __('Ukupno korisnika') }}</div>
                        <div class="stat-value text-primary">{{ $totalUsers }}</div>
                        <div class="stat-desc">{{ __('Klijenti i vlasnici') }}</div>
                    </div>
                </div>

                <div class="stats shadow bg-base-100 border border-base-200">
                    <div class="stat">
                        <div class="stat-title text-base-content/70">{{ __('Saloni (na čekanju)') }}</div>
                        <div class="stat-value text-secondary">{{ $totalSalons }} ({{ $pendingSalons }})</div>
                        <div class="stat-desc text-warning">{{ $pendingSalons }} {{ __('novih zahteva') }}</div>
                    </div>
                </div>

                <div class="stats shadow bg-base-100 border border-base-200">
                    <div class="stat">
                        <div class="stat-title text-base-content/70">{{ __('Ukupno zakazivanja') }}</div>
                        <div class="stat-value text-accent">{{ $totalAppointments }}</div>
                        <div class="stat-desc text-success">{{ $completedAppointments }} {{ __('završenih') }}</div>
                    </div>
                </div>

                <div class="stats shadow bg-base-100 border border-base-200">
                    <div class="stat">
                        <div class="stat-title text-base-content/70">{{ __('Ukupan promet') }}</div>
                        <div class="stat-value text-warning">{{ number_format($totalRevenue, 0) }} RSD</div>
                        <div class="stat-desc">{{ __('Preko celog sistema') }}</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Registrations -->
                <div class="card bg-base-100 shadow border border-base-200">
                    <div class="card-body">
                        <h3 class="card-title mb-4">{{ __('Poslednje registracije') }}</h3>
                        <div class="overflow-x-auto">
                            <table class="table w-full">
                                <thead>
                                    <tr>
                                        <th>{{ __('Korisnik') }}</th>
                                        <th>{{ __('Uloga') }}</th>
                                        <th>{{ __('Datum') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentUsers as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>
                                                <div class="badge badge-outline badge-sm">
                                                    {{ $user->role === 'salon_owner' ? 'Vlasnik' : ($user->role === 'admin' ? 'Admin' : 'Klijent') }}
                                                </div>
                                            </td>
                                            <td>{{ $user->created_at->format('d.m.Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Top Salons -->
                <div class="card bg-base-100 shadow border border-base-200">
                    <div class="card-body">
                        <h3 class="card-title mb-4">{{ __('Najaktivniji saloni') }}</h3>
                        <div class="space-y-4">
                            @foreach($topSalons as $salon)
                                <div class="flex items-center justify-between">
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ $salon->name }}</span>
                                        <span class="text-xs text-base-content/50">{{ $salon->appointments_count }} {{ __('zakazivanja') }}</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span class="text-xs font-semibold">{{ number_format($salon->average_rating, 1) }}</span>
                                        <div class="rating rating-xs">
                                            <input type="radio" class="mask mask-star-2 bg-orange-400" disabled checked />
                                        </div>
                                    </div>
                                </div>
                                @if(!$loop->last) <div class="divider my-0"></div> @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
