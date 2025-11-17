@extends('layouts.app-admin')

@section('title', 'Détail collecte')
@section('page_title', 'Détail collecte')

@section('content')
@php 
  $currency = $collecte->tontine?->settings['currency'] ?? 'XAF';
  $agent = $collecte->user ?? $collecte->agent ?? null;
  $names = trim(($agent?->first_name ?? '').' '.($agent?->last_name ?? ''));
  $display = $names !== '' ? $names : ($agent?->name ?? $agent?->email ?? '-');
@endphp
<div class="max-w-4xl mx-auto space-y-6">
  {{-- Header Section --}}
  <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-start justify-between">
      <div class="flex-1">
        <div class="flex items-center gap-3 mb-2">
          <div class="w-12 h-12 rounded-lg flex items-center justify-center text-white shadow-lg" style="background: var(--mb-secondary);">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
          </div>
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Collecte #{{ $collecte->id }}</h1>
            <p class="text-sm text-gray-600 mt-1">Détails complets de la collecte</p>
          </div>
        </div>
        <div class="flex items-center gap-3 mt-3">
          <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-green-100 text-green-700">
            <span class="mb-ind-active"></span>
            Collectée
          </span>
          <span class="text-sm text-gray-600">
            {{ optional($collecte->collected_at ?? $collecte->created_at)->format('d/m/Y à H:i') }}
          </span>
        </div>
      </div>
      {{-- Action Button --}}
      <div class="ml-4">
        <a href="{{ route('admin.collectes.index') }}" 
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

  {{-- Information Cards --}}
  <div class="grid gap-6 md:grid-cols-2">
    {{-- Collecte Information --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="px-6 py-4" style="background: var(--mb-secondary);">
        <h2 class="text-lg font-semibold text-white">Informations de collecte</h2>
      </div>
      <div class="p-6 space-y-4">
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Identifiant</div>
            <div class="mt-1 text-sm font-medium text-gray-900">#{{ $collecte->id }}</div>
          </div>
        </div>
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-white" style="background: var(--mb-secondary);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Montant collecté</div>
            <div class="mt-1 text-xl font-bold" style="color: var(--mb-secondary);">{{ number_format($collecte->amount ?? 0, 2) }} {{ $currency }}</div>
          </div>
        </div>
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Date de collecte</div>
            <div class="mt-1 text-sm font-medium text-gray-900">{{ optional($collecte->collected_at ?? $collecte->created_at)->format('d/m/Y à H:i') }}</div>
          </div>
        </div>
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Créée le</div>
            <div class="mt-1 text-sm font-medium text-gray-900">{{ $collecte->created_at->format('d/m/Y à H:i') }}</div>
          </div>
        </div>
      </div>
    </div>

    {{-- Agent & Tontine Information --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="px-6 py-4" style="background: var(--mb-primary);">
        <h2 class="text-lg font-semibold text-white">Agent & Tontine</h2>
      </div>
      <div class="p-6 space-y-4">
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Agent collecteur</div>
            <div class="mt-1 text-sm font-medium text-gray-900">{{ $display }}</div>
            @if($agent && $agent?->email && $display !== $agent->email)
              <div class="mt-0.5 text-xs text-gray-500">{{ $agent->email }}</div>
            @endif
          </div>
        </div>
        <div class="border-t border-gray-100 pt-4">
          <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-white" style="background: var(--mb-primary);">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-xs text-gray-500 uppercase tracking-wider">Tontine associée</div>
              <div class="mt-1 text-sm font-bold text-gray-900">{{ $collecte->tontine?->code ?? '-' }}</div>
              @if($collecte->tontine)
                <div class="mt-2">
                  <a href="{{ route('admin.tontines.show', $collecte->tontine->id) }}" 
                     class="inline-flex items-center gap-1.5 text-xs font-medium hover:underline" 
                     style="color: var(--mb-primary);">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Voir la tontine
                  </a>
                </div>
                <div class="mt-3 text-xs text-gray-600 space-y-1">
                  <div class="flex justify-between">
                    <span class="text-gray-500">Montant journalier:</span>
                    <span class="font-medium">{{ number_format($collecte->tontine->daily_amount ?? 0, 2) }} {{ $currency }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-500">Statut:</span>
                    <span class="font-medium capitalize">{{ $collecte->tontine->status ?? '-' }}</span>
                  </div>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection