{{-- Sidebar Agent (desktop) --}}
@php $logoPath = file_exists(public_path('images/logo2.png')) ? 'images/logo2.png' : 'images/logo2.png'; @endphp
<aside class="hidden lg:flex lg:flex-col w-64 bg-[#274718] text-white h-screen fixed left-0 top-0 shadow-xl">
  <div class="h-16 flex items-center justify-between px-6 border-b border-white/10 flex-shrink-0">
    <a href="{{ route('agent.dashboard') }}" class="flex items-center gap-3 group">
      <img src="{{ asset($logoPath) }}" alt="Logo" class="h-8 w-auto transition-transform group-hover:scale-105">
      <span class="text-sm font-semibold text-white/90 group-hover:text-white transition-colors">Agent</span>
    </a>
  </div>

  <nav class="px-0 py-4 space-y-1 flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-white/20">
    <a href="{{ route('agent.dashboard') }}"
       class="group w-full text-sm font-medium flex items-center gap-3 px-5 py-3 transition-all duration-200
       {{ request()->routeIs('agent.dashboard') ? 'bg-[var(--mb-secondary,#7FBC47)] text-white shadow-sm border-l-4 border-[var(--mb-tertiary)]' : 'text-white/80 hover:bg-white/10 hover:text-white hover:border-l-4 hover:border-white/20 border-l-4 border-transparent' }}">
      <svg class="w-5 h-5 flex-shrink-0 transition-transform group-hover:scale-110" viewBox="0 0 24 24" fill="{{ request()->routeIs('agent.dashboard') ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
        <path d="M3 13h8V3H3v10z"></path>
        <path d="M13 21h8V11h-8v10z"></path>
        <path d="M3 21h8v-6H3v6z"></path>
      </svg>
      <span class="leading-tight">Tableau de bord</span>
      @if(request()->routeIs('agent.dashboard'))
        <svg class="w-4 h-4 ml-auto flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
      @endif
    </a>

    <a href="{{ route('clients.index') }}"
       class="group w-full text-sm font-medium flex items-center gap-3 px-5 py-3 transition-all duration-200
       {{ request()->routeIs('clients.*') ? 'bg-[var(--mb-secondary,#7FBC47)] text-white shadow-sm border-l-4 border-[var(--mb-tertiary)]' : 'text-white/80 hover:bg-white/10 hover:text-white hover:border-l-4 hover:border-white/20 border-l-4 border-transparent' }}">
      <svg class="w-5 h-5 flex-shrink-0 transition-transform group-hover:scale-110" viewBox="0 0 24 24" fill="{{ request()->routeIs('clients.*') ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
        <path d="M3 7h18M3 12h18M3 17h18" />
      </svg>
      <span class="leading-tight">Clients</span>
      @if(request()->routeIs('clients.*'))
        <svg class="w-4 h-4 ml-auto flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
      @endif
    </a>

    <a href="{{ route('agent.tontines.index') }}"
       class="group w-full text-sm font-medium flex items-center gap-3 px-5 py-3 transition-all duration-200
       {{ request()->routeIs('agent.tontines.*') ? 'bg-[var(--mb-secondary,#7FBC47)] text-white shadow-sm border-l-4 border-[var(--mb-tertiary)]' : 'text-white/80 hover:bg-white/10 hover:text-white hover:border-l-4 hover:border-white/20 border-l-4 border-transparent' }}">
      <svg class="w-5 h-5 flex-shrink-0 transition-transform group-hover:scale-110" viewBox="0 0 24 24" fill="{{ request()->routeIs('agent.tontines.*') ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <ellipse cx="12" cy="5" rx="7" ry="2.5" />
        <path d="M5 5v3.5C5 10 8.134 12 12 12s7-2 7-3.5V5" fill="none"/>
        <ellipse cx="12" cy="12" rx="7" ry="2.5" />
        <path d="M5 12v3.5C5 20 8.134 22 12 22s7-2 7-6.5V12" fill="none"/>
      </svg>
      <span class="leading-tight">Tontines</span>
      @if(request()->routeIs('agent.tontines.*'))
        <svg class="w-4 h-4 ml-auto flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
      @endif
    </a>
  </nav>

  {{-- Footer avec info utilisateur --}}
  <div class="border-t border-white/10 p-4 flex-shrink-0 bg-[#1E3612]">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 rounded-full bg-[var(--mb-secondary)] flex items-center justify-center text-white font-semibold text-sm">
        {{ strtoupper(substr(auth()->user()->first_name ?? 'A', 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name ?? 'G', 0, 1)) }}
      </div>
      <div class="flex-1 min-w-0">
        <p class="text-sm font-medium text-white truncate">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>
        <p class="text-xs text-white/60 truncate">{{ auth()->user()->email }}</p>
      </div>
    </div>
  </div>
</aside>

{{-- Drawer mobile --}}
<div x-cloak x-show="sidebarOpen" class="fixed inset-0 z-50 lg:hidden">
  <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="sidebarOpen=false" aria-hidden="true"></div>

  <div
    x-show="sidebarOpen"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="-translate-x-full opacity-0"
    x-transition:enter-end="translate-x-0 opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="translate-x-0 opacity-100"
    x-transition:leave-end="-translate-x-full opacity-0"
    class="absolute inset-y-0 left-0 w-72 bg-[#274718] text-white shadow-2xl flex flex-col"
    role="dialog" aria-modal="true"
  >
    <div class="h-16 flex items-center justify-between px-6 border-b border-white/10 flex-shrink-0">
      <a href="{{ route('agent.dashboard') }}" class="flex items-center gap-3 group">
        <img src="{{ asset($logoPath) }}" alt="Logo" class="h-8 w-auto transition-transform group-hover:scale-105">
        <span class="text-sm font-semibold text-white/90 group-hover:text-white transition-colors">Agent</span>
      </a>
      <button class="p-2 rounded-lg hover:bg-white/10 transition-colors" @click="sidebarOpen=false" aria-label="Fermer la navigation">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>

    <nav class="px-0 py-4 space-y-1 flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-white/20">
      <a href="{{ route('agent.dashboard') }}"
         class="group w-full text-sm font-medium flex items-center gap-3 px-5 py-3 transition-all duration-200
         {{ request()->routeIs('agent.dashboard') ? 'bg-[var(--mb-secondary,#7FBC47)] text-white shadow-sm border-l-4 border-[var(--mb-tertiary)]' : 'text-white/80 hover:bg-white/10 hover:text-white hover:border-l-4 hover:border-white/20 border-l-4 border-transparent' }}">
        <svg class="w-5 h-5 flex-shrink-0 transition-transform group-hover:scale-110" viewBox="0 0 24 24" fill="{{ request()->routeIs('agent.dashboard') ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
          <path d="M3 13h8V3H3v10z"></path>
          <path d="M13 21h8V11h-8v10z"></path>
          <path d="M3 21h8v-6H3v6z"></path>
        </svg>
        <span class="leading-tight">Tableau de bord</span>
        @if(request()->routeIs('agent.dashboard'))
          <svg class="w-4 h-4 ml-auto flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
        @endif
      </a>

      <a href="{{ route('clients.index') }}"
         class="group w-full text-sm font-medium flex items-center gap-3 px-5 py-3 transition-all duration-200
         {{ request()->routeIs('clients.*') ? 'bg-[var(--mb-secondary,#7FBC47)] text-white shadow-sm border-l-4 border-[var(--mb-tertiary)]' : 'text-white/80 hover:bg-white/10 hover:text-white hover:border-l-4 hover:border-white/20 border-l-4 border-transparent' }}">
        <svg class="w-5 h-5 flex-shrink-0 transition-transform group-hover:scale-110" viewBox="0 0 24 24" fill="{{ request()->routeIs('clients.*') ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
          <path d="M3 7h18M3 12h18M3 17h18" />
        </svg>
        <span class="leading-tight">Clients</span>
        @if(request()->routeIs('clients.*'))
          <svg class="w-4 h-4 ml-auto flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
        @endif
      </a>

      <a href="{{ route('agent.tontines.index') }}"
         class="group w-full text-sm font-medium flex items-center gap-3 px-5 py-3 transition-all duration-200
         {{ request()->routeIs('agent.tontines.*') ? 'bg-[var(--mb-secondary,#7FBC47)] text-white shadow-sm border-l-4 border-[var(--mb-tertiary)]' : 'text-white/80 hover:bg-white/10 hover:text-white hover:border-l-4 hover:border-white/20 border-l-4 border-transparent' }}">
        <svg class="w-5 h-5 flex-shrink-0 transition-transform group-hover:scale-110" viewBox="0 0 24 24" fill="{{ request()->routeIs('agent.tontines.*') ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
          <ellipse cx="12" cy="5" rx="7" ry="2.5" />
          <path d="M5 5v3.5C5 10 8.134 12 12 12s7-2 7-3.5V5" fill="none"/>
          <ellipse cx="12" cy="12" rx="7" ry="2.5" />
          <path d="M5 12v3.5C5 20 8.134 22 12 22s7-2 7-6.5V12" fill="none"/>
        </svg>
        <span class="leading-tight">Tontines</span>
        @if(request()->routeIs('agent.tontines.*'))
          <svg class="w-4 h-4 ml-auto flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
        @endif
      </a>
    </nav>

    {{-- Footer avec info utilisateur (mobile drawer) --}}
    <div class="border-t border-white/10 p-4 flex-shrink-0 bg-[#1E3612]">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-[var(--mb-secondary)] flex items-center justify-center text-white font-semibold text-sm">
          {{ strtoupper(substr(auth()->user()->first_name ?? 'A', 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name ?? 'G', 0, 1)) }}
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-sm font-medium text-white truncate">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>
          <p class="text-xs text-white/60 truncate">{{ auth()->user()->email }}</p>
        </div>
      </div>
    </div>
  </div>
</div>