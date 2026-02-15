<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ __('Razgovor sa') }} {{ $otherUser->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-base-100 shadow-xl sm:rounded-lg h-[calc(100vh-250px)] max-h-[700px] min-h-[500px] flex flex-col border border-base-200">
                <!-- Chat Header -->
                <div class="p-4 border-b border-base-200 flex items-center gap-3 bg-base-100/50">
                     <div class="avatar placeholder">
                        <div class="bg-secondary text-secondary-content rounded-full w-10">
                            <span class="text-sm font-bold">{{ substr($otherUser->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm">{{ $otherUser->name }}</h3>
                        <p class="text-[10px] opacity-60 uppercase">{{ $otherUser->role === 'salon_owner' ? __('Vlasnik salona') : __('Klijent') }}</p>
                    </div>
                </div>

                <!-- Chat Body -->
                <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-base-200/30" id="chat-container">
                    @forelse ($messages as $message)
                        <div class="chat {{ (string)$message->sender_id == (string)auth()->id() ? 'chat-end' : 'chat-start' }}">
                            <div class="chat-bubble shadow-sm {{ (string)$message->sender_id == (string)auth()->id() ? 'chat-bubble-primary' : 'chat-bubble-secondary' }}">
                                {{ $message->content }}
                            </div>
                            <div class="chat-footer opacity-40 text-[10px] mt-1 flex items-center gap-1">
                                {{ $message->created_at->format('H:i') }}
                                @if((string)$message->sender_id == (string)auth()->id())
                                    @if($message->read_at)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-primary" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="h-full flex flex-col items-center justify-center opacity-30 italic text-sm">
                            <p>{{ __('Još nema poruka u ovom razgovoru.') }}</p>
                            <p>{{ __('Pošaljite prvu poruku ispod.') }}</p>
                        </div>
                    @endforelse
                </div>
                
                <!-- Chat Footer / Input -->
                <div class="p-4 bg-base-100 border-t border-base-200">
                    <form method="POST" action="{{ route('messages.store', $otherUser) }}" class="flex gap-2">
                        @csrf
                        <input type="text" name="content" class="input input-bordered flex-1 rounded-full" placeholder="{{ __('Vaša poruka...') }}" required autofocus autocomplete="off" />
                        <button type="submit" class="btn btn-primary btn-circle">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var chatContainer = document.getElementById("chat-container");
            chatContainer.scrollTop = chatContainer.scrollHeight;
        });
    </script>
</x-app-layout>
