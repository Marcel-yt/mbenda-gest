@extends('layouts.app-admin')

@section('title', 'Nouveau utilisateur')
@section('page_title', 'Nouveau utilisateur')

@section('content')
@php $auth = auth()->user(); @endphp

<div class="max-w-5xl mx-auto space-y-6">
  {{-- Header with Breadcrumb Navigation --}}
  <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-center justify-between">
      <div>
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
          <a href="{{ route('admin.users.index') }}" class="hover:text-blue-600 transition-colors">Utilisateurs</a>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
          </svg>
          <span class="text-gray-700 font-medium">Nouveau</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
          <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
          </svg>
          Créer un nouvel utilisateur
        </h1>
        <p class="mt-1 text-sm text-gray-600">Ajoutez un nouveau membre à l'équipe</p>
      </div>
      <a href="{{ route('admin.users.index') }}" 
         class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all shadow-sm"
         title="Retour à la liste">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Retour
      </a>
    </div>
  </div>

  {{-- Alerts --}}
  @if(session('success'))
    <div class="flex items-start gap-3 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">
      <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
      </svg>
      <div class="flex-1">{{ session('success') }}</div>
    </div>
  @endif

  @if(session('info'))
    <div class="flex items-start gap-3 p-4 rounded-xl bg-yellow-50 border border-yellow-200 text-yellow-800">
      <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
      </svg>
      <div class="flex-1">{{ session('info') }}</div>
    </div>
  @endif

  @if($errors->any())
    <div class="flex items-start gap-3 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800">
      <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
      </svg>
      <div class="flex-1">
        <ul class="list-disc pl-5 space-y-1">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    </div>
  @endif

  {{-- Main Form --}}
  <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
          </svg>
        </div>
        <div>
          <h2 class="text-lg font-semibold text-white">Informations de l'utilisateur</h2>
          <p class="text-sm text-green-100">Remplissez tous les champs requis</p>
        </div>
      </div>
    </div>

    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6" autocomplete="off">
      @csrf

      {{-- Name Fields --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
          <label for="first_name" class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Prénom <span class="text-red-500">*</span>
          </label>
          <input id="first_name" name="first_name" type="text" value="{{ old('first_name') }}"
                 class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" 
                 placeholder="Entrez le prénom" required>
          @error('first_name') <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            {{ $message }}
          </p> @enderror
        </div>

        <div>
          <label for="last_name" class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Nom
          </label>
          <input id="last_name" name="last_name" type="text" value="{{ old('last_name') }}"
                 class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" 
                 placeholder="Entrez le nom">
          @error('last_name') <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            {{ $message }}
          </p> @enderror
        </div>
      </div>

      {{-- Contact Fields --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
          <label for="email" class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            Email <span class="text-red-500">*</span>
          </label>
          <input id="email" name="email" type="email" value="{{ old('email') }}"
                 class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" 
                 placeholder="exemple@email.com" required>
          @error('email') <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            {{ $message }}
          </p> @enderror
        </div>

        <div>
          <label for="phone" class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
            </svg>
            Téléphone
          </label>
          <input id="phone" name="phone" type="text" value="{{ old('phone') }}"
                 class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" 
                 placeholder="+243 XXX XXX XXX">
          @error('phone') <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            {{ $message }}
          </p> @enderror
        </div>
      </div>

      <div class="border-t border-gray-100 my-6"></div>

      {{-- Role, Color, Status --}}
      <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div>
          <label for="role" class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
            </svg>
            Rôle <span class="text-red-500">*</span>
          </label>
          <select id="role" name="role" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
            <option value="">Sélectionner un rôle</option>
            @if(!empty($auth) && $auth->is_super_admin)
              <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>🔵 Admin</option>
              <option value="agent" {{ old('role') === 'agent' ? 'selected' : '' }}>🟢 Agent</option>
            @else
              <option value="agent" selected>🟢 Agent</option>
            @endif
          </select>
          @error('role') <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            {{ $message }}
          </p> @enderror
        </div>

        <div>
          <label for="color_hex" class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
            </svg>
            Couleur
          </label>
          <div class="flex items-center gap-2">
            <input id="color_picker" type="color" value="{{ old('color_hex', '#7FBC47') }}"
                   class="w-12 h-12 p-1 border-2 border-gray-300 rounded-lg cursor-pointer" 
                   onchange="document.getElementById('color_hex').value = this.value" />
            <input id="color_hex" name="color_hex" type="text" value="{{ old('color_hex') }}" placeholder="#RRGGBB"
                   class="flex-1 block rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" />
          </div>
          @error('color_hex') <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            {{ $message }}
          </p> @enderror
        </div>

        <div>
          <label for="active" class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Statut
          </label>
          <select id="active" name="active" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
            <option value="1" {{ old('active', '1') == '1' ? 'selected' : '' }}>✅ Activé</option>
            <option value="0" {{ old('active') == '0' ? 'selected' : '' }}>❌ Désactivé</option>
          </select>
          @error('active') <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            {{ $message }}
          </p> @enderror
        </div>
      </div>

      <div class="border-t border-gray-100 my-6"></div>

      {{-- Photo Upload --}}
      <div>
        <label for="photo_profil" class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-3">
          <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
          </svg>
          Photo de profil
        </label>
        <div class="flex items-center gap-5">
          <div class="relative">
            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center border-4 border-gray-200 shadow-md">
              <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
            </div>
            <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white border-2 border-white">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
              </svg>
            </div>
          </div>
          <div class="flex-1">
            <input id="photo_profil" name="photo_profil" type="file" accept="image/*"
                   class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 cursor-pointer" />
            <p class="mt-2 text-xs text-gray-500">Formats acceptés : JPG, PNG, GIF (max 2MB)</p>
          </div>
        </div>
        @error('photo_profil') <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
          <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
          </svg>
          {{ $message }}
        </p> @enderror
      </div>

      <div class="border-t border-gray-100 my-6"></div>

      {{-- Password Fields --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
          <label for="password" class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            Mot de passe <span class="text-red-500">*</span>
          </label>
          <input id="password" name="password" type="password"
                 class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" 
                 placeholder="Entrez un mot de passe sécurisé" required>
          @error('password') <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            {{ $message }}
          </p> @enderror
        </div>

        <div>
          <label for="password_confirmation" class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
            Confirmer le mot de passe <span class="text-red-500">*</span>
          </label>
          <input id="password_confirmation" name="password_confirmation" type="password"
                 class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" 
                 placeholder="Confirmez le mot de passe" required>
        </div>
      </div>

      <div class="border-t border-gray-100 pt-6 mt-6">
        <div class="flex items-center justify-end gap-3">
          <a href="{{ route('admin.users.index') }}" 
             class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            Annuler
          </a>
          <button type="submit" 
                  class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg text-sm font-medium hover:from-green-600 hover:to-green-700 transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Créer l'utilisateur
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection