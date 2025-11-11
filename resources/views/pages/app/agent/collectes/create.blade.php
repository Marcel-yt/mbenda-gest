@extends('layouts.app-agent')

@section('title', 'Nouvelle collecte')
@section('page_title', 'Nouvelle collecte')

@section('content')
@php /** @var \App\Models\Tontine|null $tontine */ @endphp
@php use \Illuminate\Support\Carbon; @endphp

<div class="max-w-2xl mx-auto">
  <section class="bg-white border rounded-xl p-6 shadow-sm">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-lg font-semibold">Enregistrer une collecte</h2>
    </div>

    @if ($errors->any())
      <div class="mb-4 text-sm text-red-600">
        <ul class="list-disc pl-5">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('agent.collectes.store') }}" method="post" class="space-y-4" id="collecte-form">
      @csrf

      @if($tontine)
        <input type="hidden" name="tontine_id" value="{{ $tontine->id }}" />
        @if(request()->filled('date'))
          <input type="hidden" name="date" value="{{ request('date') }}" />
        @endif
        {{-- plus de champ date: created_at = maintenant côté serveur --}}
        <div>
          <div class="text-xs text-gray-500">Tontine</div>
          <div class="mt-1 font-medium">{{ $tontine->code }} — {{ $tontine->client?->first_name }} {{ $tontine->client?->last_name }}</div>
          <div class="text-sm text-gray-500 mt-1">
            Montant journalier : <span class="font-medium">{{ number_format($tontine->daily_amount,2) }} {{ $tontine->settings['currency'] ?? 'XAF' }}</span>
          </div>
        </div>
      @else
        <div>
          <label class="text-xs text-gray-500">Tontine</label>
          <select name="tontine_id" required class="mt-1 block w-full rounded border-gray-300">
            <option value="">-- sélectionnez une tontine --</option>
            @foreach(\App\Models\Tontine::with('client')->orderByDesc('created_at')->limit(200)->get() as $t)
              <option value="{{ $t->id }}" {{ old('tontine_id') == $t->id ? 'selected' : '' }}>
                {{ $t->code }} — {{ $t->client?->first_name }} {{ $t->client?->last_name }} — {{ number_format($t->daily_amount,2) }}
              </option>
            @endforeach
          </select>
        </div>
      @endif

      <div>
        <label class="text-xs text-gray-500">Remarques (facultatif)</label>
        <textarea name="notes" rows="3" class="mt-1 block w-full rounded border-gray-300" placeholder="Ex: Particularité, cachet, etc.">{{ old('notes') }}</textarea>
      </div>

      {{-- NOTE: Date de collecte supprimée — created_at de la table collectes sera utilisée --}}

      {{-- confirmation checkbox: must be checked to enable submit --}}
      <div class="flex items-start gap-3 mt-2">
        <div class="flex items-center h-5">
          <input
            id="confirm_collecte"
            name="confirmed"
            type="checkbox"
            value="1"
            {{ old('confirmed') ? 'checked' : '' }}
            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
          />
        </div>
        <div class="text-sm text-gray-700">
          <label for="confirm_collecte" class="font-medium">Je confirme que la collecte a été effectuée en espèces et apposé le cachet sur le carnet du client.</label>
          <p class="text-xs text-gray-500 mt-1">La collecte ne pourra pas être enregistrée tant que vous n'avez pas confirmé.</p>
        </div>
      </div>

      <div class="flex items-center justify-end gap-3 pt-4">
        <a href="{{ url()->previous() }}" class="mb-link" id="btn_cancel">Annuler</a>
        <button
          type="submit"
          id="submit_collecte"
          class="mb-btn-primary px-4 py-2 rounded"
          {{ old('confirmed') ? '' : 'disabled' }}
        >
          Enregistrer la collecte
        </button>
      </div>
    </form>
  </section>
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