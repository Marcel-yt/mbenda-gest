@extends('layouts.app-admin')

@section('title', 'Liste des utilisateurs')
@section('page_title', 'Liste des utilisateurs')

@section('content')
@php
  use App\Models\User;
  use App\Models\Client;
  $totalUsers = User::count();
  $totalAdmins = User::where('role','admin')->count();
  $totalAgents = User::where('role','agent')->count();
  $totalClients = Client::count();
@endphp

<div class="space-y-6">
  <x-app.users-header
    :total-users="$totalUsers"
    :total-admins="$totalAdmins"
    :total-agents="$totalAgents"
    :total-clients="$totalClients"
    active="staff"
    staff-route="admin.users.index"
    clients-route="admin.clients.index"
  />

    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4" id="user-filters">
    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
      <div class="grid gap-5 md:grid-cols-4">
        {{-- Recherche avec icône --}}
        <div class="md:col-span-2">
          <label class="text-xs font-semibold text-gray-700 mb-2 block uppercase tracking-wider">Recherche</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
              </svg>
            </div>
            <input type="text" name="q" value="{{ old('q',$q ?? '') }}" 
                   class="pl-10 pr-10 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all" 
                   placeholder="Nom, prénom, email, téléphone...">
            @if(!empty($q))
              <button type="button" onclick="document.querySelector('input[name=q]').value=''; document.getElementById('user-filters').submit();" 
                      class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            @endif
          </div>
        </div>

        {{-- Filtre Rôle --}}
        @if(auth()->user()?->is_super_admin)
          <div>
            <label class="text-xs font-semibold text-gray-700 mb-2 block uppercase tracking-wider">Rôle</label>
            <select name="role" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all">
              <option value="">Tous les rôles</option>
              <option value="admin" @selected(($role ?? '')==='admin')>🔑 Admin</option>
              <option value="agent" @selected(($role ?? '')==='agent')>👤 Agent</option>
            </select>
          </div>
        @else
          <input type="hidden" name="role" value="agent">
        @endif

        {{-- Filtre Statut --}}
        <div>
          <label class="text-xs font-semibold text-gray-700 mb-2 block uppercase tracking-wider">Statut</label>
          <select name="status" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all">
            <option value="">Tous les statuts</option>
            <option value="active" @selected(($status ?? '')==='active')>✅ Actif</option>
            <option value="inactive" @selected(($status ?? '')==='inactive')>❌ Désactivé</option>
          </select>
        </div>
      </div>
      
      {{-- Indicateur de filtres actifs --}}
      @if(!empty($q) || !empty($role) || !empty($status))
        <div class="mt-4 pt-4 border-t border-gray-200 flex items-center gap-2 text-sm">
          <span class="text-gray-600 font-medium">Filtres actifs :</span>
          @if(!empty($q))
            <span class="inline-flex items-center gap-1 px-2 py-1 bg-indigo-100 text-indigo-700 rounded-lg text-xs font-medium">
              Recherche: "{{ $q }}"
            </span>
          @endif
          @if(!empty($role))
            <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-medium">
              Rôle: {{ ucfirst($role) }}
            </span>
          @endif
          @if(!empty($status))
            <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-medium">
              Statut: {{ $status === 'active' ? 'Actif' : 'Désactivé' }}
            </span>
          @endif
        </div>
      @endif
    </div>
  </form>

  {{-- Titre + bouton --}}
  <div class="flex items-center justify-between">
    <div>
      <h2 class="text-xl font-bold text-gray-900">Liste des agents et admins</h2>
      <p class="text-sm text-gray-500 mt-1">Gérez les membres de votre équipe</p>
    </div>
    <a href="{{ route('admin.users.create') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200"
       style="background: linear-gradient(135deg, #7FBC47 0%, #6BA63D 100%);">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
      </svg>
      Nouveau utilisateur
    </a>
  </div>

  {{-- Tableau --}}
  <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
      <table class="min-w-full w-full">
        <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
          <tr>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Utilisateur</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Contact</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Rôle</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Couleur</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Statut</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Création</th>
            <th class="px-5 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
          @forelse($users as $user)
            <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200">
              {{-- Colonne Utilisateur avec avatar --}}
              <td class="px-5 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-full bg-gradient-to-br {{ $user->role === 'admin' ? 'from-yellow-400 to-orange-500' : 'from-green-400 to-emerald-500' }} flex items-center justify-center text-white font-bold text-sm shadow-md">
                    {{ strtoupper(substr($user->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($user->last_name ?? 'S', 0, 1)) }}
                  </div>
                  <div class="min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $user->first_name }} {{ $user->last_name }}</p>
                    <p class="text-xs text-gray-500 truncate">#{{ $loop->iteration + ($users->currentPage()-1)*$users->perPage() }}</p>
                  </div>
                </div>
              </td>

              {{-- Colonne Contact --}}
              <td class="px-5 py-4">
                <div class="flex flex-col gap-1">
                  <span class="text-sm text-gray-900">{{ $user->email }}</span>
                  @if($user->phone)
                    <span class="text-xs text-gray-500">📞 {{ $user->phone }}</span>
                  @endif
                </div>
              </td>

              {{-- Colonne Rôle --}}
              <td class="px-5 py-4">
                @if($user->role === 'admin')
                  <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-gradient-to-r from-yellow-100 to-orange-100 text-orange-800 border border-orange-200">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Admin
                  </span>
                @elseif($user->role === 'agent')
                  <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                    </svg>
                    Agent
                  </span>
                @else
                  <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-600">
                    {{ $user->role ?? '—' }}
                  </span>
                @endif
              </td>

              {{-- Colonne Couleur --}}
              <td class="px-5 py-4">
                <div class="flex items-center gap-2">
                  <div class="w-8 h-8 rounded-lg shadow-inner border-2 border-white ring-1 ring-gray-200" style="background: {{ $user->color_hex ?? '#E5E7EB' }};"></div>
                  <span class="text-xs font-mono text-gray-600">{{ $user->color_hex ?? '—' }}</span>
                </div>
              </td>

              {{-- Colonne Statut --}}
              <td class="px-5 py-4">
                @if($user->active)
                  <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    Actif
                  </span>
                @else
                  <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                    Désactivé
                  </span>
                @endif
              </td>

              {{-- Colonne Création --}}
              <td class="px-5 py-4">
                <div class="flex flex-col gap-1">
                  <span class="text-sm text-gray-900 font-medium">{{ optional($user->created_at)->format('d/m/Y') }}</span>
                  <span class="text-xs text-gray-500">par {{ $user->creator?->first_name ?? 'Système' }}</span>
                </div>
              </td>
              {{-- Colonne Actions --}}
              <td class="px-5 py-4">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.users.show', $user) }}" 
                     class="group relative inline-flex items-center justify-center w-9 h-9 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 hover:scale-110 transition-all duration-200 shadow-sm hover:shadow"
                     title="Voir les détails">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <span class="absolute -top-8 right-0 px-2 py-1 bg-gray-900 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">Voir</span>
                  </a>
                  <a href="{{ route('admin.users.edit', $user) }}" 
                     class="group relative inline-flex items-center justify-center w-9 h-9 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 hover:scale-110 transition-all duration-200 shadow-sm hover:shadow"
                     title="Éditer l'utilisateur">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span class="absolute -top-8 right-0 px-2 py-1 bg-gray-900 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">Éditer</span>
                  </a>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-5 py-16">
                <div class="flex flex-col items-center justify-center text-center">
                  <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                  </div>
                  <h3 class="text-lg font-semibold text-gray-900 mb-1">Aucun utilisateur trouvé</h3>
                  <p class="text-sm text-gray-500 mb-4">Essayez d'ajuster vos filtres ou créez un nouvel utilisateur</p>
                  @if(!empty($q) || !empty($role) || !empty($status))
                    <button onclick="window.location.href='{{ route('admin.users.index') }}'" 
                            class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                      </svg>
                      Réinitialiser les filtres
                    </button>
                  @endif
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="px-6 py-4 border-t border-gray-200 bg-gradient-to-r from-gray-50 to-white">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <span class="text-sm font-medium text-gray-700">Affichage</span>
          <span class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded-lg text-sm font-bold">{{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }}</span>
          <span class="text-sm font-medium text-gray-700">sur</span>
          <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-lg text-sm font-bold">{{ $users->total() }}</span>
        </div>
        <div>{{ $users->appends(request()->query())->links() }}</div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
(function(){
  const ready = (cb) => {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', cb);
    } else { cb(); }
  };

  ready(function(){
    const form = document.getElementById('user-filters');
    if (!form) return;

    const submit = () => form.submit(); // GET immédiat

    // Saisie: soumission à chaque frappe
    const input = form.querySelector('input[name="q"]');
    if (input) {
      input.setAttribute('autocomplete','off');
      input.addEventListener('input', submit);
      input.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') { input.value=''; submit(); }
      });
    }

    // Menus déroulants: soumission instantanée
    form.querySelectorAll('select').forEach(sel => {
      sel.addEventListener('change', submit);
    });
  });
})();
</script>
@endsection