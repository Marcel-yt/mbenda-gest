@extends('layouts.app-agent')

@section('title', 'Tontines')
@section('page_title', 'Toutes les tontines')

@section('content')
@php /** @var \Illuminate\Pagination\LengthAwarePaginator $tontines */ @endphp

<div class="max-w-7xl mx-auto space-y-6">

  <!-- En-tête -->
  <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background: linear-gradient(135deg, var(--mb-secondary) 0%, #6ba83a 100%);">
          <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
            <circle cx="12" cy="14" r="2"/>
          </svg>
        </div>
        <div>
          <h2 class="text-2xl font-bold text-gray-900">Mes Tontines</h2>
          <p class="text-sm text-gray-500 mt-1">
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
              {{ $tontines->total() }} tontine(s)
            </span>
          </p>
        </div>
      </div>
      <a href="{{ route('agent.tontines.create') }}" class="mb-btn-secondary inline-flex items-center gap-2 px-4 py-2.5 rounded-lg">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"/>
          <line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        <span>Créer une tontine</span>
      </a>
    </div>
  </div>

  <!-- Filtres -->
  <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6">
    <div class="flex items-center gap-3 mb-4">
      <svg class="w-5 h-5" style="color: var(--mb-primary);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
      </svg>
      <h3 class="text-lg font-semibold text-gray-900">Filtres de recherche</h3>
    </div>
    <form id="tontine-filters" method="GET" action="{{ route('agent.tontines.index') }}">
      <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700 mb-2">Recherche client / code</label>
          <input type="text" name="q" value="{{ $q ?? request('q') }}" class="mb-input"
                 placeholder="Nom, prénom, téléphone, code" autocomplete="off">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Créée du</label>
          <input type="date" name="created_from" value="{{ $created_from ?? request('created_from') }}" class="mb-input">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Au</label>
          <input type="date" name="created_to" value="{{ $created_to ?? request('created_to') }}" class="mb-input">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
          <select name="status" class="mb-input">
            <option value="">Tous</option>
            <option value="draft"     @selected(($status ?? '')==='draft')>Brouillon</option>
            <option value="active"    @selected(($status ?? '')==='active')>Actif</option>
            <option value="completed" @selected(($status ?? '')==='completed')>Terminée</option>
            <option value="paid"      @selected(($status ?? '')==='paid')>Payée</option>
              <option value="cancelled" @selected(($status ?? '')==='cancelled')>Annulée</option>
          </select>
        </div>
      </div>
      <div class="mt-4 flex justify-end">
        <button type="button" id="reset-dates"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-[var(--mb-primary)] bg-white text-sm font-medium text-[var(--mb-primary)] hover:bg-gray-50">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
            <path d="M3 3v5h5"/>
          </svg>
          <span>Réinitialiser</span>
        </button>
      </div>
    </form>
  </div>

  <!-- Tableau -->
  <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Code</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Client</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Journalier</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Période</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Agent</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Statut</th>
            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
          @forelse($tontines as $t)
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="text-sm font-bold text-gray-900">{{ $t->code }}</span>
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold text-sm"
                       style="background: linear-gradient(135deg, var(--mb-primary) 0%, #005f8d 100%);">
                    {{ strtoupper(substr($t->client?->first_name ?? 'C', 0, 1)) }}{{ strtoupper(substr($t->client?->last_name ?? 'L', 0, 1)) }}
                  </div>
                  <div>
                    <div class="text-sm font-medium text-gray-900">{{ $t->client?->first_name }} {{ $t->client?->last_name }}</div>
                    @if($t->client?->phone)
                      <div class="flex items-center gap-1 text-xs text-gray-500">
                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        {{ $t->client?->indicatif }} {{ $t->client?->phone }}
                      </div>
                    @endif
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="text-sm font-semibold text-gray-900">{{ number_format($t->daily_amount, 0, ',', ' ') }}</span>
                <span class="text-xs text-gray-500">{{ $t->settings['currency'] ?? 'XAF' }}</span>
              </td>
              <td class="px-6 py-4">
                <div class="text-sm text-gray-900">{{ $t->start_date?->format('d/m/Y') }}</div>
                <div class="text-xs text-gray-500">→ {{ $t->expected_end_date?->format('d/m/Y') }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-700">{{ $t->creator?->name ?? '-' }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $t->status_badge_classes }}">
                  {{ $t->status_label }}
                </span>
              </td>
              <td class="px-6 py-4 text-right whitespace-nowrap">
                <div class="inline-flex items-center gap-2">
                  @if($t->status !== 'cancelled')
                    <a href="{{ route('agent.collectes.index', ['tontine_id' => $t->id]) }}"
                       class="mb-btn-primary inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-medium">
                      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M10.5 9.5c.6-.4 1.3-.6 1.99-.6 1.66 0 3 1.34 3 3s-1.34 3-3 3c-.69 0-1.39-.22-1.99-.6"/>
                        <path d="M12 8v1.5M12 14.5V16"/>
                      </svg>
                      <span>Collecter</span>
                    </a>
                  @endif
                  <a href="{{ route('agent.tontines.show', $t) }}"
                     class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-medium bg-white border border-gray-200 text-gray-700 hover:bg-gray-50">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                      <circle cx="12" cy="12" r="3"/>
                    </svg>
                    <span>Voir</span>
                  </a>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-6 py-16 text-center">
                <div class="flex flex-col items-center justify-center">
                  <svg class="w-16 h-16 text-gray-300 mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                    <circle cx="12" cy="14" r="2"/>
                  </svg>
                  <p class="text-sm font-medium text-gray-900 mb-1">Aucune tontine trouvée</p>
                  <p class="text-sm text-gray-500 mb-4">Essayez d'ajuster vos filtres ou créez une nouvelle tontine.</p>
                  @if(request()->hasAny(['q', 'created_from', 'created_to', 'status']))
                    <button type="button" id="clear-all-filters"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                      </svg>
                      <span>Effacer tous les filtres</span>
                    </button>
                  @endif
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($tontines->hasPages())
      <div class="px-6 py-4 border-t border-gray-100">
        <div class="flex items-center justify-between">
          <div class="text-sm text-gray-700">
            Affichage de <span class="font-medium">{{ $tontines->firstItem() }}</span> à <span class="font-medium">{{ $tontines->lastItem() }}</span> sur <span class="font-medium">{{ $tontines->total() }}</span> tontine(s)
          </div>
          <div>
            {{ $tontines->appends(request()->query())->links() }}
          </div>
        </div>
      </div>
    @endif
  </div>

</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const f = document.getElementById('tontine-filters');
  if (!f) return;
  const submit = () => f.requestSubmit();

  const q = f.querySelector('input[name="q"]');
  if (q) {
    q.addEventListener('input', submit);
    q.addEventListener('keydown', e => { if (e.key === 'Escape') { q.value=''; submit(); } });
  }

  ['created_from','created_to','status'].forEach(name => {
    const el = f.querySelector(`[name="${name}"]`);
    if (el) el.addEventListener('change', submit);
  });

  const reset = document.getElementById('reset-dates');
  if (reset) {
    reset.addEventListener('click', () => {
      ['created_from','created_to','status'].forEach(name => {
        const el = f.querySelector(`[name="${name}"]`);
        if (el) el.value = '';
      });
      submit();
    });
  }

  const clearAll = document.getElementById('clear-all-filters');
  if (clearAll) {
    clearAll.addEventListener('click', () => {
      ['q','created_from','created_to','status'].forEach(name => {
        const el = f.querySelector(`[name="${name}"]`);
        if (el) el.value = '';
      });
      submit();
    });
  }
});
</script>
@endsection