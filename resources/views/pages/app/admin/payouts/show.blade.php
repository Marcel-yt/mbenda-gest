@extends('layouts.app-admin')

@section('title','Détail paiement')
@section('content')
@php $currency = $payout->tontine->settings['currency'] ?? 'XAF'; @endphp
<div class="max-w-3xl mx-auto space-y-6">
  <div class="flex items-center justify-between">
    <a href="{{ route('admin.payouts.index') }}" class="mb-link text-sm">&larr; Retour</a>
    <h1 class="text-lg font-semibold">Paiement #{{ $payout->id }}</h1>
  </div>
  <div class="bg-white border rounded-xl p-6 shadow-sm text-sm text-gray-700 space-y-4">
    <div class="grid grid-cols-2 gap-4">
      <div>
        <div class="text-xs text-gray-500">Tontine</div>
        <div class="mt-1 font-medium">{{ $payout->tontine?->code ?? '-' }}</div>
      </div>
      <div>
        <div class="text-xs text-gray-500">Date / Heure</div>
        <div class="mt-1 font-medium">{{ optional($payout->paid_at)->format('d/m/Y H:i') }}</div>
      </div>
      <div>
        <div class="text-xs text-gray-500">Montant brut</div>
        <div class="mt-1 font-medium">{{ number_format($payout->amount_gross,2) }} {{ $currency }}</div>
      </div>
      <div>
        <div class="text-xs text-gray-500">Frais de collecte (1 jour)</div>
        <div class="mt-1 font-medium">{{ number_format($payout->commission_amount,2) }} {{ $currency }}</div>
      </div>
      <div>
        <div class="text-xs text-gray-500">Montant net</div>
        <div class="mt-1 font-medium">{{ number_format($payout->amount_net,2) }} {{ $currency }}</div>
      </div>
      <div>
        <div class="text-xs text-gray-500">Admin</div>
        <div class="mt-1 font-medium">{{ $payout->admin?->name ?? $payout->admin?->email ?? '-' }}</div>
      </div>
      <div class="col-span-2">
        <div class="text-xs text-gray-500">Client</div>
        <div class="mt-1 font-medium">{{ trim(($payout->client?->first_name ?? '').' '.($payout->client?->last_name ?? '')) ?: '-' }}</div>
      </div>
      <div class="col-span-2">
        <div class="text-xs text-gray-500">Reçu PDF</div>
        <div class="mt-1">
          @php
            $rel = $payout->receipt_path;
            $exists = $rel && \Illuminate\Support\Facades\Storage::disk('public')->exists($rel);
          @endphp
          @if($exists)
            <a href="{{ asset('storage/'.$rel) }}" target="_blank" class="text-blue-600 hover:underline">Télécharger le reçu</a>
          @else
            <span class="text-gray-500">Aucun reçu</span>
          @endif
        </div>
      </div>
      @if($payout->notes)
        <div class="col-span-2">
          <div class="text-xs text-gray-500">Notes</div>
          <div class="mt-1 font-medium">{{ $payout->notes }}</div>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection