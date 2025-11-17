@extends(auth()->user()?->role === 'admin' ? 'layouts.app-admin' : 'layouts.app-agent')

@section('title', 'Modifier le profil')
@section('page_title', 'Modifier le profil')

@section('content')
  @php
    // restreindre l'accès à la page d'édition aux admins
    if (auth()->user()?->role !== 'admin') {
        abort(403);
    }
  @endphp

<div class="max-w-5xl mx-auto space-y-6">

  <!-- Breadcrumb -->
  <nav class="flex items-center gap-2 text-sm text-gray-600">
    <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-900 transition-colors">
      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
        <polyline points="9 22 9 12 15 12 15 22"/>
      </svg>
    </a>
    <svg class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="9 18 15 12 9 6"/>
    </svg>
    <a href="{{ route('profile.show') }}" class="hover:text-gray-900 transition-colors">Mon Profil</a>
    <svg class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="9 18 15 12 9 6"/>
    </svg>
    <span class="font-medium text-gray-900">Modifier</span>
  </nav>

  <!-- Header Card -->
  <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-start justify-between">
      <div class="flex items-center gap-4">
        <div class="w-14 h-14 rounded-lg flex items-center justify-center text-white shadow-lg" style="background: linear-gradient(135deg, var(--mb-primary) 0%, #005f8d 100%);">
          <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
          </svg>
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Modifier mon profil</h1>
          <p class="text-sm text-gray-600 mt-1">Gérez vos informations personnelles et la sécurité de votre compte</p>
        </div>
      </div>
      <a href="{{ route('profile.show') }}"
         class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Retour au profil
      </a>
    </div>
  </div>

  {{-- Infos profil (formulaire d'édition pour admin only) --}}
  @include('profile.partials.update-profile-information-form', ['user' => $user])

  {{-- Mot de passe --}}
  @include('profile.partials.update-password-form')

</div>
@endsection