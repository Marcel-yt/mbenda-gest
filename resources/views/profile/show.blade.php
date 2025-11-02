@extends(auth()->user()?->role === 'admin' ? 'layouts.app-admin' : 'layouts.app-agent')

@section('title', 'Profil')
@section('page_title', 'Profil')

@section('content')
  <div class="max-w-4xl mx-auto space-y-6">
    <header>
      <h1 class="text-2xl md:text-3xl font-semibold tracking-tight">Profil</h1>
      <p class="mt-1 text-sm text-gray-600">Détails et informations de contact.</p>
    </header>

    <section class="bg-white shadow sm:rounded-lg p-6">
      <div class="md:flex md:items-start md:space-x-6">
        {{-- Texte à gauche --}}
        <div class="flex-1">
          <div class="flex items-start justify-between">
            <div>
              <h2 class="text-xl font-semibold text-gray-900">
                {{ $user->first_name ?? '—' }} {{ $user->last_name ?? '—' }}
              </h2>
              <p class="mt-1 text-sm text-gray-500">{{ $user->email ?? '—' }}</p>
            </div>

            {{-- Bouton modifier (visible seulement aux admins) --}}
            @if(auth()->user()?->role === 'admin')
              <div>
                <a href="{{ route('profile.edit') }}"
                   class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                  Modifier
                </a>
              </div>
            @endif
          </div>

          <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700">
            <div>
              <div class="text-xs text-gray-500">Prénom</div>
              <div class="mt-1 font-medium text-gray-900">{{ $user->first_name ?? '—' }}</div>
            </div>

            <div>
              <div class="text-xs text-gray-500">Nom</div>
              <div class="mt-1 font-medium text-gray-900">{{ $user->last_name ?? '—' }}</div>
            </div>

            <div>
              <div class="text-xs text-gray-500">Téléphone</div>
              <div class="mt-1 font-medium text-gray-900">{{ $user->phone ?? '—' }}</div>
            </div>

            <div>
              <div class="text-xs text-gray-500">Rôle</div>
              <div class="mt-1 font-medium text-gray-900">{{ ucfirst($user->role ?? '—') }}</div>
            </div>

            <div>
              <div class="text-xs text-gray-500">Couleur</div>
              <div class="mt-1 flex items-center gap-3">
                <span class="inline-block w-6 h-6 rounded border" style="background: {{ $user->color_hex ?? '#E5E7EB' }};"></span>
                <span class="font-medium text-gray-900">{{ $user->color_hex ?? '—' }}</span>
              </div>
            </div>

            <div>
              <div class="text-xs text-gray-500">Statut</div>
              <div class="mt-1 font-medium {{ $user->active ? 'text-green-600' : 'text-red-600' }}">
                {{ $user->active ? 'Actif' : 'Désactivé' }}
              </div>
            </div>

            <div class="sm:col-span-2">
              <div class="text-xs text-gray-500">Dernière connexion</div>
              <div class="mt-1 font-medium text-gray-900">
                {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Jamais' }}
              </div>
            </div>

            @if(!empty($user->is_super_admin))
              <div class="sm:col-span-2">
                <div class="text-xs text-gray-500">Rôle spécial</div>
                <div class="mt-1 font-medium text-yellow-700">Super‑admin</div>
              </div>
            @endif
          </div>
        </div>

        {{-- Photo à droite --}}
        <div class="mt-6 md:mt-0 flex-shrink-0">
          <div class="w-32 h-32 rounded-full overflow-hidden border">
            <img class="w-full h-full object-cover"
                 src="{{ $user->photo_profil ? Storage::url($user->photo_profil) . '?t=' . ($user->updated_at ? $user->updated_at->timestamp : time()) : asset('images/default-avatar.png') }}"
                 alt="{{ $user->first_name }} {{ $user->last_name }}">
          </div>

          {{-- petit texte sous la photo --}}
          <div class="mt-3 text-xs text-gray-500 text-center">
            Photo de profil
          </div>
        </div>
      </div>
    </section>
  </div>
@endsection