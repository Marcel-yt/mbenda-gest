@extends('layouts.app-agent')

@section('title', 'Clients')
@section('page_title', 'Clients')

@section('content')
<form id="client-filters" method="GET" action="{{ route('clients.index') }}" class="mb-3">
  <div class="bg-white border rounded-xl p-3 flex items-end gap-4 flex-nowrap overflow-x-auto">
    <div class="shrink-0" style="width:400px;">
      <label class="text-xs text-gray-500 mb-1 block">Recherche</label>
      <input type="text" name="q" value="{{ $q }}" class="mb-input" placeholder="Nom / tél / adresse" autocomplete="off">
    </div>
    <div class="shrink-0" style="width:180px;">
      <label class="text-xs text-gray-500 mb-1 block">Du</label>
      <input type="date" name="date_from" value="{{ $date_from }}" class="mb-input">
    </div>
    <div class="shrink-0" style="width:180px;">
      <label class="text-xs text-gray-500 mb-1 block">Au</label>
      <input type="date" name="date_to" value="{{ $date_to }}" class="mb-input">
    </div>
    <div class="shrink-0" style="width:180px;">
      <label class="text-xs text-gray-500 mb-1 block">Statut</label>
      <select name="status" class="mb-input">
        <option value="">Tous</option>
        <option value="active" @selected(in_array($status,['active','1'],true))>Actif</option>
        <option value="inactive" @selected(in_array($status,['inactive','0'],true))>Inactif</option>
      </select>
    </div>
    <div class="shrink-0" style="width:130px;">
      <label class="text-xs text-transparent mb-1 block">Reset</label>
      <button type="button" id="reset-dates"
              class="w-full inline-flex justify-center items-center px-3 py-3 rounded-md border border-[var(--mb-primary)] bg-white text-xs text-[var(--mb-primary)] hover:bg-gray-200">
        Réinitialiser
      </button>
    </div>
  </div>
</form>

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
      <div>{{ $clients->appends(request()->query())->links() }}</div>
    </div>
  </section>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const f = document.getElementById('client-filters');
  if (!f) return;

  const submit = () => f.requestSubmit(); // mieux que form.submit()

  // Recherche: soumission à chaque frappe (instantané)
  const q = f.querySelector('input[name="q"]');
  if (q) {
    q.addEventListener('input', submit);
    q.addEventListener('keydown', e => {
      if (e.key === 'Escape') { q.value=''; submit(); }
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
});
</script>
@endsection