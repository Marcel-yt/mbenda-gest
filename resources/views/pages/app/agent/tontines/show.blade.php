@extends('layouts.app-agent')

@section('title', 'Détails tontine')
@section('page_title', 'Détails tontine')

@section('content')
@php /** @var \App\Models\Tontine $tontine */ @endphp

<div class="max-w-3xl mx-auto space-y-6">

  {{-- Header / actions --}}
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-semibold">{{ $tontine->code }}</h1>
    </div>

    <div class="flex items-center gap-2">
      <a href="{{ route('agent.tontines.index') }}" class="mb-link">← Retour</a>
      {{-- Edition réservée à l'admin --}}
    </div>
  </div>

  {{-- SECTION 1 : Informations client (fields from DB only) --}}
  <section class="bg-white border rounded-xl p-6 shadow-sm">
    <div class="flex items-start gap-4">
      <div class="flex-shrink-0">
        {{-- avatar initials or photo if present --}}
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
            {{-- separated info styled like the lower grid --}}
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
          {{-- Adresse (si présente) --}}
          <div>
            <div class="text-xs text-gray-500">Adresse</div>
            <div class="mt-1 font-medium">{{ $tontine->client?->address ?? '-' }}</div>
          </div>

          {{-- Statut (si présent) --}}
          <div>
            <div class="text-xs text-gray-500">Statut</div>
            <div class="mt-1 font-medium">
              @if(isset($tontine->client->statut))
                {{ $tontine->client->statut == '1' ? 'Activé' : 'Désactivé' }}
              @else
                -
              @endif
            </div>
          </div>

          {{-- Notes (si présentes) --}}
          <div class="md:col-span-2">
            <div class="text-xs text-gray-500">Remarques</div>
            <div class="mt-1 font-medium">{{ $tontine->client?->notes ?? '-' }}</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- SECTION 2 : Informations tontine --}}
  <section class="bg-white border rounded-xl p-6 shadow-sm">
    <div class="grid grid-cols-1 gap-4 text-sm text-gray-700">
      <div>
        <div class="text-xs text-gray-500">Code tontine</div>
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

      <div>
        <div class="text-xs text-gray-500">Statut</div>
        <div class="mt-1 font-medium">{{ ucfirst($tontine->status) }}</div>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <div class="text-xs text-gray-500">Total collecté (cache)</div>
          <div class="mt-1 font-medium">{{ number_format($tontine->collected_total ?? 0,2) }}</div>
        </div>

        <div>
          <div class="text-xs text-gray-500">Commission (jours)</div>
          <div class="mt-1 font-medium">{{ $tontine->commission_days }}</div>
        </div>
      </div>

      <div>
        <div class="text-xs text-gray-500">Notes / paramètres</div>
        <div class="mt-1 font-medium">{{ json_encode($tontine->settings ?? []) }}</div>
      </div>
    </div>
  </section>

</div>
@endsection