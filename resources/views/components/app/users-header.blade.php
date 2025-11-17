@props([
  'totalUsers' => 0,
  'totalAdmins' => 0,
  'totalAgents' => 0,
  'totalClients' => 0,
  'active' => null,                 // 'staff' | 'clients' (auto détecté si null)
  'staffRoute' => null,             // ex: 'admin.users.index'
  'clientsRoute' => null,           // ex: 'admin.clients.index'
])

@php
  $staffHref = ($staffRoute && \Illuminate\Support\Facades\Route::has($staffRoute)) ? route($staffRoute) : '#';
  $clientsHref = ($clientsRoute && \Illuminate\Support\Facades\Route::has($clientsRoute)) ? route($clientsRoute) : '#';

  // auto-détecte l'onglet actif si non fourni — plus robuste
  if (is_null($active)) {
    $current = \Illuminate\Support\Facades\Route::currentRouteName() ?? '';
    if ($clientsRoute && (request()->routeIs($clientsRoute) || request()->routeIs($clientsRoute.'*') || str_contains($current, 'clients'))) {
      $active = 'clients';
    } elseif ($staffRoute && (request()->routeIs($staffRoute) || request()->routeIs($staffRoute.'*') || str_contains($current, 'users') || str_contains($current, 'agent'))) {
      $active = 'staff';
    } else {
      if (request()->is('admin/clients*')) {
        $active = 'clients';
      } elseif (request()->is('admin/users*') || request()->is('admin/agents*')) {
        $active = 'staff';
      } else {
        $active = 'staff';
      }
    }
  }
@endphp

<div class="space-y-4">
  {{-- Stat cards avec design moderne --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    {{-- Total utilisateurs --}}
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs font-medium text-blue-600 uppercase tracking-wide mb-1">Total utilisateurs</p>
          <p class="text-3xl font-bold text-blue-900">{{ str_pad($totalUsers, 2, '0', STR_PAD_LEFT) }}</p>
        </div>
        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center shadow-sm">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
          </svg>
        </div>
      </div>
    </div>

    {{-- Total admins --}}
    <div class="bg-gradient-to-br from-yellow-50 to-orange-100 border border-orange-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs font-medium text-orange-600 uppercase tracking-wide mb-1">Administrateurs</p>
          <p class="text-3xl font-bold text-orange-900">{{ str_pad($totalAdmins, 2, '0', STR_PAD_LEFT) }}</p>
        </div>
        <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-600 rounded-full flex items-center justify-center shadow-sm">
          <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
          </svg>
        </div>
      </div>
    </div>

    {{-- Total agents --}}
    <div class="bg-gradient-to-br from-green-50 to-emerald-100 border border-green-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs font-medium text-green-600 uppercase tracking-wide mb-1">Agents</p>
          <p class="text-3xl font-bold text-green-900">{{ str_pad($totalAgents, 2, '0', STR_PAD_LEFT) }}</p>
        </div>
        <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-600 rounded-full flex items-center justify-center shadow-sm">
          <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
          </svg>
        </div>
      </div>
    </div>

    {{-- Total clients --}}
    <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs font-medium text-purple-600 uppercase tracking-wide mb-1">Clients</p>
          <p class="text-3xl font-bold text-purple-900">{{ str_pad($totalClients, 2, '0', STR_PAD_LEFT) }}</p>
        </div>
        <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center shadow-sm">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
          </svg>
        </div>
      </div>
    </div>
  </div>

  {{-- Switch listes avec design moderne --}}
  <div class="bg-white border border-gray-200 rounded-2xl p-2 shadow-sm">
    <div class="flex flex-nowrap gap-2">
      <a href="{{ $staffHref }}"
         class="group flex-1 text-center text-sm font-semibold py-3 px-4 transition-all duration-200 rounded-xl {{ $active === 'staff' ? 'bg-gradient-to-r from-[#0078B7] to-[#005A8C] text-white shadow-md' : 'text-gray-700 hover:bg-gray-100' }}">
        <span class="inline-flex items-center gap-2">
          @if($active === 'staff')
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
              <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
            </svg>
          @endif
          Agents & Admins
        </span>
      </a>
      <a href="{{ $clientsHref }}"
         class="group flex-1 text-center text-sm font-semibold py-3 px-4 transition-all duration-200 rounded-xl {{ $active === 'clients' ? 'bg-gradient-to-r from-[#0078B7] to-[#005A8C] text-white shadow-md' : 'text-gray-700 hover:bg-gray-100' }}">
        <span class="inline-flex items-center gap-2">
          @if($active === 'clients')
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
          @endif
          Clients
        </span>
      </a>
    </div>
  </div>
</div>