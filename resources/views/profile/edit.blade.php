@extends(auth()->user()?->role === 'admin' ? 'layouts.app-admin' : 'layouts.app-agent')

@section('title', 'Profil')
@section('page_title', 'Paramètres du profil')

@section('content')
  @php
    // restreindre l'accès à la page d'édition aux admins
    if (auth()->user()?->role !== 'admin') {
        abort(403);
    }
  @endphp

  <div class="max-w-7xl mx-auto space-y-8">
    <header class="flex items-center justify-between">
      <div class="flex items-center gap-4">
        <div>
          <h1 class="text-2xl md:text-3xl font-semibold tracking-tight">Paramètres du profil</h1>
          <p class="mt-1 text-sm text-gray-600">Gérez vos informations personnelles et la sécurité de votre compte.</p>
        </div>
      </div>

      <a href="{{ route('profile.show') }}"
         class="ml-4 inline-flex items-center px-3 py-2 border-2 border-blue-600 text-sm text-blue-600 rounded-md hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-200">
        ← Retour
      </a>
    </header>

    {{-- Infos profil (formulaire d'édition pour admin only) --}}
    @include('profile.partials.update-profile-information-form', ['user' => $user])

    {{-- Mot de passe --}}
    @include('profile.partials.update-password-form')

    <!-- {{-- Suppression du compte --}}
    @include('profile.partials.delete-user-form') -->
  </div>
@endsection