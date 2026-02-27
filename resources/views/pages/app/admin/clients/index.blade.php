@extends('layouts.app-admin')

@section('title', 'Clients')
@section('page_title', 'Liste des clients')

@section('content')
@php
  use App\Models\User;
  use App\Models\Client;
  $totalUsers = User::count();
  $totalAdmins = User::where('role','admin')->count();
  $totalAgents = User::where('role','agent')->count();
  $totalClients = Client::count();
@endphp

<div class="space-y-6">
  <div>
    <x-app.users-header
      :total-users="$totalUsers"
      :total-admins="$totalAdmins"
      :total-agents="$totalAgents"
      :total-clients="$totalClients"
      active="clients"
      staff-route="admin.users.index"
      clients-route="admin.clients.index"
    />
  </div>

  <!-- Titre + bouton -->
  <div class="flex items-center justify-between">
    <h2 class="text-lg font-semibold">Liste des Clients</h2>
  </div>

  {{-- Filtres modernes --}}
  <form id="client-filters" method="GET" action="{{ route('admin.clients.index') }}" class="mb-6">
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
            <input type="text" name="q" value="{{ request('q','') }}" 
                   class="pl-10 pr-10 mb-input w-full"
                   placeholder="Nom, prénom, téléphone, adresse..." autocomplete="off">
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
          <label class="text-xs font-medium text-gray-700 mb-2 block">Date début</label>
          <input type="date" name="date_from" value="{{ request('date_from','') }}" 
                 class="mb-input w-full">
        </div>
        
        <div class="w-40">
          <label class="text-xs font-medium text-gray-700 mb-2 block">Date fin</label>
          <input type="date" name="date_to" value="{{ request('date_to','') }}" 
                 class="mb-input w-full">
        </div>
        
        <div class="w-40">
          <label class="text-xs font-medium text-gray-700 mb-2 block">Statut</label>
          <select name="status" class="mb-input w-full">
            <option value="">Tous</option>
            <option value="active" @selected(in_array(request('status'),['active','1'],true))>Actif</option>
            <option value="inactive" @selected(in_array(request('status'),['inactive','0'],true))>Désactivé</option>
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
      @if(request('q') || request('date_from') || request('date_to') || request('status'))
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
          @if(request('status'))
          <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-50 text-purple-700 text-xs font-medium rounded-full">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ request('status') === 'active' ? 'Actif' : 'Désactivé' }}
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
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">#</th>
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Client</th>
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Téléphone</th>
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Adresse</th>
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Statut</th>
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Créé le</th>
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Créé par</th>
            <th class="px-5 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-100">
          @forelse($clients as $client)
            <tr class="hover:bg-gradient-to-r hover:from-blue-50/30 hover:to-purple-50/30 transition-all duration-200">
              <td class="px-5 py-4 text-sm font-medium text-gray-600">{{ $loop->iteration + ($clients->currentPage()-1)*$clients->perPage() }}</td>

              <td class="px-5 py-4">
                <div class="flex items-center gap-3">
                  <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                    {{ strtoupper(substr($client->first_name, 0, 1) . substr($client->last_name, 0, 1)) }}
                  </div>
                  <div>
                    <div class="font-semibold text-gray-900">{{ $client->first_name }} {{ $client->last_name }}</div>
                  </div>
                </div>
              </td>

              <td class="px-5 py-4 text-sm text-gray-700">
                @if($client->phone)
                <div class="flex items-center gap-2">
                  <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                  </svg>
                  {{ $client->phone }}
                </div>
                @else
                <span class="text-gray-400">—</span>
                @endif
              </td>

              <td class="px-5 py-4 text-sm text-gray-700">
                @if($client->address)
                <div class="flex items-center gap-2 max-w-xs">
                  <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                  </svg>
                  <span class="truncate" title="{{ $client->address }}">{{ $client->address }}</span>
                </div>
                @else
                <span class="text-gray-400">—</span>
                @endif
              </td>

              <td class="px-5 py-4 text-sm">
                <div class="flex items-center gap-2">
                  <span class="relative inline-flex items-center justify-center w-2.5 h-2.5">
                    <span class="absolute inline-flex h-full w-full rounded-full {{ $client->statut ? 'bg-green-400 animate-ping' : 'bg-red-400' }} opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 {{ $client->statut ? 'bg-green-500' : 'bg-red-500' }}"></span>
                  </span>
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $client->statut ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $client->statut ? 'Actif' : 'Désactivé' }}
                  </span>
                </div>
              </td>

              <td class="px-5 py-4 text-sm text-gray-700">
                <div class="flex items-center gap-2">
                  <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                  {{ $client->created_at?->format('d/m/Y H:i') ?? '—' }}
                </div>
              </td>

              <td class="px-5 py-4 text-sm text-gray-700">
                @if($client->creatorAgent)
                <div class="flex items-center gap-2">
                  <div class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white text-xs font-bold">
                    {{ strtoupper(substr($client->creatorAgent->email, 0, 1)) }}
                  </div>
                  <span class="text-sm">{{ $client->creatorAgent->email }}</span>
                </div>
                @else
                <span class="text-gray-400">—</span>
                @endif
              </td>

              <td class="px-5 py-4 text-sm text-right">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.clients.show', $client) }}" 
                     class="group relative inline-flex items-center justify-center w-9 h-9 rounded-lg bg-gradient-to-br from-blue-50 to-blue-100 text-blue-600 hover:from-blue-100 hover:to-blue-200 transition-all duration-200 shadow-sm hover:shadow">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <span class="absolute -top-8 right-0 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">Voir</span>
                  </a>
                  <a href="{{ route('admin.clients.edit', $client) }}" 
                     class="group relative inline-flex items-center justify-center w-9 h-9 rounded-lg bg-gradient-to-br from-yellow-50 to-orange-100 text-yellow-600 hover:from-yellow-100 hover:to-orange-200 transition-all duration-200 shadow-sm hover:shadow">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span class="absolute -top-8 right-0 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">Éditer</span>
                  </a>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="px-5 py-16">
                <div class="flex flex-col items-center justify-center text-center">
                  <div class="w-20 h-20 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                  </div>
                  <h3 class="text-lg font-semibold text-gray-700 mb-1">Aucun client trouvé</h3>
                  <p class="text-sm text-gray-500 mb-4">Aucun client ne correspond à vos critères de recherche.</p>
                  @if(request('q') || request('date_from') || request('date_to') || request('status'))
                  <button type="button" onclick="window.location.href='{{ route('admin.clients.index') }}'"
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
    @if($clients->hasPages())
    <div class="bg-gray-50 px-5 py-4 border-t border-gray-200">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <span class="text-sm text-gray-700 font-medium">Affichage</span>
          <span class="px-2 py-1 bg-white border border-gray-300 rounded text-sm font-semibold text-gray-900">{{ $clients->firstItem() ?? 0 }}‑{{ $clients->lastItem() ?? 0 }}</span>
          <span class="text-sm text-gray-700 font-medium">sur</span>
          <span class="px-2 py-1 bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded text-sm font-semibold text-blue-700">{{ $clients->total() }}</span>
        </div>
        <div>{{ $clients->appends(request()->query())->links() }}</div>
      </div>
    </div>
    @endif
  </section>
</div>
@endsection

@section('scripts')
<script>
(function(){
  const form = document.getElementById('client-filters');
  if(!form) return;
  const submit = () => form.submit();

  // Recherche avec debounce
  const q = form.querySelector('input[name="q"]');
  if (q) {
    // Auto-focus si une recherche est active (après rechargement de page)
    if (q.value.trim()) {
      q.focus();
      const len = q.value.length;
      q.setSelectionRange(len, len);
    }
    let timeout;
    q.addEventListener('input', () => {
      clearTimeout(timeout);
      timeout = setTimeout(submit, 500);
    });
    q.addEventListener('keydown', e => { 
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
  form.querySelectorAll('input[type="date"], select[name="status"]').forEach(el => {
    el.addEventListener('change', submit);
  });

  // Reset dates
  const resetBtn = document.getElementById('reset-dates');
  if (resetBtn) {
    resetBtn.addEventListener('click', () => {
      const from = form.querySelector('input[name="date_from"]');
      const to = form.querySelector('input[name="date_to"]');
      if (from) from.value = '';
      if (to) to.value = '';
      submit();
    });
  }
})();
</script>
@endsection