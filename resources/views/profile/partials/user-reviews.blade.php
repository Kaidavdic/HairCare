<section>
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-medium text-base-content">
                    {{ __('Moje Recenzije') }}
                </h2>
                <p class="mt-1 text-sm text-base-content/70">
                    {{ __('Ocene i komentari koje ste dobili od salona.') }}
                </p>
            </div>
            @if($user->reviews_count > 0)
                <div class="text-right">
                    <div class="flex items-center justify-end gap-1 text-orange-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 fill-current" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span class="text-2xl font-bold">{{ number_format($user->average_rating, 1) }}</span>
                    </div>
                    <div class="text-xs text-base-content/50">{{ $user->reviews_count }} {{ __('recenzija') }}</div>
                </div>
            @endif
        </div>

    @if ($user->receivedReviews->isEmpty())
        <p class="mt-4 text-sm text-base-content/70">{{ __('Nemate recenzija.') }}</p>
    @else
        <div class="mt-6 space-y-4">
            @foreach ($user->receivedReviews as $review)
                <div class="card bg-base-100 shadow border border-base-200">
                    <div class="card-body p-4">
                        <div class="flex justify-between items-center">
                            <h3 class="font-bold">{{ $review->salon->name }}</h3>
                            <div class="rating rating-sm">
                                @for($i=1; $i<=5; $i++)
                                    <input type="radio" name="rating-{{$review->id}}" class="mask mask-star-2 bg-orange-400" @checked($i <= $review->rating) disabled />
                                @endfor
                            </div>
                        </div>
                        <p class="text-sm mt-2">{{ $review->comment }}</p>
                        <span class="text-xs text-base-content/50 mt-2 block">{{ $review->created_at->format('d.m.Y') }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</section>
