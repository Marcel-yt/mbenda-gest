@extends('layouts.app-agent')

@section('title', 'Nouvelle collecte')
@section('page_title', 'Nouvelle collecte')

@section('content')
@php /** @var \App\Models\Tontine|null $tontine */ @endphp
@php use \Illuminate\Support\Carbon; @endphp

<div class="max-w-4xl mx-auto space-y-6">

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
    @if($tontine)
      <svg class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="9 18 15 12 9 6"/>
      </svg>
      <a href="{{ route('agent.tontines.show', $tontine) }}" class="hover:text-gray-900 transition-colors">{{ $tontine->code }}</a>
      <svg class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="9 18 15 12 9 6"/>
      </svg>
      <a href="{{ route('agent.collectes.index', ['tontine_id' => $tontine->id]) }}" class="hover:text-gray-900 transition-colors">Calendrier</a>
    @endif
    <svg class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="9 18 15 12 9 6"/>
    </svg>
    <span class="font-medium text-gray-900">Nouvelle collecte</span>
  </nav>

  <!-- Validation Errors -->
  @if ($errors->any())
    <div class="rounded-xl shadow-sm border border-red-200 overflow-hidden">
      <div class="px-6 py-4" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
            <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"/>
              <line x1="12" y1="8" x2="12" y2="12"/>
              <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
          </div>
          <div>
            <h3 class="text-lg font-semibold text-white">Erreurs de validation</h3>
            <p class="text-sm text-red-100">Veuillez corriger les erreurs suivantes</p>
          </div>
        </div>
      </div>
      <div class="bg-white px-6 py-4">
        <ul class="list-disc pl-5 space-y-1 text-sm text-red-600">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    </div>
  @endif

  <!-- Header Card -->
  <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-center gap-4">
      <div class="w-14 h-14 rounded-lg flex items-center justify-center text-white shadow-lg" style="background: linear-gradient(135deg, var(--mb-primary) 0%, #005f8d 100%);">
        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
        </svg>
      </div>
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Enregistrer une nouvelle collecte</h1>
        <p class="text-sm text-gray-600 mt-1">Confirmez la collecte en espèces auprès du client</p>
      </div>
    </div>
  </div>

  <!-- Form -->
  <form action="{{ route('agent.collectes.store') }}" method="post" class="space-y-6" id="collecte-form">
    @csrf

    <!-- Tontine Information Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="px-6 py-4" style="background: linear-gradient(135deg, var(--mb-secondary) 0%, #6ba83a 100%);">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
            <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
              <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
              <circle cx="12" cy="14" r="2"/>
            </svg>
          </div>
          <div>
            <h2 class="text-lg font-semibold text-white">Informations de la tontine</h2>
            <p class="text-sm text-green-100">Tontine concernée par la collecte</p>
          </div>
        </div>
      </div>
      <div class="p-6">
        @if($tontine)
          <input type="hidden" name="tontine_id" value="{{ $tontine->id }}" />
          @if(request()->filled('date'))
            <input type="hidden" name="date" value="{{ request('date') }}" />
          @endif
          
          <div class="space-y-4">
            <div class="flex items-start gap-3">
              <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <div class="text-xs text-gray-500 uppercase tracking-wider">Code de la tontine</div>
                <div class="mt-1 text-lg font-bold text-gray-900">{{ $tontine->code }}</div>
              </div>
            </div>

            <div class="flex items-start gap-3">
              <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <div class="text-xs text-gray-500 uppercase tracking-wider">Client</div>
                <div class="mt-1 text-sm font-medium text-gray-900">{{ $tontine->client?->first_name }} {{ $tontine->client?->last_name }}</div>
              </div>
            </div>

            <div class="flex items-start gap-3">
              <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-white" style="background: linear-gradient(135deg, var(--mb-secondary) 0%, #6ba83a 100%);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <div class="text-xs text-gray-500 uppercase tracking-wider">Montant journalier</div>
                <div class="mt-1">
                  <span class="text-2xl font-bold" style="color: var(--mb-secondary);">{{ number_format($tontine->daily_amount, 0, ',', ' ') }}</span>
                  <span class="text-sm text-gray-600 ml-1">{{ $tontine->settings['currency'] ?? 'XAF' }}</span>
                </div>
              </div>
            </div>
          </div>
        @else
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              <span class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                </svg>
                Sélectionnez une tontine <span class="text-red-500">*</span>
              </span>
            </label>
            <select name="tontine_id" required class="mb-input w-full">
              <option value="">-- Choisir une tontine --</option>
              @foreach(\App\Models\Tontine::with('client')->orderByDesc('created_at')->limit(200)->get() as $t)
                <option value="{{ $t->id }}" {{ old('tontine_id') == $t->id ? 'selected' : '' }}>
                  {{ $t->code }} — {{ $t->client?->first_name }} {{ $t->client?->last_name }} — {{ number_format($t->daily_amount,2) }} {{ $t->settings['currency'] ?? 'XAF' }}
                </option>
              @endforeach
            </select>
          </div>
        @endif
      </div>
    </div>

    <!-- Notes Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-lg bg-gray-200 flex items-center justify-center">
            <svg class="w-5 h-5 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14 2 14 8 20 8"/>
              <line x1="16" y1="13" x2="8" y2="13"/>
              <line x1="16" y1="17" x2="8" y2="17"/>
              <polyline points="10 9 9 9 8 9"/>
            </svg>
          </div>
          <div>
            <h2 class="text-lg font-semibold text-gray-900">Remarques</h2>
            <p class="text-sm text-gray-600">Observations ou informations complémentaires (facultatif)</p>
          </div>
        </div>
      </div>
      <div class="p-6">
        <textarea 
          name="notes" 
          rows="4" 
          class="mb-input w-full" 
          placeholder="Ex: Client a demandé un reçu, particularité du paiement, état du carnet, etc.">{{ old('notes') }}</textarea>
        <p class="mt-2 text-xs text-gray-500">Ces remarques seront visibles dans l'historique de la collecte</p>
      </div>
    </div>

    <!-- Confirmation Section -->
    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl shadow-sm border-2 border-blue-200 overflow-hidden">
      <div class="p-6">
        <div class="flex items-start gap-4">
          <div class="flex items-center h-5 mt-0.5">
            <input
              id="confirm_collecte"
              name="confirmed"
              type="checkbox"
              value="1"
              {{ old('confirmed') ? 'checked' : '' }}
              class="h-5 w-5 rounded border-blue-300 text-blue-600 focus:ring-blue-500 focus:ring-offset-2"
            />
          </div>
          <div class="flex-1">
            <label for="confirm_collecte" class="block">
              <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-600 flex items-center justify-center flex-shrink-0">
                  <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                  </svg>
                </div>
                <div class="flex-1">
                  <span class="text-base font-semibold text-gray-900 cursor-pointer">
                    Je confirme que la collecte a été effectuée en espèces et que j'ai apposé le cachet sur le carnet du client
                  </span>
                  <p class="text-sm text-gray-600 mt-2">
                    ⚠️ Cette confirmation est obligatoire pour enregistrer la collecte dans le système.
                  </p>
                  <div class="mt-3 p-3 bg-white/50 rounded-lg border border-blue-200">
                    <p class="text-xs text-gray-700 font-medium">Vérifications requises :</p>
                    <ul class="mt-2 text-xs text-gray-600 space-y-1">
                      <li class="flex items-center gap-2">
                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                        Montant correct reçu en espèces
                      </li>
                      <li class="flex items-center gap-2">
                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                        Cachet apposé sur le carnet client
                      </li>
                      <li class="flex items-center gap-2">
                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                        Date et signature vérifiées
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </label>
          </div>
        </div>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
      <div class="flex flex-col sm:flex-row items-center justify-end gap-3">
        <a href="{{ $tontine ? route('agent.collectes.index', ['tontine_id' => $tontine->id]) : url()->previous() }}" 
           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-white border-2 border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all shadow-sm" 
           id="btn_cancel">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
          Annuler
        </a>
        <button
          type="submit"
          id="submit_collecte"
          class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg text-sm font-semibold text-white transition-all shadow-lg mb-btn-primary"
          style="background: linear-gradient(135deg, var(--mb-primary) 0%, #005f8d 100%);"
          {{ old('confirmed') ? '' : 'disabled' }}
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
          Enregistrer la collecte
        </button>
      </div>
    </div>

  </form>

</div>

{{-- inline script (garantit l'exécution même si @stack n'est pas rendu) --}}
<script>
  (function () {
    document.addEventListener('DOMContentLoaded', function () {
      const checkbox = document.getElementById('confirm_collecte');
      const submitBtn = document.getElementById('submit_collecte');

      function updateSubmitState() {
        if (!checkbox || !submitBtn) return;
        if (checkbox.checked) {
          submitBtn.removeAttribute('disabled');
          submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
          submitBtn.setAttribute('disabled', 'disabled');
          submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
      }

      // initialize state
      updateSubmitState();

      // toggle on change
      checkbox && checkbox.addEventListener('change', updateSubmitState);
    });
  })();
</script>
@endsection