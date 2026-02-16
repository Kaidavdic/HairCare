<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ __('Upravljanje korisnicima') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-base-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-base-content">
                    @if (session('status'))
                        <div class="alert alert-success mb-4">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-error mb-4">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-error mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4">
                        <div class="flex gap-2">
                            <input type="text" name="search" placeholder="Pretraži korisnike..." value="{{ request('search') }}" class="input input-bordered w-full max-w-xs" />
                            <button type="submit" class="btn btn-ghost">{{ __('Pretraži') }}</button>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th>{{ __('Ime') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Uloga') }}</th>
                                    <th>{{ __('Rejting') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Akcije') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="font-bold">
                                            <a href="{{ route('profile.show', $user) }}" class="hover:text-primary hover:underline">
                                                {{ $user->name }}
                                            </a>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->role }}</td>
                                        <td>
                                            @if($user->isSalonOwner() && $user->salon)
                                                @if($user->salon->reviews_count > 0)
                                                    <div class="flex flex-col">
                                                        <div class="flex items-center gap-1">
                                                            <span class="font-bold text-primary">{{ number_format($user->salon->average_rating, 1) }} ★</span>
                                                            <span class="text-[10px] opacity-40">({{ $user->salon->reviews_count }})</span>
                                                        </div>
                                                        <span class="text-[9px] uppercase opacity-40 leading-none mt-1">{{ __('Salon') }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-xs opacity-30 italic">{{ __('Nema ocena salona') }}</span>
                                                @endif
                                            @elseif($user->reviews_count > 0)
                                                <div class="flex flex-col">
                                                    <div class="flex items-center gap-1">
                                                        <span class="font-bold text-warning">{{ number_format($user->average_rating, 1) }} ★</span>
                                                        <span class="text-[10px] opacity-40">({{ $user->reviews_count }})</span>
                                                    </div>
                                                    <span class="text-[9px] uppercase opacity-40 leading-none mt-1">{{ __('Klijent') }}</span>
                                                </div>
                                            @else
                                                <span class="text-xs opacity-30 italic">{{ __('Nema ocena') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($user->status === 'active')
                                                <div class="badge badge-success">{{ __('Aktivan') }}</div>
                                            @elseif ($user->status === 'banned')
                                                <div class="badge badge-error">{{ __('Banovan') }}</div>
                                            @else
                                                <div class="badge badge-warning">{{ $user->status }}</div>
                                            @endif
                                        </td>
                                        <td class="flex gap-2">
                                            @if($user->status !== 'banned')
                                                <a href="{{ route('messages.show', $user->id) }}" class="btn btn-ghost btn-xs text-primary">{{ __('Poruka') }}</a>
                                                <button class="btn btn-warning btn-xs" onclick="document.getElementById('warn-modal-{{ $user->id }}').showModal()">{{ __('Opomena') }}</button>
                                                <form action="{{ route('admin.users.ban', $user) }}" method="POST" onsubmit="return confirm('Da li ste sigurni da želite da banujete korisnika?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-error btn-xs">{{ __('Banuj') }}</button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.users.activate', $user) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-ghost btn-xs text-success">{{ __('Aktiviraj') }}</button>
                                                </form>
                                            @endif

                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Da li ste sigurni da želite da obrišete ovog korisnika? Ova akcija je nepovratna.') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-ghost btn-xs text-error">{{ __('Obriši') }}</button>
                                            </form>

                                            <!-- Warning Modal -->
                                            <dialog id="warn-modal-{{ $user->id }}" class="modal">
                                                <div class="modal-box">
                                                    <h3 class="font-bold text-lg">Pošalji upozorenje korisniku {{ $user->name }}</h3>
                                                    <form action="{{ route('admin.users.warn', $user) }}" method="POST">
                                                        @csrf
                                                        <div class="form-control w-full mt-4">
                                                            <label class="label"><span class="label-text">Poruka upozorenja</span></label>
                                                            <textarea name="message" class="textarea textarea-bordered h-24" placeholder="Vaš nalog krši pravila... (ova poruka će biti vidljiva korisniku u njegovim obaveštenjima)" required minlength="3"></textarea>
                                                        </div>
                                                        <div class="modal-action">
                                                            <button type="submit" class="btn btn-error">Pošalji</button>
                                                            <button type="button" class="btn" onclick="document.getElementById('warn-modal-{{ $user->id }}').close()">Zatvori</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <form method="dialog" class="modal-backdrop">
                                                    <button>close</button>
                                                </form>
                                            </dialog>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
