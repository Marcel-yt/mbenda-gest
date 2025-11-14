@extends('layouts.app-agent')

@section('title', 'Tontines')
@section('page_title', 'Toutes les tontines')

@section('content')
@php /** @var \Illuminate\Pagination\LengthAwarePaginator $tontines */ @endphp

<div class="flex items-center justify-between mb-4">
  <h2 class="text-lg font-semibold">Toutes les tontines</h2>
  <a href="{{ route('agent.tontines.create') }}" class="mb-btn-secondary px-3 py-2 rounded">+ Créer une tontine</a>
</div>

<!-- Filtres (une ligne) -->
<form id="tontine-filters" method="GET" action="{{ route('agent.tontines.index') }}" class="mb-3">
  <div class="bg-white border rounded-xl p-3 flex items-end gap-4 flex-nowrap overflow-x-auto">
    <div class="shrink-0" style="width:400px;">
      <label class="text-xs text-gray-500 mb-1 block">Recherche client / code</label>
      <input type="text" name="q" value="{{ $q ?? request('q') }}" class="mb-input"
             placeholder="Nom, prénom, téléphone, code" autocomplete="off">
    </div>
    <div class="shrink-0" style="width:160px;">
      <label class="text-xs text-gray-500 mb-1 block">Créée du</label>
      <input type="date" name="created_from" value="{{ $created_from ?? request('created_from') }}" class="mb-input">
    </div>
    <div class="shrink-0" style="width:160px;">
      <label class="text-xs text-gray-500 mb-1 block">Au</label>
      <input type="date" name="created_to" value="{{ $created_to ?? request('created_to') }}" class="mb-input">
    </div>
    <div class="shrink-0" style="width:180px;">
      <label class="text-xs text-gray-500 mb-1 block">Statut</label>
      <select name="status" class="mb-input">
        <option value="">Tous</option>
        <option value="draft"     @selected(($status ?? '')==='draft')>Brouillon</option>
        <option value="active"    @selected(($status ?? '')==='active')>Actif</option>
        <option value="completed" @selected(($status ?? '')==='completed')>Terminée</option>
        <option value="paid"      @selected(($status ?? '')==='paid')>Payée</option>
      </select>
    </div>
    <div class="shrink-0" style="width:140px;">
      <label class="text-xs text-transparent mb-1 block">Reset</label>
      <button type="button" id="reset-dates"
              class="w-full inline-flex justify-center items-center px-3 py-3 rounded-md border border-[var(--mb-primary)] bg-white text-xs text-[var(--mb-primary)] hover:bg-gray-200">
        Réinitialiser
      </button>
    </div>
  </div>
</form>

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
            <td class="px-4 py-3">
              {{ $t->client?->first_name }} {{ $t->client?->last_name }}
              <div class="text-xs text-gray-500">{{ $t->client?->email }}</div>
            </td>
            <td class="px-4 py-3">{{ number_format($t->daily_amount,2) }} {{ $t->settings['currency'] ?? 'XAF' }}</td>
            <td class="px-4 py-3">{{ $t->start_date?->format('d/m/Y') }} — {{ $t->expected_end_date?->format('d/m/Y') }}</td>
            <td class="px-4 py-3">{{ $t->creator?->email ?? '-' }}</td>
            <td class="px-4 py-3">
              <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $t->status_badge_classes }}">
                {{ $t->status_label }}
              </span>
            </td>
            <td class="px-4 py-3 text-right">
              <div class="inline-flex items-center gap-2">
                <a href="{{ route('agent.collectes.index', ['tontine_id' => $t->id]) }}"
                   class="mb-btn-primary px-3 py-2 rounded inline-flex items-center gap-2">
                  <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="9" class="text-white" />
                    <path d="M10.5 9.5c.6-.4 1.3-.6 1.99-.6 1.66 0 3 1.34 3 3s-1.34 3-3 3c-.69 0-1.39-.22-1.99-.6" />
                    <path d="M12 8v1.5M12 14.5V16" />
                  </svg>
                  <span>Collecter</span>
                </a>
                <a href="{{ route('agent.tontines.show', $t) }}"
                   class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md text-sm font-medium bg-white border border-gray-200 text-gray-700 hover:bg-gray-50">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
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
    {{ $tontines->appends(request()->query())->links() }}
  </div>
</section>
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
      ['created_from','created_to'].forEach(name => {
        const el = f.querySelector(`[name="${name}"]`);
        if (el) el.value = '';
      });
      submit();
    });
  }
});
</script>
@endsection