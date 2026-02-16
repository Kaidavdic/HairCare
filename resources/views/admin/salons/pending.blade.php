<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ __('Upravljanje salonima') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-base-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-base-content">
                    @if (session('status'))
                        <div class="mb-4 text-sm text-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($salons->isEmpty())
                        <p class="text-sm text-base-content/80">
                            {{ __('Trenutno nema registrovanih salona.') }}
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="table table-zebra w-full">
                                <thead>
                                    <tr>
                                        <th>{{ __('Salon') }}</th>
                                        <th>{{ __('Vlasnik') }}</th>
                                        <th>{{ __('Lokacija / Tip') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th class="text-right">{{ __('Akcije') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($salons as $salon)
                                        <tr>
                                            <td>
                                                <div class="font-semibold">{{ $salon->name }}</div>
                                                @if ($salon->description)
                                                    <div class="text-xs text-base-content/60 truncate max-w-xs">{{ Str::limit($salon->description, 50) }}</div>
                                                @endif
                                            </td>
                                            <td>
                                                <div>{{ $salon->owner->name ?? '-' }}</div>
                                                <div class="text-xs text-base-content/60">{{ $salon->owner->email ?? '' }}</div>
                                            </td>
                                            <td>
                                                <div>{{ $salon->location }}</div>
                                                <div class="text-xs">
                                                    @switch($salon->type)
                                                        @case('male') <span class="badge badge-sm">{{ __('Muški') }}</span> @break
                                                        @case('female') <span class="badge badge-sm">{{ __('Ženski') }}</span> @break
                                                        @case('unisex') <span class="badge badge-sm">{{ __('Unisex') }}</span> @break
                                                    @endswitch
                                                </div>
                                            </td>
                                            <td>
                                                @switch($salon->status)
                                                    @case('pending')
                                                        <span class="badge badge-warning">{{ __('Na čekanju') }}</span>
                                                        @break
                                                    @case('approved')
                                                        <span class="badge badge-success">{{ __('Odobren') }}</span>
                                                        @break
                                                    @case('rejected')
                                                        <span class="badge badge-error">{{ __('Odbijen') }}</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                <div class="flex items-center justify-end gap-2">
                                                    @if($salon->status === 'pending')
                                                        <form method="POST" action="{{ route('admin.salons.approve', $salon) }}" class="inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-xs">{{ __('Odobri') }}</button>
                                                        </form>
                                                        <form method="POST" action="{{ route('admin.salons.reject', $salon) }}" class="inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-error btn-xs">{{ __('Odbij') }}</button>
                                                        </form>
                                                    @endif
                                                    <form method="POST" action="{{ route('admin.salons.destroy', $salon) }}" onsubmit="return confirm('{{ __('Da li ste sigurni da želite da trajno obrišete ovaj salon?') }}')" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-ghost btn-xs text-error">{{ __('Obriši') }}</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $salons->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

