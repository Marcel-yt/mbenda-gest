@extends('layouts.app-admin')

@section('title', 'Voir utilisateur')
@section('page_title', 'Détails utilisateur')

@section('content')
@php /** @var \App\Models\User $user */ @endphp

<div class="max-w-4xl mx-auto space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-semibold text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</h1>
      <p class="text-sm text-gray-500">{{ $user->email }}</p>
    </div>

    <div class="flex items-center gap-2">
      {{-- Lien retour en style brand --}}
      <a href="{{ route('admin.users.index') }}" class="mb-link px-3 py-2 rounded text-sm">← Retour</a>

      {{-- Bouton Éditer : couleur primaire --}}
      <a href="{{ route('admin.users.edit', $user->id) }}" class="mb-btn-secondary px-3 py-2 rounded text-sm">
        Éditer
      </a>
    </div>
  </div>

  <section class="bg-white shadow sm:rounded-lg p-6">
    <div class="md:flex md:items-start md:space-x-6">
      <div class="flex-1">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700">
          <div>
            <div class="text-xs text-gray-500">Prénom</div>
            <div class="mt-1 font-medium text-gray-900">{{ $user->first_name ?? '—' }}</div>
          </div>

          <div>
            <div class="text-xs text-gray-500">Nom</div>
            <div class="mt-1 font-medium text-gray-900">{{ $user->last_name ?? '—' }}</div>
          </div>

          <div>
            <div class="text-xs text-gray-500">Téléphone</div>
            <div class="mt-1 font-medium text-gray-900">{{ $user->phone ?? '—' }}</div>
          </div>

          <div>
            <div class="text-xs text-gray-500">Email</div>
            <div class="mt-1 font-medium text-gray-900">{{ $user->email ?? '—' }}</div>
          </div>

          <div>
            <div class="text-xs text-gray-500">Rôle</div>
            <div class="mt-1">
              <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                {{ $user->role === 'admin' ? 'mb-badge-admin' : ($user->role === 'agent' ? 'mb-badge-agent' : 'mb-badge-client') }}">
                {{ $user->role ?? '—' }}
              </span>
            </div>
          </div>

          <div>
            <div class="text-xs text-gray-500">Statut</div>
            <div class="mt-1 flex items-center gap-2">
              <span class="{{ $user->active ? 'mb-ind-active' : 'mb-ind-inactive' }}"></span>
              <span class="font-medium text-sm {{ $user->active ? 'text-gray-900' : 'text-gray-700' }}">
                {{ $user->active ? 'Actif' : 'Désactivé' }}
              </span>
            </div>
            @if(!$user->active)
              <p class="mt-2 text-sm text-gray-700">Compte désactivé — cet utilisateur ne pourra plus se connecter.</p>
            @endif
          </div>

          <div>
            <div class="text-xs text-gray-500">Couleur</div>
            <div class="mt-1 flex items-center gap-3">
              <span class="inline-block w-6 h-6 rounded border" style="background: {{ $user->color_hex ?? '#E5E7EB' }};"></span>
              <span class="font-medium text-gray-900">{{ $user->color_hex ?? '—' }}</span>
            </div>
          </div>

          <div class="sm:col-span-2">
            <div class="text-xs text-gray-500">Dernière connexion</div>
            <div class="mt-1 font-medium text-gray-900">{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Jamais' }}</div>
          </div>

          <div class="sm:col-span-2">
            <div class="text-xs text-gray-500">Créé par</div>
            <div class="mt-1 font-medium text-gray-900">
              {{ $user->creator?->email ?? '—' }}
            </div>
          </div>

          @if($user->is_super_admin)
            <div class="sm:col-span-2">
              <div class="text-xs text-gray-500">Rôle spécial</div>
              <div class="mt-1">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold mb-badge-admin">Super‑admin</span>
              </div>
            </div>
          @endif
        </div>
      </div>

      <div class="mt-6 md:mt-0 flex-shrink-0">
        <div class="w-36 h-36 rounded-full overflow-hidden border">
          <img class="w-full h-full object-cover"
               src="{{ $user->photo_profil ? Storage::url($user->photo_profil).'?t='.($user->updated_at?->timestamp ?? time()) : asset('images/default-avatar.png') }}"
               alt="{{ $user->first_name }} {{ $user->last_name }}">
        </div>
        <div class="mt-3 text-xs text-gray-500 text-center">Photo de profil</div>
      </div>
    </div>
  </section>

  {{-- Alerts: success / info / errors --}}
  @if(session('success'))
    <div class="p-4 rounded-md bg-green-50 border-l-4 border-green-600 text-green-800">
      {{ session('success') }}
    </div>
  @endif

  @if(session('info'))
    <div class="p-4 rounded-md bg-yellow-50 border-l-4 border-yellow-600 text-yellow-800">
      {{ session('info') }}
    </div>
  @endif

  @if($errors->any())
    <div class="p-4 rounded-md bg-red-50 border-l-4 border-red-600 text-red-800">
      <ul class="list-disc pl-5 space-y-1">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Suppression : bouton au bas, fond rouge --}}
  <div class="mt-6 border-t pt-4">
    <form action="{{ route('admin.users.destroy', $user->id) }}" method="post" onsubmit="return confirm('Supprimer cet utilisateur ?');" class="flex justify-end">
      @csrf
      @method('delete')
      <button type="submit" class=" px-3 text-smpx-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
        Supprimer l'utilisateur
      </button>
    </form>
  </div>
</div>
@endsection