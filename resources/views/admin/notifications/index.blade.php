<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-base-content leading-tight">
                {{ __('Upravljanje notifikacijama') }}
            </h2>
            <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary btn-sm">
                {{ __('Nova notifikacija') }}
            </a>
        </div>
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

                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th>{{ __('Naslov') }}</th>
                                    <th>{{ __('Sadržaj') }}</th>
                                    <th>{{ __('Vidljivo') }}</th>
                                    <th>{{ __('Datum') }}</th>
                                    <th>{{ __('Akcije') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($notifications as $notification)
                                    <tr>
                                        <td class="font-bold">{{ $notification->title }}</td>
                                        <td>{{ Str::limit($notification->content, 50) }}</td>
                                        <td>
                                            @if ($notification->is_visible)
                                                <div class="badge badge-success">{{ __('Da') }}</div>
                                            @else
                                                <div class="badge badge-error">{{ __('Ne') }}</div>
                                            @endif
                                        </td>
                                        <td class="text-sm opacity-70">{{ $notification->created_at->format('d.m.Y H:i') }}</td>
                                        <td class="flex gap-2">
                                            <a href="{{ route('admin.notifications.edit', $notification) }}" class="btn btn-xs">{{ __('Uredi') }}</a>
                                            <form action="{{ route('admin.notifications.destroy', $notification) }}" method="POST" onsubmit="return confirm('Da li ste sigurni?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-error btn-xs">{{ __('Obriši') }}</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
