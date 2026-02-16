<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ __('Poruke') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-base-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="text-base-content">
                    @if ($conversations->isEmpty())
                        <div class="text-center py-16 px-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto text-base-content/20 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                            <p class="text-base-content/50 text-lg">{{ __('Još uvek nemate započetih razgovora.') }}</p>
                        </div>
                    @else
                        <div class="divide-y divide-base-200">
                            @foreach ($conversations as $conversation)
                                @php
                                    $otherUser = $conversation->sender_id === auth()->id() ? $conversation->receiver : $conversation->sender;
                                    $isUnread = $conversation->receiver_id === auth()->id() && is_null($conversation->read_at);
                                @endphp
                                <a href="{{ route('messages.show', $otherUser) }}" 
                                   class="flex items-start gap-4 p-4 hover:bg-base-200/50 transition-all duration-200 {{ $isUnread ? 'bg-primary/5 border-l-4 border-primary' : '' }}">
                                    <!-- Profile Picture -->
                                    <div class="avatar @if(!$otherUser->profile_picture) placeholder @endif flex-shrink-0">
                                        @if($otherUser->profile_picture)
                                            <div class="w-14 h-14 rounded-full ring-2 ring-base-300">
                                                <img src="{{ asset('storage/' . $otherUser->profile_picture) }}" 
                                                     alt="{{ $otherUser->name }}" 
                                                     class="w-full h-full object-cover rounded-full" />
                                            </div>
                                        @else
                                            <div class="bg-primary text-primary-content rounded-full w-14 h-14 flex items-center justify-center ring-2 ring-base-300">
                                                <span class="text-xl font-bold">{{ substr($otherUser->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Message Content -->
                                    <div class="flex-1 min-w-0 py-1">
                                        <div class="flex justify-between items-start mb-1.5">
                                            <h3 class="font-bold text-base text-base-content truncate pr-2 {{ $isUnread ? 'text-primary' : '' }}">
                                                {{ $otherUser->name }}
                                            </h3>
                                            <span class="text-xs text-base-content/50 whitespace-nowrap ml-2">
                                                {{ $conversation->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-center gap-2">
                                            <p class="text-sm text-base-content/70 truncate {{ $isUnread ? 'font-semibold text-base-content' : '' }}">
                                                {{ $conversation->content }}
                                            </p>
                                            @if($isUnread)
                                                <span class="badge badge-primary badge-sm flex-shrink-0">Novo</span>
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
