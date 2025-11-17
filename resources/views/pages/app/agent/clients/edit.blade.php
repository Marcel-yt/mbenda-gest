@extends('layouts.app-agent')

@section('title', 'Éditer client')
@section('page_title', 'Éditer client')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
  {{-- Breadcrumb Navigation --}}
  <nav class="flex items-center gap-2 text-sm text-gray-600">
    <a href="{{ route('clients.index') }}" class="hover:text-blue-600 transition-colors flex items-center gap-1" style="color: var(--mb-primary);">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
      </svg>
      Clients
    </a>
    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
    </svg>
    <a href="{{ route('clients.show', $client) }}" class="hover:text-blue-600 transition-colors" style="color: var(--mb-primary);">
      {{ $client->first_name }} {{ $client->last_name }}
    </a>
    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
    </svg>
    <span class="text-gray-900 font-medium">Modifier</span>
  </nav>

  {{-- Alerts Section --}}
  @if($errors->any())
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
            @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
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
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
        </svg>
      </div>
      <div class="flex-1">
        <h1 class="text-2xl font-bold text-gray-900">Modifier le client</h1>
        <p class="text-sm text-gray-600 mt-1">Mettez à jour les informations du client</p>
      </div>
    </div>
  </div>

  {{-- Form --}}
  <form action="{{ route('clients.update', $client) }}" method="post" autocomplete="off" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('patch')

    @php $stat = old('statut', isset($client->statut) ? ($client->statut ? '1' : '0') : '1'); @endphp

    {{-- Readonly Information --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">Informations en lecture seule</h2>
      </div>
      <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
          <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
              <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-xs text-gray-500 uppercase tracking-wider">ID Client</div>
              <div class="mt-1 text-sm font-semibold text-gray-900">#{{ $client->id }}</div>
            </div>
          </div>

          <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
              <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-xs text-gray-500 uppercase tracking-wider">Date de création</div>
              <div class="mt-1 text-sm font-semibold text-gray-900">{{ $client->created_at?->format('d/m/Y H:i') ?? '—' }}</div>
            </div>
          </div>

          <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
              <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-xs text-gray-500 uppercase tracking-wider mb-2">Statut</div>
              <div class="flex items-center gap-2">
                <span id="statut-indicator" class="inline-block w-3 h-3 rounded-full" style="background: {{ $stat === '1' ? '#16A34A' : '#DC2626' }};"></span>
                <select id="statut-select" name="statut" class="mb-input">
                  <option value="1" {{ $stat === '1' ? 'selected' : '' }}>Activé</option>
                  <option value="0" {{ $stat === '0' ? 'selected' : '' }}>Désactivé</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Editable Information --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="px-6 py-4" style="background: var(--mb-primary);">
        <h2 class="text-lg font-semibold text-white">Informations modifiables</h2>
      </div>
      <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div>
            <label class="text-xs font-medium text-gray-700 mb-2 block">Prénom <span class="text-red-500">*</span></label>
            <input name="first_name" value="{{ old('first_name', $client->first_name) }}" required class="mb-input" placeholder="Entrez le prénom" />
          </div>

          <div>
            <label class="text-xs font-medium text-gray-700 mb-2 block">Nom</label>
            <input name="last_name" value="{{ old('last_name', $client->last_name) }}" class="mb-input" placeholder="Entrez le nom" />
          </div>

          <div>
            <label class="text-xs font-medium text-gray-700 mb-2 block">Indicatif</label>
            <input name="indicatif" value="{{ old('indicatif', $client->indicatif) }}" class="mb-input" placeholder="+237" />
          </div>

          <div>
            <label class="text-xs font-medium text-gray-700 mb-2 block">Téléphone</label>
            <input name="phone" value="{{ old('phone', $client->phone) }}" class="mb-input" placeholder="690000000" />
          </div>

          <div class="md:col-span-2">
            <label class="text-xs font-medium text-gray-700 mb-2 block">Adresse</label>
            <input name="address" value="{{ old('address', $client->address) }}" class="mb-input" placeholder="Entrez l'adresse complète" />
          </div>

          <div class="md:col-span-2">
            <label class="text-xs font-medium text-gray-700 mb-2 block">Notes</label>
            <textarea name="notes" class="mb-input" rows="4" placeholder="Ajoutez des notes ou observations sur ce client...">{{ old('notes', $client->notes) }}</textarea>
          </div>
        </div>
      </div>
    </div>

    {{-- Photo Upload --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="px-6 py-4" style="background: var(--mb-secondary);">
        <h2 class="text-lg font-semibold text-white">Photo de profil</h2>
      </div>
      <div class="p-6">
        @if($client->photo_profil)
          <div class="mb-4 flex items-center gap-4">
            <div class="w-24 h-24 rounded-full overflow-hidden border-4 shadow-md" style="border-color: var(--mb-primary);">
              <img src="{{ asset('storage/'.$client->photo_profil) }}" alt="photo" class="w-full h-full object-cover" />
            </div>
            <div>
              <p class="text-sm font-medium text-gray-900">Photo actuelle</p>
              <p class="text-xs text-gray-500 mt-1">Sélectionnez une nouvelle image pour remplacer</p>
            </div>
          </div>
        @endif
        <div>
          <label class="text-xs font-medium text-gray-700 mb-2 block">Choisir une nouvelle photo</label>
          <input name="photo_profil" type="file" accept="image/*" class="mb-input text-sm" />
          <p class="mt-2 text-xs text-gray-500">Formats acceptés: JPG, PNG, GIF (max 2MB)</p>
        </div>
      </div>
    </div>

    {{-- Action Buttons --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="px-6 py-4 flex items-center justify-between gap-4">
        <a href="{{ route('clients.show', $client) }}" class="mb-link inline-flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
          Annuler
        </a>
        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg text-white font-semibold shadow-md transition-all hover:shadow-lg" style="background: var(--mb-primary);">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
          Enregistrer les modifications
        </button>
      </div>
    </div>
  </form>
</div>
@endsection

@section('scripts')
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
@endsection