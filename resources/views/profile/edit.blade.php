@extends(auth()->user()?->role === 'admin' ? 'layouts.app-admin' : 'layouts.app-agent')

@section('title', 'Profil')
@section('page_title', 'Profil')

@section('content')
  <div class="max-w-7xl mx-auto space-y-8">
    <header>
      <h1 class="text-2xl md:text-3xl font-semibold tracking-tight">Paramètres du profil</h1>
      <p class="mt-1 text-sm text-gray-600">Gérez vos informations personnelles et la sécurité de votre compte.</p>
    </header>

    {{-- Infos profil --}}
    @include('profile.partials.update-profile-information-form', ['user' => $user])

    {{-- Mot de passe --}}
    @include('profile.partials.update-password-form')

    {{-- Suppression du compte --}}
    @include('profile.partials.delete-user-form')
  </div>
@endsection