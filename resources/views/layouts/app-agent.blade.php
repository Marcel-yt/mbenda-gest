<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'Mbenda Gest'))</title>
    <meta name="description" content="@yield('meta_description', 'Solution d\'épargne quotidienne simple et sécurisée')">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:title" content="@yield('og_title', config('app.name'))">
    <meta property="og:description" content="@yield('og_description', 'Solution d\'épargne quotidienne simple et sécurisée')">
    <meta property="og:image" content="{{ asset('icon-192x192.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('icon-192x192.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('icon-192x192.png') }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <style>
      :root { --mb-primary:#0078B7; --mb-secondary:#7FBC47; --mb-tertiary:#F7A52C; }
    </style>
    @vite(['resources/css/app.css','resources/js/app.js'])
  </head>
  <body class="font-sans antialiased text-gray-900 bg-gray-100" x-data="{ sidebarOpen:false }">
    <div class="min-h-screen flex">
      {{-- Sidebar Agent --}}
      @include('layouts.navigation-agent')

      {{-- Contenu principal avec marge pour sidebar fixe --}}
      <div class="flex-1 flex flex-col min-w-0 lg:ml-64">
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 md:px-6">
          <div class="flex items-center gap-3">
            <!-- Hamburger (visible sur petits écrans) -->
            <button class="lg:hidden p-2 rounded-md hover:bg-gray-100" @click="sidebarOpen = true" aria-label="Ouvrir la navigation">
              <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
              </svg>
            </button>

            <!-- Titre responsive (plus petit sur mobile) -->
            <h1 class="text-base sm:text-lg md:text-xl font-semibold leading-tight">
              @yield('page_title', 'Tableau de bord Agent')
            </h1>
          </div>

          <!-- Profil : visible sur toutes tailles -->
          <div class="flex items-center space-x-3">
            <x-app.profil-menu role="agent" align="right" />
          </div>
        </header>

        <main class="p-4 md:p-6">
          @yield('content')
        </main>
      </div>
    </div>

    @yield('scripts')
    @stack('scripts')
  </body>
</html>