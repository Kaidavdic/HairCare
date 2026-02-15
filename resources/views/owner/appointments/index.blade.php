<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ __('Termini u mom salonu') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-base-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-base-content">
                    <p class="mb-4 text-sm text-base-content/80">
                        {{ $salon->name }} — {{ $salon->location }}
                    </p>

                    @if (session('status'))
                        <div class="mb-4 text-sm text-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($appointments->isEmpty())
                        <p class="text-sm text-base-content/80">
                            {{ __('Trenutno nema zakazanih termina.') }}
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="border-b border-base-200">
                                        <th class="py-2 px-4 text-left text-base-content/70">
                                            {{ __('Klijent') }}
                                        </th>
                                        <th class="py-2 px-4 text-left text-base-content/70">
                                            {{ __('Usluga') }}
                                        </th>
                                        <th class="py-2 px-4 text-left text-base-content/70">
                                            {{ __('Termin') }}
                                        </th>
                                        <th class="py-2 px-4 text-left text-base-content/70">
                                            {{ __('Status') }}
                                        </th>
                                        <th class="py-2 px-4 text-right text-base-content/70">
                                            {{ __('Akcije') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($appointments as $appointment)
                                        <tr class="border-b border-base-200">
                                            <td class="py-3 px-4">
                                                <div class="font-medium flex items-center gap-2">
                                                    {{ $appointment->client->name ?? __('Klijent') }}
                                                    @if(isset($appointment->client->average_rating) && $appointment->client->reviews_count > 0)
                                                        <span class="inline-flex items-center text-xs font-semibold text-orange-500">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 fill-current mb-0.5" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                            </svg>
                                                            {{ number_format($appointment->client->average_rating, 1) }}
                                                        </span>
                                                    @endif
                                                </div>
                                                @if ($appointment->note)
                                                    <div class="mt-1 text-xs text-base-content/70">
                                                        {{ __('Napomena:') }} {{ $appointment->note }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4">
                                                {{ $appointment->service->name ?? '' }}
                                            </td>
                                            <td class="py-3 px-4">
                                                <div>
                                                    {{ $appointment->scheduled_at->format('d.m.Y H:i') }}
                                                </div>
                                                <div class="text-xs text-base-content/70">
                                                    {{ __('do') }} {{ $appointment->ends_at->format('H:i') }}
                                                </div>
                                            </td>
                                            <td class="py-3 px-4">
                                                @php
                                                    $status = $appointment->status;
                                                    $statusLabels = [
                                                        'pending' => __('Na čekanju'),
                                                        'confirmed' => __('Potvrđen'),
                                                        'rejected' => __('Odbijen'),
                                                        'cancelled' => __('Otkazan'),
                                                        'completed' => __('Završen'),
                                                    ];
                                                @endphp
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs
                                                            @class([
                                                                'bg-yellow-100 text-yellow-800' => $status === 'pending',
                                                                'bg-success/20 text-success' => $status === 'confirmed' || $status === 'completed',
                                                                'bg-red-100 text-red-800' => $status === 'rejected' || $status === 'cancelled',
                                                            ])">
                                                    {{ $statusLabels[$status] ?? $status }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-4 text-right">
                                                <div class="inline-flex flex-wrap justify-end gap-1">
                                                    @if ($appointment->status === 'pending')
                                                        <form method="POST" action="{{ route('owner.appointments.update-status', $appointment) }}">
                                                            @csrf
                                                            <input type="hidden" name="status" value="confirmed">
                                                            <x-primary-button class="text-xs py-1 px-3">
                                                                {{ __('Potvrdi') }}
                                                            </x-primary-button>
                                                        </form>
                                                    @endif

                                                    @if (in_array($appointment->status, ['pending', 'confirmed'], true))
                                                        <form method="POST" action="{{ route('owner.appointments.update-status', $appointment) }}">
                                                            @csrf
                                                            <input type="hidden" name="status" value="rejected">
                                                            <x-secondary-button class="text-xs py-1 px-3">
                                                                {{ __('Odbij') }}
                                                            </x-secondary-button>
                                                        </form>
                                                    @endif

                                                    @if ($appointment->status === 'confirmed')
                                                        <form method="POST" action="{{ route('owner.appointments.update-status', $appointment) }}">
                                                            @csrf
                                                            <input type="hidden" name="status" value="completed">
                                                            <x-primary-button class="text-xs py-1 px-3 !bg-success !border-success hover:!bg-success-focus">
                                                                {{ __('Označi kao završen') }}
                                                            </x-primary-button>
                                                        </form>
                                                    @endif
                                                </div>

                                                @if ($appointment->status === 'completed' && !$appointment->reviews()->where('type', 'user')->exists())
                                                    <div class="mt-2">
                                                        <button class="btn btn-xs btn-outline btn-warning" onclick="document.getElementById('rateUser{{ $appointment->id }}').showModal()">
                                                            {{ __('Ocenite klijenta') }}
                                                        </button>
                                                    </div>
                                                    <dialog id="rateUser{{ $appointment->id }}" class="modal text-left">
                                                        <div class="modal-box">
                                                            <h3 class="font-bold text-lg">Ocenite klijenta: {{ $appointment->client->name }}</h3>
                                                            <form method="POST" action="{{ route('appointments.reviews.store', $appointment) }}">
                                                                @csrf
                                                                <input type="hidden" name="type" value="user">
                                                                <div class="form-control w-full my-4">
                                                                    <label class="label"><span class="label-text">Ocena</span></label>
                                                                    <div class="rating">
                                                                        <input type="radio" name="rating" class="mask mask-star-2 bg-orange-400" value="1" />
                                                                        <input type="radio" name="rating" class="mask mask-star-2 bg-orange-400" value="2" />
                                                                        <input type="radio" name="rating" class="mask mask-star-2 bg-orange-400" value="3" />
                                                                        <input type="radio" name="rating" class="mask mask-star-2 bg-orange-400" value="4" />
                                                                        <input type="radio" name="rating" class="mask mask-star-2 bg-orange-400" value="5" checked />
                                                                    </div>
                                                                </div>
                                                                <div class="form-control mb-4">
                                                                    <label class="label"><span class="label-text">Komentar</span></label>
                                                                    <textarea class="textarea textarea-bordered h-24" name="comment"></textarea>
                                                                </div>
                                                                <div class="modal-action">
                                                                    <button class="btn btn-primary">Pošalji</button>
                                                                    <button type="button" class="btn" onclick="document.getElementById('rateUser{{ $appointment->id }}').close()">Zatvori</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <form method="dialog" class="modal-backdrop">
                                                            <button>close</button>
                                                        </form>
                                                    </dialog>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $appointments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>