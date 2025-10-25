<section class="bg-white shadow-sm ring-1 ring-gray-100 sm:rounded-xl p-6">
  <header class="mb-4">
    <h2 class="text-lg font-medium text-gray-900">Supprimer le compte</h2>
    <p class="mt-1 text-sm text-gray-600">
      Une fois votre compte supprimé, toutes ses données seront définitivement effacées. Cette action est irréversible.
    </p>
  </header>

  <form method="post" action="{{ route('profile.destroy') }}" class="space-y-6">
    @csrf
    @method('delete')

    <div>
      <x-input-label for="password" :value="__('Mot de passe')" />
      <x-text-input id="password" name="password" type="password" class="mt-1 block w-full"
                    autocomplete="current-password" />
      <x-input-error class="mt-2" :messages="$errors->get('password')" />
    </div>

    <x-danger-button>Supprimer définitivement</x-danger-button>
  </form>
</section>
