<section class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
  <div class="px-6 py-4" style="background: linear-gradient(135deg, var(--mb-tertiary) 0%, #e68a1f 100%);">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
          <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
        </svg>
      </div>
      <div>
        <h2 class="text-lg font-semibold text-white">Sécurité du compte</h2>
        <p class="text-sm text-orange-100">Mettre à jour votre mot de passe</p>
      </div>
    </div>
  </div>

  <form method="post" action="{{ route('password.update') }}" class="p-6 space-y-6">
    @csrf
    @method('put')

    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
      <div class="flex items-start gap-3">
        <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <div class="flex-1">
          <p class="text-sm font-medium text-amber-900">Conseils de sécurité</p>
          <ul class="text-xs text-amber-800 mt-2 space-y-1">
            <li>• Utilisez au moins 8 caractères</li>
            <li>• Mélangez majuscules, minuscules, chiffres et symboles</li>
            <li>• N'utilisez pas d'informations personnelles</li>
          </ul>
        </div>
      </div>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-2">
        <span class="flex items-center gap-2">
          <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
          </svg>
          Mot de passe actuel <span class="text-red-500">*</span>
        </span>
      </label>
      <x-text-input id="current_password" name="current_password" type="password" class="mb-input w-full"
                    autocomplete="current-password" placeholder="Entrez votre mot de passe actuel" />
      <x-input-error class="mt-2" :messages="$errors->get('current_password')" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          <span class="flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
            </svg>
            Nouveau mot de passe <span class="text-red-500">*</span>
          </span>
        </label>
        <x-text-input id="password" name="password" type="password" class="mb-input w-full"
                      autocomplete="new-password" placeholder="Nouveau mot de passe" />
        <x-input-error class="mt-2" :messages="$errors->get('password')" />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          <span class="flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Confirmer le mot de passe <span class="text-red-500">*</span>
          </span>
        </label>
        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mb-input w-full"
                      autocomplete="new-password" placeholder="Confirmez le nouveau mot de passe" />
        <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
      </div>
    </div>

    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-100">
      <div class="w-full sm:w-auto">
        @if (session('status') === 'password-updated')
          <div x-data="{ show: true }" x-show="show" x-transition
               x-init="setTimeout(() => show = false, 3000)"
               class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 border border-green-200 rounded-lg">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium text-green-900">✓ Mot de passe mis à jour avec succès</span>
          </div>
        @endif
      </div>
      <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg text-sm font-semibold text-white transition-all shadow-lg hover:shadow-xl mb-btn-primary"
              style="background: linear-gradient(135deg, var(--mb-tertiary) 0%, #e68a1f 100%);">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>
        Mettre à jour le mot de passe
      </button>
    </div>
  </form>
</section>
