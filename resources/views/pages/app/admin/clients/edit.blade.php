@extends('layouts.app-admin')

@section('title', 'Éditer client')
@section('page_title', 'Éditer client')

@section('content')
@php /** @var \App\Models\Client $client */ @endphp

<div class="max-w-3xl mx-auto">
  <div class="bg-white p-6 rounded shadow">
    <h2 class="text-lg font-medium mb-4">Modifier le client</h2>

    <form action="{{ route('admin.clients.update', $client) }}" method="post" class="space-y-4" enctype="multipart/form-data">
      @csrf
      @method('patch')

      @php $stat = old('statut', isset($client->statut) ? ($client->statut ? '1' : '0') : '1'); @endphp

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="text-xs text-gray-500">Prénom</label>
          <input name="first_name" value="{{ old('first_name', $client->first_name) }}" required class="mt-1 block w-full rounded border-gray-300" />
        </div>
        <div>
          <label class="text-xs text-gray-500">Nom</label>
          <input name="last_name" value="{{ old('last_name', $client->last_name) }}" class="mt-1 block w-full rounded border-gray-300" />
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="text-xs text-gray-500">Indicatif</label>
          <input name="indicatif" value="{{ old('indicatif', $client->indicatif) }}" class="mt-1 block w-full rounded border-gray-300" />
        </div>
        <div>
          <label class="text-xs text-gray-500">Téléphone</label>
          <input name="phone" value="{{ old('phone', $client->phone) }}" class="mt-1 block w-full rounded border-gray-300" />
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
        <div>
          <label class="text-xs text-gray-500">Statut</label>
          <div class="mt-1 flex items-center gap-3">
            <span id="stat-ind" class="inline-block w-3 h-3 rounded-full" style="background: {{ $stat === '1' ? '#16A34A' : '#DC2626' }};"></span>
            <select id="stat-select" name="statut" class="block w-full rounded border-gray-300 text-sm">
              <option value="1" {{ $stat === '1' ? 'selected' : '' }}>Activé</option>
              <option value="0" {{ $stat === '0' ? 'selected' : '' }}>Désactivé</option>
            </select>
          </div>
        </div>

        <div>
          <label class="text-xs text-gray-500">Date de création</label>
          <div class="mt-1 font-medium text-gray-900">{{ $client->created_at?->format('d/m/Y H:i') ?? '—' }}</div>
        </div>
      </div>

      <div>
        <label class="text-xs text-gray-500">Adresse</label>
        <input name="address" value="{{ old('address', $client->address) }}" class="mt-1 block w-full rounded border-gray-300" />
      </div>

      <div>
        <label class="text-xs text-gray-500">Notes</label>
        <textarea name="notes" class="mt-1 block w-full rounded border-gray-300" rows="3">{{ old('notes', $client->notes) }}</textarea>
      </div>

      <div>
        <label class="text-xs text-gray-500">Photo de profil</label>
        @if($client->photo_profil)
          <div class="mt-2 mb-2">
            <img src="{{ asset('storage/'.$client->photo_profil) }}" alt="photo" class="w-24 h-24 object-cover rounded-full border" />
          </div>
        @endif
        <input name="photo_profil" type="file" accept="image/*" class="mt-1 block w-full rounded border-gray-300 text-sm" />
      </div>

      <div class="flex items-center gap-3 pt-4">
        <a href="{{ route('admin.clients.show', $client) }}" class="mb-link">Annuler</a>
        <button type="submit" class="mb-btn-primary px-4 py-2 rounded">Enregistrer</button>
      </div>
    </form>

    <script>
      (function(){
        const sel = document.getElementById('stat-select');
        const ind = document.getElementById('stat-ind');
        if (!sel || !ind) return;
        sel.addEventListener('change', function(){ ind.style.background = this.value === '1' ? '#16A34A' : '#DC2626'; });
      })();
    </script>
  </div>
</div>
@endsection