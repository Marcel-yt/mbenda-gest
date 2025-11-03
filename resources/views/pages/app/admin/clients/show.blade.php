@extends('layouts.app-admin')

@section('title', 'Détails client')
@section('page_title', 'Détails client')

@section('content')
@php /** @var \App\Models\Client $client */ @endphp

<div class="max-w-4xl mx-auto space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-semibold text-gray-900">{{ $client->first_name }} {{ $client->last_name }}</h1>
    </div>

    <div class="flex items-center gap-2">
      <a href="{{ route('admin.clients.index') }}" class="mb-link px-3 py-2 rounded text-sm">← Retour</a>
      <a href="{{ route('admin.clients.edit', $client) }}" class="mb-btn-primary px-3 py-2 rounded text-sm">Éditer</a>
    </div>
  </div>

  <section class="bg-white shadow sm:rounded-lg p-6">
    <div class="md:flex md:items-start md:space-x-6">
      <div class="flex-1">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700">
          <div>
            <div class="text-xs text-gray-500">Prénom</div>
            <div class="mt-1 font-medium text-gray-900">{{ $client->first_name ?? '—' }}</div>
          </div>

          <div>
            <div class="text-xs text-gray-500">Nom</div>
            <div class="mt-1 font-medium text-gray-900">{{ $client->last_name ?? '—' }}</div>
          </div>

          <div>
            <div class="text-xs text-gray-500">Téléphone</div>
            <div class="mt-1 font-medium text-gray-900">{{ $client->phone ?? '—' }}</div>
          </div>

          <div>
            <div class="text-xs text-gray-500">Créé par</div>
            <div class="mt-1 font-medium text-gray-900">{{ $client->creatorAgent?->email ?? '—' }}</div>
          </div>

          <div>
            <div class="text-xs text-gray-500">Date d'inscription</div>
            <div class="mt-1 font-medium text-gray-900">{{ $client->created_at?->format('d/m/Y H:i') ?? '—' }}</div>
          </div>

          <div>
            <div class="text-xs text-gray-500">Indicatif</div>
            <div class="mt-1 font-medium text-gray-900">{{ $client->indicatif ?? '—' }}</div>
          </div>

          <div>
            <div class="text-xs text-gray-500">Statut</div>
            <div class="mt-1 flex items-center gap-2">
              <span class="{{ isset($client->statut) ? ($client->statut ? 'mb-ind-active' : 'mb-ind-inactive') : '' }}"></span>
              <span class="font-medium text-sm {{ (isset($client->statut) && $client->statut) ? 'text-gray-900' : 'text-gray-700' }}">
                {{ isset($client->statut) ? ($client->statut ? 'Actif' : 'Désactivé') : '—' }}
              </span>
            </div>
          </div>

          <div class="sm:col-span-2">
            <div class="text-xs text-gray-500">Adresse</div>
            <div class="mt-1 font-medium text-gray-900">{{ $client->address ?? '—' }}</div>
          </div>

          <div class="sm:col-span-2">
            <div class="text-xs text-gray-500">Notes</div>
            <div class="mt-1 font-medium whitespace-pre-wrap text-gray-900">{{ $client->notes ?? '—' }}</div>
          </div>
        </div>
      </div>

      <div class="mt-6 md:mt-0 flex-shrink-0">
        <div class="w-36 h-36 rounded-full overflow-hidden border bg-gray-50 flex items-center justify-center">
          @if($client->photo_profil)
            <img src="{{ asset('storage/'.$client->photo_profil) }}" alt="photo" class="w-full h-full object-cover" />
          @else
            {{-- Placeholder avatar with initials --}}
            <span class="text-2xl font-semibold text-gray-700">
              {{ strtoupper(substr($client->first_name ?? '', 0, 1) . ($client->last_name ? substr($client->last_name, 0, 1) : '')) }}
            </span>
          @endif
        </div>
        <div class="mt-3 text-xs text-gray-500 text-center">Client</div>
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

  <div class="mt-6 border-t pt-4 flex justify-end gap-3">
    <form action="{{ route('admin.clients.destroy', $client) }}" method="post" onsubmit="return confirm('Supprimer ce client ?');">
      @csrf
      @method('delete')
      <button type="submit" class="px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Supprimer</button>
    </form>
  </div>
</div>
@endsection