<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-base-content leading-tight">
                {{ __('Podešavanja Profila') }}
            </h2>
            <a href="{{ route('profile.show', request()->user()) }}" class="btn btn-primary btn-sm">
                {{ __('Pogledaj Javni Profil') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column: Profile Info & Password -->
                <div class="space-y-6">
                    <div class="p-4 sm:p-8 bg-base-100 shadow sm:rounded-lg">
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <div class="p-4 sm:p-8 bg-base-100 shadow sm:rounded-lg">
                        <div class="max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>

                <!-- Right Column: Reviews & Danger Zone -->
                <div class="space-y-6">
                    <div id="user-reviews" class="p-4 sm:p-8 bg-base-100 shadow sm:rounded-lg h-fit">
                        <div class="max-w-xl">
                            @include('profile.partials.user-reviews')
                        </div>
                    </div>

                    <div class="p-4 sm:p-8 bg-base-100 shadow sm:rounded-lg border border-error/20">
                        <div class="max-w-xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>