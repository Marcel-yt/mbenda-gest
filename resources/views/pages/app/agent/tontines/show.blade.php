@extends('layouts.app-agent')

@section('title', 'Détails tontine')
@section('page_title', 'Détails tontine')

@section('content')
@php /** @var \App\Models\Tontine $tontine */ @endphp

<div class="max-w-6xl mx-auto space-y-6">

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
    <span class="font-medium text-gray-900">{{ $tontine->code }}</span>
  </nav>

  <!-- Header Section -->
  <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-start justify-between flex-wrap">
      <div class="flex-1 min-w-0">
        <div class="flex items-center gap-3 mb-2">
          <div class="w-12 h-12 rounded-lg flex items-center justify-center text-white shadow-lg" style="background: linear-gradient(135deg, var(--mb-secondary) 0%, #6ba83a 100%);">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
              <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
              <circle cx="12" cy="14" r="2"/>
            </svg>
          </div>
          <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $tontine->code }}</h1>
            <p class="text-sm text-gray-600 mt-1">Détails complets de la tontine</p>
          </div>
        </div>
        <div class="flex items-center gap-3 mt-3">
          @php
            $statusColors = [
              'draft' => 'bg-gray-100 text-gray-700',
              'active' => 'bg-blue-100 text-blue-700',
              'completed' => 'bg-green-100 text-green-700',
              'paid' => 'bg-purple-100 text-purple-700',
            ];
            $statusIcons = [
              'draft' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
              'active' => 'M13 10V3L4 14h7v7l9-11h-7z',
              'completed' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
              'paid' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            ];
            $statusClass = $statusColors[$tontine->status] ?? 'bg-gray-100 text-gray-700';
            $statusIcon = $statusIcons[$tontine->status] ?? '';
          @endphp
          <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold {{ $statusClass }}">
            @if($statusIcon)
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusIcon }}"></path>
              </svg>
            @endif
            {{ ucfirst($tontine->status) }}
          </span>
          @if($tontine->status === 'cancelled')
            <div class="ml-4 text-sm text-gray-600">
              <div class="font-semibold text-sm text-red-700">Tontine annulée</div>
              <div class="mt-1 text-xs text-gray-700">Annulée par : {{ $tontine->cancelledBy?->name ?? '—' }}</div>
              @if(!empty($tontine->cancelled_reason))
                <div class="mt-1 text-xs text-gray-700">Motif : <span class="font-medium">{{ $tontine->cancelled_reason }}</span></div>
              @endif
              @if(!empty($tontine->cancelled_at))
                <div class="mt-1 text-xs text-gray-500">Le {{ $tontine->cancelled_at->format('d/m/Y H:i') }}</div>
              @endif
            </div>
          @endif
          @if(!in_array($tontine->status, ['paid','cancelled']))
            <button type="button" onclick="handlePayoutClick()" title="Procéder au paiement"
                    class="ml-3 inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg text-sm font-medium hover:from-green-600 hover:to-green-700 transition-all shadow-sm">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
              </svg>
              Payer
            </button>
            @endif
          @if(!in_array($tontine->status, ['paid','cancelled']))
          @endif
        </div>
      </div>
      <!-- Action Buttons -->
      <div class="flex items-center gap-2 ml-4">
        <a href="{{ route('agent.tontines.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all shadow-sm"
           title="Retour à la liste">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
          </svg>
          Retour
        </a>
        @if($tontine->status !== 'cancelled')
          <a href="{{ route('agent.collectes.index', ['tontine_id' => $tontine->id]) }}" 
             class="mb-btn-primary inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all shadow-sm"
             title="Collecter pour cette tontine">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="9"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 9.5c.6-.4 1.3-.6 1.99-.6 1.66 0 3 1.34 3 3s-1.34 3-3 3c-.69 0-1.39-.22-1.99-.6"/>
            <path d="M12 8v1.5M12 14.5V16"/>
          </svg>
          Collecter
          </a>
        @else
          <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium bg-gray-100 text-gray-500 cursor-not-allowed" title="Tontine annulée">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            Collecter
          </button>
        @endif
      </div>
    </div>
  </div>

  <!-- Information Cards Grid -->
  <div class="grid gap-6 md:grid-cols-2">
    <!-- Tontine Information Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="px-6 py-4" style="background: linear-gradient(135deg, var(--mb-secondary) 0%, #6ba83a 100%);">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
              <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
              <circle cx="12" cy="14" r="2"/>
            </svg>
          </div>
          <div>
            <h2 class="text-lg font-semibold text-white">Informations tontine</h2>
            <p class="text-sm text-green-100">Détails et configuration</p>
          </div>
        </div>
      </div>
      <div class="p-6 space-y-4">
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Code</div>
            <div class="mt-1 text-sm font-medium text-gray-900">{{ $tontine->code }}</div>
          </div>
        </div>
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Créée par (agent)</div>
            <div class="mt-1 text-sm font-medium text-gray-900">{{ $tontine->creator?->email ?? '-' }}</div>
          </div>
        </div>
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-white" style="background: linear-gradient(135deg, var(--mb-secondary) 0%, #6ba83a 100%);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Montant journalier</div>
            <div class="mt-1 text-lg font-bold" style="color: var(--mb-secondary);">{{ number_format($tontine->daily_amount, 0, ',', ' ') }} {{ $tontine->settings['currency'] ?? 'XAF' }}</div>
          </div>
        </div>
        <div class="border-t border-gray-100 pt-4">
          <div class="grid grid-cols-2 gap-4">
            <div class="flex items-start gap-2">
              <svg class="w-4 h-4 mt-0.5" style="color: var(--mb-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
              <div class="flex-1">
                <div class="text-xs text-gray-500">Date de début</div>
                <div class="mt-1 text-sm font-medium text-gray-900">{{ $tontine->start_date?->format('d/m/Y') ?? '-' }}</div>
              </div>
            </div>
            <div class="flex items-start gap-2">
              <svg class="w-4 h-4 mt-0.5" style="color: var(--mb-tertiary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
              <div class="flex-1">
                <div class="text-xs text-gray-500">Fin prévue</div>
                <div class="mt-1 text-sm font-medium text-gray-900">{{ $tontine->expected_end_date?->format('d/m/Y') ?? '-' }}</div>
              </div>
            </div>
          </div>
        </div>
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Durée</div>
            <div class="mt-1 text-sm font-medium text-gray-900">{{ $tontine->duration_days }} jours</div>
          </div>
        </div>
        <div class="border-t border-gray-100 pt-4">
          <div class="grid grid-cols-2 gap-4">
            <div class="rounded-lg p-3" style="background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);">
              <div class="text-xs font-medium" style="color: var(--mb-primary);">Total collecté</div>
              <div class="mt-1 text-lg font-bold" style="color: var(--mb-primary);">{{ number_format($tontine->collected_total ?? 0, 0, ',', ' ') }}</div>
              <div class="text-xs mt-0.5" style="color: var(--mb-primary);">{{ $tontine->settings['currency'] ?? 'XAF' }}</div>
            </div>
            <div class="rounded-lg p-3" style="background: linear-gradient(135deg, #ffedd5 0%, #fed7aa 100%);">
              <div class="text-xs font-medium" style="color: var(--mb-tertiary);">Commission</div>
              <div class="mt-1 text-lg font-bold" style="color: var(--mb-tertiary);">{{ $tontine->commission_days }}</div>
              <div class="text-xs mt-0.5" style="color: var(--mb-tertiary);">jours</div>
            </div>
          </div>
        </div>
        @php
          $currency = $tontine->settings['currency'] ?? 'XAF';
          $daily = (float)($tontine->daily_amount ?? 0);
          $totalCollecte = (float)($tontine->collected_total ?? 0);
          $montantAPayer = max($totalCollecte - $daily, 0);
          $commissionMontant = $daily;
        @endphp
        <div class="border-t border-gray-100 pt-4">
          <div class="grid grid-cols-2 gap-4">
            <div class="rounded-lg p-4 border" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-color: var(--mb-secondary);">
              <div class="flex items-center gap-2 mb-2">
                <svg class="w-4 h-4" style="color: var(--mb-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-xs font-semibold" style="color: var(--mb-secondary);">Montant net</div>
              </div>
              <div class="text-xl font-bold" style="color: var(--mb-secondary);">{{ number_format($montantAPayer, 0, ',', ' ') }}</div>
              <div class="text-xs mt-1" style="color: var(--mb-secondary);">{{ $currency }}</div>
              <div class="text-[10px] mt-1" style="color: var(--mb-secondary);">Total - 1 jour</div>
            </div>
            <div class="rounded-lg p-4 border" style="background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%); border-color: #6366f1;">
              <div class="flex items-center gap-2 mb-2">
                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-xs text-indigo-700 font-semibold">Commission</div>
              </div>
              <div class="text-xl font-bold text-indigo-700">{{ number_format($commissionMontant, 0, ',', ' ') }}</div>
              <div class="text-xs text-indigo-600 mt-1">{{ $currency }}</div>
              <div class="text-[10px] text-indigo-600 mt-1">Rémunération</div>
            </div>
          </div>
          </div>

        <!-- Cancel action (placed at bottom of tontine info card) -->
        <div class="border-t border-gray-100 px-6 py-4 mb-6">
          @if(!in_array($tontine->status, ['paid','cancelled']))
            <form id="cancel-tontine-form" action="{{ route('agent.tontines.cancel', $tontine->id) }}" method="POST" class="flex justify-end">
              @csrf
              <input type="hidden" name="cancel_reason" id="cancelReasonInput" value="">
              <button type="button" onclick="openCancelModal()" title="Annuler la tontine"
                      class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Annuler la tontine
              </button>
            </form>
          @endif
        </div>
          </div>
        </div>

      <!-- Client Information Card -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="px-6 py-4" style="background: linear-gradient(135deg, var(--mb-primary) 0%, #005f8d 100%);">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
          </div>
          <div>
            <h2 class="text-lg font-semibold text-white">Informations du client</h2>
            <p class="text-sm text-blue-100">Titulaire de la tontine</p>
          </div>
        </div>
      </div>
      <div class="p-6">
        <div class="flex items-start gap-4 mb-6">
          <div class="flex-shrink-0">
            @if(!empty($tontine->client?->photo_profil))
              <img src="{{ asset('storage/' . ltrim($tontine->client->photo_profil, '/')) }}" alt="Photo client" 
                   class="w-20 h-20 rounded-full object-cover border-4 shadow-md" style="border-color: #bae6fd;" />
            @else
              <div class="w-20 h-20 rounded-full text-white flex items-center justify-center font-bold text-xl border-4 shadow-md" style="background: linear-gradient(135deg, var(--mb-primary) 0%, #005f8d 100%); border-color: #bae6fd;">
                {{ strtoupper(substr($tontine->client?->first_name ?? '-',0,1) . substr($tontine->client?->last_name ?? '-',0,1)) }}
              </div>
            @endif
          </div>
          <div class="flex-1">
            <h3 class="text-xl font-bold text-gray-900">
              {{ trim(($tontine->client?->first_name ?? '') . ' ' . ($tontine->client?->last_name ?? '')) ?: '-' }}
            </h3>
            <div class="mt-2 flex items-center gap-2">
              <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $tontine->client?->statut ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $tontine->client?->statut ? 'bg-green-500' : 'bg-red-500' }}"></span>
                {{ $tontine->client?->statut ? 'Actif' : 'Désactivé' }}
              </span>
              <span class="text-xs text-gray-500">
                Inscrit le {{ optional($tontine->client?->created_at)->format('d/m/Y') ?? '-' }}
              </span>
            </div>
          </div>
        </div>
        <div class="space-y-4">
          <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
              <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-xs text-gray-500 uppercase tracking-wider">Téléphone</div>
              <div class="mt-1 text-sm font-medium text-gray-900">{{ $tontine->client?->phone ?? '-' }}</div>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
              <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-xs text-gray-500 uppercase tracking-wider">Indicatif</div>
              <div class="mt-1 text-sm font-medium text-gray-900">{{ $tontine->client?->indicatif ?? '-' }}</div>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
              <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-xs text-gray-500 uppercase tracking-wider">Adresse</div>
              <div class="mt-1 text-sm font-medium text-gray-900">{{ $tontine->client?->address ?? '-' }}</div>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
              <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-xs text-gray-500 uppercase tracking-wider">Créé par</div>
              <div class="mt-1 text-sm font-medium text-gray-900">
                {{ $tontine->creator ? trim(($tontine->creator->first_name ?? '') . ' ' . ($tontine->creator->last_name ?? '')) : ($tontine->creator?->email ?? '-') }}
              </div>
            </div>
          </div>
          @if($tontine->client?->notes)
            <div class="border-t border-gray-100 pt-4">
              <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                  <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                  </svg>
                </div>
                <div class="flex-1 min-w-0">
                  <div class="text-xs text-gray-500 uppercase tracking-wider">Remarques</div>
                  <div class="mt-1 text-sm text-gray-700 whitespace-pre-wrap">{{ $tontine->client->notes }}</div>
                </div>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

</div>
{{-- Modals de vérification avant paiement (centrés) --}}
<div id="payBlockedModal" class="fixed inset-0 z-50 hidden bg-black/40 p-4">
  <div class="bg-white w-full max-w-md rounded-xl shadow-lg border p-6">
    <h3 class="text-base font-semibold mb-2">Paiement impossible</h3>
    <p class="text-sm text-gray-600">
      impossible de payer cette tontine car aucune collecte n'a été effectuée
    </p>
    <div class="mt-6 flex justify-end">
      <button type="button" class="mb-btn-secondary px-4 py-2 rounded" onclick="closeModal('payBlockedModal')">Annuler</button>
    </div>
  </div>
</div>

<div id="payConfirmModal" class="fixed inset-0 z-50 hidden bg-black/40 p-4">
  <div class="bg-white w-full max-w-md rounded-xl shadow-lg border p-6">
    <h3 class="text-base font-semibold mb-2">Confirmer le paiement</h3>
    <p class="text-sm text-gray-600">
      cette tontine n'est pas terminée, voulez-vous quand même continuer le paiement?
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
    const createUrl = '{{ route('agent.payouts.create', $tontine->id) }}';

    window.handlePayoutClick = function () {
      if (status === 'draft') {
        document.getElementById('payBlockedModal').classList.remove('hidden');
      } else if (status === 'active') {
        document.getElementById('payConfirmModal').classList.remove('hidden');
      } else if (status === 'completed') {
        window.location.href = createUrl;
      } else {
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
{{-- Cancel confirmation modal --}}
<div id="cancelModal" class="fixed inset-0 z-50 hidden bg-black/40 p-4">
  <div class="bg-white w-full max-w-md rounded-xl shadow-lg border p-6">
    <h3 class="text-base font-semibold mb-2">Confirmer l'annulation</h3>
    <p class="text-sm text-gray-600">Êtes-vous sûr de vouloir annuler cette tontine ? Les collectes déjà enregistrées ne seront plus prises en compte dans les calculs.</p>
    <div class="mt-4">
      <label for="cancelReason" class="text-sm text-gray-700">Motif (optionnel)</label>
      <textarea id="cancelReason" rows="3" class="w-full mt-2 border border-gray-200 rounded p-2 text-sm" placeholder="Raison de l'annulation (facultatif)"></textarea>
    </div>
    <div class="mt-4 flex items-center gap-3">
      <input id="confirmCancelCheckbox" type="checkbox" class="h-4 w-4" onchange="onCancelCheckboxChange(this)">
      <label for="confirmCancelCheckbox" class="text-sm text-gray-700">Je confirme vouloir annuler cette tontine</label>
    </div>
    <div class="mt-6 flex justify-end gap-2">
      <button type="button" class="mb-btn-secondary px-4 py-2 rounded" onclick="closeCancelModal()">Annuler</button>
      <button id="confirmCancelBtn" type="button" class="px-4 py-2 rounded bg-red-600 hover:bg-red-700 text-white disabled:opacity-50" onclick="submitCancel()" disabled>Annuler la tontine</button>
    </div>
  </div>
</div>

<script>
function openCancelModal(){ document.getElementById('cancelModal').classList.remove('hidden'); }
function closeCancelModal(){ const modal = document.getElementById('cancelModal'); if (modal) modal.classList.add('hidden'); const cb = document.getElementById('confirmCancelCheckbox'); if (cb) cb.checked = false; onCancelCheckboxChange(cb); const reason = document.getElementById('cancelReason'); if (reason) reason.value = ''; const hidden = document.getElementById('cancelReasonInput'); if (hidden) hidden.value = ''; }
function onCancelCheckboxChange(cb){
  const btn = document.getElementById('confirmCancelBtn');
  if (!btn) return;
  btn.disabled = !cb.checked;
}
function submitCancel(){
  const form = document.getElementById('cancel-tontine-form');
  if (!form) return;
  // copy visible reason into hidden input so it's submitted
  const reasonVisible = document.getElementById('cancelReason');
  const reasonInput = document.getElementById('cancelReasonInput');
  if (reasonVisible && reasonInput) reasonInput.value = reasonVisible.value || '';
  form.submit();
}
</script>
@endsection