@extends('layouts.app-admin')

@section('title', 'Détail collecte')
@section('page_title', 'Détail collecte')

@section('content')
@php $currency = $collecte->tontine?->settings['currency'] ?? 'XAF'; @endphp
<div class="max-w-5xl mx-auto space-y-6">
  <div class="flex items-center justify-between">
    <a href="{{ route('admin.collectes.index') }}" class="mb-link text-sm">&larr; Retour</a>
  </div>
  <div class="bg-white border rounded-xl p-6 shadow-sm text-sm text-gray-700 space-y-4">
    <div class="grid grid-cols-2 gap-4">
      <div>
        <div class="text-xs text-gray-500">ID</div>
        <div class="mt-1 font-medium">{{ $collecte->id }}</div>
      </div>
      <div>
        <div class="text-xs text-gray-500">Date</div>
        <div class="mt-1 font-medium">{{ optional($collecte->collected_at ?? $collecte->created_at)->format('d/m/Y H:i') }}</div>
      </div>
      <div>
        <div class="text-xs text-gray-500">Montant</div>
        <div class="mt-1 font-medium">{{ number_format($collecte->amount ?? 0, 2) }} {{ $currency }}</div>
      </div>
      <div>
        <div class="text-xs text-gray-500">Agent</div>
        @php
          // Essayer relation user puis agent (selon implémentation)
          $agent = $collecte->user ?? $collecte->agent ?? null;
          $names = trim(($agent?->first_name ?? '').' '.($agent?->last_name ?? ''));
          $display = $names !== '' ? $names : ($agent?->name ?? $agent?->email ?? '-');
        @endphp
        <div class="mt-1 font-medium">
          {{ $display }}
          @if($agent && $agent?->email && $display !== $agent->email)
            <span class="text-xs text-gray-500 ml-1">({{ $agent->email }})</span>
          @endif
        </div>
      </div>
      <div class="col-span-2">
        <div class="text-xs text-gray-500">Tontine</div>
        <div class="mt-1 font-medium">
          {{ $collecte->tontine?->code ?? '-' }}
          @if($collecte->tontine)
            <a href="{{ route('admin.tontines.show', $collecte->tontine->id) }}" class="ml-2 text-blue-600 hover:underline">Voir la tontine</a>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection