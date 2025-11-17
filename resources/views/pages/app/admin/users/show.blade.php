@extends('layouts.app-admin')

@section('title', 'Voir utilisateur')
@section('page_title', 'Détails utilisateur')

@section('content')
@php /** @var \App\Models\User $user */ @endphp

<div class="max-w-5xl mx-auto space-y-6">
  {{-- Header Section with Avatar --}}
  <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-start gap-6">
      {{-- Avatar --}}
      <div class="flex-shrink-0">
        <div class="relative">
          <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-lg">
            <img class="w-full h-full object-cover"
                 src="{{ $user->photo_profil ? Storage::url($user->photo_profil).'?t='.($user->updated_at?->timestamp ?? time()) : asset('images/default-avatar.png') }}"
                 alt="{{ $user->first_name }} {{ $user->last_name }}">
          </div>
          {{-- Status indicator on avatar --}}
          <span class="absolute bottom-1 right-1 w-5 h-5 {{ $user->active ? 'bg-green-500' : 'bg-gray-400' }} border-2 border-white rounded-full"></span>
        </div>
      </div>

      {{-- User Info --}}
      <div class="flex-1 min-w-0">
        <div class="flex items-start justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</h1>
            <p class="mt-1 text-sm text-gray-600 flex items-center gap-2">
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
              </svg>
              {{ $user->email }}
            </p>
            <div class="mt-3 flex items-center gap-3">
              {{-- Role Badge --}}
              <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold
                {{ $user->role === 'admin' ? 'bg-gradient-to-r from-purple-500 to-purple-600 text-white' : ($user->role === 'agent' ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white' : 'bg-gradient-to-r from-green-500 to-green-600 text-white') }}">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                </svg>
                {{ ucfirst($user->role) }}
              </span>
              {{-- Status Badge --}}
              <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold
                {{ $user->active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $user->active ? 'bg-green-500 animate-pulse' : 'bg-gray-400' }}"></span>
                {{ $user->active ? 'Actif' : 'Désactivé' }}
              </span>
              @if($user->is_super_admin)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-yellow-500 to-orange-500 text-white">
                  <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                  </svg>
                  Super Admin
                </span>
              @endif
            </div>
          </div>

          {{-- Action Buttons --}}
          <div class="flex items-center gap-2 ml-4">
            <a href="{{ route('admin.users.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all shadow-sm"
               title="Retour à la liste">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
              </svg>
              Retour
            </a>
            <a href="{{ route('admin.users.edit', $user->id) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg text-sm font-medium hover:from-blue-600 hover:to-blue-700 transition-all shadow-sm"
               title="Modifier cet utilisateur">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
              </svg>
              Éditer
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Information Cards Grid --}}
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    {{-- Personal Information Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
      <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
          </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900">Informations personnelles</h3>
      </div>
      <div class="space-y-4">
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Prénom</div>
            <div class="mt-1 text-sm font-medium text-gray-900">{{ $user->first_name ?? '—' }}</div>
          </div>
        </div>
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Nom</div>
            <div class="mt-1 text-sm font-medium text-gray-900">{{ $user->last_name ?? '—' }}</div>
          </div>
        </div>
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Téléphone</div>
            <div class="mt-1 text-sm font-medium text-gray-900">{{ $user->phone ?? '—' }}</div>
          </div>
        </div>
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Couleur</div>
            <div class="mt-1 flex items-center gap-2">
              <span class="inline-block w-6 h-6 rounded-lg border-2 border-gray-200 shadow-sm" style="background: {{ $user->color_hex ?? '#E5E7EB' }};"></span>
              <span class="text-sm font-medium text-gray-900">{{ $user->color_hex ?? '—' }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Account Information Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
      <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
          </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900">Compte & Accès</h3>
      </div>
      <div class="space-y-4">
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Email</div>
            <div class="mt-1 text-sm font-medium text-gray-900 break-all">{{ $user->email ?? '—' }}</div>
          </div>
        </div>
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Rôle</div>
            <div class="mt-1 text-sm font-medium text-gray-900">{{ ucfirst($user->role ?? '—') }}</div>
          </div>
        </div>
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Statut du compte</div>
            <div class="mt-1 text-sm font-medium {{ $user->active ? 'text-green-600' : 'text-gray-600' }}">
              {{ $user->active ? 'Actif' : 'Désactivé' }}
            </div>
            @if(!$user->active)
              <p class="mt-1 text-xs text-gray-500">Cet utilisateur ne peut plus se connecter.</p>
            @endif
          </div>
        </div>
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Dernière connexion</div>
            <div class="mt-1 text-sm font-medium text-gray-900">{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y à H:i') : 'Jamais connecté' }}</div>
          </div>
        </div>
      </div>
    </div>

    {{-- Creation Info Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 md:col-span-2">
      <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900">Informations système</h3>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Créé par</div>
            <div class="mt-1 text-sm font-medium text-gray-900">{{ $user->creator?->email ?? 'Système' }}</div>
          </div>
        </div>
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Créé le</div>
            <div class="mt-1 text-sm font-medium text-gray-900">{{ $user->created_at?->format('d/m/Y à H:i') ?? '—' }}</div>
          </div>
        </div>
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Modifié le</div>
            <div class="mt-1 text-sm font-medium text-gray-900">{{ $user->updated_at?->format('d/m/Y à H:i') ?? '—' }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>

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

  {{-- Danger Zone --}}
  <div class="bg-white rounded-xl shadow-sm border-2 border-red-200 p-5">
    <div class="flex items-start justify-between">
      <div class="flex-1">
        <div class="flex items-center gap-2">
          <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
          </svg>
          <h3 class="text-lg font-semibold text-red-900">Zone dangereuse</h3>
        </div>
        <p class="mt-2 text-sm text-gray-600">La suppression de cet utilisateur est irréversible. Toutes les données associées seront perdues définitivement.</p>
      </div>
      <form action="{{ route('admin.users.destroy', $user->id) }}" method="post" onsubmit="return confirm('⚠️ Êtes-vous absolument sûr de vouloir supprimer cet utilisateur ?\n\nCette action est IRRÉVERSIBLE et supprimera :\n• Le compte utilisateur\n• Toutes les données associées\n• L\'historique des actions\n\nTapez OUI dans la confirmation suivante si vous voulez continuer.');" class="ml-4">
        @csrf
        @method('delete')
        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg text-sm font-medium hover:from-red-700 hover:to-red-800 transition-all shadow-sm"
                title="Supprimer définitivement cet utilisateur">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
          </svg>
          Supprimer l'utilisateur
        </button>
      </form>
    </div>
  </div>
</div>
@endsection