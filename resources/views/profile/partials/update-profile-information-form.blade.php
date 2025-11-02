<section class="bg-white shadow-sm ring-1 ring-gray-100 sm:rounded-xl p-6">
  <header class="mb-6">
    <h2 class="text-lg font-medium text-gray-900">Informations du profil</h2>
    <p class="mt-1 text-sm text-gray-600">Mettez à jour vos informations personnelles et votre adresse e‑mail.</p>
  </header>

  @php
    $currentUserRole = auth()->user()?->role;
  @endphp

  {{-- formulaire d'édition (admins uniquement) --}}
  <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
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
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <x-input-label :value="__('Rôle')" />
        <div class="mt-1 text-gray-700">{{ ucfirst($user->role ?? '—') }}</div>
      </div>

      <div>
        <x-input-label for="color_hex" :value="__('Couleur')" />
        <div class="mt-1 flex items-center gap-3">
          <x-text-input id="color_hex" name="color_hex" type="text" class="block w-32"
                        :value="old('color_hex', $user->color_hex)" placeholder="#RRGGBB" />
          <input id="color_picker" type="color" class="w-10 h-10 p-0 border rounded" value="{{ old('color_hex', $user->color_hex ?? '#E5E7EB') }}"
                 onchange="document.getElementById('color_hex').value = this.value">
        </div>
        <x-input-error class="mt-2" :messages="$errors->get('color_hex')" />
      </div>

      <div>
        {{-- Statut : affichage seulement avec indicatif coloré (non modifiable) --}}
        <x-input-label :value="__('Statut')" />
        <div class="mt-2 flex items-center gap-3">
          <span class="inline-block w-2 h-2 rounded-full" style="background: {{ $user->active ? '#16A34A' : '#DC2626' }};"></span>
          <div class="text-gray-700 font-medium">{{ $user->active ? 'Actif' : 'Désactivé' }}</div>
        </div>

        {{-- Message si compte désactivé --}}
        @if(!$user->active)
          <p class="mt-2 text-sm text-red-600">Compte désactivé — cet utilisateur ne pourra plus se connecter. Contactez un administrateur pour le réactiver.</p>
        @endif
      </div>
    </div>

    <div>
      <x-input-label for="photo_profil" :value="__('Photo de profil')" />
      <div class="mt-2 flex items-center gap-4">
        <img class="h-20 w-20 rounded-full object-cover border"
             id="photo-preview"
             src="{{ $user->photo_profil ? Storage::url($user->photo_profil) . '?t=' . ($user->updated_at ? $user->updated_at->timestamp : time()) : asset('images/default-avatar.png') }}"
             alt="{{ $user->name }}">

        <div class="flex-1">
          <input id="photo_profil" name="photo_profil" type="file" accept="image/*" class="block w-full" />
          <x-input-error class="mt-2" :messages="$errors->get('photo_profil')" />
          <p class="text-xs text-gray-500 mt-1">Formats acceptés : jpg, png. Max recommandé 2MB.</p>
        </div>
      </div>
    </div>

    <div>
      <x-input-label :value="__('Dernière connexion')" />
      <div class="mt-1 text-gray-700">{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Jamais' }}</div>
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

  <script>
    // preview image client-side
    (function(){
      const input = document.getElementById('photo_profil');
      const preview = document.getElementById('photo-preview');
      if(!input || !preview) return;
      input.addEventListener('change', function(e){
        const file = this.files && this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(ev){ preview.src = ev.target.result; };
        reader.readAsDataURL(file);
      });
    })();
  </script>
</section>
