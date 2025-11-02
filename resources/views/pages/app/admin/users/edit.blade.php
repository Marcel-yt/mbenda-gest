@extends('layouts.app-admin')

@section('title', 'Éditer utilisateur')
@section('page_title', 'Éditer utilisateur')

@section('content')
@php
  /** @var \App\Models\User $user */
  $auth = auth()->user();
@endphp

<div class="max-w-4xl mx-auto space-y-6">
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-semibold text-gray-900">Modifier : {{ $user->first_name }} {{ $user->last_name }}</h1>
    <div class="flex items-center gap-2">
      <a href="{{ route('admin.users.show', $user->id) }}" class="mb-link px-3 py-2 rounded text-sm">← Retour</a>
    </div>
  </div>

  {{-- SECTION: informations personnelles (y compris photo) --}}
  <section class="bg-white shadow sm:rounded-lg p-6">
    {{-- Alerts: success / info / validation errors --}}
    @if(session('success'))
      <div class="mb-4 p-3 rounded-md bg-green-50 border-l-4 border-green-600 text-green-800">
        {{ session('success') }}
      </div>
    @endif

    @if(session('info'))
      <div class="mb-4 p-3 rounded-md bg-yellow-50 border-l-4 border-yellow-600 text-yellow-800">
        {{ session('info') }}
      </div>
    @endif

    @if($errors->any())
      <div class="mb-4 p-3 rounded-md bg-red-50 border-l-4 border-red-600 text-red-800">
        <ul class="list-disc pl-5 space-y-1">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="post" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data" class="space-y-6" autocomplete="off">
      @csrf
      @method('patch')

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="text-xs text-gray-500">Prénom</label>
          <input name="first_name" type="text" value="{{ old('first_name', $user->first_name) }}" required class="mt-1 block w-full rounded border-gray-300" />
        </div>

        <div>
          <label class="text-xs text-gray-500">Nom</label>
          <input name="last_name" type="text" value="{{ old('last_name', $user->last_name) }}" class="mt-1 block w-full rounded border-gray-300" />
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="text-xs text-gray-500">Email</label>
          <input name="email" type="email" value="{{ old('email', $user->email) }}" required class="mt-1 block w-full rounded border-gray-300" />
        </div>

        <div>
          <label class="text-xs text-gray-500">Téléphone</label>
          <input name="phone" type="text" value="{{ old('phone', $user->phone) }}" class="mt-1 block w-full rounded border-gray-300" />
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Rôle affiché comme badge (non modifiable) --}}
        <div>
          <label class="text-xs text-gray-500">Rôle</label>
          <div class="mt-1">
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
              {{ $user->role === 'admin' ? 'mb-badge-admin' : ($user->role === 'agent' ? 'mb-badge-agent' : 'mb-badge-client') }}">
              {{ $user->role ?? '—' }}
            </span>
          </div>
          <input type="hidden" name="role" value="{{ $user->role }}">
        </div>

        <div>
          <label class="text-xs text-gray-500">Couleur</label>
          <div class="mt-1 flex items-center gap-3">
            <input id="color_hex" name="color_hex" type="text" value="{{ old('color_hex', $user->color_hex) }}" class="block w-32 rounded border-gray-300" placeholder="#RRGGBB" />
            <input id="color_picker" type="color" value="{{ old('color_hex', $user->color_hex ?? '#E5E7EB') }}" class="w-10 h-10 p-0 border rounded" onchange="document.getElementById('color_hex').value = this.value" />
          </div>
          @error('color_hex') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          {{-- indicatif coloré avant le label Statut --}}
          <label class="text-xs text-gray-500 flex items-center gap-2">
            <span class="inline-block w-2.5 h-2.5 rounded-full {{ old('active', $user->active) ? 'mb-ind-active' : 'mb-ind-inactive' }}"></span>
            Statut
          </label>
          <div class="mt-1">
            <select name="active" class="block w-full rounded border-gray-300">
              <option value="1" {{ (old('active', $user->active) == 1) ? 'selected' : '' }}>Activé</option>
              <option value="0" {{ (old('active', $user->active) == 0) ? 'selected' : '' }}>Désactivé</option>
            </select>
          </div>
        </div>
      </div>

      <div>
        <label class="text-xs text-gray-500">Photo de profil</label>
        <div class="mt-2 flex items-center gap-4">
          <img id="photo-preview" class="h-20 w-20 rounded-full object-cover border" src="{{ $user->photo_profil ? Storage::url($user->photo_profil).'?t='.($user->updated_at?->timestamp ?? time()) : asset('images/default-avatar.png') }}" alt="avatar">
          <input type="file" name="photo_profil" accept="image/*" class="block w-full" />
        </div>
      </div>

      <div class="flex items-center gap-4">
        <button type="submit" class="mb-btn-primary px-4 py-2 rounded">Enregistrer les informations</button>
      </div>
    </form>
  </section>

  {{-- SECTION séparée: mise à jour du mot de passe --}}
  <section class="bg-white shadow sm:rounded-lg p-6">
    <h2 class="text-lg font-medium text-gray-900">Réinitialiser le mot de passe</h2>
    <p class="mt-1 text-sm text-gray-600">Laisser vide pour conserver le mot de passe actuel.</p>

    <form method="post" action="{{ route('admin.users.update', $user->id) }}" class="mt-4 space-y-4" autocomplete="off">
      @csrf
      @method('patch')

      {{-- Indicateur pour controller si nécessaire --}}
      <input type="hidden" name="update_section" value="password">

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="text-xs text-gray-500">Nouveau mot de passe</label>
          <input name="password"
                 type="password"
                 value=""
                 placeholder="Laisser vide pour conserver le mot de passe actuel"
                 autocomplete="new-password"
                 class="mt-1 block w-full rounded border-gray-300" />
          @error('password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="text-xs text-gray-500">Confirmer le mot de passe</label>
          <input name="password_confirmation"
                 type="password"
                 value=""
                 placeholder="Confirmez le nouveau mot de passe"
                 autocomplete="new-password"
                 class="mt-1 block w-full rounded border-gray-300" />
        </div>
      </div>

      <div class="flex items-center justify-end">
        <button type="submit" class="mb-btn-primary px-4 py-2 rounded">Mettre à jour le mot de passe</button>
      </div>
    </form>
  </section>
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

