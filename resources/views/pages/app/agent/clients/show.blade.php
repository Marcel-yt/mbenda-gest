@extends('layouts.app-agent')

@section('title', 'Détails client')
@section('page_title', 'Détails client')

@section('content')
@php /** @var \App\Models\Client $client */ @endphp

<div class="max-w-6xl mx-auto space-y-6">
  {{-- Breadcrumb Navigation --}}
  <nav class="flex items-center gap-2 text-sm text-gray-600">
    <a href="{{ route('clients.index') }}" class="hover:text-blue-600 transition-colors flex items-center gap-1" style="color: var(--mb-primary);">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
      </svg>
      Clients
    </a>
    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
    </svg>
    <span class="text-gray-900 font-medium">{{ $client->first_name }} {{ $client->last_name }}</span>
  </nav>

  {{-- Alerts Section --}}
  @if(session('success'))
    <div class="bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 rounded-lg p-4 shadow-sm">
      <div class="flex items-start gap-3">
        <div class="flex-shrink-0">
          <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <div class="flex-1">
          <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
      </div>
    </div>
  @endif

  @if(session('info'))
    <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 border-l-4 border-yellow-500 rounded-lg p-4 shadow-sm">
      <div class="flex items-start gap-3">
        <div class="flex-shrink-0">
          <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <div class="flex-1">
          <p class="text-sm font-medium text-yellow-800">{{ session('info') }}</p>
        </div>
      </div>
    </div>
  @endif

  @if($errors->any())
    <div class="bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 rounded-lg p-4 shadow-sm">
      <div class="flex items-start gap-3">
        <div class="flex-shrink-0">
          <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <div class="flex-1">
          <h3 class="text-sm font-semibold text-red-800 mb-2">Erreurs</h3>
          <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  @endif

  {{-- Header Card --}}
  <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-start justify-between gap-4">
      <div class="flex items-start gap-4">
        <div class="w-16 h-16 rounded-xl flex items-center justify-center text-white shadow-lg" style="background: var(--mb-primary);">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
          </svg>
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-900">{{ $client->first_name }} {{ $client->last_name }}</h1>
          <p class="text-sm text-gray-600 mt-1">Informations détaillées du client</p>
          <div class="mt-3">
            @if(isset($client->statut))
              @if($client->statut)
                <span class="mb-ind-active">Actif</span>
              @else
                <span class="mb-ind-inactive">Inactif</span>
              @endif
            @endif
          </div>
        </div>
      </div>
      <div class="flex items-center gap-2">
        @if(auth()->user()->isAgent() && $client->created_by_agent_id === auth()->id())
          <a href="{{ route('clients.edit', $client) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-white font-semibold shadow-md transition-all hover:shadow-lg" style="background: var(--mb-tertiary);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Modifier
          </a>
        @endif
      </div>
    </div>
  </div>

  <div class="grid gap-6 md:grid-cols-3">
    {{-- Photo Section --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="px-6 py-4" style="background: var(--mb-primary);">
        <h2 class="text-lg font-semibold text-white">Photo de profil</h2>
      </div>
      <div class="p-6 flex flex-col items-center">
        <div class="w-40 h-40 rounded-full overflow-hidden border-4 shadow-lg flex items-center justify-center" style="border-color: var(--mb-primary);">
          @if($client->photo_profil)
            <img src="{{ asset('storage/'.$client->photo_profil) }}" alt="photo" class="w-full h-full object-cover" />
          @else
            <div class="w-full h-full flex items-center justify-center text-white text-4xl font-bold" style="background: var(--mb-primary);">
              {{ strtoupper(substr($client->first_name ?? 'C', 0, 1)) }}{{ strtoupper(substr($client->last_name ?? 'L', 0, 1)) }}
            </div>
          @endif
        </div>
        <div class="mt-4 text-center">
          <p class="text-sm font-semibold text-gray-900">{{ $client->first_name }} {{ $client->last_name }}</p>
          <p class="text-xs text-gray-500 mt-1">Client ID: #{{ $client->id }}</p>
        </div>
      </div>
    </div>

    {{-- Information Card --}}
    <div class="md:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="px-6 py-4" style="background: var(--mb-primary);">
        <h2 class="text-lg font-semibold text-white">Informations personnelles</h2>
      </div>
      <div class="p-6 space-y-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
          <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 text-white" style="background: var(--mb-primary);">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-xs text-gray-500 uppercase tracking-wider">Prénom</div>
              <div class="mt-1 text-sm font-semibold text-gray-900">{{ $client->first_name ?? '—' }}</div>
            </div>
          </div>

          <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
              <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-xs text-gray-500 uppercase tracking-wider">Nom</div>
              <div class="mt-1 text-sm font-semibold text-gray-900">{{ $client->last_name ?? '—' }}</div>
            </div>
          </div>

          <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
              <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-xs text-gray-500 uppercase tracking-wider">Téléphone</div>
              <div class="mt-1 text-sm font-semibold text-gray-900">{{ $client->phone ?? '—' }}</div>
            </div>
          </div>

          <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
              <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-xs text-gray-500 uppercase tracking-wider">Indicatif</div>
              <div class="mt-1 text-sm font-semibold text-gray-900">{{ $client->indicatif ?? '—' }}</div>
            </div>
          </div>

          <div class="sm:col-span-2 flex items-start gap-3">
            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
              <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-xs text-gray-500 uppercase tracking-wider">Adresse</div>
              <div class="mt-1 text-sm font-semibold text-gray-900">{{ $client->address ?? '—' }}</div>
            </div>
          </div>

          <div class="sm:col-span-2 flex items-start gap-3">
            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
              <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-xs text-gray-500 uppercase tracking-wider">Notes</div>
              <div class="mt-1 text-sm font-medium text-gray-900 whitespace-pre-wrap">{{ $client->notes ?? '—' }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Metadata Card --}}
  <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4" style="background: var(--mb-secondary);">
      <h2 class="text-lg font-semibold text-white">Informations système</h2>
    </div>
    <div class="p-6">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div class="flex items-start gap-3">
          <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 text-white" style="background: var(--mb-secondary);">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Date de création</div>
            <div class="mt-1 text-sm font-semibold text-gray-900">{{ $client->created_at?->format('d/m/Y H:i') ?? '—' }}</div>
          </div>
        </div>

        <div class="flex items-start gap-3">
          <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Créé par</div>
            <div class="mt-1 text-sm font-semibold text-gray-900">{{ $client->creatorAgent?->email ?? '—' }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Actions (delete) --}}
  @if(in_array(auth()->user()->role, ['admin','super_admin']))
    <div class="bg-white rounded-xl shadow-sm border border-red-200 overflow-hidden">
      <div class="px-6 py-4 bg-red-50">
        <h2 class="text-lg font-semibold text-red-900">Zone de danger</h2>
      </div>
      <div class="p-6">
        <div class="flex items-start justify-between gap-4">
          <div>
            <h3 class="text-sm font-semibold text-gray-900">Supprimer ce client</h3>
            <p class="text-sm text-gray-600 mt-1">Cette action est irréversible. Toutes les données associées seront perdues.</p>
          </div>
          <form action="{{ route('clients.destroy', $client) }}" method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?');">
            @csrf
            @method('delete')
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition-colors shadow-md">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
              </svg>
              Supprimer
            </button>
          </form>
        </div>
      </div>
    </div>
  @endif
</div>
@endsection