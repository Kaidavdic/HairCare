<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ __('Moji termini') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-base-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-base-content border-b border-base-200">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div>
                            <h3 class="text-lg font-medium">{{ __('Moji termini') }}</h3>
                            <p class="text-sm text-base-content/70">{{ __('Pregled vaših zakazanih i završenih termina.') }}</p>
                        </div>
                        @if(auth()->user()->reviews_count > 0)
                            <div class="flex items-center gap-3 bg-base-200/50 p-3 rounded-lg border border-base-200">
                                <div class="bg-orange-100 p-2 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-500 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-xs text-base-content/60 uppercase font-bold tracking-wider">{{ __('Vaša ocena') }}</div>
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-2xl font-bold">{{ number_format(auth()->user()->average_rating, 1) }}</span>
                                        <span class="text-xs text-base-content/60">/ 5</span>
                                        <span class="text-xs text-base-content/60 ml-1">({{ auth()->user()->reviews_count }} {{ __('recenzija') }})</span>
                                    </div>
                                    <a href="{{ route('profile.edit') }}#user-reviews" class="text-xs text-primary hover:underline">{{ __('Pogledaj komentare') }}</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="p-6 text-base-content">
                    @if (session('status'))
                        <div class="mb-4 text-sm text-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($appointments->isEmpty())
                        <p class="text-sm text-base-content/80">
                            {{ __('Još uvek nemate zakazane termine.') }}
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="border-b border-base-200">
                                        <th class="py-2 pr-4 text-left text-base-content/70">
                                            {{ __('Salon') }}
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
                                            <td class="py-3 pr-4">
                                                <div class="font-medium">
                                                    {{ $appointment->salon->name ?? __('Salon') }}
                                                </div>
                                                <div class="text-xs text-base-content/70">
                                                    {{ $appointment->salon->location ?? '' }}
                                                </div>
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
                                            <td class="py-3 px-4 text-right space-y-2">
                                                {{-- Rating received from Salon Owner --}}
                                                @if ($appointment->userReview)
                                                    <div class="mb-2 p-2 bg-orange-50 rounded-lg border border-orange-100 text-left">
                                                        <div class="text-[10px] uppercase font-bold text-orange-600 mb-1 leading-none">{{ __('Ocena vlasnika') }}</div>
                                                        <div class="flex items-center gap-1">
                                                            <div class="rating rating-xs">
                                                                @for($i=1; $i<=5; $i++)
                                                                    <input type="radio" name="user-rating-{{$appointment->id}}" class="mask mask-star-2 bg-orange-400" @checked($i <= $appointment->userReview->rating) disabled />
                                                                @endfor
                                                            </div>
                                                            <span class="text-xs font-bold text-orange-700">{{ number_format($appointment->userReview->rating, 0) }}</span>
                                                        </div>
                                                        @if($appointment->userReview->comment)
                                                            <p class="text-[11px] text-base-content/80 mt-1 italic">"{{ $appointment->userReview->comment }}"</p>
                                                        @endif
                                                    </div>
                                                @endif

                                                @if (in_array($appointment->status, ['pending', 'confirmed'], true))
                                                    <form method="POST" action="{{ route('appointments.cancel', $appointment) }}"
                                                        onsubmit="return confirm('{{ __('Da li ste sigurni da želite da otkažete termin?') }}')">
                                                        @csrf
                                                        <x-secondary-button class="text-xs">
                                                            {{ __('Otkaži') }}
                                                        </x-secondary-button>
                                                    </form>
                                                @endif
 
                                                @if ($appointment->status === 'completed' && !$appointment->serviceReview)
                                                    <form method="POST"
                                                        action="{{ route('appointments.reviews.store', $appointment) }}"
                                                        class="space-y-1">
                                                        @csrf
                                                        <input type="hidden" name="type" value="service">
                                                        <select name="rating"
                                                            class="block w-full rounded-md border-base-200 bg-base-100 text-xs text-base-content"
                                                            required>
                                                            <option value="">{{ __('Oceni salon') }}</option>
                                                            @for ($i = 5; $i >= 1; $i--)
                                                                <option value="{{ $i }}">{{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                        <textarea name="comment" rows="2"
                                                            class="block w-full rounded-md border-base-200 bg-base-100 text-xs text-base-content"
                                                            placeholder="{{ __('Komentar za salon (opciono)') }}"></textarea>
                                                        <x-input-error :messages="$errors->get('rating')" class="mt-1" />
                                                        <x-primary-button class="w-full justify-center text-xs">
                                                            {{ __('Pošalji recenziju') }}
                                                        </x-primary-button>
                                                    </form>
                                                @elseif ($appointment->serviceReview)
                                                    <div class="text-xs text-base-content/70 flex items-center justify-end gap-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        {{ __('Ocenili ste salon') }} ({{ $appointment->serviceReview->rating }})
                                                    </div>
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