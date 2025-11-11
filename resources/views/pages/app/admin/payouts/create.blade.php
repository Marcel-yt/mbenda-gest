@extends('layouts.app-admin')

@section('title','Confirmer le paiement')
@section('content')
<div class="max-w-3xl mx-auto">
  <div class="bg-white border rounded-xl p-6 shadow-sm">
    <div class="text-sm text-gray-700 space-y-4">
      <div class="grid md:grid-cols-2 gap-4">
        <div>
          <div class="text-xs text-gray-500">Tontine</div>
          <div class="mt-1 font-medium">{{ $selected->code }}</div>
        </div>
        <div>
          <div class="text-xs text-gray-500">Client</div>
          <div class="mt-1 font-medium">{{ trim(($selected->client?->first_name ?? '').' '.($selected->client?->last_name ?? '')) ?: '-' }}</div>
        </div>
        <div>
          <div class="text-xs text-gray-500">Montant journalier</div>
          <div class="mt-1 font-medium">{{ number_format($summary['daily'] ?? 0,2) }} {{ $summary['currency'] ?? 'XAF' }}</div>
        </div>
        <div>
          <div class="text-xs text-gray-500">Jours collectés (effectifs)</div>
          <div class="mt-1 font-medium">{{ $summary['effectiveDays'] ?? 0 }} / {{ $selected->duration_days }}</div>
        </div>
        <div>
          <div class="text-xs text-gray-500">Montant brut</div>
          <div class="mt-1 font-medium">{{ number_format($summary['amount_gross'] ?? 0,2) }} {{ $summary['currency'] ?? 'XAF' }}</div>
        </div>
        <div>
          <div class="text-xs text-gray-500">Frais de collecte (1 jour)</div>
          <div class="mt-1 font-medium">{{ number_format($summary['commission_amount'] ?? 0,2) }} {{ $summary['currency'] ?? 'XAF' }}</div>
        </div>
        <div class="md:col-span-2">
          <div class="text-xs text-gray-500">Montant net à payer</div>
          <div class="mt-1 text-lg font-semibold text-green-700">
            {{ number_format($summary['amount_net'] ?? 0,2) }} {{ $summary['currency'] ?? 'XAF' }}
          </div>
        </div>
      </div>

      <form method="POST" action="{{ route('admin.payouts.store') }}" class="mt-6">
        @csrf
        <input type="hidden" name="tontine_id" value="{{ $selected->id }}">
        <div class="flex justify-end gap-2">
          <a href="{{ route('admin.payouts.index') }}" class="mb-link">Annuler</a>
          <button type="submit" class="mb-btn-primary px-4 py-2 rounded">Confirmer le paiement</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection