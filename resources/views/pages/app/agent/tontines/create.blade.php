@extends('layouts.app-agent')

@section('title', 'Créer une tontine')
@section('page_title', 'Créer une tontine')

@section('content')
@php /** $clients optional - ajax search used */ @endphp

<div class="max-w-5xl mx-auto space-y-6">

  <!-- Breadcrumb -->
  <nav class="flex items-center gap-2 text-sm text-gray-600">
    <a href="{{ route('agent.tontines.index') }}" class="hover:text-gray-900 transition-colors">
      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
        <circle cx="12" cy="14" r="2"/>
      </svg>
    </a>
    <svg class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="9 18 15 12 9 6"/>
    </svg>
    <a href="{{ route('agent.tontines.index') }}" class="hover:text-gray-900 transition-colors">Tontines</a>
    <svg class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="9 18 15 12 9 6"/>
    </svg>
    <span class="font-medium text-gray-900">Nouvelle tontine</span>
  </nav>

  <!-- Alerts -->
  @if($errors->any())
    <div class="rounded-xl border-l-4 p-4 shadow-sm" style="background: linear-gradient(to right, #fee2e2 0%, #fef2f2 100%); border-color: #dc2626;">
      <div class="flex items-start gap-3">
        <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"/>
          <line x1="12" y1="8" x2="12" y2="12"/>
          <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <div class="flex-1">
          <h3 class="text-sm font-semibold text-red-900 mb-1">Erreurs de validation</h3>
          <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  @endif

  <!-- Header Card -->
  <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-center gap-4">
      <div class="w-12 h-12 rounded-lg flex items-center justify-center text-white shadow-lg" style="background: linear-gradient(135deg, var(--mb-secondary) 0%, #6ba83a 100%);">
        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"/>
          <line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
      </div>
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Créer une nouvelle tontine</h1>
        <p class="text-sm text-gray-600 mt-1">Remplissez les informations pour créer une tontine</p>
      </div>
    </div>
  </div>

  <!-- Form -->
  <form id="tontine-form" action="{{ route('agent.tontines.store') }}" method="post" class="space-y-6">
    @csrf

    <!-- Client Selection Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="px-6 py-4" style="background: linear-gradient(135deg, var(--mb-primary) 0%, #005f8d 100%);">
        <h2 class="text-lg font-semibold text-white">Sélection du client</h2>
      </div>
      <div class="p-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Client <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <input
              id="client_search_input"
              type="text"
              autocomplete="off"
              placeholder="Rechercher un client par nom, prénom ou téléphone..."
              class="mb-input pr-20"
            />
            <button type="button" id="client_clear_btn" class="absolute right-3 top-1/2 -translate-y-1/2 hidden text-xs font-medium text-gray-500 hover:text-gray-700">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"/>
                <line x1="6" y1="6" x2="18" y2="18"/>
              </svg>
            </button>
            <input id="client_id" type="hidden" name="client_id" value="{{ old('client_id','') }}" />
            <ul id="client_suggestions" class="z-50 absolute left-0 right-0 mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-52 overflow-auto hidden">
              <!-- suggestions injected here -->
            </ul>
          </div>
          @error('client_id')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
          @enderror
          <p class="text-xs text-gray-500 mt-2">Tapez au moins 2 caractères pour rechercher</p>
        </div>
      </div>
    </div>

    <!-- Tontine Configuration Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="px-6 py-4" style="background: linear-gradient(135deg, var(--mb-secondary) 0%, #6ba83a 100%);">
        <h2 class="text-lg font-semibold text-white">Configuration de la tontine</h2>
      </div>
      <div class="p-6 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Montant journalier <span class="text-red-500">*</span>
            </label>
            <input name="daily_amount" type="number" step="0.01" min="0" required value="{{ old('daily_amount') }}" class="mb-input" placeholder="Ex: 5000" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Date de début <span class="text-red-500">*</span>
            </label>
            <input id="tontine_start_date" name="start_date" type="date" required value="{{ old('start_date', now()->toDateString()) }}" class="mb-input" />
          </div>
        </div>

        <!-- Fixed Values Display -->
        <div class="mt-6">
          <h3 class="text-sm font-medium text-gray-700 mb-3">Paramètres fixes</h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="rounded-lg p-4 border" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-color: var(--mb-secondary);">
              <div class="flex items-center gap-2 mb-1">
                <svg class="w-4 h-4" style="color: var(--mb-secondary);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"/>
                  <polyline points="12 6 12 12 16 14"/>
                </svg>
                <span class="text-xs font-medium" style="color: var(--mb-secondary);">Durée</span>
              </div>
              <div class="text-2xl font-bold" style="color: var(--mb-secondary);">31</div>
              <div class="text-xs mt-1" style="color: var(--mb-secondary);">jours</div>
              <input type="hidden" name="duration_days" value="31" />
            </div>

            <div class="rounded-lg p-4 border" style="background: linear-gradient(135deg, #ffedd5 0%, #fed7aa 100%); border-color: var(--mb-tertiary);">
              <div class="flex items-center gap-2 mb-1">
                <svg class="w-4 h-4" style="color: var(--mb-tertiary);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="12" y1="1" x2="12" y2="23"/>
                  <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
                <span class="text-xs font-medium" style="color: var(--mb-tertiary);">Commission</span>
              </div>
              <div class="text-2xl font-bold" style="color: var(--mb-tertiary);">1</div>
              <div class="text-xs mt-1" style="color: var(--mb-tertiary);">jour</div>
              <input type="hidden" name="commission_days" value="1" />
            </div>

            <div class="rounded-lg p-4 border" style="background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); border-color: var(--mb-primary);">
              <div class="flex items-center gap-2 mb-1">
                <svg class="w-4 h-4" style="color: var(--mb-primary);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                  <line x1="16" y1="2" x2="16" y2="6"/>
                  <line x1="8" y1="2" x2="8" y2="6"/>
                  <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                <span class="text-xs font-medium" style="color: var(--mb-primary);">Fin prévue</span>
              </div>
              <div id="expected_end_display" class="text-2xl font-bold" style="color: var(--mb-primary);">—</div>
              <div class="text-xs mt-1" style="color: var(--mb-primary);">calculé auto</div>
              <input type="hidden" id="expected_end_date" name="expected_end_date" value="" />
            </div>
          </div>
        </div>

        <!-- Options -->
        <div class="pt-4 border-t border-gray-100">
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="allow_early_payout" value="1" {{ old('allow_early_payout', true) ? 'checked' : '' }} class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500" />
            <div>
              <span class="text-sm font-medium text-gray-700">Autoriser paiement anticipé</span>
              <p class="text-xs text-gray-500">Permet de payer la tontine avant la fin de la durée</p>
            </div>
          </label>
        </div>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
      <div class="flex items-center justify-end gap-3">
        <a href="{{ route('agent.tontines.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"/>
            <line x1="6" y1="6" x2="18" y2="18"/>
          </svg>
          <span>Annuler</span>
        </a>
        <button id="submit_button" type="submit" class="mb-btn-secondary inline-flex items-center gap-2 px-6 py-2.5 rounded-lg text-sm font-medium transition-all">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"/>
            <line x1="5" y1="12" x2="19" y2="12"/>
          </svg>
          <span>Créer la tontine</span>
        </button>
      </div>
    </div>
  </form>

</div>

@endsection

@section('scripts')
<script>
(function(){
  // --- end date logic ---
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
      li.className = 'px-4 py-3 text-gray-500 text-sm';
      li.textContent = 'Aucun résultat trouvé';
      suggestions.appendChild(li);
      showSuggestions();
      return;
    }
    items.forEach(it=>{
      const li = document.createElement('li');
      li.className = 'px-4 py-3 hover:bg-gray-50 cursor-pointer text-sm border-b border-gray-100 last:border-0 transition-colors';
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
      alert('Veuillez sélectionner un client dans la liste de suggestions.');
      input.focus();
    }
  });
})();
</script>
@endsection