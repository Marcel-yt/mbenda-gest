@extends('layouts.app-admin')

@section('title', 'Dashboard Admin')
@section('page_title', 'Tableau de bord Admin')

@section('content')
<div class="space-y-8">

  {{-- KPIs entités avec icônes et animations --}}
  <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <div class="bg-gradient-to-br from-blue-50 to-white border border-blue-100 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <div class="flex items-center gap-2 text-xs text-blue-600 font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            Clients
          </div>
          <div class="mt-2 text-3xl font-bold text-gray-900" id="kpi-clients">{{ $totalClients ?? 0 }}</div>
        </div>
        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
          <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
      </div>
    </div>
    <div class="bg-gradient-to-br from-green-50 to-white border border-green-100 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <div class="flex items-center gap-2 text-xs text-green-600 font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Tontines
          </div>
          <div class="mt-2 text-3xl font-bold text-gray-900" id="kpi-tontines">{{ $totalTontines ?? 0 }}</div>
        </div>
        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
          <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        </div>
      </div>
    </div>
    <div class="bg-gradient-to-br from-purple-50 to-white border border-purple-100 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <div class="flex items-center gap-2 text-xs text-purple-600 font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            Collectes
          </div>
          <div class="mt-2 text-3xl font-bold text-gray-900" id="kpi-collectes">{{ $totalCollectes ?? 0 }}</div>
        </div>
        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
          <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
        </div>
      </div>
    </div>
    <div class="bg-gradient-to-br from-orange-50 to-white border border-orange-100 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow">
      <div class="flex items-center justify-between">
        <div class="flex-1">
          <div class="flex items-center gap-2 text-xs text-orange-600 font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Payouts
          </div>
          <div class="mt-2 text-3xl font-bold text-gray-900" id="kpi-payouts">{{ $totalPayouts ?? 0 }}</div>
        </div>
        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
          <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
      </div>
    </div>
  </div>

  {{-- KPIs montants avec couleurs et icônes --}}
  <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow">
      <div class="flex items-center gap-2 text-xs text-gray-500 font-medium mb-1">
        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
        Montants entrés (Total)
      </div>
      <div class="mt-2 text-2xl font-bold text-green-600" id="kpi-in-total">{{ number_format($amountInTotal ?? 0,2) }} <span class="text-sm text-gray-500">XAF</span></div>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow">
      <div class="flex items-center gap-2 text-xs text-gray-500 font-medium mb-1">
        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
        Montants sortis (Total)
      </div>
      <div class="mt-2 text-2xl font-bold text-red-600" id="kpi-out-total">{{ number_format($amountOutTotal ?? 0,2) }} <span class="text-sm text-gray-500">XAF</span></div>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow">
      <div class="flex items-center gap-2 text-xs text-gray-500 font-medium mb-1">
        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
        Solde net (Total)
      </div>
      <div class="mt-2 text-2xl font-bold text-blue-600" id="kpi-net-total">{{ number_format($netTotal ?? 0,2) }} <span class="text-sm text-gray-500">XAF</span></div>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow">
      <div class="flex items-center gap-2 text-xs text-gray-500 font-medium mb-1">
        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        Commissions (Total)
      </div>
      <div class="mt-2 text-2xl font-bold text-purple-600" id="kpi-comm-total">{{ number_format($commissionTotal ?? 0,2) }} <span class="text-sm text-gray-500">XAF</span></div>
    </div>
  </div>

  {{-- Nouveaux indicateurs --}}
  <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <div class="bg-gradient-to-br from-teal-50 to-white border border-teal-100 rounded-xl p-5 shadow-sm">
      <div class="flex items-center gap-2 text-xs text-teal-600 font-medium mb-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        Taux de complétion
      </div>
      <div class="mt-2 text-2xl font-bold text-gray-900" id="kpi-completion-rate">0%</div>
      <div class="mt-1 text-xs text-gray-500" id="kpi-completion-detail">0/0 tontines terminées</div>
    </div>
    <div class="bg-gradient-to-br from-amber-50 to-white border border-amber-100 rounded-xl p-5 shadow-sm">
      <div class="flex items-center gap-2 text-xs text-amber-600 font-medium mb-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
        Moyenne par collecte
      </div>
      <div class="mt-2 text-2xl font-bold text-gray-900" id="kpi-avg-collecte">0 <span class="text-sm text-gray-500">XAF</span></div>
    </div>
    <div class="bg-gradient-to-br from-pink-50 to-white border border-pink-100 rounded-xl p-5 shadow-sm">
      <div class="flex items-center gap-2 text-xs text-pink-600 font-medium mb-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
        Nouveaux clients (mois)
      </div>
      <div class="mt-2 text-2xl font-bold text-gray-900" id="kpi-new-clients">0</div>
    </div>
    <div class="bg-gradient-to-br from-rose-50 to-white border border-rose-100 rounded-xl p-5 shadow-sm">
      <div class="flex items-center gap-2 text-xs text-rose-600 font-medium mb-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        Tontines à finaliser
      </div>
      <div class="mt-2 text-2xl font-bold text-gray-900" id="kpi-to-finalize">0</div>
    </div>
  </div>


  {{-- Filtre période amélioré --}}
  <div class="bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-200 rounded-xl p-6 shadow-sm">
    <div class="flex items-center gap-2 mb-4">
      <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
      <h3 class="text-sm font-semibold text-indigo-900">Filtrer par période</h3>
    </div>
    <div class="flex items-end gap-4 flex-wrap">
      <div>
        <label class="text-xs text-indigo-700 font-medium mb-1 block">Du</label>
        <input type="date" id="f-date-from" class="px-3 py-2 border border-indigo-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
      </div>
      <div>
        <label class="text-xs text-indigo-700 font-medium mb-1 block">Au</label>
        <input type="date" id="f-date-to" class="px-3 py-2 border border-indigo-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
      </div>
      <div class="flex gap-2">
        <button id="f-apply" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
          <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
          Appliquer
        </button>
        <button id="f-reset" class="bg-white hover:bg-gray-50 text-indigo-600 border border-indigo-200 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
          <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
          Réinitialiser
        </button>
      </div>
    </div>
    <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
      <div class="bg-white border border-green-200 rounded-lg p-4 shadow-sm">
        <div class="flex items-center gap-2 text-xs text-green-600 font-medium mb-1">
          <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"></path></svg>
          Entrées
        </div>
        <div class="mt-1 text-xl font-bold text-green-600" id="kf-in">{{ number_format($filterTotals['in'] ?? 0,2) }} <span class="text-xs text-gray-500">XAF</span></div>
      </div>
      <div class="bg-white border border-red-200 rounded-lg p-4 shadow-sm">
        <div class="flex items-center gap-2 text-xs text-red-600 font-medium mb-1">
          <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"></path></svg>
          Sorties
        </div>
        <div class="mt-1 text-xl font-bold text-red-600" id="kf-out">{{ number_format($filterTotals['out'] ?? 0,2) }} <span class="text-xs text-gray-500">XAF</span></div>
      </div>
      <div class="bg-white border border-purple-200 rounded-lg p-4 shadow-sm">
        <div class="flex items-center gap-2 text-xs text-purple-600 font-medium mb-1">
          <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path></svg>
          Commissions
        </div>
        <div class="mt-1 text-xl font-bold text-purple-600" id="kf-comm">{{ number_format($filterTotals['comm'] ?? 0,2) }} <span class="text-xs text-gray-500">XAF</span></div>
      </div>
      <div class="bg-white border border-blue-200 rounded-lg p-4 shadow-sm">
        <div class="flex items-center gap-2 text-xs text-blue-600 font-medium mb-1">
          <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11 4a1 1 0 10-2 0v4a1 1 0 102 0V7zm-3 1a1 1 0 10-2 0v3a1 1 0 102 0V8zM8 9a1 1 0 00-2 0v2a1 1 0 102 0V9z" clip-rule="evenodd"></path></svg>
          Net
        </div>
        <div class="mt-1 text-xl font-bold text-blue-600" id="kf-net">{{ number_format($filterTotals['net'] ?? 0,2) }} <span class="text-xs text-gray-500">XAF</span></div>
      </div>
    </div>
  </div>

  {{-- Graphiques principaux --}}
  <div class="grid gap-6 lg:grid-cols-3">
    <div class="bg-white border rounded-xl p-6 lg:col-span-2 shadow-sm hover:shadow-md transition-shadow">
      <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
          <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
          <h2 class="text-base font-bold text-gray-800">Entrées vs Sorties — 30 derniers jours</h2>
        </div>
      </div>
      <div class="h-64"><canvas id="chartDailyIO"></canvas></div>
    </div>
    <div class="bg-white border rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
      <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
          <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
          <h2 class="text-base font-bold text-gray-800">Statuts tontines</h2>
        </div>
      </div>
      <div class="h-64"><canvas id="chartStatus"></canvas></div>
    </div>
  </div>

  {{-- Graphiques secondaires --}}
  <div class="grid gap-6 lg:grid-cols-2">
    <div class="bg-white border rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
      <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
          <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
          <h2 class="text-base font-bold text-gray-800">Commissions — 30 derniers jours</h2>
        </div>
      </div>
      <div class="h-64"><canvas id="chartCommissions"></canvas></div>
    </div>
    <div class="bg-white border rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
      <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
          <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
          <h2 class="text-base font-bold text-gray-800">Évolution clients — 12 mois</h2>
        </div>
      </div>
      <div class="h-64"><canvas id="chartClientsGrowth"></canvas></div>
    </div>
  </div>

  {{-- Performances agents --}}
  <div class="grid gap-6 md:grid-cols-2">
    <div class="bg-white border rounded-xl p-6">
      <h2 class="text-sm font-semibold text-gray-800 mb-4">Top Agents — Aujourd’hui</h2>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wide">
            <tr>
              <th class="px-4 py-2 text-left">Agent</th>
              <th class="px-4 py-2 text-left">Collectes</th>
              <th class="px-4 py-2 text-left">Montant</th>
            </tr>
          </thead>
          <tbody id="tbl-agents-today" class="divide-y divide-gray-100">
            @foreach(($agentsToday ?? []) as $a)
              <tr class="hover:bg-gray-50">
                <td class="px-4 py-2">{{ $a['label'] }}</td>
                <td class="px-4 py-2 text-gray-700">{{ $a['ops'] }}</td>
                <td class="px-4 py-2 font-medium">{{ number_format($a['amount'],2) }} XAF</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    <div class="bg-white border rounded-xl p-6">
      <h2 class="text-sm font-semibold text-gray-800 mb-4">Top Agents — Mois</h2>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wide">
            <tr>
              <th class="px-4 py-2 text-left">Agent</th>
              <th class="px-4 py-2 text-left">Collectes</th>
              <th class="px-4 py-2 text-left">Montant</th>
            </tr>
          </thead>
          <tbody id="tbl-agents-month" class="divide-y divide-gray-100">
            @foreach(($agentsMonth ?? []) as $a)
              <tr class="hover:bg-gray-50">
                <td class="px-4 py-2">{{ $a['label'] }}</td>
                <td class="px-4 py-2 text-gray-700">{{ $a['ops'] }}</td>
                <td class="px-4 py-2 font-medium">{{ number_format($a['amount'],2) }} XAF</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@php
  // Evite l'opérateur ?? dans @json (source du ParseError)
  $statusCounts = isset($statusCounts) && is_array($statusCounts)
    ? $statusCounts
    : ['draft'=>0,'active'=>0,'completed'=>0,'paid'=>0,'archived'=>0,'cancelled'=>0];
@endphp
<script>
(function(){
  const statsUrl = "{{ route('admin.dashboard.stats') }}";

  const dailyLabels = @json($dailyLabels ?? []);
  const dailyIn     = @json($dailyIn ?? []);
  const dailyOut    = @json($dailyOut ?? []);
  const monthlyLabels = @json($monthlyLabels ?? []);
  const monthlyIn     = @json($monthlyIn ?? []);
  const monthlyOut    = @json($monthlyOut ?? []);

  const commLabels  = @json($commLabels ?? []);
  const commValues  = @json($commValues ?? []);
  const statusCounts = @json($statusCounts);

  const cd = document.getElementById('chartDailyIO');
  const cm = document.getElementById('chartMonthlyIO');
  const cc = document.getElementById('chartCommissions')
  const cs = document.getElementById('chartStatus');
  const cg = document.getElementById('chartClientsGrowth');

  let chartDaily = null, chartMonthly = null, chartCom = null, chartStatus = null, chartGrowth = null;

  if (cd){
    chartDaily = new Chart(cd, {
      type: 'line',
      data: {
        labels: dailyLabels,
        datasets: [
          { label:'Entrées', data: dailyIn, borderColor:'#16a34a', backgroundColor:'rgba(22,163,74,.15)', tension:.35, fill:true, pointRadius:2 },
          { label:'Sorties', data: dailyOut, borderColor:'#dc2626', backgroundColor:'rgba(220,38,38,.12)', tension:.35, fill:true, pointRadius:2 }
        ]
      },
      options: { responsive:true, maintainAspectRatio:false, interaction:{mode:'index',intersect:false}, plugins:{ legend:{position:'bottom'} } }
    });
  }

  if (cm){
    chartMonthly = new Chart(cm, {
      type: 'bar',
      data: {
        labels: monthlyLabels,
        datasets: [
          { label:'Entrées', data: monthlyIn, backgroundColor:'rgba(22,163,74,.7)', borderRadius:6, maxBarThickness:38 },
          { label:'Sorties', data: monthlyOut, backgroundColor:'rgba(220,38,38,.7)', borderRadius:6, maxBarThickness:38 }
        ]
      },
      options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{position:'bottom'} }, scales:{ x:{ stacked:false }, y:{ beginAtZero:true } } }
    });
  }

  if (cc){
    chartCom = new Chart(cc, {
      type: 'line',
      data: {
        labels: commLabels,
        datasets: [
          { label:'Commissions', data: commValues, borderColor:'#7c3aed', backgroundColor:'rgba(124,58,237,.12)', tension:.35, fill:true, pointRadius:2 }
        ]
      },
      options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{position:'bottom'} }, scales:{ y:{ beginAtZero:true } } }
    });
  }

  if (cs){
    const order = ['draft','active','completed','paid','archived','cancelled'];
    const data = order.map(k => (statusCounts?.[k] || 0));
    chartStatus = new Chart(cs, {
      type: 'doughnut',
      data: {
        labels: ['Brouillon','Actif','Terminé','Payé','Archivé','Annulé'],
        datasets: [{
          data,
          backgroundColor: ['#94a3b8','#22c55e','#3b82f6','#14b8a6','#a78bfa','#f97316'],
          borderColor:'#fff', borderWidth:2, hoverOffset:6
        }]
      },
      options: { cutout:'55%', responsive:true, plugins:{ legend:{ position:'bottom' } } }
    });
  }

  // Graphique d'évolution des clients (12 derniers mois)
  if (cg){
    const now = new Date();
    const growthLabels = [];
    for (let i = 11; i >= 0; i--) {
      const d = new Date(now.getFullYear(), now.getMonth() - i, 1);
      growthLabels.push(d.toLocaleDateString('fr-FR', {month: 'short', year: '2-digit'}));
    }
    chartGrowth = new Chart(cg, {
      type: 'line',
      data: {
        labels: growthLabels,
        datasets: [{
          label: 'Nouveaux clients',
          data: Array(12).fill(0),
          borderColor: '#3b82f6',
          backgroundColor: 'rgba(59, 130, 246, 0.1)',
          tension: 0.4,
          fill: true,
          pointRadius: 3,
          pointHoverRadius: 5,
          pointBackgroundColor: '#3b82f6',
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: { legend: { position: 'bottom' } },
        scales: {
          y: {
            beginAtZero: true,
            ticks: { precision: 0 }
          }
        }
      }
    });
  }

  function nf(x){ try{ return new Intl.NumberFormat('fr-FR',{minimumFractionDigits:2,maximumFractionDigits:2}).format(x||0); }catch(e){ return Number(x||0).toFixed(2); } }

  function renderAgents(tbodyId, rows){
    const tb = document.getElementById(tbodyId);
    if (!tb) return;
    const isToday = tbodyId === 'tbl-agents-today';
    const hoverClass = isToday ? 'hover:bg-amber-50' : 'hover:bg-green-50';
    
    if (!rows || rows.length === 0) {
      tb.innerHTML = `
        <tr>
          <td colspan="3" class="px-4 py-8 text-center text-gray-400">
            <svg class="w-10 h-10 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
            <p class="text-sm">${isToday ? 'Aucune collecte aujourd\'hui' : 'Aucune collecte ce mois'}</p>
          </td>
        </tr>
      `;
      return;
    }
    
    tb.innerHTML = rows.map((r, i) => {
      const badge = i < 3 ? `<span class="w-6 h-6 rounded-full bg-gradient-to-br ${isToday ? 'from-amber-400 to-orange-500' : 'from-green-400 to-emerald-500'} text-white text-xs font-bold flex items-center justify-center">${i + 1}</span>` : '';
      return `
        <tr class="${hoverClass} transition-colors">
          <td class="px-4 py-3 flex items-center gap-2">
            ${badge}
            <span class="font-medium">${r.label}</span>
          </td>
          <td class="px-4 py-3">
            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-semibold">${r.ops}</span>
          </td>
          <td class="px-4 py-3 font-bold text-green-600">${nf(r.amount)} XAF</td>
        </tr>
      `;
    }).join('');
  }

  async function refresh(extraParams = {}){
    try{
      const url = new URL(statsUrl, window.location.origin);
      Object.entries(extraParams).forEach(([k,v]) => { if (v) url.searchParams.set(k, v); });
      const res = await fetch(url.toString(), { headers: { 'Accept':'application/json' } });
      if(!res.ok) return;
      const d = await res.json();

      // KPIs entités
      const setText = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };
      setText('kpi-clients', d.totalClients ?? 0);
      setText('kpi-tontines', d.totalTontines ?? 0);
      setText('kpi-collectes', d.totalCollectes ?? 0);
      setText('kpi-payouts', d.totalPayouts ?? 0);

      setText('kpi-in-total', nf(d.amountInTotal)+' XAF');
      setText('kpi-out-total', nf(d.amountOutTotal)+' XAF');
      setText('kpi-net-total', nf(d.netTotal)+' XAF');
      setText('kpi-comm-total', nf(d.commissionTotal)+' XAF');

      // Nouveaux KPIs
      if (d.totalTontines > 0) {
        const completed = (d.statusCounts?.completed || 0) + (d.statusCounts?.paid || 0);
        const rate = ((completed / d.totalTontines) * 100).toFixed(1);
        setText('kpi-completion-rate', rate + '%');
        setText('kpi-completion-detail', completed + '/' + d.totalTontines + ' tontines terminées');
      }
      if (d.totalCollectes > 0 && d.amountInTotal > 0) {
        const avg = (d.amountInTotal / d.totalCollectes).toFixed(0);
        document.getElementById('kpi-avg-collecte').innerHTML = avg + ' <span class="text-sm text-gray-500">XAF</span>';
      }
      if (d.newClientsMonth !== undefined) {
        setText('kpi-new-clients', d.newClientsMonth);
      }
      if (d.tontinesToFinalize !== undefined) {
        setText('kpi-to-finalize', d.tontinesToFinalize);
      }

      // Périodes
      if (d.periods){
        setText('pi-in', nf(d.periods.today?.in));
        setText('pi-out', nf(d.periods.today?.out));
        setText('pi-comm', nf(d.periods.today?.comm));
        setText('pi-net', nf(d.periods.today?.net));
        setText('pw-in', nf(d.periods.week?.in));
        setText('pw-out', nf(d.periods.week?.out));
        setText('pw-comm', nf(d.periods.week?.comm));
        setText('pw-net', nf(d.periods.week?.net));
        setText('pm-in', nf(d.periods.month?.in));
        setText('pm-out', nf(d.periods.month?.out));
        setText('pm-comm', nf(d.periods.month?.comm));
        setText('pm-net', nf(d.periods.month?.net));
        setText('py-in', nf(d.periods.year?.in));
        setText('py-out', nf(d.periods.year?.out));
        setText('py-comm', nf(d.periods.year?.comm));
        setText('py-net', nf(d.periods.year?.net));
      }

      // Charts IO
      if (chartDaily && d.dailyLabels && d.dailyIn && d.dailyOut){
        chartDaily.data.labels = d.dailyLabels;
        chartDaily.data.datasets[0].data = d.dailyIn;
        chartDaily.data.datasets[1].data = d.dailyOut;
        chartDaily.update('active');
      }
      if (chartMonthly && d.monthlyLabels && d.monthlyIn && d.monthlyOut){
        chartMonthly.data.labels = d.monthlyLabels;
        chartMonthly.data.datasets[0].data = d.monthlyIn;
        chartMonthly.data.datasets[1].data = d.monthlyOut;
        chartMonthly.update('active');
      }

      // Chart Commissions
      if (chartCom && d.commLabels && d.commValues){
        chartCom.data.labels = d.commLabels;
        chartCom.data.datasets[0].data = d.commValues;
        chartCom.update('active');
      }

      // Donut statuts
      if (chartStatus && d.statusCounts){
        const order = ['draft','active','completed','paid','archived','cancelled'];
        chartStatus.data.datasets[0].data = order.map(k => d.statusCounts?.[k] || 0);
        chartStatus.update('active');
      }

      // Chart croissance clients
      if (chartGrowth && d.clientsGrowth && d.clientsGrowth.length > 0){
        chartGrowth.data.datasets[0].data = d.clientsGrowth;
        chartGrowth.update('active');
      }

      // Agents
      renderAgents('tbl-agents-today', d.agentsToday || []);
      renderAgents('tbl-agents-month', d.agentsMonth || []);

      // Mises à jour filtre période (si présent)
      if (d.filterTotals){
        setText('kf-in',   nf(d.filterTotals.in)+' XAF');
        setText('kf-out',  nf(d.filterTotals.out)+' XAF');
        setText('kf-comm', nf(d.filterTotals.comm)+' XAF');
        setText('kf-net',  nf(d.filterTotals.net)+' XAF');
      }
    }catch(e){}
  }

  // Filtre période
  const fApply = document.getElementById('f-apply');
  const fReset = document.getElementById('f-reset');
  const fFrom  = document.getElementById('f-date-from');
  const fTo    = document.getElementById('f-date-to');

  if (fApply) {
    fApply.addEventListener('click', function(ev) {
      ev.preventDefault();
      ev.stopPropagation();
      const dateFrom = fFrom?.value || '';
      const dateTo = fTo?.value || '';
      refresh({ date_from: dateFrom, date_to: dateTo });
    });
  }
  
  if (fReset) {
    fReset.addEventListener('click', function(ev) {
      ev.preventDefault();
      ev.stopPropagation();
      if (fFrom) fFrom.value = '';
      if (fTo) fTo.value = '';
      refresh({});
    });
  }

  refresh();
  setInterval(()=>refresh({}), 15000);
})();
</script>
@endsection
