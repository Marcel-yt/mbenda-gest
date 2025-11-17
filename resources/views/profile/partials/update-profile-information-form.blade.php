<section class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
  <div class="px-6 py-4" style="background: linear-gradient(135deg, var(--mb-secondary) 0%, #6ba83a 100%);">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
          <circle cx="12" cy="7" r="4"/>
        </svg>
      </div>
      <div>
        <h2 class="text-lg font-semibold text-white">Informations du profil</h2>
        <p class="text-sm text-green-100">Mettez à jour vos informations personnelles et votre photo</p>
      </div>
    </div>
  </div>

  @php
    $currentUserRole = auth()->user()?->role;
  @endphp

  {{-- formulaire d'édition (admins uniquement) --}}
  <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="p-6 space-y-6">
    @csrf
    @method('patch')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          <span class="flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Prénom <span class="text-red-500">*</span>
          </span>
        </label>
        <x-text-input id="first_name" name="first_name" type="text" class="mb-input w-full"
                      :value="old('first_name', $user->first_name)" required autocomplete="given-name" 
                      placeholder="Votre prénom" />
        <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          <span class="flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Nom <span class="text-red-500">*</span>
          </span>
        </label>
        <x-text-input id="last_name" name="last_name" type="text" class="mb-input w-full"
                      :value="old('last_name', $user->last_name)" required autocomplete="family-name" 
                      placeholder="Votre nom" />
        <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
      </div>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-2">
        <span class="flex items-center gap-2">
          <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
          </svg>
          Adresse e‑mail <span class="text-red-500">*</span>
        </span>
      </label>
      <x-text-input id="email" name="email" type="email" class="mb-input w-full"
                    :value="old('email', $user->email)" required autocomplete="username" 
                    placeholder="votre@email.com" />
      <x-input-error class="mt-2" :messages="$errors->get('email')" />
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-2">
        <span class="flex items-center gap-2">
          <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
          </svg>
          Téléphone
        </span>
      </label>
      <x-text-input id="phone" name="phone" type="text" class="mb-input w-full"
                    :value="old('phone', $user->phone)" autocomplete="tel" 
                    placeholder="+237 6XX XXX XXX" />
      <x-input-error class="mt-2" :messages="$errors->get('phone')" />
    </div>

    <div class="border-t border-gray-100 pt-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            <span class="flex items-center gap-2">
              <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
              </svg>
              Rôle utilisateur
            </span>
          </label>
          <div class="mt-2 inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-50 border border-indigo-200">
            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-semibold text-indigo-900">{{ ucfirst($user->role ?? '—') }}</span>
          </div>
          <p class="mt-2 text-xs text-gray-500">Le rôle ne peut pas être modifié depuis cette page</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            <span class="flex items-center gap-2">
              <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              Statut du compte
            </span>
          </label>
          <div class="mt-2 inline-flex items-center gap-2 px-4 py-2 rounded-lg {{ $user->active ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
            <span class="w-2.5 h-2.5 rounded-full {{ $user->active ? 'bg-green-500' : 'bg-red-500' }}"></span>
            <span class="text-sm font-semibold {{ $user->active ? 'text-green-900' : 'text-red-900' }}">{{ $user->active ? 'Actif' : 'Désactivé' }}</span>
          </div>
          @if(!$user->active)
            <p class="mt-2 text-xs text-red-600">⚠️ Compte désactivé — contactez un administrateur pour le réactiver</p>
          @endif
        </div>
      </div>
    </div>

    <div class="border-t border-gray-100 pt-6">
      <label class="block text-sm font-medium text-gray-700 mb-2">
        <span class="flex items-center gap-2">
          <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
          </svg>
          Couleur d'identification
        </span>
      </label>
      <div class="mt-2 flex items-center gap-4">
        <div class="flex items-center gap-3">
          <input id="color_picker" type="color" class="w-14 h-14 rounded-lg border-2 border-gray-300 cursor-pointer shadow-sm hover:shadow-md transition-shadow" 
                 value="{{ old('color_hex', $user->color_hex ?? '#7FBC47') }}"
                 onchange="document.getElementById('color_hex').value = this.value">
          <div>
            <x-text-input id="color_hex" name="color_hex" type="text" class="mb-input w-32 font-mono text-sm"
                          :value="old('color_hex', $user->color_hex)" placeholder="#RRGGBB" />
            <p class="text-xs text-gray-500 mt-1">Format hexadécimal</p>
          </div>
        </div>
        <div class="flex-1">
          <p class="text-xs text-gray-600">Cette couleur vous identifie dans le système (calendrier des collectes, etc.)</p>
        </div>
      </div>
      <x-input-error class="mt-2" :messages="$errors->get('color_hex')" />
    </div>

    <div class="border-t border-gray-100 pt-6">
      <label class="block text-sm font-medium text-gray-700 mb-2">
        <span class="flex items-center gap-2">
          <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
          </svg>
          Photo de profil
        </span>
      </label>
      <div class="mt-3 flex items-start gap-6">
        <div class="relative group">
          <img class="h-24 w-24 rounded-full object-cover border-4 border-gray-200 shadow-lg group-hover:shadow-xl transition-shadow"
               id="photo-preview"
               src="{{ $user->photo_profil ? Storage::url($user->photo_profil) . '?t=' . ($user->updated_at ? $user->updated_at->timestamp : time()) : asset('images/default-avatar.png') }}"
               alt="{{ $user->name }}">
          <div class="absolute inset-0 rounded-full bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
          </div>
        </div>

        <div class="flex-1">
          <div class="flex items-center gap-3">
            <label for="photo_profil" class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all shadow-sm cursor-pointer">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
              </svg>
              Choisir une photo
            </label>
            <input id="photo_profil" name="photo_profil" type="file" accept="image/*" class="hidden" />
          </div>
          <x-input-error class="mt-2" :messages="$errors->get('photo_profil')" />
          <div class="mt-2 text-xs text-gray-500 space-y-1">
            <p>• Formats acceptés : JPG, PNG, GIF</p>
            <p>• Taille recommandée : 500x500 pixels</p>
            <p>• Taille maximale : 2 MB</p>
          </div>
        </div>
      </div>
    </div>

    <div class="border-t border-gray-100 pt-6">
      <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start gap-3">
          <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <div class="flex-1">
            <p class="text-sm font-medium text-blue-900">Dernière connexion</p>
            <p class="text-sm text-blue-700 mt-1">{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y à H:i') : 'Jamais' }}</p>
            @if($user->last_login_at)
              <p class="text-xs text-blue-600 mt-1">{{ $user->last_login_at->diffForHumans() }}</p>
            @endif
          </div>
        </div>
      </div>
    </div>

    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-100">
      <div class="w-full sm:w-auto">
        @if (session('status') === 'profile-updated')
          <div x-data="{ show: true }" x-show="show" x-transition
               x-init="setTimeout(() => show = false, 3000)"
               class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 border border-green-200 rounded-lg">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium text-green-900">✓ Modifications enregistrées avec succès</span>
          </div>
        @endif
      </div>
      <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg text-sm font-semibold text-white transition-all shadow-lg hover:shadow-xl mb-btn-primary"
              style="background: linear-gradient(135deg, var(--mb-secondary) 0%, #6ba83a 100%);">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        Enregistrer les modifications
      </button>
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
