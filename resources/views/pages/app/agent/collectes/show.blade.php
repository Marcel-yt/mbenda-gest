@extends('layouts.app-agent')

@section('title', 'Détails collecte')
@section('page_title', 'Détails collecte')

@section('content')
@php
  /** @var \App\Models\Collecte $collecte */
  $forDate = !empty($collecte->for_date) ? \Illuminate\Support\Carbon::parse($collecte->for_date) : null;
  $sameDay = $forDate && $collecte->created_at ? $forDate->isSameDay($collecte->created_at) : null;
  $agentHex = $collecte->agent?->color_hex ?: '#22c55e';
@endphp

<div class="max-w-2xl mx-auto space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-semibold">Collecte — {{ $collecte->tontine?->code ?? ('#'.$collecte->tontine_id) }}</h1>
      <div class="text-sm text-gray-600">
        {{ $collecte->tontine?->client?->first_name ?? '-' }} {{ $collecte->tontine?->client?->last_name ?? '' }}
      </div>
    </div>
    <a href="{{ route('agent.collectes.index', ['tontine_id' => $collecte->tontine_id]) }}" class="mb-link">← Retour</a>
  </div>

  <section class="bg-white border rounded-xl p-6 shadow-sm grid grid-cols-1 gap-4 text-sm text-gray-700">
    <div>
      <div class="text-xs text-gray-500">Date réelle (enregistrement)</div>
      <div class="mt-1 font-medium">{{ $collecte->created_at?->format('d/m/Y H:i') ?? '-' }}</div>
    </div>

    <div>
      <div class="text-xs text-gray-500">Jour du calendrier</div>
      <div class="mt-1 font-medium">
        {{ $forDate ? $forDate->format('d/m/Y') : '-' }}
        @if($forDate && $sameDay === false)
          <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs bg-amber-100 text-amber-800">différent</span>
        @endif
      </div>
    </div>

    <div>
      <div class="text-xs text-gray-500">Agent</div>
      <div class="mt-1 flex items-center gap-2">
        <span class="inline-block w-3 h-3 rounded-full" style="background-color: {{ $agentHex }}"></span>
        <span class="font-medium">
          {{ trim(($collecte->agent->first_name ?? '').' '.($collecte->agent->last_name ?? '')) ?: ($collecte->agent->email ?? '-') }}
        </span>
      </div>
    </div>

    <div>
      <div class="text-xs text-gray-500">Montant</div>
      <div class="mt-1 font-medium">
        {{ number_format($collecte->amount ?? ($collecte->tontine?->daily_amount ?? 0), 2) }} {{ $collecte->tontine?->settings['currency'] ?? 'XAF' }}
      </div>
    </div>

    <div>
      <div class="text-xs text-gray-500">Notes</div>
      <div class="mt-1 text-gray-700">{{ $collecte->notes ?? '-' }}</div>
    </div>
  </section>
</div>
@endsection