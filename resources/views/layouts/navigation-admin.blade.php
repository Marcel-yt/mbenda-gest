@php $logoPath = file_exists(public_path('images/logo2.png')) ? 'images/logo2.png' : 'images/logo2.png'; @endphp
<aside class="hidden lg:flex lg:flex-col w-64 bg-[#003E60] text-white min-h-screen">
  <a href="{{ route('admin.dashboard') }}" class="h-16 flex items-center px-6 font-extrabold tracking-wide">
    <img src="{{ asset($logoPath) }}" alt="{{ config('app.name', 'Mbenda Gest') }}" class="h-8 w-auto">
  </a>

  <nav class="px-0 py-4 space-y-1">
    <a href="{{ route('admin.dashboard') }}"
       class="group w-full text-sm font-medium flex items-center gap-3 px-5 py-3
       {{ request()->routeIs('admin.dashboard') ? 'bg-[var(--mb-primary)] text-white' : 'text-white/80 hover:bg-white/10' }} rounded-none">
      <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="{{ request()->routeIs('admin.dashboard') ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
        <path d="M3 13h8V3H3v10z"></path>
        <path d="M13 21h8V11h-8v10z"></path>
        <path d="M3 21h8v-6H3v6z"></path>
      </svg>
      <span class="leading-tight">Tableau de bord</span>
    </a>

    <!-- Nouveau lien : Liste des utilisateurs -->
    <a href="{{ route('admin.users.index') }}"
       class="group w-full text-sm font-medium flex items-center gap-3 px-5 py-3
       {{ request()->routeIs('admin.users*') ? 'bg-[var(--mb-primary)] text-white' : 'text-white/80 hover:bg-white/10' }} rounded-none">
      <!-- icône utilisateurs -->
      <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="{{ request()->routeIs('admin.users*') ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
        <path d="M16 11c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM8 11c1.657 0 3-1.343 3-3S9.657 5 8 5 5 6.343 5 8s1.343 3 3 3z"/>
        <path d="M2 20c0-2.21 3.582-4 8-4s8 1.79 8 4v1H2v-1z"/>
      </svg>
      <span class="leading-tight">Liste des utilisateurs</span>
    </a>

    {{-- autres liens admin ici --}}
  </nav>
</aside>

{{-- Drawer mobile --}}
<div x-cloak x-show="sidebarOpen" class="fixed inset-0 z-40 lg:hidden">
  <div class="absolute inset-0 bg-black/40" @click="sidebarOpen=false" aria-hidden="true"></div>

  <div
    x-show="sidebarOpen"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="-translate-x-full opacity-0"
    x-transition:enter-end="translate-x-0 opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="translate-x-0 opacity-100"
    x-transition:leave-end="-translate-x-full opacity-0"
    class="absolute inset-y-0 left-0 w-72 bg-[#003E60] text-white shadow-xl"
    role="dialog" aria-modal="true"
  >
    <div class="h-16 flex items-center justify-between px-6">
      <a href="{{ route('admin.dashboard') }}">
        <img src="{{ asset($logoPath) }}" alt="{{ config('app.name', 'Mbenda Gest') }}" class="h-8 w-auto">
      </a>
      <button class="p-2 rounded-lg hover:bg-white/10" @click="sidebarOpen=false" aria-label="Fermer la navigation">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>

    <nav class="px-0 py-4 space-y-1">
      <a href="{{ route('admin.dashboard') }}"
         class="group w-full text-sm font-medium flex items-center gap-3 px-5 py-3
         {{ request()->routeIs('admin.dashboard') ? 'bg-[var(--mb-primary)] text-white' : 'text-white/80 hover:bg-white/10' }} rounded-none">
        <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="{{ request()->routeIs('admin.dashboard') ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
          <path d="M3 13h8V3H3v10z"></path>
          <path d="M13 21h8V11h-8v10z"></path>
          <path d="M3 21h8v-6H3v6z"></path>
        </svg>
        <span class="leading-tight">Tableau de bord</span>
      </a>

      <a href="{{ route('admin.users.index') }}"
         class="group w-full text-sm font-medium flex items-center gap-3 px-5 py-3
         {{ request()->routeIs('admin.users*') ? 'bg-[var(--mb-primary)] text-white' : 'text-white/80 hover:bg-white/10' }} rounded-none">
        <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="{{ request()->routeIs('admin.users*') ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
          <path d="M16 11c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM8 11c1.657 0 3-1.343 3-3S9.657 5 8 5 5 6.343 5 8s1.343 3 3 3z"/>
          <path d="M2 20c0-2.21 3.582-4 8-4s8 1.79 8 4v1H2v-1z"/>
        </svg>
        <span class="leading-tight">Liste des utilisateurs</span>
      </a>

      {{-- autres liens admin ici --}}
    </nav>
  </div>
</div>