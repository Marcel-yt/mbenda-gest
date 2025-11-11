@extends('layouts.app-admin')

@section('title', 'Détails tontine')
@section('page_title', 'Détails tontine')

@section('content')
@php /** @var \App\Models\Tontine $tontine */ @endphp
<div class="max-w-5xl mx-auto space-y-6">
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-semibold flex items-center gap-2">
      {{ $tontine->code }}
      <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $tontine->status_badge_classes }}">
        {{ ucfirst($tontine->status) }}
      </span>
    </h1>
    <div class="flex gap-2">
      <a class="mb-link" href="{{ route('admin.tontines.index') }}">← Retour</a>
      <a class="mb-btn-primary px-3 py-2 rounded" href="{{ route('admin.tontines.edit', $tontine->id) }}">Modifier</a>
    </div>
  </div>

  {{-- Deux colonnes: gauche = Tontine, droite = Client --}}
  <div class="grid gap-6 md:grid-cols-2">
    {{-- Colonne gauche: Tontine --}}
    <section class="bg-white border rounded-xl p-6 shadow-sm flex flex-col">
      <h2 class="text-sm font-semibold text-gray-700 mb-4 flex items-center justify-between">
        <span>Informations tontine</span>
        @if($tontine->status !== 'paid')
          <button type="button"
                  class="mb-btn-tertiary px-4 py-2 rounded text-sm font-medium"
                  onclick="handlePayoutClick()">
            Payer
          </button>
        @endif
      </h2>
      <div class="grid grid-cols-1 gap-4 text-sm text-gray-700">
        <div>
          <div class="text-xs text-gray-500">Code</div>
          <div class="mt-1 font-medium">{{ $tontine->code }}</div>
        </div>
        <div>
          <div class="text-xs text-gray-500">Créée par (agent)</div>
          <div class="mt-1 font-medium">{{ $tontine->creator?->email ?? '-' }}</div>
        </div>
        <div>
          <div class="text-xs text-gray-500">Montant journalier</div>
          <div class="mt-1 font-medium">{{ number_format($tontine->daily_amount,2) }} {{ $tontine->settings['currency'] ?? 'XAF' }}</div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <div class="text-xs text-gray-500">Date de début</div>
            <div class="mt-1 font-medium">{{ $tontine->start_date?->format('d/m/Y') ?? '-' }}</div>
          </div>
          <div>
            <div class="text-xs text-gray-500">Fin prévue</div>
            <div class="mt-1 font-medium">{{ $tontine->expected_end_date?->format('d/m/Y') ?? '-' }}</div>
          </div>
        </div>
        <div>
          <div class="text-xs text-gray-500">Durée</div>
          <div class="mt-1 font-medium">{{ $tontine->duration_days }} jours</div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <div class="text-xs text-gray-500">Total collecté (cache)</div>
            <div class="mt-1 font-medium">{{ number_format($tontine->collected_total ?? 0,2) }} {{ $tontine->settings['currency'] ?? 'XAF' }}</div>
          </div>
          <div>
            <div class="text-xs text-gray-500">Commission (jours)</div>
            <div class="mt-1 font-medium">{{ $tontine->commission_days }}</div>
          </div>
        </div>

        @php
          $currency = $tontine->settings['currency'] ?? 'XAF';
          $daily = (float)($tontine->daily_amount ?? 0);
          $totalCollecte = (float)($tontine->collected_total ?? 0);
          $montantAPayer = max($totalCollecte - $daily, 0);
          $commissionMontant = $daily;
        @endphp
        <div class="grid grid-cols-2 gap-4">
          <div>
            <div class="text-xs text-gray-500">Montant à payer (net)</div>
            <div class="mt-1 font-semibold text-green-700">
              {{ number_format($montantAPayer, 2) }} {{ $currency }}
            </div>
            <div class="text-[11px] text-gray-400 mt-0.5">Total collecté - 1 jour de collecte</div>
          </div>
          <div>
            <div class="text-xs text-gray-500">Commission (montant)</div>
            <div class="mt-1 font-semibold text-indigo-700">
              {{ number_format($commissionMontant, 2) }} {{ $currency }}
            </div>
            <div class="text-[11px] text-gray-400 mt-0.5">Rémunération (1 jour)</div>
          </div>
        </div>
        <div>
          <div class="text-xs text-gray-500">Statut</div>
          <div class="mt-1">
            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $tontine->status_badge_classes }}">
              {{ ucfirst($tontine->status) }}
            </span>
          </div>
        </div>
        <div>
          <div class="text-xs text-gray-500">Paramètres</div>
          <div class="mt-1 font-medium break-all">{{ json_encode($tontine->settings ?? []) }}</div>
        </div>
      </div>
    </section>
    {{-- Colonne droite: Client --}}
    <section class="bg-white border rounded-xl p-6 shadow-sm">
      <h2 class="text-sm font-semibold text-gray-700 mb-4">Informations du client</h2>
      <div class="flex items-start gap-4">
        <div class="flex-shrink-0">
          @if(!empty($tontine->client?->photo_profil))
            <img src="{{ asset('storage/' . ltrim($tontine->client->photo_profil, '/')) }}" alt="Photo client" class="w-12 h-12 rounded-full object-cover" />
          @else
            <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-800 flex items-center justify-center font-semibold">
              {{ strtoupper(substr($tontine->client?->first_name ?? '-',0,1) . substr($tontine->client?->last_name ?? '-',0,1)) }}
            </div>
          @endif
        </div>
        <div class="flex-1">
          <div class="flex items-center justify-between">
            <div>
              <div class="text-xs text-gray-500">Client</div>
              <div class="text-lg font-semibold mt-1">
                {{ trim(($tontine->client?->first_name ?? '') . ' ' . ($tontine->client?->last_name ?? '')) ?: '-' }}
              </div>
              <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-700">
                <div>
                  <div class="text-xs text-gray-500">Téléphone</div>
                  <div class="mt-1 font-medium">{{ $tontine->client?->phone ?? '-' }}</div>
                </div>
                <div>
                  <div class="text-xs text-gray-500">Indicatif</div>
                  <div class="mt-1 font-medium">{{ $tontine->client?->indicatif ?? '-' }}</div>
                </div>
                <div>
                  <div class="text-xs text-gray-500">Créé par</div>
                  <div class="mt-1 font-medium">
                    {{ $tontine->creator ? trim(($tontine->creator->first_name ?? '') . ' ' . ($tontine->creator->last_name ?? '')) : ($tontine->creator?->email ?? '-') }}
                  </div>
                </div>
              </div>
            </div>
            <div class="text-right text-sm">
              <div class="text-xs text-gray-500">Inscrit le</div>
              <div class="mt-1 font-medium">
                {{ optional($tontine->client?->created_at)->format('d/m/Y') ?? '-' }}
              </div>
            </div>
          </div>
          <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-700">
            <div>
              <div class="text-xs text-gray-500">Adresse</div>
              <div class="mt-1 font-medium">{{ $tontine->client?->address ?? '-' }}</div>
            </div>
            <div>
              <div class="text-xs text-gray-500">Statut compte</div>
              <div class="mt-1">
                <div class="flex items-center gap-2">
                  <span class="inline-block w-2.5 h-2.5 rounded-full" style="background: {{ $tontine->client?->statut ? '#16A34A' : '#DC2626' }};"></span>
                  <span class="{{ $tontine->client?->statut ? 'text-green-600' : 'text-red-600' }} text-sm font-medium">
                    {{ $tontine->client?->statut ? 'Actif' : 'Désactivé' }}
                  </span>
                </div>
              </div>
            </div>
            <div class="md:col-span-2">
              <div class="text-xs text-gray-500">Remarques</div>
              <div class="mt-1 font-medium">{{ $tontine->client?->notes ?? '-' }}</div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

{{-- Modals de vérification avant paiement (centrés) --}}
<div id="payBlockedModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 p-4">
  <div class="bg-white w-full max-w-md rounded-xl shadow-lg border p-6">
    <h3 class="text-base font-semibold mb-2">Paiement impossible</h3>
    <p class="text-sm text-gray-600">
      inposible de payer cette tontine car aucunne collecte n'a ete effectuer
    </p>
    <div class="mt-6 flex justify-end">
      <button type="button" class="mb-btn-secondary px-4 py-2 rounded" onclick="closeModal('payBlockedModal')">Annuler</button>
    </div>
  </div>
</div>

<div id="payConfirmModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 p-4">
  <div class="bg-white w-full max-w-md rounded-xl shadow-lg border p-6">
    <h3 class="text-base font-semibold mb-2">Confirmer le paiement</h3>
    <p class="text-sm text-gray-600">
      cette tontine n'est pas terminer, voulez vous qu'a meme continuer le payement?
    </p>
    <div class="mt-6 flex justify-end gap-2">
      <button type="button" class="mb-btn-secondary px-4 py-2 rounded" onclick="closeModal('payConfirmModal')">Annuler</button>
      <button type="button" class="mb-btn-primary px-4 py-2 rounded" onclick="continuePayout()">Continuer</button>
    </div>
  </div>
</div>

<script>
  (function () {
    const status = '{{ $tontine->status }}';
    const createUrl = '{{ route('admin.payouts.create', $tontine->id) }}';

    window.handlePayoutClick = function () {
      if (status === 'draft') {
        document.getElementById('payBlockedModal').classList.remove('hidden');
      } else if (status === 'active') {
        document.getElementById('payConfirmModal').classList.remove('hidden');
      } else if (status === 'completed') {
        window.location.href = createUrl;
      } else {
        // Autres statuts: demander confirmation par défaut
        document.getElementById('payConfirmModal').classList.remove('hidden');
      }
    };

    window.closeModal = function (id) {
      document.getElementById(id).classList.add('hidden');
    };

    window.continuePayout = function () {
      window.location.href = createUrl;
    };
  })();
</script>
@endsection