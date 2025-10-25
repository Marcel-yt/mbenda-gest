<section class="bg-white shadow-sm ring-1 ring-gray-100 sm:rounded-xl p-6">
  <header class="mb-6">
    <h2 class="text-lg font-medium text-gray-900">Informations du profil</h2>
    <p class="mt-1 text-sm text-gray-600">Mettez à jour vos informations personnelles et votre adresse e‑mail.</p>
  </header>

  <form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
  </form>

  <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
    @csrf
    @method('patch')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <x-input-label for="first_name" :value="__('Prénom')" />
        <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full"
                      :value="old('first_name', $user->first_name)" required autocomplete="given-name" />
        <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
      </div>

      <div>
        <x-input-label for="last_name" :value="__('Nom')" />
        <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full"
                      :value="old('last_name', $user->last_name)" required autocomplete="family-name" />
        <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
      </div>
    </div>

    <div>
      <x-input-label for="phone" :value="__('Téléphone')" />
      <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full"
                    :value="old('phone', $user->phone)" autocomplete="tel" />
      <x-input-error class="mt-2" :messages="$errors->get('phone')" />
    </div>

    <div>
      <x-input-label for="email" :value="__('E‑mail')" />
      <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                    :value="old('email', $user->email)" required autocomplete="username" />
      <x-input-error class="mt-2" :messages="$errors->get('email')" />

      @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
        <div class="mt-3">
          <p class="text-sm text-gray-800">
            Votre adresse e‑mail n’est pas vérifiée.
            <button form="send-verification"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
              Cliquez ici pour renvoyer l’e‑mail de vérification.
            </button>
          </p>

          @if (session('status') === 'verification-link-sent')
            <p class="mt-2 font-medium text-sm text-green-600">
              Un nouveau lien de vérification a été envoyé à votre adresse e‑mail.
            </p>
          @endif
        </div>
      @endif
    </div>

    <div class="flex items-center gap-4">
      <x-primary-button>Enregistrer</x-primary-button>

      @if (session('status') === 'profile-updated')
        <p x-data="{ show: true }" x-show="show" x-transition
           x-init="setTimeout(() => show = false, 2000)"
           class="text-sm text-gray-600">Modifications enregistrées.</p>
      @endif
    </div>
  </form>
</section>
