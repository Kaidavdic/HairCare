<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ __('Upravljanje recenzijama') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-base-100 overflow-hidden shadow-sm sm:rounded-lg border border-base-200">
                <div class="p-6 text-base-content">
                    @if (session('status'))
                        <div class="alert alert-success shadow-lg mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th>{{ __('Datum') }}</th>
                                    <th>{{ __('Klijent') }}</th>
                                    <th>{{ __('Salon & Usluga') }}</th>
                                    <th>{{ __('Ocene') }}</th>
                                    <th>{{ __('Komentar') }}</th>
                                    <th class="text-right">{{ __('Moderacija') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reviews as $review)
                                    <tr class="hover:bg-base-200/50 transition-colors">
                                        <td class="text-xs opacity-60">
                                            {{ $review->created_at->format('d.m.Y H:i') }}
                                        </td>
                                        <td>
                                            <div class="flex flex-col">
                                                <span class="font-bold">{{ $review->client->name }}</span>
                                                <span class="text-[10px] opacity-50">{{ $review->client->email }}</span>
                                                <div class="mt-1 flex gap-1">
                                                    <button onclick="openWarnModal({{ $review->client->id }}, '{{ $review->client->name }}')" class="btn btn-warning btn-xs btn-outline">Opomena</button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex flex-col">
                                                <span class="font-medium text-primary">{{ $review->salon->name }}</span>
                                                <span class="text-xs opacity-60">{{ $review->service?->name ?? __('Obrisana usluga') }}</span>
                                                <div class="mt-1">
                                                    <button onclick="openWarnModal({{ $review->salon?->owner_id }}, '{{ $review->salon?->name }} (Vlasnik)')" class="btn btn-warning btn-xs btn-outline">Opomena vlasniku</button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex flex-col gap-1">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-[9px] uppercase opacity-40">Salon:</span>
                                                    <span class="font-bold text-warning">{{ $review->salon_rating }} ★</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-[9px] uppercase opacity-40">Usluga:</span>
                                                    <span class="font-bold text-success">{{ $review->service_rating }} ★</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($review->comment)
                                                <div class="max-w-xs text-sm italic opacity-80 truncate" title="{{ $review->comment }}">
                                                    "{{ $review->comment }}"
                                                </div>
                                            @else
                                                <span class="text-xs opacity-30 italic">{{ __('Bez komentara') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <div class="flex justify-end gap-2">
                                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Da li ste sigurni da želite da obrišete ovu recenziju?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-ghost btn-xs text-error" title="Obriši recenziju">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-12 opacity-50 italic">
                                            {{ __('Nema pronađenih recenzija.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $reviews->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dynamic Warning Modal -->
    <dialog id="admin_warn_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg" id="warn_modal_title">Pošalji upozorenje</h3>
            <form id="warn_form" method="POST">
                @csrf
                <div class="form-control w-full mt-4">
                    <label class="label"><span class="label-text">Poruka obaveštenja</span></label>
                    <textarea name="message" class="textarea textarea-bordered h-24" placeholder="Vaš nalog krši pravila korišćenja..."></textarea>
                    <label class="label">
                        <span class="label-text-alt text-base-content/40 italic">Ova poruka će biti direktno vidljiva korisniku.</span>
                    </label>
                </div>
                <div class="modal-action">
                    <button type="submit" class="btn btn-error">Pošalji opomenu</button>
                    <button type="button" class="btn" onclick="admin_warn_modal.close()">Zatvori</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <script>
        function openWarnModal(userId, userName) {
            if (!userId) {
                alert('Greška: Korisnik nije pronađen.');
                return;
            }
            const modal = document.getElementById('admin_warn_modal');
            const title = document.getElementById('warn_modal_title');
            const form = document.getElementById('warn_form');
            
            title.innerText = 'Pošalji upozorenje: ' + userName;
            form.action = '/admin/users/' + userId + '/warn';
            
            modal.showModal();
        }
    </script>
</x-app-layout>
