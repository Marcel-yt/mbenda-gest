@extends('layouts.app-admin')

@section('title', 'Modifier la tontine')
@section('page_title', 'Modifier la tontine')

@section('content')
@php /** @var \App\Models\Tontine $tontine */ @endphp
<div class="max-w-3xl mx-auto">
  <form action="{{ route('admin.tontines.update', $tontine->id) }}" method="POST" class="bg-white border rounded-xl p-6 shadow-sm space-y-4">
    @csrf
    @method('PUT')

    @if ($errors->any())
      <div class="text-sm text-red-600">
        <ul class="list-disc pl-5">
          @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
      </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
      <div>
        <label class="text-xs text-gray-600">Code</label>
        <input type="text" class="mt-1 w-full rounded border-gray-300" value="{{ $tontine->code }}" disabled>
      </div>

      <!-- Statut affiché en lecture seule -->
      <div>
        <label class="text-xs text-gray-600">Statut</label>
        <div class="mt-1">
          <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $tontine->status_badge_classes }}">
            {{ ucfirst($tontine->status) }}
          </span>
        </div>
      </div>

      <div>
        <label class="text-xs text-gray-600">Montant journalier</label>
        <input type="number" step="0.01" name="daily_amount" class="mt-1 w-full rounded border-gray-300"
               value="{{ old('daily_amount', $tontine->daily_amount) }}">
      </div>

      <!-- Commission (lecture seule) -->
      <div>
        <label class="text-xs text-gray-600">Commission (jours)</label>
        <input type="text" class="mt-1 w-full rounded border-gray-300 bg-gray-50" value="{{ $tontine->commission_days }}" disabled>
      </div>

      <!-- Durée (lecture seule) -->
      <div>
        <label class="text-xs text-gray-600">Durée (jours)</label>
        <input type="text" class="mt-1 w-full rounded border-gray-300 bg-gray-50" value="{{ $tontine->duration_days }}" disabled>
      </div>

      <div>
        <label class="text-xs text-gray-600">Date de début</label>
        <input type="date" name="start_date" class="mt-1 w-full rounded border-gray-300"
               value="{{ old('start_date', optional($tontine->start_date)->toDateString()) }}">
      </div>

      <!-- Fin prévue (lecture seule) -->
      <div>
        <label class="text-xs text-gray-600">Fin prévue</label>
        <input type="text" class="mt-1 w-full rounded border-gray-300 bg-gray-50"
               value="{{ optional($tontine->expected_end_date)->format('Y-m-d') }}" disabled>
      </div>
    </div>

    <div class="flex justify-end gap-2 pt-2">
      <a href="{{ route('admin.tontines.show', $tontine->id) }}" class="mb-link">Annuler</a>
      <button type="submit" class="mb-btn-primary px-4 py-2 rounded">Enregistrer</button>
    </div>
  </form>
</div>
@endsection