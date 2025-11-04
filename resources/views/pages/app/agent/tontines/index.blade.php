@extends('layouts.app-agent')

@section('title', 'Tontines')
@section('page_title', 'Toutes les tontines')

@section('content')
@php /** @var \Illuminate\Pagination\LengthAwarePaginator $tontines */ @endphp

<div class="flex items-center justify-between mb-4">
  <h2 class="text-lg font-semibold">Toutes les tontines</h2>
  <a href="{{ route('agent.tontines.create') }}" class="mb-btn-primary px-3 py-2 rounded">Créer une tontine</a>
</div>

<section class="bg-white border rounded-xl p-0 overflow-hidden">
  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-3 text-left">Code</th>
          <th class="px-4 py-3 text-left">Client</th>
          <th class="px-4 py-3 text-left">Journalier</th>
          <th class="px-4 py-3 text-left">Début / Fin prévue</th>
          <th class="px-4 py-3 text-left">Agent (créateur)</th>
          <th class="px-4 py-3 text-left">Statut</th>
          <th class="px-4 py-3 text-right">Actions</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-100">
        @forelse($tontines as $t)
          <tr>
            <td class="px-4 py-3 font-medium">{{ $t->code }}</td>
            <td class="px-4 py-3">{{ $t->client?->first_name }} {{ $t->client?->last_name }} <div class="text-xs text-gray-500">{{ $t->client?->email }}</div></td>
            <td class="px-4 py-3">{{ number_format($t->daily_amount,2) }} {{ $t->settings['currency'] ?? 'XAF' }}</td>
            <td class="px-4 py-3">{{ $t->start_date?->format('d/m/Y') }} — {{ $t->expected_end_date?->format('d/m/Y') }}</td>
            <td class="px-4 py-3">{{ $t->creator?->email ?? '-' }}</td>
            <td class="px-4 py-3">
              <span class="inline-block px-2 py-1 rounded text-xs {{ $t->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">{{ ucfirst($t->status) }}</span>
            </td>
            <td class="px-4 py-3 text-right">
              <div class="inline-flex items-center gap-2">
                {{-- Collecter : action primaire --}}
                <a href="{{ url('/agent/collectes/create?tontine_id=' . $t->id) }}"
                   class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                  <!-- svg coin/plus -->
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 8v8M8 12h8" />
                    <ellipse cx="12" cy="6" rx="7" ry="2.5" />
                  </svg>
                  <span>Collecter</span>
                </a>

                {{-- Voir : link neutre --}}
                <a href="{{ route('agent.tontines.show', $t) }}"
                   class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md text-sm font-medium bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 focus:outline-none">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                    <path d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7z" />
                  </svg>
                  <span>Voir</span>
                </a>
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="7" class="px-4 py-10 text-center text-sm text-gray-500">Aucune tontine trouvée.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="p-4">
    {{ $tontines->links() }}
  </div>
</section>
@endsection