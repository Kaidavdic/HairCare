<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ __('Nova notifikacija') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-base-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-base-content">
                    <form method="POST" action="{{ route('admin.notifications.store') }}" class="space-y-4">
                        @csrf
                        <div class="form-control">
                            <label class="label"><span class="label-text">{{ __('Naslov') }}</span></label>
                            <input type="text" name="title" class="input input-bordered w-full" required />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text">{{ __('Sadržaj') }}</span></label>
                            <textarea name="content" class="textarea textarea-bordered h-24" required></textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>

                        <div class="form-control">
                            <label class="label cursor-pointer justify-start gap-4">
                                <span class="label-text">{{ __('Vidljivo za sve korisnike') }}</span>
                                <input type="checkbox" name="is_visible" class="checkbox checkbox-primary" checked />
                            </label>
                        </div>

                        <div class="pt-4 flex justify-between">
                            <a href="{{ route('admin.notifications.index') }}" class="btn btn-ghost">{{ __('Otkaži') }}</a>
                            <button type="submit" class="btn btn-primary">{{ __('Sačuvaj') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
