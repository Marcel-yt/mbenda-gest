@extends('layouts.app-admin')

@section('title', 'Tontines')
@section('page_title', 'Toutes les tontines')

@section('content')
@php /** @var \Illuminate\Pagination\LengthAwarePaginator $tontines */ @endphp

<div class="space-y-6">
  {{-- En-tête avec statistiques --}}
  <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs font-medium text-blue-600 uppercase tracking-wide mb-1">Total Tontines</p>
          <p class="text-3xl font-bold text-blue-900">{{ $tontines->total() }}</p>
        </div>
        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center shadow-sm">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
          </svg>
        </div>
      </div>
    </div>
    
    <div class="bg-gradient-to-br from-green-50 to-emerald-100 border border-green-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs font-medium text-green-600 uppercase tracking-wide mb-1">Actives</p>
          <p class="text-3xl font-bold text-green-900">{{ $tontines->where('status', 'active')->count() }}</p>
        </div>
        <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-600 rounded-full flex items-center justify-center shadow-sm">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
      </div>
    </div>
    
    <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs font-medium text-purple-600 uppercase tracking-wide mb-1">Terminées</p>
          <p class="text-3xl font-bold text-purple-900">{{ $tontines->where('status', 'completed')->count() }}</p>
        </div>
        <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center shadow-sm">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
        </div>
      </div>
    </div>
    
    <div class="bg-gradient-to-br from-yellow-50 to-orange-100 border border-yellow-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs font-medium text-orange-600 uppercase tracking-wide mb-1">Brouillons</p>
          <p class="text-3xl font-bold text-orange-900">{{ $tontines->where('status', 'draft')->count() }}</p>
        </div>
        <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-600 rounded-full flex items-center justify-center shadow-sm">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
          </svg>
        </div>
      </div>
    </div>
  </div>

  {{-- Filtres modernes --}}
  <form id="tontine-filters" method="GET" action="{{ route('admin.tontines.index') }}" class="mb-6">
    <div class="bg-white border rounded-xl p-4 shadow-sm">
      <div class="flex items-end gap-4 flex-wrap">
        <div class="flex-1 min-w-[300px]">
          <label class="text-xs font-medium text-gray-700 mb-2 block">Recherche</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
            </div>
            <input type="text" name="q" value="{{ old('q', $q ?? request('q','')) }}" 
                   class="pl-10 pr-10 mb-input w-full"
                   placeholder="Nom, prénom, email, téléphone, code tontine..." autocomplete="off">
            @if(request('q'))
            <button type="button" id="clear-search" 
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
            @endif
          </div>
        </div>
        
        <div class="w-40">
          <label class="text-xs font-medium text-gray-700 mb-2 block">Créée du</label>
          <input type="date" name="created_from" value="{{ old('created_from', $created_from ?? request('created_from','')) }}" 
                 class="mb-input w-full">
        </div>
        
        <div class="w-40">
          <label class="text-xs font-medium text-gray-700 mb-2 block">Au</label>
          <input type="date" name="created_to" value="{{ old('created_to', $created_to ?? request('created_to','')) }}" 
                 class="mb-input w-full">
        </div>
        
        <div class="w-44">
          <label class="text-xs font-medium text-gray-700 mb-2 block">Statut</label>
          <select name="status" class="mb-input w-full">
            <option value="">Tous les statuts</option>
            <option value="draft" @selected(($status ?? request('status'))==='draft')>Brouillon</option>
            <option value="active" @selected(($status ?? request('status'))==='active')>Actif</option>
            <option value="completed" @selected(($status ?? request('status'))==='completed')>Terminée</option>
            <option value="paid" @selected(($status ?? request('status'))==='paid')>Payée</option>
            <option value="cancelled" @selected(($status ?? request('status'))==='cancelled')>Annulée</option>
          </select>
        </div>
        
        <button type="button" id="reset-dates"
                class="px-4 py-2.5 rounded-lg border-2 border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-colors duration-200">
          <svg class="w-4 h-4 inline-block mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
          </svg>
          Réinitialiser
        </button>
      </div>
      
      {{-- Filtres actifs --}}
      @if(request('q') || request('created_from') || request('created_to') || request('status'))
      <div class="mt-4 pt-4 border-t border-gray-200">
        <div class="flex items-center gap-2 flex-wrap">
          <span class="text-xs font-medium text-gray-500">Filtres actifs:</span>
          @if(request('q'))
          <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-full">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            {{ request('q') }}
          </span>
          @endif
          @if(request('created_from'))
          <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-700 text-xs font-medium rounded-full">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Du: {{ \Carbon\Carbon::parse(request('created_from'))->format('d/m/Y') }}
          </span>
          @endif
          @if(request('created_to'))
          <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-700 text-xs font-medium rounded-full">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Au: {{ \Carbon\Carbon::parse(request('created_to'))->format('d/m/Y') }}
          </span>
          @endif
          @if(request('status'))
          <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-50 text-purple-700 text-xs font-medium rounded-full">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            @php
              $statusLabels = ['draft' => 'Brouillon', 'active' => 'Actif', 'completed' => 'Terminée', 'paid' => 'Payée', 'cancelled' => 'Annulée'];
            @endphp
            {{ $statusLabels[request('status')] ?? request('status') }}
          </span>
          @endif
        </div>
      </div>
      @endif
    </div>
  </form>

  <section class="bg-gradient-to-br from-white to-gray-50 border rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
          <tr>
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Code</th>
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Client</th>
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Montant</th>
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Période</th>
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Créateur</th>
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Statut</th>
            <th class="px-5 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
          @forelse($tontines as $t)
            <tr class="hover:bg-gradient-to-r hover:from-blue-50/30 hover:to-purple-50/30 transition-all duration-200">
              <td class="px-5 py-4">
                <div class="flex items-center gap-2">
                  <div class="w-8 h-8 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-lg flex items-center justify-center shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                    </svg>
                  </div>
                  <span class="font-semibold text-gray-900">{{ $t->code }}</span>
                </div>
              </td>
              
              <td class="px-5 py-4">
                <div class="flex items-center gap-3">
                  <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                    @if($t->client)
                      {{ strtoupper(substr($t->client->first_name, 0, 1) . substr($t->client->last_name, 0, 1)) }}
                    @else
                      ?
                    @endif
                  </div>
                  <div>
                    <div class="font-semibold text-gray-900">{{ $t->client?->first_name }} {{ $t->client?->last_name }}</div>
                    @if($t->client?->email)
                    <div class="text-xs text-gray-500">{{ $t->client->email }}</div>
                    @endif
                  </div>
                </div>
              </td>
              
              <td class="px-5 py-4">
                <div class="flex items-center gap-2">
                  <div class="w-8 h-8 bg-gradient-to-br from-green-100 to-emerald-200 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                  </div>
                  <div>
                    <div class="font-semibold text-gray-900">{{ number_format($t->daily_amount, 0, ',', ' ') }}</div>
                    <div class="text-xs text-gray-500">{{ $t->settings['currency'] ?? 'XAF' }} /jour</div>
                  </div>
                </div>
              </td>
              
              <td class="px-5 py-4">
                <div class="space-y-1">
                  <div class="flex items-center gap-2 text-sm text-gray-700">
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $t->start_date?->format('d/m/Y') ?? '—' }}
                  </div>
                  <div class="flex items-center gap-2 text-sm text-gray-700">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $t->expected_end_date?->format('d/m/Y') ?? '—' }}
                  </div>
                </div>
              </td>
              
              <td class="px-5 py-4 text-sm text-gray-700">
                @if($t->creator)
                <div class="flex items-center gap-2">
                  <div class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white text-xs font-bold">
                    {{ strtoupper(substr($t->creator->email, 0, 1)) }}
                  </div>
                  <span class="text-sm">{{ $t->creator->email }}</span>
                </div>
                @else
                <span class="text-gray-400">—</span>
                @endif
              </td>
              
              <td class="px-5 py-4">
                @php
                  $statusConfig = [
                    'draft' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>'],
                    'active' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                    'completed' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                    'paid' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                  ];
                  $config = $statusConfig[$t->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => ''];
                @endphp
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold {{ $config['bg'] }} {{ $config['text'] }}">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {!! $config['icon'] !!}
                  </svg>
                  {{ $t->status_label }}
                </span>
              </td>
              
              <td class="px-5 py-4 text-sm text-right">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.tontines.show', $t->id) }}" 
                     class="group relative inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg bg-gradient-to-br from-blue-50 to-blue-100 text-blue-700 hover:from-blue-100 hover:to-blue-200 transition-all duration-200 shadow-sm hover:shadow font-medium text-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <span>Voir</span>
                    <span class="absolute -top-8 right-0 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">Détails</span>
                  </a>
                  {{-- Edit action removed: editing moved to admin workflow outside UI --}}
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-5 py-16">
                <div class="flex flex-col items-center justify-center text-center">
                  <div class="w-20 h-20 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                  </div>
                  <h3 class="text-lg font-semibold text-gray-700 mb-1">Aucune tontine trouvée</h3>
                  <p class="text-sm text-gray-500 mb-4">Aucune tontine ne correspond à vos critères de recherche.</p>
                  @if(request('q') || request('created_from') || request('created_to') || request('status'))
                  <button type="button" onclick="window.location.href='{{ route('admin.tontines.index') }}'"
                          class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-[#0078B7] to-[#005A8C] text-white text-sm font-medium rounded-lg hover:shadow-lg transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Réinitialiser les filtres
                  </button>
                  @endif
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination améliorée --}}
    @if($tontines->hasPages())
    <div class="bg-gray-50 px-5 py-4 border-t border-gray-200">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <span class="text-sm text-gray-700 font-medium">Affichage</span>
          <span class="px-2 py-1 bg-white border border-gray-300 rounded text-sm font-semibold text-gray-900">{{ $tontines->firstItem() ?? 0 }}‑{{ $tontines->lastItem() ?? 0 }}</span>
          <span class="text-sm text-gray-700 font-medium">sur</span>
          <span class="px-2 py-1 bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded text-sm font-semibold text-blue-700">{{ $tontines->total() }}</span>
        </div>
        <div>{{ $tontines->appends(request()->query())->links() }}</div>
      </div>
    </div>
    @endif
  </section>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('tontine-filters');
  if (!form) return;
  const submit = () => form.submit();

  // Recherche avec debounce
  const q = form.querySelector('input[name="q"]');
  if (q) {
    q.setAttribute('autocomplete','off');
    let timeout;
    q.addEventListener('input', () => {
      clearTimeout(timeout);
      timeout = setTimeout(submit, 500);
    });
    q.addEventListener('keydown', (e) => { 
      if (e.key === 'Escape') { 
        q.value=''; 
        submit(); 
      }
    });
  }

  // Bouton clear search
  const clearBtn = document.getElementById('clear-search');
  if (clearBtn && q) {
    clearBtn.addEventListener('click', () => {
      q.value = '';
      submit();
    });
  }

  // Auto-submit pour dates et statut
  ['created_from','created_to'].forEach(n => {
    const el = form.querySelector(`[name="${n}"]`);
    if (el) el.addEventListener('change', submit);
  });

  const status = form.querySelector('select[name="status"]');
  if (status) status.addEventListener('change', submit);

  // Reset dates
  const resetBtn = document.getElementById('reset-dates');
  if (resetBtn) {
    resetBtn.addEventListener('click', () => {
      const from = form.querySelector('input[name="created_from"]');
      const to = form.querySelector('input[name="created_to"]');
      if (from) from.value = '';
      if (to) to.value = '';
      submit();
    });
  }
});
</script>
@endsection