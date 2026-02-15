<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ __('Poruke') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-base-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-base-content">
                    @if ($conversations->isEmpty())
                        <div class="text-center py-12">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-base-content/20 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                            <p class="text-base-content/50">{{ __('Još uvek nemate započetih razgovora.') }}</p>
                        </div>
                    @else
                        <div class="divide-y divide-base-200">
                            @foreach ($conversations as $conversation)
                                @php
                                    $otherUser = $conversation->sender_id === auth()->id() ? $conversation->receiver : $conversation->sender;
                                    $isUnread = $conversation->receiver_id === auth()->id() && is_null($conversation->read_at);
                                @endphp
                                <a href="{{ route('messages.show', $otherUser) }}" 
                                   class="flex items-center gap-4 p-4 hover:bg-base-200 transition-colors {{ $isUnread ? 'bg-primary/5 border-l-4 border-primary' : '' }}">
                                    <div class="avatar placeholder">
                                        <div class="bg-primary text-primary-content rounded-full w-12 h-12 shadow-sm">
                                            <span class="text-lg font-bold">{{ substr($otherUser->name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-baseline mb-1">
                                            <h3 class="font-bold text-base-content truncate pr-2">{{ $otherUser->name }}</h3>
                                            <span class="text-[10px] opacity-50 whitespace-nowrap">{{ $conversation->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <p class="text-sm text-base-content/70 truncate {{ $isUnread ? 'font-semibold text-base-content' : '' }}">
                                                {{ $conversation->content }}
                                            </p>
                                            @if($isUnread)
                                                <span class="badge badge-primary badge-xs ml-2"></span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
