@extends('layouts.app-admin')

@section('title', 'Éditer utilisateur')
@section('page_title', 'Éditer utilisateur')

@section('content')
@php
  /** @var \App\Models\User $user */
  $auth = auth()->user();
@endphp

<div class="max-w-5xl mx-auto space-y-6">
  {{-- Header with Breadcrumb Navigation --}}
  <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-center justify-between">
      <div>
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
          <a href="{{ route('admin.users.index') }}" class="hover:text-blue-600 transition-colors">Utilisateurs</a>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
          </svg>
          <a href="{{ route('admin.users.show', $user->id) }}" class="hover:text-blue-600 transition-colors">{{ $user->first_name }} {{ $user->last_name }}</a>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
          </svg>
          <span class="text-gray-700 font-medium">Édition</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
          <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
          </svg>
          Modifier : {{ $user->first_name }} {{ $user->last_name }}
        </h1>
        <p class="mt-1 text-sm text-gray-600">Mettez à jour les informations de cet utilisateur</p>
      </div>
      <a href="{{ route('admin.users.show', $user->id) }}" 
         class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all shadow-sm"
         title="Retour au profil">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Retour
      </a>
    </div>
  </div>

  {{-- Alerts --}}
  @if(session('success'))
    <div class="flex items-start gap-3 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">
      <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
      </svg>
      <div class="flex-1">{{ session('success') }}</div>
    </div>
  @endif

  @if(session('info'))
    <div class="flex items-start gap-3 p-4 rounded-xl bg-yellow-50 border border-yellow-200 text-yellow-800">
      <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
      </svg>
      <div class="flex-1">{{ session('info') }}</div>
    </div>
  @endif

  @if($errors->any())
    <div class="flex items-start gap-3 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800">
      <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
      </svg>
      <div class="flex-1">
        <ul class="list-disc pl-5 space-y-1">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    </div>
  @endif

  {{-- Main Information Form --}}
  <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
          </svg>
        </div>
        <div>
          <h2 class="text-lg font-semibold text-white">Informations personnelles</h2>
          <p class="text-sm text-blue-100">Modifier les détails de l'utilisateur</p>
        </div>
      </div>
    </div>

    <form method="post" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data" class="p-6 space-y-6" autocomplete="off">
      @csrf
      @method('patch')

      {{-- Name Fields --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
          <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Prénom <span class="text-red-500">*</span>
          </label>
          <input name="first_name" type="text" value="{{ old('first_name', $user->first_name) }}" required 
                 class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                 placeholder="Entrez le prénom" />
        </div>

        <div>
          <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Nom
          </label>
          <input name="last_name" type="text" value="{{ old('last_name', $user->last_name) }}" 
                 class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                 placeholder="Entrez le nom" />
        </div>
      </div>

      {{-- Contact Fields --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
          <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            Email <span class="text-red-500">*</span>
          </label>
          <input name="email" type="email" value="{{ old('email', $user->email) }}" required 
                 class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                 placeholder="exemple@email.com" />
        </div>

        <div>
          <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
            </svg>
            Téléphone
          </label>
          <input name="phone" type="text" value="{{ old('phone', $user->phone) }}" 
                 class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                 placeholder="+243 XXX XXX XXX" />
        </div>
      </div>

      <div class="border-t border-gray-100 my-6"></div>

      {{-- Role, Color, Status --}}
      <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div>
          <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
            </svg>
            Rôle
          </label>
          <div class="mt-1">
            <span class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-semibold
              {{ $user->role === 'admin' ? 'bg-gradient-to-r from-purple-500 to-purple-600 text-white' : ($user->role === 'agent' ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white' : 'bg-gradient-to-r from-green-500 to-green-600 text-white') }}">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
              </svg>
              {{ ucfirst($user->role ?? '—') }}
            </span>
            <p class="mt-1 text-xs text-gray-500">Le rôle ne peut pas être modifié</p>
          </div>
          <input type="hidden" name="role" value="{{ $user->role }}">
        </div>

        <div>
          <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
            </svg>
            Couleur
          </label>
          <div class="mt-1 flex items-center gap-2">
            <input id="color_picker" type="color" value="{{ old('color_hex', $user->color_hex ?? '#E5E7EB') }}" 
                   class="w-12 h-12 p-1 border-2 border-gray-300 rounded-lg cursor-pointer" 
                   onchange="document.getElementById('color_hex').value = this.value" />
            <input id="color_hex" name="color_hex" type="text" value="{{ old('color_hex', $user->color_hex) }}" 
                   class="flex-1 block rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                   placeholder="#RRGGBB" />
          </div>
          @error('color_hex') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Statut
          </label>
          <select name="active" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="1" {{ (old('active', $user->active) == 1) ? 'selected' : '' }}>✅ Activé</option>
            <option value="0" {{ (old('active', $user->active) == 0) ? 'selected' : '' }}>❌ Désactivé</option>
          </select>
        </div>
      </div>

      <div class="border-t border-gray-100 my-6"></div>

      {{-- Photo Upload --}}
      <div>
        <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-3">
          <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
          </svg>
          Photo de profil
        </label>
        <div class="flex items-center gap-5">
          <div class="relative">
            <img id="photo-preview" class="w-24 h-24 rounded-full object-cover border-4 border-gray-200 shadow-md" 
                 src="{{ $user->photo_profil ? Storage::url($user->photo_profil).'?t='.($user->updated_at?->timestamp ?? time()) : asset('images/default-avatar.png') }}" 
                 alt="avatar">
            <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white border-2 border-white">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
              </svg>
            </div>
          </div>
          <div class="flex-1">
            <input type="file" name="photo_profil" accept="image/*" 
                   class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer" />
            <p class="mt-2 text-xs text-gray-500">Formats acceptés : JPG, PNG, GIF (max 2MB)</p>
          </div>
        </div>
      </div>

      <div class="border-t border-gray-100 pt-6 mt-6">
        <div class="flex items-center justify-end gap-3">
          <a href="{{ route('admin.users.show', $user->id) }}" 
             class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            Annuler
          </a>
          <button type="submit" 
                  class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg text-sm font-medium hover:from-blue-600 hover:to-blue-700 transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Enregistrer les modifications
          </button>
        </div>
      </div>
    </form>
  </div>

  {{-- Password Reset Section --}}
  <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
          </svg>
        </div>
        <div>
          <h2 class="text-lg font-semibold text-white">Réinitialiser le mot de passe</h2>
          <p class="text-sm text-purple-100">Modifier le mot de passe de connexion</p>
        </div>
      </div>
    </div>

    <form method="post" action="{{ route('admin.users.update', $user->id) }}" class="p-6 space-y-5" autocomplete="off">
      @csrf
      @method('patch')
      <input type="hidden" name="update_section" value="password">

      <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
        </svg>
        <div class="flex-1">
          <p class="text-sm font-medium text-yellow-900">Information importante</p>
          <p class="text-sm text-yellow-700 mt-1">Laisser les champs vides pour conserver le mot de passe actuel. Remplissez uniquement si vous souhaitez le modifier.</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
          <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            Nouveau mot de passe
          </label>
          <input name="password"
                 type="password"
                 value=""
                 placeholder="Entrez le nouveau mot de passe"
                 autocomplete="new-password"
                 class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" />
          @error('password') <p class="text-sm text-red-600 mt-1 flex items-center gap-1">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            {{ $message }}
          </p> @enderror
        </div>

        <div>
          <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
            Confirmer le mot de passe
          </label>
          <input name="password_confirmation"
                 type="password"
                 value=""
                 placeholder="Confirmez le nouveau mot de passe"
                 autocomplete="new-password"
                 class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" />
        </div>
      </div>

      <div class="border-t border-gray-100 pt-5">
        <div class="flex items-center justify-end">
          <button type="submit" 
                  class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg text-sm font-medium hover:from-purple-600 hover:to-purple-700 transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            Mettre à jour le mot de passe
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  (function(){
    const input = document.querySelector('input[name="photo_profil"]');
    const preview = document.getElementById('photo-preview');
    if (!input || !preview) return;
    input.addEventListener('change', function () {
      const f = this.files && this.files[0];
      if (!f) return;
      const r = new FileReader();
      r.onload = e => preview.src = e.target.result;
      r.readAsDataURL(f);
    });
  })();
</script>
@endsection

