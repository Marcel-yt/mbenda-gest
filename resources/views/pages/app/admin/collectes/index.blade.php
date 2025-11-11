@extends('layouts.app-admin')

@section('title', 'Collectes')
@section('page_title', 'Toutes les collectes')

@section('content')
<div class="bg-white border rounded-xl shadow-sm overflow-hidden">
  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
      <thead class="bg-gray-50 text-xs font-semibold text-gray-600">
        <tr>
          <th class="px-4 py-3 text-left">ID</th>
          <th class="px-4 py-3 text-left">Tontine</th>
          <th class="px-4 py-3 text-left">Client</th>
          <th class="px-4 py-3 text-left">Montant</th>
          <th class="px-4 py-3 text-left">Date / Heure</th>
          <th class="px-4 py-3 text-left">Agent</th>
          <th class="px-4 py-3 text-right">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        @forelse($collectes as $c)
          @php $currency = $c->tontine?->settings['currency'] ?? 'XAF'; @endphp
          <tr class="hover:bg-gray-50">
            <td class="px-4 py-3">{{ $c->id }}</td>
            <td class="px-4 py-3">
              {{ $c->tontine?->code ?? '-' }}
            </td>
            <td class="px-4 py-3">
              @php
                $clientFirst = $c->tontine?->client?->first_name ?? '';
                $clientLast  = $c->tontine?->client?->last_name ?? '';
                $clientName  = trim($clientFirst.' '.$clientLast);
              @endphp
              {{ $clientName !== '' ? $clientName : ($c->tontine?->client?->name ?? '-') }}
            </td>
            <td class="px-4 py-3">{{ number_format($c->amount ?? 0, 2) }} {{ $currency }}</td>
            <td class="px-4 py-3">{{ optional($c->collected_at ?? $c->created_at)->format('d/m/Y H:i') }}</td>
            <td class="px-4 py-3">
              @php
                $agentFirst = $c->user?->first_name ?? ($c->agent?->first_name ?? '');
                $agentLast  = $c->user?->last_name ?? ($c->agent?->last_name ?? '');
                $agentName  = trim($agentFirst.' '.$agentLast);
              @endphp
              {{ $agentName !== '' ? $agentName : ($c->user?->name ?? $c->user?->email ?? $c->agent?->email ?? '-') }}
            </td>
            <td class="px-4 py-3 text-right">
              <a href="{{ route('admin.collectes.show', $c->id) }}"
                 class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md text-sm font-medium bg-white border border-gray-200 text-gray-700 hover:bg-gray-50">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                  <path d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7z" />
                </svg>
                <span>Voir</span>
              </a>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">Aucune collecte</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @php
    $total   = $collectes->total();
    $from    = $collectes->firstItem();
    $to      = $collectes->lastItem();
    $current = $collectes->currentPage();
    $last    = $collectes->lastPage();
    $start   = max(1, $current - 2);
    $end     = min($last, $current + 2);
    if (($end - $start) < 4) { $start = max(1, $end - 4); }
  @endphp
  <div class="px-4 py-3 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
    <div class="text-xs text-gray-600">
      Affichage de {{ $from ?? 0 }} à {{ $to ?? 0 }} sur {{ $total }} collectes
    </div>
    <nav class="inline-flex items-center gap-1" aria-label="Pagination">
      {{-- Première page --}}
      <a href="{{ $current > 1 ? $collectes->url(1) : '#' }}"
         class="px-3 py-2 border rounded-md text-sm {{ $current > 1 ? 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' : 'bg-gray-100 text-gray-400 border-gray-200 pointer-events-none' }}"
         aria-disabled="{{ $current <= 1 ? 'true' : 'false' }}">«</a>
      {{-- Précédent --}}
      <a href="{{ $collectes->previousPageUrl() ?: '#' }}"
         class="px-3 py-2 border rounded-md text-sm {{ $collectes->onFirstPage() ? 'bg-gray-100 text-gray-400 border-gray-200 pointer-events-none' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}"
         aria-disabled="{{ $collectes->onFirstPage() ? 'true' : 'false' }}">Précédent</a>
      {{-- Pages --}}
      @for ($i = $start; $i <= $end; $i++)
        <a href="{{ $collectes->url($i) }}"
           class="px-3 py-2 border rounded-md text-sm {{ $i === $current ? 'bg-[var(--mb-primary)] text-white border-[var(--mb-primary)]' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}">
          {{ $i }}
        </a>
      @endfor
      {{-- Suivant --}}
      <a href="{{ $collectes->hasMorePages() ? $collectes->nextPageUrl() : '#' }}"
         class="px-3 py-2 border rounded-md text-sm {{ $collectes->hasMorePages() ? 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' : 'bg-gray-100 text-gray-400 border-gray-200 pointer-events-none' }}"
         aria-disabled="{{ $collectes->hasMorePages() ? 'false' : 'true' }}">Suivant</a>
      {{-- Dernière page --}}
      <a href="{{ $current < $last ? $collectes->url($last) : '#' }}"
         class="px-3 py-2 border rounded-md text-sm {{ $current < $last ? 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' : 'bg-gray-100 text-gray-400 border-gray-200 pointer-events-none' }}"
         aria-disabled="{{ $current >= $last ? 'true' : 'false' }}">»</a>
    </nav>
  </div>
</div>
@endsection