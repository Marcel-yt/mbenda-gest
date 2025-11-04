@extends('layouts.app-agent')

@section('title', 'Créer une tontine')
@section('page_title', 'Créer une tontine')

@section('content')
@php /** $clients optional - ajax search used */ @endphp

<div class="max-w-3xl mx-auto">
  <section class="bg-white border rounded-xl p-6 shadow-sm">
    <form id="tontine-form" action="{{ route('agent.tontines.store') }}" method="post" class="space-y-4">
      @csrf

      <div>
        <label class="text-xs text-gray-500">Client</label>

        <div class="relative">
          <input
            id="client_search_input"
            type="text"
            autocomplete="off"
            placeholder="Rechercher un client par nom, prénom ou téléphone..."
            class="mt-1 block w-full rounded border-gray-300 pr-28"
          />

          <button type="button" id="client_clear_btn" class="absolute right-2 top-2 hidden text-xs text-gray-500">Effacer</button>

          <input id="client_id" type="hidden" name="client_id" value="{{ old('client_id','') }}" />

          <ul id="client_suggestions" class="z-50 absolute left-0 right-0 mt-1 bg-white border rounded shadow-sm max-h-52 overflow-auto hidden text-sm">
            <!-- suggestions injected here -->
          </ul>
        </div>

        @error('client_id')
          <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
        @enderror
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="text-xs text-gray-500">Montant journalier</label>
          <input name="daily_amount" type="number" step="0.01" min="0" required value="{{ old('daily_amount') }}" class="mt-1 block w-full rounded border-gray-300" />
        </div>

        <div>
          <label class="text-xs text-gray-500">Date de début</label>
          <input id="tontine_start_date" name="start_date" type="date" required value="{{ old('start_date', now()->toDateString()) }}" class="mt-1 block w-full rounded border-gray-300" />
        </div>
      </div>

      {{-- Conteneur fixe durée / commission / date de fin --}}
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
        <div class="bg-white border rounded-lg p-3">
          <div class="text-xs text-gray-500">Durée</div>
          <div class="mt-1 font-medium text-gray-900">31 jours</div>
          <input type="hidden" name="duration_days" value="31" />
        </div>

        <div class="bg-white border rounded-lg p-3">
          <div class="text-xs text-gray-500">Commission</div>
          <div class="mt-1 font-medium text-gray-900">1 jour</div>
          <input type="hidden" name="commission_days" value="1" />
        </div>

        <div class="bg-white border rounded-lg p-3">
          <div class="text-xs text-gray-500">Fin prévue</div>
          <div id="expected_end_display" class="mt-1 font-medium text-gray-900">—</div>
          <input type="hidden" id="expected_end_date" name="expected_end_date" value="" />
        </div>
      </div>

      <div class="flex items-center gap-3">
        <label class="flex items-center gap-2">
          <input type="checkbox" name="allow_early_payout" value="1" {{ old('allow_early_payout', true) ? 'checked' : '' }} />
          <span class="text-sm text-gray-600">Autoriser paiement anticipé</span>
        </label>
      </div>

      <div class="flex justify-end gap-3 pt-4">
        <a href="{{ route('agent.tontines.index') }}" class="mb-link">Annuler</a>
        <button id="submit_button" type="submit" class="mb-btn-primary px-4 py-2 rounded">Créer</button>
      </div>
    </form>
  </section>
</div>

<script>
(function(){
  // --- end date logic (unchanged) ---
  const durationDays = 31;
  const startInput = document.getElementById('tontine_start_date');
  const endDisplay = document.getElementById('expected_end_display');
  const endHidden = document.getElementById('expected_end_date');

  function formatDate(d) {
    const dd = String(d.getDate()).padStart(2,'0');
    const mm = String(d.getMonth()+1).padStart(2,'0');
    const yyyy = d.getFullYear();
    return dd + '/' + mm + '/' + yyyy;
  }
  function computeEnd(dateStr) {
    if (!dateStr) return null;
    const parts = dateStr.split('-');
    if (parts.length !== 3) return null;
    const d = new Date(parts[0], parts[1]-1, parts[2]);
    d.setDate(d.getDate() + (durationDays - 1));
    return d;
  }
  function updateEnd() {
    const val = startInput.value;
    const d = computeEnd(val);
    if (d) {
      endDisplay.textContent = formatDate(d);
      const iso = d.getFullYear() + '-' + String(d.getMonth()+1).padStart(2,'0') + '-' + String(d.getDate()).padStart(2,'0');
      endHidden.value = iso;
    } else {
      endDisplay.textContent = '—';
      endHidden.value = '';
    }
  }
  document.addEventListener('DOMContentLoaded', updateEnd);
  startInput.addEventListener('change', updateEnd);

  // --- client search logic ---
  const input = document.getElementById('client_search_input');
  const hidden = document.getElementById('client_id');
  const suggestions = document.getElementById('client_suggestions');
  const clearBtn = document.getElementById('client_clear_btn');
  const minLen = 2;
  const endpoint = '{{ route('agent.tontines.clients.search') }}';

  let timer = null;
  let currentItems = [];

  function hideSuggestions(){ suggestions.classList.add('hidden'); suggestions.innerHTML=''; }
  function showSuggestions(){ suggestions.classList.remove('hidden'); }

  function renderList(items){
    currentItems = items || [];
    suggestions.innerHTML = '';
    if (!items || items.length === 0) {
      const li = document.createElement('li');
      li.className = 'px-3 py-2 text-gray-500 text-xs';
      li.textContent = 'Aucun résultat';
      suggestions.appendChild(li);
      showSuggestions();
      return;
    }
    items.forEach(it=>{
      const li = document.createElement('li');
      li.className = 'px-3 py-2 hover:bg-gray-50 cursor-pointer';
      li.textContent = it.text;
      li.dataset.id = it.id;
      li.addEventListener('click', ()=> selectClient(it.id, it.text));
      suggestions.appendChild(li);
    });
    showSuggestions();
  }

  function selectClient(id, label){
    hidden.value = id;
    input.value = label;
    clearBtn.classList.remove('hidden');
    hideSuggestions();
  }

  function clearSelection(){
    hidden.value = '';
    input.value = '';
    clearBtn.classList.add('hidden');
    hideSuggestions();
    input.focus();
  }

  clearBtn.addEventListener('click', clearSelection);

  input.addEventListener('input', function(e){
    const v = e.target.value.trim();
    hidden.value = ''; // clear previous selection when typing
    clearBtn.classList.add('hidden');

    if (timer) clearTimeout(timer);
    if (v.length < minLen) {
      hideSuggestions();
      return;
    }
    timer = setTimeout(()=> {
      fetch(endpoint + '?q=' + encodeURIComponent(v), { headers: { 'Accept': 'application/json' } })
        .then(r=>r.json())
        .then(data=> renderList(data))
        .catch(()=> renderList([]));
    }, 250);
  });

  // close suggestions on outside click
  document.addEventListener('click', function(e){
    if (!e.target.closest('#client_search_input') && !e.target.closest('#client_suggestions')) {
      hideSuggestions();
    }
  });

  // prevent form submit if no client_id selected
  document.getElementById('tontine-form').addEventListener('submit', function(e){
    if (!hidden.value) {
      e.preventDefault();
      alert('Veuillez sélectionner un client dans la liste (rechercher et choisir).');
      input.focus();
    }
  });
})();
</script>
@endsection