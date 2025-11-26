@extends('layouts.app-agent')

@section('title','Détail paiement')
@section('page_title','Détail paiement')

@section('content')
@php 
  $currency = $payout->tontine->settings['currency'] ?? 'XAF';
  $adminDisplay = $payout->admin ? trim(($payout->admin->first_name ?? '').' '.($payout->admin->last_name ?? '')) : '';
  $adminDisplay = $adminDisplay ?: ($payout->admin?->name ?? $payout->admin?->email ?? '-');
  $clientName = trim(($payout->client?->first_name ?? '').' '.($payout->client?->last_name ?? '')) ?: '-';
@endphp
<div class="max-w-5xl mx-auto space-y-6">
  {{-- Header Section --}}
  <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-start justify-between">
      <div class="flex-1">
        <div class="flex items-center gap-3 mb-2">
          <div class="w-12 h-12 rounded-lg flex items-center justify-center text-white shadow-lg" style="background: var(--mb-tertiary);">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
          </div>
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Paiement #{{ $payout->id }}</h1>
            <p class="text-sm text-gray-600 mt-1">Détails complets du paiement de tontine</p>
          </div>
        </div>
        <div class="flex items-center gap-3 mt-3">
          <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-green-100 text-green-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Payé
          </span>
          <span class="text-sm text-gray-600">
            {{ optional($payout->paid_at)->format('d/m/Y à H:i') }}
          </span>
        </div>
      </div>
      {{-- Action Button --}}
      <div class="ml-4">
        <a href="{{ route('agent.payouts.index') }}" 
           class="mb-link"
           title="Retour à la liste">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
          </svg>
          Retour
        </a>
      </div>
    </div>
  </div>

  {{-- Montants Section --}}
  <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4" style="background: var(--mb-tertiary);">
      <h2 class="text-lg font-semibold text-white">Détails des montants</h2>
    </div>
    <div class="p-6">
      <div class="grid gap-4 md:grid-cols-3">
        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
          <div class="text-xs text-blue-600 font-medium uppercase tracking-wider">Montant brut</div>
          <div class="mt-2 text-2xl font-bold text-blue-700">{{ number_format($payout->amount_gross,2) }}</div>
          <div class="text-xs text-blue-600 mt-1">{{ $currency }}</div>
        </div>
        <div class="bg-red-50 rounded-lg p-4 border border-red-200">
          <div class="text-xs text-red-600 font-medium uppercase tracking-wider">Frais de collecte</div>
          <div class="mt-2 text-2xl font-bold text-red-700">{{ number_format($payout->commission_amount,2) }}</div>
          <div class="text-xs text-red-600 mt-1">{{ $currency }} (1 jour)</div>
        </div>
        <div class="rounded-lg p-4 border-2" style="background: linear-gradient(135deg, #7FBC47 0%, #6BA03A 100%); border-color: #6BA03A;">
          <div class="text-xs text-white font-medium uppercase tracking-wider">Montant net remis</div>
          <div class="mt-2 text-2xl font-bold text-white">{{ number_format($payout->amount_net,2) }}</div>
          <div class="text-xs text-white/90 mt-1">{{ $currency }}</div>
        </div>
      </div>
    </div>
  </div>

  {{-- Information Cards Grid --}}
  <div class="grid gap-6 md:grid-cols-2">
    {{-- Tontine & Client Information --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="px-6 py-4" style="background: var(--mb-primary);">
        <h2 class="text-lg font-semibold text-white">Tontine & Client</h2>
      </div>
      <div class="p-6 space-y-4">
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-white" style="background: var(--mb-primary);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Code tontine</div>
            <div class="mt-1 text-sm font-bold text-gray-900">{{ $payout->tontine?->code ?? '-' }}</div>
            @if($payout->tontine)
              <div class="mt-2">
                <a href="{{ route('agent.tontines.show', $payout->tontine->id) }}" 
                   class="inline-flex items-center gap-1.5 text-xs font-medium hover:underline" 
                   style="color: var(--mb-primary);">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                  </svg>
                  Voir la tontine
                </a>
              </div>
            @endif
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
              <div class="text-xs text-gray-500 uppercase tracking-wider">Client bénéficiaire</div>
              <div class="mt-1 text-sm font-medium text-gray-900">{{ $clientName }}</div>
              @if($payout->client)
                <div class="mt-2">
                  <a href="{{ route('clients.show', $payout->client->id) }}" 
                     class="inline-flex items-center gap-1.5 text-xs font-medium hover:underline" 
                     style="color: var(--mb-primary);">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Voir le client
                  </a>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Admin & Document Information --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="px-6 py-4" style="background: var(--mb-tertiary);">
        <h2 class="text-lg font-semibold text-white">Admin & Document</h2>
      </div>
      <div class="p-6 space-y-4">
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Paiement effectué par</div>
            <div class="mt-1 text-sm font-medium text-gray-900">{{ $adminDisplay }}</div>
          </div>
        </div>
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Date du paiement</div>
            <div class="mt-1 text-sm font-medium text-gray-900">{{ optional($payout->paid_at)->format('d/m/Y à H:i') }}</div>
          </div>
        </div>
        <div class="border-t border-gray-100 pt-4">
          <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-white" style="background: var(--mb-tertiary);">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-xs text-gray-500 uppercase tracking-wider">Reçu PDF</div>
              @php
                $rel = $payout->receipt_path;
                $exists = $rel && \Illuminate\Support\Facades\Storage::disk('public')->exists($rel);
              @endphp
              @if($exists)
                <div class="mt-2">
                  <a href="{{ route('agent.payouts.download', $payout->id) }}" 
                    target="_blank" 
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white transition-all hover:opacity-90"
                    style="background: var(--mb-tertiary);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Télécharger le reçu
                  </a>
                </div>
              @else
                <div class="mt-1 text-sm text-gray-500">Aucun reçu disponible</div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Notes Section --}}
  @if($payout->notes)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
        <h2 class="text-lg font-semibold text-gray-900">Notes</h2>
      </div>
      <div class="p-6">
        <div class="text-sm text-gray-700 whitespace-pre-wrap">{{ $payout->notes }}</div>
      </div>
    </div>
  @endif
</div>
@endsection
