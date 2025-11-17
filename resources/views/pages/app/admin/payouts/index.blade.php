@extends('layouts.app-admin')

@section('title','Payouts')
@section('page_title', 'Liste des paiements')

@section('content')
<div class="space-y-6">
  {{-- Statistiques --}}
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs font-medium text-blue-600 uppercase tracking-wide mb-1">Total Paiements</p>
          <p class="text-3xl font-bold text-blue-900">{{ $payouts->total() }}</p>
        </div>
        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center shadow-sm">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
          </svg>
        </div>
      </div>
    </div>
    
    <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs font-medium text-purple-600 uppercase tracking-wide mb-1">Aujourd'hui</p>
          <p class="text-3xl font-bold text-purple-900">{{ \App\Models\Payout::whereDate('paid_at', today())->count() }}</p>
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
          <p class="text-3xl font-bold text-green-900">{{ \App\Models\Payout::whereBetween('paid_at', [now()->startOfWeek(), now()->endOfWeek()])->count() }}</p>
        </div>
        <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-600 rounded-full flex items-center justify-center shadow-sm">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
          </svg>
        </div>
      </div>
    </div>
  </div>

  {{-- Filtres modernes --}}
  <form id="payout-filters" method="GET" action="{{ route('admin.payouts.index') }}" class="mb-6">
    <div class="bg-white border rounded-xl p-4 shadow-sm">
      <div class="flex items-end gap-4 flex-wrap">
        <div class="flex-1 min-w-[300px]">
          <label class="text-xs font-medium text-gray-700 mb-2 block">Recherche client</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
            </div>
            <input type="text" name="q_client" value="{{ request('q_client',$qClient ?? '') }}" 
                   class="pl-10 pr-10 mb-input w-full"
                   placeholder="Nom, prénom, téléphone, email, code tontine..." autocomplete="off">
            @if(request('q_client'))
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
          <label class="text-xs font-medium text-gray-700 mb-2 block">Payé du</label>
          <input type="date" name="paid_from" value="{{ request('paid_from',$paidFrom ?? '') }}" 
                 class="mb-input w-full">
        </div>
        
        <div class="w-40">
          <label class="text-xs font-medium text-gray-700 mb-2 block">Au</label>
          <input type="date" name="paid_to" value="{{ request('paid_to',$paidTo ?? '') }}" 
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
      @if(request('q_client') || request('paid_from') || request('paid_to'))
      <div class="mt-4 pt-4 border-t border-gray-200">
        <div class="flex items-center gap-2 flex-wrap">
          <span class="text-xs font-medium text-gray-500">Filtres actifs:</span>
          @if(request('q_client'))
          <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-full">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            {{ request('q_client') }}
          </span>
          @endif
          @if(request('paid_from'))
          <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-700 text-xs font-medium rounded-full">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Du: {{ \Carbon\Carbon::parse(request('paid_from'))->format('d/m/Y') }}
          </span>
          @endif
          @if(request('paid_to'))
          <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-700 text-xs font-medium rounded-full">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Au: {{ \Carbon\Carbon::parse(request('paid_to'))->format('d/m/Y') }}
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
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Montant Net</th>
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Admin</th>
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Date / Heure</th>
            <th class="px-5 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
          @forelse($payouts as $p)
            @php $currency = $p->tontine->settings['currency'] ?? 'XAF'; @endphp
            <tr class="hover:bg-gradient-to-r hover:from-blue-50/30 hover:to-purple-50/30 transition-all duration-200">
              <td class="px-5 py-4">
                <div class="flex items-center gap-2">
                  <div class="w-8 h-8 bg-gradient-to-br from-gray-400 to-gray-600 rounded-lg flex items-center justify-center shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                    </svg>
                  </div>
                  <span class="font-semibold text-gray-700">#{{ $p->id }}</span>
                </div>
              </td>
              
              <td class="px-5 py-4">
                <div class="flex items-center gap-2">
                  <div class="w-8 h-8 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-lg flex items-center justify-center shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                  </div>
                  <span class="font-semibold text-gray-900">{{ $p->tontine?->code ?? '—' }}</span>
                </div>
              </td>
              
              <td class="px-5 py-4">
                @php
                  $clientFirst = $p->client?->first_name ?? '';
                  $clientLast = $p->client?->last_name ?? '';
                  $clientName = trim($clientFirst.' '.$clientLast);
                @endphp
                @if($clientName)
                <div class="flex items-center gap-3">
                  <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                    {{ strtoupper(substr($clientFirst, 0, 1) . substr($clientLast, 0, 1)) }}
                  </div>
                  <div>
                    <div class="font-semibold text-gray-900">{{ $clientName }}</div>
                    @if($p->client?->email)
                    <div class="text-xs text-gray-500">{{ $p->client->email }}</div>
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
                    <div class="font-semibold text-gray-900">{{ number_format($p->amount_net, 0, ',', ' ') }}</div>
                    <div class="text-xs text-gray-500">{{ $currency }}</div>
                  </div>
                </div>
              </td>
              
              <td class="px-5 py-4">
                @if($p->admin)
                <div class="flex items-center gap-2">
                  <div class="w-6 h-6 rounded-full bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center text-white text-xs font-bold">
                    {{ strtoupper(substr($p->admin->email, 0, 1)) }}
                  </div>
                  <span class="text-sm text-gray-900">{{ $p->admin->email }}</span>
                </div>
                @else
                <span class="text-gray-400">—</span>
                @endif
              </td>
              
              <td class="px-5 py-4">
                <div class="flex items-center gap-2 text-sm text-gray-700">
                  <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                  <div>
                    <div class="font-medium">{{ optional($p->paid_at)->format('d/m/Y') ?? '—' }}</div>
                    <div class="text-xs text-gray-500">{{ optional($p->paid_at)->format('H:i') ?? '' }}</div>
                  </div>
                </div>
              </td>
              
              <td class="px-5 py-4 text-sm text-right">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.payouts.show',$p->id) }}" 
                     class="group relative inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg bg-gradient-to-br from-blue-50 to-blue-100 text-blue-700 hover:from-blue-100 hover:to-blue-200 transition-all duration-200 shadow-sm hover:shadow font-medium text-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <span>Voir</span>
                    <span class="absolute -top-8 right-0 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">Détails</span>
                  </a>
                  @if($p->receipt_path)
                  <a href="{{ url('storage/'.$p->receipt_path) }}" target="_blank"
                     class="group relative inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg bg-gradient-to-br from-green-50 to-emerald-100 text-green-700 hover:from-green-100 hover:to-emerald-200 transition-all duration-200 shadow-sm hover:shadow font-medium text-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <span>PDF</span>
                    <span class="absolute -top-8 right-0 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">Télécharger reçu</span>
                  </a>
                  @else
                  <span class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-gray-100 text-gray-400 text-xs font-medium cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <span>Pas de PDF</span>
                  </span>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-5 py-16">
                <div class="flex flex-col items-center justify-center text-center">
                  <div class="w-20 h-20 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                  </div>
                  <h3 class="text-lg font-semibold text-gray-700 mb-1">Aucun paiement trouvé</h3>
                  <p class="text-sm text-gray-500 mb-4">Aucun paiement ne correspond à vos critères de recherche.</p>
                  @if(request('q_client') || request('paid_from') || request('paid_to'))
                  <button type="button" onclick="window.location.href='{{ route('admin.payouts.index') }}'"
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
    @if($payouts->hasPages())
    <div class="bg-gray-50 px-5 py-4 border-t border-gray-200">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <span class="text-sm text-gray-700 font-medium">Affichage</span>
          <span class="px-2 py-1 bg-white border border-gray-300 rounded text-sm font-semibold text-gray-900">{{ $payouts->firstItem() ?? 0 }}‑{{ $payouts->lastItem() ?? 0 }}</span>
          <span class="text-sm text-gray-700 font-medium">sur</span>
          <span class="px-2 py-1 bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded text-sm font-semibold text-blue-700">{{ $payouts->total() }}</span>
        </div>
        <div>{{ $payouts->appends(request()->query())->links() }}</div>
      </div>
    </div>
    @endif
  </section>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('payout-filters');
  if (!form) return;
  const submit = () => form.submit();

  // Recherche avec debounce
  const qc = form.querySelector('input[name="q_client"]');
  if (qc) {
    qc.setAttribute('autocomplete','off');
    let timeout;
    qc.addEventListener('input', () => {
      clearTimeout(timeout);
      timeout = setTimeout(submit, 500);
    });
    qc.addEventListener('keydown', e => { 
      if (e.key === 'Escape') { 
        qc.value=''; 
        submit(); 
      }
    });
  }

  // Bouton clear search
  const clearBtn = document.getElementById('clear-search');
  if (clearBtn && qc) {
    clearBtn.addEventListener('click', () => {
      qc.value = '';
      submit();
    });
  }

  // Auto-submit pour dates
  ['paid_from','paid_to'].forEach(n => {
    const el = form.querySelector(`[name="${n}"]`);
    if (el) el.addEventListener('change', submit);
  });

  // Reset dates
  const reset = document.getElementById('reset-dates');
  if (reset) {
    reset.addEventListener('click', () => {
      const f = form.querySelector('input[name="paid_from"]');
      const t = form.querySelector('input[name="paid_to"]');
      if (f) f.value = '';
      if (t) t.value = '';
      submit();
    });
  }
});
</script>
@endsection