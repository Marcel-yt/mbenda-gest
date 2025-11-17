@extends('layouts.app-admin')

@section('title', 'Modifier la tontine')
@section('page_title', 'Modifier la tontine')

@section('content')
@php /** @var \App\Models\Tontine $tontine */ @endphp
<div class="max-w-4xl mx-auto space-y-6">
  {{-- Breadcrumb Navigation --}}
  <nav class="flex items-center gap-2 text-sm text-gray-600">
    <a href="{{ route('admin.tontines.index') }}" class="hover:text-indigo-600 transition-colors flex items-center gap-1">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
      </svg>
      Tontines
    </a>
    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
    </svg>
    <a href="{{ route('admin.tontines.show', $tontine->id) }}" class="hover:text-indigo-600 transition-colors">
      {{ $tontine->code }}
    </a>
    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
    </svg>
    <span class="text-gray-900 font-medium">Édition</span>
  </nav>

  {{-- Alerts Section --}}
  @if(session('success'))
    <div class="bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 rounded-lg p-4 flex items-start gap-3 shadow-sm">
      <div class="flex-shrink-0">
        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
      </div>
      <div class="flex-1">
        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
      </div>
    </div>
  @endif

  @if(session('info'))
    <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 border-l-4 border-yellow-500 rounded-lg p-4 flex items-start gap-3 shadow-sm">
      <div class="flex-shrink-0">
        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
      </div>
      <div class="flex-1">
        <p class="text-sm font-medium text-yellow-800">{{ session('info') }}</p>
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
          <h3 class="text-sm font-semibold text-red-800 mb-2">Erreurs de validation</h3>
          <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  @endif

  {{-- Edit Form --}}
  <form action="{{ route('admin.tontines.update', $tontine->id) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    @csrf
    @method('PUT')

    {{-- Form Header --}}
    <div style="background: var(--mb-primary);" class="px-6 py-5">
      <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
          </svg>
        </div>
        <div>
          <h2 class="text-xl font-bold text-white">Modifier la tontine</h2>
          <p class="text-sm text-white/80 mt-0.5">Mettez à jour les informations modifiables</p>
        </div>
      </div>
    </div>

    {{-- Form Body --}}
    <div class="p-6 space-y-6">
      {{-- Readonly Information Section --}}
      <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
        <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
          <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          Informations en lecture seule
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-medium text-gray-600 uppercase tracking-wider mb-2">Code</label>
            <input type="text" class="w-full px-4 py-2.5 bg-gray-100 border border-gray-300 rounded-lg text-sm text-gray-700 font-medium cursor-not-allowed" value="{{ $tontine->code }}" disabled>
          </div>

          <div>
            <label class="block text-xs font-medium text-gray-600 uppercase tracking-wider mb-2">Statut</label>
            @php
              $statusColors = [
                'draft' => 'bg-gray-100 text-gray-700 border-gray-300',
                'active' => 'border-gray-300',
                'completed' => 'border-gray-300',
                'paid' => 'border-gray-300',
              ];
              $statusTextColors = [
                'draft' => 'text-gray-700',
                'active' => 'text-blue-700',
                'completed' => 'text-green-700',
                'paid' => 'text-purple-700',
              ];
              $statusClass = $statusColors[$tontine->status] ?? 'bg-gray-100 text-gray-700 border-gray-300';
              $statusText = $statusTextColors[$tontine->status] ?? 'text-gray-700';
            @endphp
            <div class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg border {{ $statusClass }} font-semibold text-sm {{ $statusText }}">
              <span class="mb-ind-{{ $tontine->status === 'active' || $tontine->status === 'completed' || $tontine->status === 'paid' ? 'active' : 'inactive' }}"></span>
              {{ ucfirst($tontine->status) }}
            </div>
          </div>

          <div>
            <label class="block text-xs font-medium text-gray-600 uppercase tracking-wider mb-2">Commission (jours)</label>
            <input type="text" class="w-full px-4 py-2.5 bg-gray-100 border border-gray-300 rounded-lg text-sm text-gray-700 cursor-not-allowed" value="{{ $tontine->commission_days }}" disabled>
          </div>

          <div>
            <label class="block text-xs font-medium text-gray-600 uppercase tracking-wider mb-2">Durée (jours)</label>
            <input type="text" class="w-full px-4 py-2.5 bg-gray-100 border border-gray-300 rounded-lg text-sm text-gray-700 cursor-not-allowed" value="{{ $tontine->duration_days }}" disabled>
          </div>

          <div>
            <label class="block text-xs font-medium text-gray-600 uppercase tracking-wider mb-2">Fin prévue</label>
            <input type="text" class="w-full px-4 py-2.5 bg-gray-100 border border-gray-300 rounded-lg text-sm text-gray-700 cursor-not-allowed" value="{{ optional($tontine->expected_end_date)->format('d/m/Y') ?? '-' }}" disabled>
          </div>
        </div>
      </div>

      {{-- Editable Fields Section --}}
      <div>
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Champs modifiables</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="daily_amount" class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-2">
              Montant journalier <span class="text-red-500">*</span>
            </label>
            <input type="number" step="0.01" name="daily_amount" id="daily_amount"
                   class="mb-input"
                   value="{{ old('daily_amount', $tontine->daily_amount) }}"
                   placeholder="Ex: 5000.00">
            @error('daily_amount')
              <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label for="start_date" class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-2">
              Date de début <span class="text-red-500">*</span>
            </label>
            <input type="date" name="start_date" id="start_date"
                   class="mb-input"
                   value="{{ old('start_date', optional($tontine->start_date)->toDateString()) }}">
            @error('start_date')
              <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>
    </div>

    {{-- Form Actions --}}
    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-end gap-3">
      <a href="{{ route('admin.tontines.show', $tontine->id) }}" class="mb-link">Annuler</a>
      <button type="submit" class="mb-btn-primary px-4 py-2 rounded">Enregistrer</button>
    </div>
  </form>
</div>
@endsection