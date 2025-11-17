@extends('layouts.app-agent')

@section('title', 'Détails collecte')
@section('page_title', 'Détails collecte')

@section('content')
@php
  /** @var \App\Models\Collecte $collecte */
  $forDate = !empty($collecte->for_date) ? \Illuminate\Support\Carbon::parse($collecte->for_date) : null;
  $sameDay = $forDate && $collecte->created_at ? $forDate->isSameDay($collecte->created_at) : null;
  $agentHex = $collecte->agent?->color_hex ?: '#7FBC47';
@endphp

<div class="max-w-5xl mx-auto space-y-6">

  <!-- Breadcrumb -->
  <nav class="flex items-center gap-2 text-sm text-gray-600">
    <a href="{{ route('agent.tontines.index') }}" class="hover:text-gray-900 transition-colors">
      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
        <circle cx="12" cy="14" r="2"/>
      </svg>
    </a>
    <svg class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="9 18 15 12 9 6"/>
    </svg>
    <a href="{{ route('agent.tontines.index') }}" class="hover:text-gray-900 transition-colors">Tontines</a>
    <svg class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="9 18 15 12 9 6"/>
    </svg>
    <a href="{{ route('agent.tontines.show', $collecte->tontine) }}" class="hover:text-gray-900 transition-colors">{{ $collecte->tontine?->code ?? '#'.$collecte->tontine_id }}</a>
    <svg class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="9 18 15 12 9 6"/>
    </svg>
    <a href="{{ route('agent.collectes.index', ['tontine_id' => $collecte->tontine_id]) }}" class="hover:text-gray-900 transition-colors">Calendrier</a>
    <svg class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="9 18 15 12 9 6"/>
    </svg>
    <span class="font-medium text-gray-900">Collecte #{{ $collecte->id }}</span>
  </nav>

  <!-- Header Card -->
  <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-start justify-between">
      <div class="flex items-start gap-4">
        <div class="w-14 h-14 rounded-lg flex items-center justify-center text-white shadow-lg" style="background: linear-gradient(135deg, {{ $agentHex }} 0%, {{ $agentHex }}dd 100%);">
          <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"/>
          </svg>
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Détails de la collecte</h1>
          <div class="flex items-center gap-3 mt-2">
            <span class="text-sm text-gray-600">Tontine:</span>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold" style="background-color: rgba(127, 188, 71, 0.1); color: var(--mb-secondary);">
              {{ $collecte->tontine?->code ?? '#'.$collecte->tontine_id }}
            </span>
          </div>
          <div class="flex items-center gap-2 mt-1 text-sm text-gray-600">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
              <circle cx="9" cy="7" r="4"/>
              <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
              <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            <span>{{ $collecte->tontine?->client?->first_name ?? '-' }} {{ $collecte->tontine?->client?->last_name ?? '' }}</span>
          </div>
        </div>
      </div>
      <div class="flex items-center gap-2">
        <a href="{{ route('agent.collectes.index', ['tontine_id' => $collecte->tontine_id]) }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all shadow-sm">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
          </svg>
          Retour au calendrier
        </a>
      </div>
    </div>
  </div>

  <!-- Information Cards Grid -->
  <div class="grid gap-6 md:grid-cols-2">
    
    <!-- Dates Information Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="px-6 py-4" style="background: linear-gradient(135deg, var(--mb-primary) 0%, #005f8d 100%);">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
            <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
              <line x1="16" y1="2" x2="16" y2="6"/>
              <line x1="8" y1="2" x2="8" y2="6"/>
              <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
          </div>
          <div>
            <h2 class="text-lg font-semibold text-white">Informations de date</h2>
            <p class="text-sm text-blue-100">Dates et période</p>
          </div>
        </div>
      </div>
      <div class="p-6 space-y-4">
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Date réelle (enregistrement)</div>
            <div class="mt-1 text-sm font-medium text-gray-900">{{ $collecte->created_at?->format('d/m/Y H:i') ?? '-' }}</div>
            <div class="text-xs text-gray-500 mt-0.5">{{ $collecte->created_at?->diffForHumans() ?? '-' }}</div>
          </div>
        </div>
        
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: {{ $agentHex }}20;">
            <svg class="w-4 h-4" style="color: {{ $agentHex }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Jour du calendrier</div>
            <div class="mt-1">
              <span class="text-sm font-medium text-gray-900">{{ $forDate ? $forDate->format('d/m/Y') : '-' }}</span>
              @if($forDate && $sameDay === false)
                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                  <svg class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                  </svg>
                  Date différente
                </span>
              @elseif($sameDay === true)
                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                  <svg class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                  </svg>
                  Même jour
                </span>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Collection Details Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="px-6 py-4" style="background: linear-gradient(135deg, {{ $agentHex }} 0%, {{ $agentHex }}dd 100%);">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
            <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="9"/>
              <path d="M10.5 9.5c.6-.4 1.3-.6 1.99-.6 1.66 0 3 1.34 3 3s-1.34 3-3 3c-.69 0-1.39-.22-1.99-.6"/>
              <path d="M12 8v1.5M12 14.5V16"/>
            </svg>
          </div>
          <div>
            <h2 class="text-lg font-semibold text-white">Détails de collecte</h2>
            <p class="text-sm text-white/80">Montant et agent</p>
          </div>
        </div>
      </div>
      <div class="p-6 space-y-4">
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-white" style="background: linear-gradient(135deg, var(--mb-secondary) 0%, #6ba83a 100%);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Montant collecté</div>
            <div class="mt-1 text-2xl font-bold" style="color: var(--mb-secondary);">
              {{ number_format($collecte->amount ?? ($collecte->tontine?->daily_amount ?? 0), 0, ',', ' ') }}
            </div>
            <div class="text-sm text-gray-600 mt-0.5">{{ $collecte->tontine?->settings['currency'] ?? 'XAF' }}</div>
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
              <div class="text-xs text-gray-500 uppercase tracking-wider">Agent collecteur</div>
              <div class="mt-1 flex items-center gap-2">
                <span class="inline-block w-3 h-3 rounded-full" style="background-color: {{ $agentHex }}"></span>
                <span class="text-sm font-medium text-gray-900">
                  {{ trim(($collecte->agent->first_name ?? '').' '.($collecte->agent->last_name ?? '')) ?: ($collecte->agent->email ?? '-') }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Notes Card -->
  @if($collecte->notes)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-lg bg-gray-200 flex items-center justify-center">
            <svg class="w-5 h-5 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14 2 14 8 20 8"/>
              <line x1="16" y1="13" x2="8" y2="13"/>
              <line x1="16" y1="17" x2="8" y2="17"/>
              <polyline points="10 9 9 9 8 9"/>
            </svg>
          </div>
          <div>
            <h2 class="text-lg font-semibold text-gray-900">Notes</h2>
            <p class="text-sm text-gray-600">Remarques de l'agent</p>
          </div>
        </div>
      </div>
      <div class="p-6">
        <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $collecte->notes }}</p>
      </div>
    </div>
  @endif

</div>
@endsection