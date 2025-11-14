@extends('layouts.app-admin')

@section('title', 'Liste des utilisateurs')
@section('page_title', 'Liste des utilisateurs')

@section('content')
@php
  use App\Models\User;
  $totalUsers = User::count();
  $totalAdmins = User::where('role','admin')->count();
  $totalAgents = User::where('role','agent')->count();
  $totalClients = User::where('role','client')->count();
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
    <div class="bg-white border rounded-xl p-4 grid gap-4 md:grid-cols-4">
      <div class="md:col-span-2">
        <label class="text-xs text-gray-500 mb-1 block">Recherche (nom, prénom, email, téléphone)</label>
        <input type="text" name="q" value="{{ old('q',$q ?? '') }}" class="mb-input" placeholder="Rechercher...">
      </div>

      @if(auth()->user()?->is_super_admin)
        <div>
          <label class="text-xs text-gray-500 mb-1 block">Rôle</label>
          <select name="role" class="mb-input">
            <option value="">Tous</option>
            <option value="admin" @selected(($role ?? '')==='admin')>Admin</option>
            <option value="agent" @selected(($role ?? '')==='agent')>Agent</option>
          </select>
        </div>
      @else
        <input type="hidden" name="role" value="agent">
      @endif

      <div>
        <label class="text-xs text-gray-500 mb-1 block">Statut</label>
        <select name="status" class="mb-input">
          <option value="">Tous</option>
          <option value="active"   @selected(($status ?? '')==='active')>Actif</option>
          <option value="inactive" @selected(($status ?? '')==='inactive')>Désactivé</option>
        </select>
      </div>
      {{-- Suppression du bouton Réinitialiser (filtrage entièrement interactif) --}}
    </div>
  </form>

  <!-- Titre + bouton -->
  <div class="flex items-center justify-between">
    <h2 class="text-lg font-semibold">Liste des agents et admins</h2>
    <a href="{{ route('admin.users.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm text-white shadow-sm hover:shadow
              focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2"
       style="background-color:#7FBC47">
      + Nouveau utilisateur
    </a>
  </div>


  <!-- Tableau -->
  <div class="bg-white border rounded-xl p-0 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">#</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Prenom</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Nom</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Email</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Role</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Couleur</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Statut</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Creer le</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Créé par</th>
            <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
          @forelse($users as $user)
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-3 text-sm text-gray-700 w-12">{{ $loop->iteration + ($users->currentPage()-1)*$users->perPage() }}</td>
              <td class="px-4 py-3 text-sm text-gray-700">{{ $user->first_name }}</td>
              <td class="px-4 py-3 text-sm text-gray-700">{{ $user->last_name }}</td>
              <td class="px-4 py-3 text-sm text-gray-700">{{ $user->email }}</td>
              <td class="px-4 py-3 text-sm">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                  {{ $user->role === 'admin' ? 'bg-yellow-100 text-yellow-800' : ($user->role === 'agent' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                  {{ $user->role ?? '—' }}
                </span>
              </td>

              <!-- colonne Couleur -->
              <td class="px-4 py-3 text-sm">
                <div class="flex items-center gap-2">
                  <span class="inline-block w-5 h-5 rounded border" style="background: {{ $user->color_hex ?? '#E5E7EB' }};"></span>
                  <span class="text-sm text-gray-700">{{ $user->color_hex ?? '—' }}</span>
                </div>
              </td>

              <!-- colonne Statut -->
              <td class="px-4 py-3 text-sm">
                <div class="flex items-center gap-2">
                  <span class="inline-block w-2.5 h-2.5 rounded-full" style="background: {{ $user->active ? '#16A34A' : '#DC2626' }};"></span>
                  <span class="{{ $user->active ? 'text-green-600' : 'text-red-600' }} text-sm font-medium">
                    {{ $user->active ? 'Actif' : 'Désactivé' }}
                  </span>
                </div>
              </td>

              <td class="px-4 py-3 text-sm text-gray-700">{{ optional($user->created_at)->format('d/m/Y') }}</td>
              <td class="px-4 py-3 text-sm text-gray-700">
                {{ $user->creator?->email ?? $user->created_by_email ?? '—' }}
              </td>
              <td class="px-4 py-3 text-sm text-right space-x-2">
                <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-blue-50 text-blue-600 hover:bg-blue-100" title="Voir">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                  </svg>
                </a>
                <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-yellow-50 text-yellow-600 hover:bg-yellow-100" title="Éditer">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536M4 13.5V20h6.5L20.768 9.732a2 2 0 00-2.828-2.828L7.5 17.172V13.5H4z"/>
                  </svg>
                </a>
                {{-- suppression désactivée : icône supprimée --}}
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="px-4 py-10 text-center text-sm text-gray-500">Aucun utilisateur trouvé.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="p-4 flex items-center justify-between">
      <div class="text-sm text-gray-500">Affichage {{ $users->firstItem() ?? 0 }}‑{{ $users->lastItem() ?? 0 }} sur {{ $users->total() }}</div>
      <div>{{ $users->appends(request()->query())->links() }}</div>
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