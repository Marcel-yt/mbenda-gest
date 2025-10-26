@extends('layouts.app-admin')

@section('title', 'Nouveau utilisateur')
@section('page_title', 'Nouveau utilisateur')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
  <div class="bg-white border rounded-lg p-6 shadow-sm">
    <h2 class="text-lg font-semibold mb-4">Créer un nouvel utilisateur</h2>

    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
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
          <label for="role" class="block text-sm font-medium text-gray-700">Rôle</label>
          <select id="role" name="role" class="mt-1 block w-full rounded-lg border-gray-200 bg-white py-2 px-3 text-sm">
            <option value="">Sélectionner</option>
            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="agent" {{ old('role') === 'agent' ? 'selected' : '' }}>Agent</option>
            <option value="client" {{ old('role') === 'client' ? 'selected' : '' }}>Client</option>
          </select>
          @error('role') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
          <input id="password" name="password" type="password"
                 class="mt-1 block w-full rounded-lg border-gray-200 bg-white py-2 px-3 text-sm focus:ring-0" required>
          @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
      </div>

      <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
        <input id="password_confirmation" name="password_confirmation" type="password"
               class="mt-1 block w-full rounded-lg border-gray-200 bg-white py-2 px-3 text-sm focus:ring-0" required>
      </div>

      <div class="flex items-center justify-between pt-4">
        <div class="flex gap-3">
          <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 border rounded-md text-sm text-gray-700 bg-white hover:bg-gray-50">Annuler</a>
          <button type="submit" class="inline-flex items-center px-4 py-2 bg-[#0078B7] text-white rounded-md text-sm hover:bg-[#006aa0]">Enregistrer</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection