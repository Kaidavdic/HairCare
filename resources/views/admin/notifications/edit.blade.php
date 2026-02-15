<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ __('Uredi notifikaciju') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-base-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-base-content">
                    <form method="POST" action="{{ route('admin.notifications.update', $notification) }}" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div class="form-control">
                            <label class="label"><span class="label-text">{{ __('Naslov') }}</span></label>
                            <input type="text" name="title" value="{{ old('title', $notification->title) }}" class="input input-bordered w-full" required />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text">{{ __('Sadržaj') }}</span></label>
                            <textarea name="content" class="textarea textarea-bordered h-24" required>{{ old('content', $notification->content) }}</textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>

                        <div class="form-control">
                            <label class="label cursor-pointer justify-start gap-4">
                                <span class="label-text">{{ __('Vidljivo za sve korisnike') }}</span>
                                <input type="checkbox" name="is_visible" class="checkbox checkbox-primary" @checked(old('is_visible', $notification->is_visible)) />
                            </label>
                        </div>

                        <div class="pt-4 flex justify-between">
                            <a href="{{ route('admin.notifications.index') }}" class="btn btn-ghost">{{ __('Otkaži') }}</a>
                            <button type="submit" class="btn btn-primary">{{ __('Sačuvaj izmene') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
