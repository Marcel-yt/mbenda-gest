<section class="bg-white shadow-sm ring-1 ring-gray-100 sm:rounded-xl p-6">
  <header class="mb-6">
    <h2 class="text-lg font-medium text-gray-900">Mettre à jour le mot de passe</h2>
    <p class="mt-1 text-sm text-gray-600">Utilisez un mot de passe long et unique pour sécuriser votre compte.</p>
  </header>

  <form method="post" action="{{ route('password.update') }}" class="space-y-6">
    @csrf
    @method('put')

    <div>
      <x-input-label for="current_password" :value="__('Mot de passe actuel')" />
      <x-text-input id="current_password" name="current_password" type="password" class="mt-1 block w-full"
                    autocomplete="current-password" />
      <x-input-error class="mt-2" :messages="$errors->get('current_password')" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <x-input-label for="password" :value="__('Nouveau mot de passe')" />
        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full"
                      autocomplete="new-password" />
        <x-input-error class="mt-2" :messages="$errors->get('password')" />
      </div>

      <div>
        <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" />
        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full"
                      autocomplete="new-password" />
        <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
      </div>
    </div>

    <div class="flex items-center gap-4">
      <x-primary-button>Enregistrer</x-primary-button>

      @if (session('status') === 'password-updated')
        <p x-data="{ show: true }" x-show="show" x-transition
           x-init="setTimeout(() => show = false, 2000)"
           class="text-sm text-gray-600">Mot de passe mis à jour.</p>
      @endif
    </div>
  </form>
</section>
