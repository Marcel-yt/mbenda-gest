@extends('layouts.app-admin')

@section('title','Confirmer le paiement')
@section('page_title','Confirmer le paiement')

@section('content')
@php 
  $clientName = trim(($selected->client?->first_name ?? '').' '.($selected->client?->last_name ?? '')) ?: '-';
  $currency = $summary['currency'] ?? 'XAF';
@endphp
<div class="max-w-5xl mx-auto space-y-6">
  {{-- Breadcrumb Navigation --}}
  <nav class="flex items-center gap-2 text-sm text-gray-600">
    <a href="{{ route('admin.payouts.index') }}" class="hover:text-blue-600 transition-colors flex items-center gap-1" style="color: var(--mb-primary);">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
      </svg>
      Paiements
    </a>
    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
    </svg>
    <span class="text-gray-900 font-medium">Nouveau paiement</span>
  </nav>

  {{-- Alerts Section --}}
  @if(session('error'))
    <div class="bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 rounded-lg p-4 shadow-sm">
      <div class="flex items-start gap-3">
        <div class="flex-shrink-0">
          <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <div class="flex-1">
          <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
        </div>
      </div>
    </div>
  @endif

  @if ($errors->any())
    <div class="bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 rounded-lg p-4 shadow-sm">
      <div class="flex items-start gap-3">
        <div class="flex-shrink-0">
          <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <div class="flex-1">
          <h3 class="text-sm font-semibold text-red-800 mb-2">Erreurs</h3>
          <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  @endif

  {{-- Header Card --}}
  <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-start gap-4">
      <div class="w-16 h-16 rounded-xl flex items-center justify-center text-white shadow-lg" style="background: var(--mb-tertiary);">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
      </div>
      <div class="flex-1">
        <h1 class="text-2xl font-bold text-gray-900">Confirmation de paiement</h1>
        <p class="text-sm text-gray-600 mt-1">Vérifiez les informations avant de confirmer le paiement de la tontine</p>
        <div class="mt-3 inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
          </svg>
          Action irréversible
        </div>
      </div>
    </div>
  </div>

  <div class="grid gap-6 md:grid-cols-2">
    {{-- Tontine & Client Information --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="px-6 py-4" style="background: var(--mb-primary);">
        <h2 class="text-lg font-semibold text-white">Tontine & Bénéficiaire</h2>
      </div>
      <div class="p-6 space-y-4">
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-white" style="background: var(--mb-primary);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Code Tontine</div>
            <div class="mt-1 text-lg font-bold text-gray-900">{{ $selected->code }}</div>
          </div>
        </div>
        <div class="border-t border-gray-100 pt-4">
          <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
              <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-xs text-gray-500 uppercase tracking-wider">Client Bénéficiaire</div>
              <div class="mt-1 text-sm font-bold text-gray-900">{{ $clientName }}</div>
              @if($selected->client)
                <div class="mt-1 text-xs text-gray-600">{{ $selected->client->phone ?? '-' }}</div>
              @endif
            </div>
          </div>
        </div>
        <div class="border-t border-gray-100 pt-4">
          <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
              <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-xs text-gray-500 uppercase tracking-wider">Durée</div>
              <div class="mt-1 text-sm font-medium text-gray-900">{{ $selected->duration_days }} jours</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Collection Details --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="px-6 py-4" style="background: var(--mb-secondary);">
        <h2 class="text-lg font-semibold text-white">Détails de collecte</h2>
      </div>
      <div class="p-6 space-y-4">
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-white" style="background: var(--mb-secondary);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Montant journalier</div>
            <div class="mt-1 text-lg font-bold" style="color: var(--mb-secondary);">{{ number_format($summary['daily'] ?? 0,2) }} {{ $currency }}</div>
          </div>
        </div>
        <div class="border-t border-gray-100 pt-4">
          <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
              <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-xs text-gray-500 uppercase tracking-wider">Jours collectés</div>
              <div class="mt-1 text-sm font-medium text-gray-900">{{ $summary['effectiveDays'] ?? 0 }} / {{ $selected->duration_days }} jours</div>
              <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                <div class="h-2 rounded-full" style="background: var(--mb-secondary); width: {{ min(100, ($summary['effectiveDays'] ?? 0) / max(1, $selected->duration_days) * 100) }}%;"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Calculation Summary --}}
  <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4" style="background: var(--mb-tertiary);">
      <h2 class="text-lg font-semibold text-white">Récapitulatif des montants</h2>
    </div>
    <div class="p-6">
      <div class="space-y-3">
        <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg border border-blue-200">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-blue-500 flex items-center justify-center text-white">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
            </div>
            <div>
              <div class="text-sm font-medium text-gray-700">Montant brut collecté</div>
              <div class="text-xs text-gray-500">{{ $summary['effectiveDays'] ?? 0 }} jours × {{ number_format($summary['daily'] ?? 0,2) }} {{ $currency }}</div>
            </div>
          </div>
          <div class="text-xl font-bold text-blue-700">{{ number_format($summary['amount_gross'] ?? 0,2) }} {{ $currency }}</div>
        </div>

        <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg border border-red-200">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-red-500 flex items-center justify-center text-white">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
              </svg>
            </div>
            <div>
              <div class="text-sm font-medium text-gray-700">Frais de collecte</div>
              <div class="text-xs text-gray-500">1 jour de collecte</div>
            </div>
          </div>
          <div class="text-xl font-bold text-red-700">- {{ number_format($summary['commission_amount'] ?? 0,2) }} {{ $currency }}</div>
        </div>

        <div class="flex items-center justify-between p-5 rounded-lg border-2 shadow-sm" style="background: linear-gradient(135deg, #7FBC47 0%, #6BA03A 100%); border-color: #6BA03A;">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center text-white">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div>
              <div class="text-sm font-bold text-white uppercase tracking-wider">Montant net à payer</div>
              <div class="text-xs text-white/90">Montant remis au client</div>
            </div>
          </div>
          <div class="text-3xl font-black text-white">{{ number_format($summary['amount_net'] ?? 0,2) }} {{ $currency }}</div>
        </div>
      </div>
    </div>
  </div>

  {{-- Confirmation Form --}}
  <form method="POST" action="{{ route('admin.payouts.store') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    @csrf
    <input type="hidden" name="tontine_id" value="{{ $selected->id }}">
    
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
      <h3 class="text-sm font-semibold text-gray-700">Notes additionnelles (optionnel)</h3>
    </div>
    <div class="p-6">
      <textarea name="notes" rows="3" 
                class="mb-input w-full" 
                placeholder="Ajoutez des notes ou observations sur ce paiement..."></textarea>
    </div>
    
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
      <a href="{{ route('admin.payouts.index') }}" class="mb-link">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
        Annuler
      </a>
      <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg text-white font-semibold transition-all shadow-md hover:shadow-lg" style="background: var(--mb-tertiary);">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        Confirmer le paiement
      </button>
    </div>
  </form>
</div>
@endsection