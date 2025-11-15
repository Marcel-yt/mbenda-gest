@extends('layouts.app-agent')

@section('title', 'Nouveau client')
@section('page_title', 'Nouveau client')

@section('content')
<div class="max-w-3xl mx-auto">
  <div class="bg-white p-6 rounded shadow">
    <h2 class="text-lg font-medium mb-4">Créer un client</h2>

    @if($errors->any())
      <div class="mb-4 p-3 rounded bg-red-50 border-l-4 border-red-600 text-red-800">
        <ul class="list-disc pl-5">
          @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('clients.store') }}" method="post" class="space-y-4" autocomplete="off" enctype="multipart/form-data">
      @csrf

      @php $stat = old('statut', '1'); @endphp

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="text-xs text-gray-500">Prénom</label>
          <input name="first_name" value="{{ old('first_name') }}" required class="mb-input" />
        </div>
        <div>
          <label class="text-xs text-gray-500">Nom</label>
          <input name="last_name" value="{{ old('last_name') }}" required class="mb-input" />
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="text-xs text-gray-500">Indicatif</label>
          <input name="indicatif" value="{{ old('indicatif') }}" class="mb-input" />
        </div>
        <div>
          <label class="text-xs text-gray-500">Téléphone</label>
          <input type="tel" name="phone" value="{{ old('phone') }}" class="mb-input" />
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
        <div>
          <label class="text-xs text-gray-500">Statut</label>
          <div class="mt-1 flex items-center gap-3">
            <span id="statut-indicator" class="inline-block w-3 h-3 rounded-full" style="background: {{ $stat === '1' ? '#16A34A' : '#DC2626' }};"></span>
            <select id="statut-select" name="statut" class="mb-input text-sm">
              <option value="1" {{ $stat === '1' ? 'selected' : '' }}>Activé</option>
              <option value="0" {{ $stat === '0' ? 'selected' : '' }}>Désactivé</option>
            </select>
          </div>
        </div>

        <div>
          <label class="text-xs text-gray-500">Date d'inscription</label>
          <div class="mt-1 font-medium text-gray-900">{{ now()->format('d/m/Y') }}</div>
        </div>
      </div>

      <div>
        <label class="text-xs text-gray-500">Adresse</label>
        <input name="address" value="{{ old('address') }}" required class="mb-input" />
      </div>

      <div>
        <label class="text-xs text-gray-500">Notes</label>
        <textarea name="notes" class="mb-input" rows="3">{{ old('notes') }}</textarea>
      </div>

      {{-- photo de profil --}}
      <div>
        <label class="text-xs text-gray-500">Photo de profil</label>
        <input name="photo_profil" type="file" accept="image/*" class="mb-input text-sm" />
      </div>

      <div class="flex items-center gap-3 pt-4">
        <a href="{{ route('clients.index') }}" class="mb-link">Annuler</a>
        <button type="submit" class="mb-btn-primary px-4 py-2 rounded">Créer</button>
      </div>
    </form>
    <script>
      (function(){
        const sel = document.getElementById('statut-select');
        const ind = document.getElementById('statut-indicator');
        if (!sel || !ind) return;
        sel.addEventListener('change', function(){
          ind.style.background = this.value === '1' ? '#16A34A' : '#DC2626';
        });
      })();
    </script>
  </div>
</div>
@endsection