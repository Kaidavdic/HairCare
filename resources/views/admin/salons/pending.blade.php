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
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="border-b border-base-200">
                                        <th class="py-2 px-4 text-left text-base-content/70">
                                            {{ __('Salon') }}
                                        </th>
                                        <th class="py-2 px-4 text-left text-base-content/70">
                                            {{ __('Vlasnik') }}
                                        </th>
                                        <th class="py-2 px-4 text-left text-base-content/70">
                                            {{ __('Lokacija / Tip') }}
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
                                    @foreach ($salons as $salon)
                                        <tr class="border-b border-base-200 hover:bg-base-200/50 transition-colors">
                                            <td class="py-3 px-4">
                                                <div class="font-medium">
                                                    {{ $salon->name }}
                                                </div>
                                                @if ($salon->description)
                                                    <div class="mt-1 text-xs text-base-content/70 line-clamp-1">
                                                        {{ $salon->description }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4">
                                                <div>{{ $salon->owner->name ?? '-' }}</div>
                                                <div class="text-xs text-base-content/70">{{ $salon->owner->email ?? '' }}</div>
                                            </td>
                                            <td class="py-3 px-4">
                                                <div>{{ $salon->location }}</div>
                                                <div class="text-xs text-secondary-content">
                                                    @switch($salon->type)
                                                        @case('male') {{ __('Muški') }} @break
                                                        @case('female') {{ __('Ženski') }} @break
                                                        @case('unisex') {{ __('Unisex') }} @break
                                                    @endswitch
                                                </div>
                                            </td>
                                            <td class="py-3 px-4">
                                                @switch($salon->status)
                                                    @case('pending')
                                                        <span class="badge badge-warning badge-sm">{{ __('Na čekanju') }}</span>
                                                        @break
                                                    @case('approved')
                                                        <span class="badge badge-success badge-sm text-white">{{ __('Odobren') }}</span>
                                                        @break
                                                    @case('rejected')
                                                        <span class="badge badge-error badge-sm">{{ __('Odbijen') }}</span>
                                                        @break
                                                    @default
                                                        <span class="badge badge-ghost badge-sm">{{ $salon->status }}</span>
                                                @endswitch
                                            </td>
                                            <td class="py-3 px-4 text-right">
                                                <div class="inline-flex items-center gap-2">
                                                    @if($salon->status === 'pending')
                                                        <form method="POST" action="{{ route('admin.salons.approve', $salon) }}">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-xs text-white">
                                                                {{ __('Odobri') }}
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="{{ route('admin.salons.reject', $salon) }}">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline btn-error btn-xs">
                                                                {{ __('Odbij') }}
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <form method="POST" action="{{ route('admin.salons.destroy', $salon) }}" onsubmit="return confirm('{{ __('Da li ste sigurni da želite da trajno obrišete ovaj salon?') }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-ghost btn-xs text-error">
                                                            {{ __('Obriši') }}
                                                        </button>
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

