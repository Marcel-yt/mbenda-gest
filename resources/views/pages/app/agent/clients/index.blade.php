@extends('layouts.app-agent')

@section('title', 'Clients')
@section('page_title', 'Clients')

@section('content')
@php
  /** @var \Illuminate\Pagination\LengthAwarePaginator $clients */
@endphp

<div class="space-y-6 max-w-7xl mx-auto">
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-semibold text-gray-900">Liste des clients</h1>
    <a href="{{ route('clients.create') }}" class="mb-btn-secondary inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm">
      + Nouveau client
    </a>
  </div>

  <section class="bg-white border rounded-xl p-0 overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
      <table class="min-w-full w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 w-12">#</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Nom</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Tél</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Adresse</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Statut</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Créé le</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Créé par</th>
            <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Actions</th>
          </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-100">
          @forelse($clients as $client)
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-3 text-sm text-gray-700">
                {{ $loop->iteration + ($clients->currentPage()-1)*$clients->perPage() }}
              </td>

              <td class="px-4 py-3 text-sm text-gray-700">
                <div class="font-medium">{{ $client->first_name }} {{ $client->last_name }}</div>
              </td>

              <td class="px-4 py-3 text-sm text-gray-700">{{ $client->phone ?? '—' }}</td>

              <td class="px-4 py-3 text-sm text-gray-700">{{ $client->address ?? '—' }}</td>

              <td class="px-4 py-3 text-sm">
                @if(isset($client->statut))
                  <div class="flex items-center gap-2">
                    <span class="inline-block w-2.5 h-2.5 rounded-full" style="background: {{ $client->statut ? '#16A34A' : '#DC2626' }};"></span>
                    <span class="{{ $client->statut ? 'text-green-600' : 'text-red-600' }} text-sm font-medium">
                      {{ $client->statut ? 'Actif' : 'Désactivé' }}
                    </span>
                  </div>
                @else
                  —
                @endif
              </td>

              <td class="px-4 py-3 text-sm text-gray-700">
                {{ $client->created_at?->format('d/m/Y H:i') ?? '—' }}
              </td>

              <td class="px-4 py-3 text-sm text-gray-700">
                {{ $client->creatorAgent?->email ?? '—' }}
              </td>

              <td class="px-4 py-3 text-sm text-right space-x-2">
                <a href="{{ route('clients.show', $client) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-blue-50 text-blue-600 hover:bg-blue-100" title="Voir">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </a>

                @if(auth()->user()->isAgent() && $client->created_by_agent_id === auth()->id())
                  <a href="{{ route('clients.edit', $client) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-yellow-50 text-yellow-600 hover:bg-yellow-100" title="Éditer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536M4 13.5V20h6.5L20.768 9.732a2 2 0 00-2.828-2.828L7.5 17.172V13.5H4z"/></svg>
                  </a>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="px-4 py-10 text-center text-sm text-gray-500">Aucun client trouvé.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="p-4 flex items-center justify-between">
      <div class="text-sm text-gray-500">Affichage {{ $clients->firstItem() ?? 0 }}‑{{ $clients->lastItem() ?? 0 }} sur {{ $clients->total() }}</div>
      <div>{{ $clients->links() }}</div>
    </div>
  </section>
</div>
@endsection