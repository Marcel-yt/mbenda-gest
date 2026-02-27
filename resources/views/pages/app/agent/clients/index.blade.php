@extends('layouts.app-agent')

@section('title', 'Clients')
@section('page_title', 'Clients')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
  {{-- Header Section --}}
  <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-start justify-between gap-4">
      <div class="flex items-start gap-4">
        <div class="w-16 h-16 rounded-xl flex items-center justify-center text-white shadow-lg" style="background: var(--mb-primary);">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
          </svg>
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Mes Clients</h1>
          <p class="text-sm text-gray-600 mt-1">Gérez vos clients et leurs informations</p>
          <div class="mt-3 inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ $clients->total() }} client(s)
          </div>
        </div>
      </div>
      <a href="{{ route('clients.create') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-lg text-white font-semibold shadow-md transition-all hover:shadow-lg" style="background: var(--mb-secondary);">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Nouveau client
      </a>
    </div>
  </div>

  {{-- Filters Section --}}
  <form id="client-filters" method="GET" action="{{ route('clients.index') }}">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
      <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white" style="background: var(--mb-primary);">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
          </svg>
        </div>
        <div>
          <h2 class="text-lg font-semibold text-gray-900">Filtres de recherche</h2>
          <p class="text-xs text-gray-600">Affinez votre recherche de clients</p>
        </div>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="lg:col-span-2">
          <label class="text-xs font-medium text-gray-700 mb-2 block">Recherche</label>
          <div class="relative">
            <input type="text" name="q" value="{{ $q }}" class="mb-input pl-10" placeholder="Nom, téléphone ou adresse..." autocomplete="off">
            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </div>
        </div>
        
        <div>
          <label class="text-xs font-medium text-gray-700 mb-2 block">Date de début</label>
          <input type="date" name="date_from" value="{{ $date_from }}" class="mb-input">
        </div>
        
        <div>
          <label class="text-xs font-medium text-gray-700 mb-2 block">Date de fin</label>
          <input type="date" name="date_to" value="{{ $date_to }}" class="mb-input">
        </div>
        
        <div>
          <label class="text-xs font-medium text-gray-700 mb-2 block">Statut</label>
          <select name="status" class="mb-input">
            <option value="">Tous les statuts</option>
            <option value="active" @selected(in_array($status,['active','1'],true))>Actif</option>
            <option value="inactive" @selected(in_array($status,['inactive','0'],true))>Inactif</option>
          </select>
        </div>
        
        <div class="lg:col-span-3"></div>
        
        <div class="flex items-end">
          <button type="button" id="reset-dates"
                  class="w-full inline-flex justify-center items-center gap-2 px-4 py-3 rounded-lg border-2 text-sm font-semibold transition-all hover:bg-gray-50" style="border-color: var(--mb-primary); color: var(--mb-primary);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Réinitialiser
          </button>
        </div>
      </div>
    </div>
  </form>

  {{-- Table Section --}}
  <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full w-full divide-y divide-gray-200">
        <thead style="background: linear-gradient(to right, #f9fafb, #f3f4f6);">
          <tr>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider w-16">#</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Client</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Contact</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Adresse</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Statut</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Créé le</th>
            <th class="px-5 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-100">
          @forelse($clients as $client)
            <tr class="hover:bg-blue-50/30 transition-colors">
              <td class="px-5 py-4 text-sm font-medium text-gray-700">
                {{ $loop->iteration + ($clients->currentPage()-1)*$clients->perPage() }}
              </td>

              <td class="px-5 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold text-sm" style="background: var(--mb-primary);">
                    {{ strtoupper(substr($client->first_name ?? 'C', 0, 1)) }}{{ strtoupper(substr($client->last_name ?? 'L', 0, 1)) }}
                  </div>
                  <div>
                    <div class="text-sm font-semibold text-gray-900">{{ $client->first_name }} {{ $client->last_name }}</div>
                    <div class="text-xs text-gray-500">Client #{{ $client->id }}</div>
                  </div>
                </div>
              </td>

              <td class="px-5 py-4">
                @if($client->phone)
                  <div class="flex items-center gap-2 text-sm text-gray-700">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    {{ $client->phone }}
                  </div>
                @else
                  <span class="text-sm text-gray-400">—</span>
                @endif
              </td>

              <td class="px-5 py-4">
                @if($client->address)
                  <div class="flex items-start gap-2 text-sm text-gray-700">
                    <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="line-clamp-2">{{ $client->address }}</span>
                  </div>
                @else
                  <span class="text-sm text-gray-400">—</span>
                @endif
              </td>

              <td class="px-5 py-4">
                @if(isset($client->statut))
                  @if($client->statut)
                    <span class="mb-ind-active">Actif</span>
                  @else
                    <span class="mb-ind-inactive">Inactif</span>
                  @endif
                @else
                  <span class="text-sm text-gray-400">—</span>
                @endif
              </td>

              <td class="px-5 py-4">
                <div class="text-sm text-gray-700">
                  {{ $client->created_at?->format('d/m/Y') ?? '—' }}
                </div>
                <div class="text-xs text-gray-500">
                  {{ $client->created_at?->format('H:i') ?? '' }}
                </div>
              </td>

              <td class="px-5 py-4">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('clients.show', $client) }}" 
                     class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-white transition-all hover:shadow-md" 
                     style="background: var(--mb-primary);"
                     title="Voir les détails">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                  </a>

                  @if(auth()->user()->isAgent() && $client->created_by_agent_id === auth()->id())
                    <a href="{{ route('clients.edit', $client) }}" 
                       class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-white transition-all hover:shadow-md" 
                       style="background: var(--mb-tertiary);"
                       title="Modifier">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                      </svg>
                    </a>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-5 py-16">
                <div class="flex flex-col items-center justify-center text-center">
                  <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                  </div>
                  <h3 class="text-lg font-semibold text-gray-900 mb-1">Aucun client trouvé</h3>
                  <p class="text-sm text-gray-600 mb-4">Aucun client ne correspond à vos critères de recherche</p>
                  @if($q || $date_from || $date_to || $status)
                    <button type="button" id="clear-filters" class="mb-btn-primary px-4 py-2 rounded-lg">
                      Effacer les filtres
                    </button>
                  @endif
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    @if($clients->hasPages())
      <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        <div class="flex items-center justify-between">
          <div class="text-sm text-gray-600">
            Affichage de <span class="font-semibold text-gray-900">{{ $clients->firstItem() ?? 0 }}</span> 
            à <span class="font-semibold text-gray-900">{{ $clients->lastItem() ?? 0 }}</span> 
            sur <span class="font-semibold text-gray-900">{{ $clients->total() }}</span> client(s)
          </div>
          <div>{{ $clients->appends(request()->query())->links() }}</div>
        </div>
      </div>
    @endif
  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const f = document.getElementById('client-filters');
  if (!f) return;

  const submit = () => f.requestSubmit();

  // Recherche: soumission avec debounce (500ms)
  const q = f.querySelector('input[name="q"]');
  if (q) {
    // Auto-focus si une recherche est active (après rechargement de page)
    if (q.value.trim()) {
      q.focus();
      const len = q.value.length;
      q.setSelectionRange(len, len);
    }
    let qTimeout;
    q.addEventListener('input', () => {
      clearTimeout(qTimeout);
      qTimeout = setTimeout(submit, 500);
    });
    q.addEventListener('keydown', e => {
      if (e.key === 'Escape') { q.value=''; clearTimeout(qTimeout); submit(); }
    });
  }

  // Dates + statut: soumission immédiate
  ['date_from','date_to','status'].forEach(name => {
    const el = f.querySelector(`[name="${name}"]`);
    if (el) el.addEventListener('change', submit);
  });

  // Réinitialiser (vide tous les filtres)
  const reset = document.getElementById('reset-dates');
  if (reset) {
    reset.addEventListener('click', () => {
      ['q','date_from','date_to','status'].forEach(name => {
        const el = f.querySelector(`[name="${name}"]`);
        if (!el) return;
        if (el.tagName === 'SELECT') {
          el.selectedIndex = 0;
        } else {
          el.value = '';
        }
      });
      submit();
    });
  }

  // Clear filters button in empty state
  const clearBtn = document.getElementById('clear-filters');
  if (clearBtn) {
    clearBtn.addEventListener('click', () => {
      ['q','date_from','date_to','status'].forEach(name => {
        const el = f.querySelector(`[name="${name}"]`);
        if (!el) return;
        if (el.tagName === 'SELECT') {
          el.selectedIndex = 0;
        } else {
          el.value = '';
        }
      });
      submit();
    });
  }
});
</script>
@endsection