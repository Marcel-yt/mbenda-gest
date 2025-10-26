@props([
  'totalUsers' => 0,
  'totalAdmins' => 0,
  'totalAgents' => 0,
  'totalClients' => 0,
  'active' => 'staff',              // 'staff' | 'clients'
  'staffRoute' => null,             // ex: 'admin.users.index'
  'clientsRoute' => null,           // ex: 'admin.clients.index'
])

@php
  $staffHref = ($staffRoute && \Illuminate\Support\Facades\Route::has($staffRoute)) ? route($staffRoute) : '#';
  $clientsHref = ($clientsRoute && \Illuminate\Support\Facades\Route::has($clientsRoute)) ? route($clientsRoute) : '#';
@endphp

<div class="space-y-4">
  {{-- Stat cards --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-white border rounded-xl p-4">
      <p class="text-sm text-gray-600">Nombre total d'utilisateur</p>
      <p class="mt-3 text-2xl font-bold">{{ str_pad($totalUsers, 2, '0', STR_PAD_LEFT) }}</p>
    </div>
    <div class="bg-white border rounded-xl p-4">
      <p class="text-sm text-gray-600">Nombre total d’admins</p>
      <p class="mt-3 text-2xl font-bold">{{ str_pad($totalAdmins, 2, '0', STR_PAD_LEFT) }}</p>
    </div>
    <div class="bg-white border rounded-xl p-4">
      <p class="text-sm text-gray-600">Nombre total d’agents</p>
      <p class="mt-3 text-2xl font-bold">{{ str_pad($totalAgents, 2, '0', STR_PAD_LEFT) }}</p>
    </div>
    <div class="bg-white border rounded-xl p-4">
      <p class="text-sm text-gray-600">Nombre total de clients</p>
      <p class="mt-3 text-2xl font-bold">{{ str_pad($totalClients, 2, '0', STR_PAD_LEFT) }}</p>
    </div>
  </div>

  {{-- Switch listes --}}
  <div class="rounded-lg overflow-hidden">
    <div class="flex flex-nowrap my-4 border-2 border-gray-200 rounded-xl bg-white">
      <a href="{{ $staffHref }}"
         class="flex-1 text-center text-sm font-medium py-3 transition rounded-xl
                {{ $active === 'staff' ? 'bg-[#0078B7] text-white' : 'text-gray-700 hover:bg-[#0078B7]/10' }}">
        Listes des agents et admin
      </a>
      <a href="{{ $clientsHref }}"
         class="flex-1 text-center text-sm font-medium py-3 transition rounded-xl
                {{ $active === 'clients' ? 'bg-[#0078B7] text-white' : 'text-gray-700 hover:bg-[#0078B7]/10' }}">
        Listes des clients
      </a>
    </div>
  </div>
</div>