<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-base-content">
            {{ __('Obriši nalog') }}
        </h2>

        <p class="mt-1 text-sm text-base-content/80">
            {{ __('Jednom kada se vaš nalog obriše, svi njegovi resursi i podaci će biti trajno obrisani. Pre nego što obrišete nalog, preuzmite sve podatke ili informacije koje želite da zadržite.') }}
        </p>
    </header>

    <x-danger-button x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">{{ __('Obriši nalog') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-base-content">
                {{ __('Da li ste sigurni da želite da obrišete svoj nalog?') }}
            </h2>

            <p class="mt-1 text-sm text-base-content/80">
                {{ __('Jednom kada se vaš nalog obriše, svi njegovi resursi i podaci će biti trajno obrisani. Molimo unesite svoju lozinku kako biste potvrdili da želite trajno da obrišete nalog.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Lozinka') }}" class="sr-only" />

                <x-text-input id="password" name="password" type="password" class="mt-1 block w-3/4"
                    placeholder="{{ __('Lozinka') }}" />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Otkaži') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Obriši nalog') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>