@extends('layouts.app-admin')

@section('title', 'Détails client')
@section('page_title', 'Détails client')

@section('content')
@php /** @var \App\Models\Client $client */ @endphp

<div class="max-w-5xl mx-auto space-y-6">
  {{-- Header Section with Avatar --}}
  <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-start gap-6">
      {{-- Avatar --}}
      <div class="flex-shrink-0">
        <div class="relative">
          <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-lg bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center">
            @if($client->photo_profil)
              <img class="w-full h-full object-cover" src="{{ asset('storage/'.$client->photo_profil) }}" alt="{{ $client->first_name }} {{ $client->last_name }}">
            @else
              <span class="text-2xl font-bold text-green-700">
                {{ strtoupper(substr($client->first_name ?? '', 0, 1) . ($client->last_name ? substr($client->last_name, 0, 1) : '')) }}
              </span>
            @endif
          </div>
          {{-- Status indicator on avatar --}}
          <span class="absolute bottom-1 right-1 w-5 h-5 {{ isset($client->statut) && $client->statut ? 'bg-green-500' : 'bg-gray-400' }} border-2 border-white rounded-full"></span>
        </div>
      </div>

      {{-- Client Info --}}
      <div class="flex-1 min-w-0">
        <div class="flex items-start justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $client->first_name }} {{ $client->last_name }}</h1>
            <p class="mt-1 text-sm text-gray-600 flex items-center gap-2">
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
              </svg>
              {{ $client->phone ?? 'Aucun téléphone' }}
            </p>
            <div class="mt-3 flex items-center gap-3">
              {{-- Status Badge --}}
              <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold
                {{ isset($client->statut) && $client->statut ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ isset($client->statut) && $client->statut ? 'bg-green-500 animate-pulse' : 'bg-gray-400' }}"></span>
                {{ isset($client->statut) ? ($client->statut ? 'Actif' : 'Désactivé') : 'Non défini' }}
              </span>
              {{-- Client Badge --}}
              <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                </svg>
                Client
              </span>
            </div>
          </div>

          {{-- Action Buttons --}}
          <div class="flex items-center gap-2 ml-4">
            <a href="{{ route('admin.clients.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all shadow-sm"
               title="Retour à la liste">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
              </svg>
              Retour
            </a>
            <a href="{{ route('admin.clients.edit', $client) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg text-sm font-medium hover:from-blue-600 hover:to-blue-700 transition-all shadow-sm"
               title="Modifier ce client">
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
            <div class="mt-1 text-sm font-medium text-gray-900">{{ $client->first_name ?? '—' }}</div>
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
            <div class="mt-1 text-sm font-medium text-gray-900">{{ $client->last_name ?? '—' }}</div>
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
            <div class="mt-1 text-sm font-medium text-gray-900">{{ $client->phone ?? '—' }}</div>
          </div>
        </div>
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Indicatif</div>
            <div class="mt-1 text-sm font-medium text-gray-900">{{ $client->indicatif ?? '—' }}</div>
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
        <h3 class="text-lg font-semibold text-gray-900">Compte</h3>
      </div>
      <div class="space-y-4">
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Statut du compte</div>
            <div class="mt-1 text-sm font-medium {{ isset($client->statut) && $client->statut ? 'text-green-600' : 'text-gray-600' }}">
              {{ isset($client->statut) ? ($client->statut ? 'Actif' : 'Désactivé') : 'Non défini' }}
            </div>
          </div>
        </div>
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Créé par</div>
            <div class="mt-1 text-sm font-medium text-gray-900">{{ $client->creatorAgent?->email ?? 'Système' }}</div>
          </div>
        </div>
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Date d'inscription</div>
            <div class="mt-1 text-sm font-medium text-gray-900">{{ $client->created_at?->format('d/m/Y à H:i') ?? '—' }}</div>
          </div>
        </div>
      </div>
    </div>

    {{-- Address & Notes Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 md:col-span-2">
      <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900">Informations complémentaires</h3>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Adresse</div>
            <div class="mt-1 text-sm font-medium text-gray-900">{{ $client->address ?? '—' }}</div>
          </div>
        </div>
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Notes</div>
            <div class="mt-1 text-sm font-medium whitespace-pre-wrap text-gray-900">{{ $client->notes ?? '—' }}</div>
          </div>
        </div>
      </div>
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
        <p class="mt-2 text-sm text-gray-600">La suppression de ce client est irréversible. Toutes les données associées (tontines, collectes) seront également affectées.</p>
      </div>
      <form action="{{ route('admin.clients.destroy', $client) }}" method="post" onsubmit="return confirm('⚠️ Êtes-vous absolument sûr de vouloir supprimer ce client ?\n\nCette action est IRRÉVERSIBLE et supprimera :\n• Le compte client\n• Toutes ses tontines\n• Toutes ses collectes\n\nTapez OUI dans la confirmation suivante si vous voulez continuer.');" class="ml-4">
        @csrf
        @method('delete')
        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg text-sm font-medium hover:from-red-700 hover:to-red-800 transition-all shadow-sm"
                title="Supprimer définitivement ce client">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
          </svg>
          Supprimer le client
        </button>
      </form>
    </div>
  </div>
</div>
@endsection