@extends('layouts.app-admin')

@section('title', 'Nouveau utilisateur')
@section('page_title', 'Nouveau utilisateur')

@section('content')
@php $auth = auth()->user(); @endphp

<div class="max-w-3xl mx-auto space-y-6">
  <div class="bg-white border rounded-lg p-6 shadow-sm">
    <h2 class="text-lg font-semibold mb-4">Créer un nouvel utilisateur</h2>

    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4" autocomplete="off">
      @csrf

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label for="first_name" class="block text-sm font-medium text-gray-700">Prénom</label>
          <input id="first_name" name="first_name" type="text" value="{{ old('first_name') }}"
                 class="mt-1 block w-full rounded-lg border-gray-200 bg-white py-2 px-3 text-sm focus:ring-0" required>
          @error('first_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
          <label for="last_name" class="block text-sm font-medium text-gray-700">Nom</label>
          <input id="last_name" name="last_name" type="text" value="{{ old('last_name') }}"
                 class="mt-1 block w-full rounded-lg border-gray-200 bg-white py-2 px-3 text-sm focus:ring-0">
          @error('last_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
      </div>

      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">E‑mail</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}"
               class="mt-1 block w-full rounded-lg border-gray-200 bg-white py-2 px-3 text-sm focus:ring-0" required>
        @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone</label>
          <input id="phone" name="phone" type="text" value="{{ old('phone') }}"
                 class="mt-1 block w-full rounded-lg border-gray-200 bg-white py-2 px-3 text-sm focus:ring-0">
          @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- ROLE: enlever l'option "client" --}}
        <div>
          <label for="role" class="block text-sm font-medium text-gray-700">Rôle</label>
          <select id="role" name="role" class="mt-1 block w-full rounded-lg border-gray-200 bg-white py-2 px-3 text-sm" required>
            <option value="">Sélectionner</option>
            @if(!empty($auth) && $auth->is_super_admin)
              <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
              <option value="agent" {{ old('role') === 'agent' ? 'selected' : '' }}>Agent</option>
            @else
              <option value="agent" selected>Agent</option>
            @endif
          </select>
          @error('role') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
      </div>

      {{-- couleur + statut sur une ligne, photo sur la ligne suivante --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
        <div>
          <label for="color_hex" class="block text-sm font-medium text-gray-700">Couleur</label>
          <div class="mt-1 flex items-center gap-3">
            <input id="color_hex" name="color_hex" type="text" value="{{ old('color_hex') }}" placeholder="#RRGGBB"
                   class="block w-32 rounded border-gray-300 py-2 px-3 text-sm" />
            <input id="color_picker" type="color" value="{{ old('color_hex', '#7FBC47') }}" class="w-10 h-10 p-0 border rounded" onchange="document.getElementById('color_hex').value = this.value" />
          </div>
          @error('color_hex') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
          <label for="active" class="block text-sm font-medium text-gray-700">Statut</label>
          <select id="active" name="active" class="mt-1 block w-full rounded-lg border-gray-200 bg-white py-2 px-3 text-sm">
            <option value="1" {{ old('active', '1') == '1' ? 'selected' : '' }}>Activé</option>
            <option value="0" {{ old('active') == '0' ? 'selected' : '' }}>Désactivé</option>
          </select>
          @error('active') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
      </div>

      <div class="mt-4">
        <label for="photo_profil" class="block text-sm font-medium text-gray-700">Photo de profil</label>
        <input id="photo_profil" name="photo_profil" type="file" accept="image/*" class="mt-1 block w-full text-sm" />
        @error('photo_profil') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
          <input id="password" name="password" type="password"
                 class="mt-1 block w-full rounded-lg border-gray-200 bg-white py-2 px-3 text-sm focus:ring-0" required>
          @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
          <input id="password_confirmation" name="password_confirmation" type="password"
                 class="mt-1 block w-full rounded-lg border-gray-200 bg-white py-2 px-3 text-sm focus:ring-0" required>
        </div>
      </div>

      <div class="flex items-center justify-between pt-4">
        <div class="flex gap-3">
          <a href="{{ route('admin.users.index') }}" class="mb-link">Annuler</a>
          <button type="submit" class="mb-btn-primary inline-flex items-center px-4 py-2 rounded-md text-sm">Enregistrer</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection