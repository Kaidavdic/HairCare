<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">{{ __('Usluge salona') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-base-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-base-content">
                    @if (session('status'))
                        <div class="mb-4 text-sm text-green-600">{{ session('status') }}</div>
                    @endif

                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="font-semibold">{{ $salon->name }}</h3>
                            <div class="text-sm text-base-content/70">{{ $salon->location }}</div>
                        </div>

                        <a href="{{ route('owner.services.create') }}"
                            class="btn btn-primary btn-sm">{{ __('Dodaj uslugu') }}</a>
                    </div>

                    @if($services->isEmpty())
                        <div class="text-base-content/80">{{ __('Nema dodatih usluga.') }}</div>
                    @else
                        <div class="space-y-3">
                            @foreach($services as $service)
                                <div
                                    class="p-3 border rounded-md border-base-200 bg-base-100 flex justify-between items-center">
                                    <div>
                                        <div class="font-semibold">{{ $service->name }}</div>
                                        <div class="text-sm text-base-content/70">{{ $service->description }}</div>
                                        <div class="text-sm text-base-content/70">{{ $service->duration_minutes }} min ·
                                            {{ $service->price }} RSD</div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('owner.services.edit', $service) }}"
                                            class="btn btn-ghost btn-sm">{{ __('Uredi') }}</a>
                                        <form action="{{ route('owner.services.destroy', $service) }}" method="POST"
                                            onsubmit="return confirm('Da li ste sigurni?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline btn-sm">{{ __('Obriši') }}</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>