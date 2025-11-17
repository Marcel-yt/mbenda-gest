@extends('layouts.app-admin')

@section('title', 'Collectes')
@section('page_title', 'Toutes les collectes')

@section('content')
<div class="space-y-6">
  {{-- Statistiques --}}
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs font-medium text-blue-600 uppercase tracking-wide mb-1">Total Collectes</p>
          <p class="text-3xl font-bold text-blue-900">{{ $collectes->total() }}</p>
        </div>
        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center shadow-sm">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
          </svg>
        </div>
      </div>
    </div>
    
    <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs font-medium text-purple-600 uppercase tracking-wide mb-1">Aujourd'hui</p>
          <p class="text-3xl font-bold text-purple-900">{{ \App\Models\Collecte::whereDate('created_at', today())->count() }}</p>
        </div>
        <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center shadow-sm">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
        </div>
      </div>
    </div>
    
    <div class="bg-gradient-to-br from-green-50 to-emerald-100 border border-green-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs font-medium text-green-600 uppercase tracking-wide mb-1">Cette semaine</p>
          <p class="text-3xl font-bold text-green-900">{{ \App\Models\Collecte::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count() }}</p>
        </div>
        <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-600 rounded-full flex items-center justify-center shadow-sm">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
        </div>
      </div>
    </div>
  </div>

  {{-- Filtres modernes --}}
  <form id="collecte-filters" method="GET" action="{{ route('admin.collectes.index') }}" class="mb-6">
    <div class="bg-white border rounded-xl p-4 shadow-sm">
      <div class="flex items-end gap-4 flex-wrap">
        <div class="flex-1 min-w-[260px]">
          <label class="text-xs font-medium text-gray-700 mb-2 block">Rechercher par Client</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
            </div>
            <input type="text" name="q_client" value="{{ request('q_client', $qClient ?? '') }}" 
                   class="pl-10 pr-10 mb-input w-full"
                   placeholder="Nom, prénom, téléphone, email..." autocomplete="off">
            @if(request('q_client'))
            <button type="button" id="clear-client" 
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
            @endif
          </div>
        </div>
        
        <div class="flex-1 min-w-[260px]">
          <label class="text-xs font-medium text-gray-700 mb-2 block">Rechercher par Agent</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <input type="text" name="q_agent" value="{{ request('q_agent', $qAgent ?? '') }}" 
                   class="pl-10 pr-10 mb-input w-full"
                   placeholder="Nom, prénom, email..." autocomplete="off">
            @if(request('q_agent'))
            <button type="button" id="clear-agent" 
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
            @endif
          </div>
        </div>
        
        <div class="w-40">
          <label class="text-xs font-medium text-gray-700 mb-2 block">Date du</label>
          <input type="date" name="date_from" value="{{ request('date_from', $dateFrom ?? '') }}" 
                 class="mb-input w-full">
        </div>
        
        <div class="w-40">
          <label class="text-xs font-medium text-gray-700 mb-2 block">Au</label>
          <input type="date" name="date_to" value="{{ request('date_to', $dateTo ?? '') }}" 
                 class="mb-input w-full">
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
      @if(request('q_client') || request('q_agent') || request('date_from') || request('date_to'))
      <div class="mt-4 pt-4 border-t border-gray-200">
        <div class="flex items-center gap-2 flex-wrap">
          <span class="text-xs font-medium text-gray-500">Filtres actifs:</span>
          @if(request('q_client'))
          <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-50 text-purple-700 text-xs font-medium rounded-full">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Client: {{ request('q_client') }}
          </span>
          @endif
          @if(request('q_agent'))
          <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-full">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Agent: {{ request('q_agent') }}
          </span>
          @endif
          @if(request('date_from'))
          <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-700 text-xs font-medium rounded-full">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Du: {{ \Carbon\Carbon::parse(request('date_from'))->format('d/m/Y') }}
          </span>
          @endif
          @if(request('date_to'))
          <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-700 text-xs font-medium rounded-full">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Au: {{ \Carbon\Carbon::parse(request('date_to'))->format('d/m/Y') }}
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
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">ID</th>
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tontine</th>
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Client</th>
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Montant</th>
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Date / Heure</th>
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Agent</th>
            <th class="px-5 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
          @forelse($collectes as $c)
            @php $currency = $c->tontine?->settings['currency'] ?? 'XAF'; @endphp
            <tr class="hover:bg-gradient-to-r hover:from-blue-50/30 hover:to-purple-50/30 transition-all duration-200">
              <td class="px-5 py-4">
                <div class="flex items-center gap-2">
                  <div class="w-8 h-8 bg-gradient-to-br from-gray-400 to-gray-600 rounded-lg flex items-center justify-center shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                    </svg>
                  </div>
                  <span class="font-semibold text-gray-700">#{{ $c->id }}</span>
                </div>
              </td>
              
              <td class="px-5 py-4">
                <div class="flex items-center gap-2">
                  <div class="w-8 h-8 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-lg flex items-center justify-center shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                  </div>
                  <span class="font-semibold text-gray-900">{{ $c->tontine?->code ?? '—' }}</span>
                </div>
              </td>
              
              <td class="px-5 py-4">
                @php
                  $clientFirst = $c->tontine?->client?->first_name ?? '';
                  $clientLast  = $c->tontine?->client?->last_name ?? '';
                  $clientName  = trim($clientFirst.' '.$clientLast);
                  $displayName = $clientName !== '' ? $clientName : ($c->tontine?->client?->name ?? '-');
                @endphp
                @if($displayName !== '-')
                <div class="flex items-center gap-3">
                  <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                    {{ $clientFirst && $clientLast ? strtoupper(substr($clientFirst, 0, 1) . substr($clientLast, 0, 1)) : strtoupper(substr($displayName, 0, 2)) }}
                  </div>
                  <div>
                    <div class="font-semibold text-gray-900">{{ $displayName }}</div>
                    @if($c->tontine?->client?->email)
                    <div class="text-xs text-gray-500">{{ $c->tontine->client->email }}</div>
                    @endif
                  </div>
                </div>
                @else
                <span class="text-gray-400">—</span>
                @endif
              </td>
              
              <td class="px-5 py-4">
                <div class="flex items-center gap-2">
                  <div class="w-8 h-8 bg-gradient-to-br from-green-100 to-emerald-200 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                  </div>
                  <div>
                    <div class="font-semibold text-gray-900">{{ number_format($c->amount ?? 0, 0, ',', ' ') }}</div>
                    <div class="text-xs text-gray-500">{{ $currency }}</div>
                  </div>
                </div>
              </td>
              
              <td class="px-5 py-4">
                <div class="flex items-center gap-2 text-sm text-gray-700">
                  <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                  <div>
                    <div class="font-medium">{{ optional($c->collected_at ?? $c->created_at)->format('d/m/Y') }}</div>
                    <div class="text-xs text-gray-500">{{ optional($c->collected_at ?? $c->created_at)->format('H:i') }}</div>
                  </div>
                </div>
              </td>
              
              <td class="px-5 py-4">
                @php
                  $agentFirst = $c->user?->first_name ?? ($c->agent?->first_name ?? '');
                  $agentLast  = $c->user?->last_name ?? ($c->agent?->last_name ?? '');
                  $agentName  = trim($agentFirst.' '.$agentLast);
                  $displayAgent = $agentName !== '' ? $agentName : ($c->user?->name ?? $c->user?->email ?? $c->agent?->email ?? '-');
                @endphp
                @if($displayAgent !== '-')
                <div class="flex items-center gap-2">
                  <div class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white text-xs font-bold">
                    {{ $agentFirst && $agentLast ? strtoupper(substr($agentFirst, 0, 1)) : strtoupper(substr($displayAgent, 0, 1)) }}
                  </div>
                  <span class="text-sm text-gray-900">{{ $displayAgent }}</span>
                </div>
                @else
                <span class="text-gray-400">—</span>
                @endif
              </td>
              
              <td class="px-5 py-4 text-right">
                <a href="{{ route('admin.collectes.show', $c->id) }}" 
                   class="group relative inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg bg-gradient-to-br from-blue-50 to-blue-100 text-blue-700 hover:from-blue-100 hover:to-blue-200 transition-all duration-200 shadow-sm hover:shadow font-medium text-xs">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                  </svg>
                  <span>Voir</span>
                  <span class="absolute -top-8 right-0 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">Détails</span>
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-5 py-16">
                <div class="flex flex-col items-center justify-center text-center">
                  <div class="w-20 h-20 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                  </div>
                  <h3 class="text-lg font-semibold text-gray-700 mb-1">Aucune collecte trouvée</h3>
                  <p class="text-sm text-gray-500 mb-4">Aucune collecte ne correspond à vos critères de recherche.</p>
                  @if(request('q_client') || request('q_agent') || request('date_from') || request('date_to'))
                  <button type="button" onclick="window.location.href='{{ route('admin.collectes.index') }}'"
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
    @if($collectes->hasPages())
    <div class="bg-gray-50 px-5 py-4 border-t border-gray-200">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <span class="text-sm text-gray-700 font-medium">Affichage</span>
          <span class="px-2 py-1 bg-white border border-gray-300 rounded text-sm font-semibold text-gray-900">{{ $collectes->firstItem() ?? 0 }}‑{{ $collectes->lastItem() ?? 0 }}</span>
          <span class="text-sm text-gray-700 font-medium">sur</span>
          <span class="px-2 py-1 bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded text-sm font-semibold text-blue-700">{{ $collectes->total() }}</span>
        </div>
        <div>{{ $collectes->appends(request()->query())->links() }}</div>
      </div>
    </div>
    @endif
  </section>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('collecte-filters');
  if (!form) return;
  const submit = () => form.submit();

  // Recherche client avec debounce
  const qc = form.querySelector('input[name="q_client"]');
  if (qc) {
    qc.setAttribute('autocomplete','off');
    let timeoutClient;
    qc.addEventListener('input', () => {
      clearTimeout(timeoutClient);
      timeoutClient = setTimeout(submit, 500);
    });
    qc.addEventListener('keydown', e => { 
      if (e.key === 'Escape') { 
        qc.value=''; 
        submit(); 
      }
    });
  }

  // Bouton clear client
  const clearClient = document.getElementById('clear-client');
  if (clearClient && qc) {
    clearClient.addEventListener('click', () => {
      qc.value = '';
      submit();
    });
  }

  // Recherche agent avec debounce
  const qa = form.querySelector('input[name="q_agent"]');
  if (qa) {
    qa.setAttribute('autocomplete','off');
    let timeoutAgent;
    qa.addEventListener('input', () => {
      clearTimeout(timeoutAgent);
      timeoutAgent = setTimeout(submit, 500);
    });
    qa.addEventListener('keydown', e => { 
      if (e.key === 'Escape') { 
        qa.value=''; 
        submit(); 
      }
    });
  }

  // Bouton clear agent
  const clearAgent = document.getElementById('clear-agent');
  if (clearAgent && qa) {
    clearAgent.addEventListener('click', () => {
      qa.value = '';
      submit();
    });
  }

  // Auto-submit pour dates
  ['date_from','date_to'].forEach(n => {
    const el = form.querySelector(`[name="${n}"]`);
    if (el) el.addEventListener('change', submit);
  });

  // Reset dates
  const reset = document.getElementById('reset-dates');
  if (reset) {
    reset.addEventListener('click', () => {
      const from = form.querySelector('input[name="date_from"]');
      const to = form.querySelector('input[name="date_to"]');
      if (from) from.value = '';
      if (to) to.value = '';
      submit();
    });
  }
});
</script>
@endsection