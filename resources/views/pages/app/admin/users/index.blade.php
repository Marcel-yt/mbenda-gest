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
  <div class="bg-white border rounded-xl px-6 py-10 space-y-4">
    <div class="flex flex-wrap items-center justify-between gap-4">
      <div class="flex items-center gap-3">
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-[#0078B7]">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18M6 12h12M10 20h4"/>
          </svg>
        </span>
        <h3 class="text-lg font-semibold text-gray-900">Filtres de recherche</h3>
      </div>
      <div class="flex items-center gap-3">
        <span class="text-sm text-gray-500">Trier par :</span>
        <div class="relative">
          <select class="appearance-none rounded-md border border-gray-200 bg-white py-2 pl-10 pr-10 text-sm text-gray-700 focus:border-[#0078B7] focus:outline-none focus:ring-2 focus:ring-[#0078B7]">
            <option>Role</option>
            <option value="admin">Admin</option>
            <option value="agent">Agent</option>
          </select>
        </div>
      </div>
    </div>
    <div class="relative">
        <input type="search"
                class="w-full rounded-lg border border-gray-200 py-3 pl-4 pr-12 text-sm text-gray-700 placeholder:text-gray-400 focus:border-[#0078B7] focus:outline-none focus:ring-2 focus:ring-[#0078B7]"
                placeholder="Rechercher les tests par technologies ou par sujets">
        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 19a8 8 0 100-16 8 8 0 000 16z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35"/>
            </svg>
        </span>
    </div>
  </div>

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
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Creer le</th>
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
              <td class="px-4 py-3 text-sm text-gray-700">{{ optional($user->created_at)->format('d/m/Y') }}</td>
              <td class="px-4 py-3 text-sm text-right space-x-2">
                <a href="{{ route('admin.users.show', $user->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-blue-50 text-blue-600 hover:bg-blue-100" title="Voir">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                  </svg>
                </a>
                <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-yellow-50 text-yellow-600 hover:bg-yellow-100" title="Éditer">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536M4 13.5V20h6.5L20.768 9.732a2 2 0 00-2.828-2.828L7.5 17.172V13.5H4z"/>
                  </svg>
                </a>
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="post" class="inline-block" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                  @csrf @method('delete')
                  <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-red-50 text-red-600 hover:bg-red-100" title="Supprimer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4l1 4H9l1-4z"/>
                    </svg>
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-4 py-10 text-center text-sm text-gray-500">Aucun utilisateur trouvé.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="p-4 flex items-center justify-between">
      <div class="text-sm text-gray-500">Affichage {{ $users->firstItem() ?? 0 }}‑{{ $users->lastItem() ?? 0 }} sur {{ $users->total() }}</div>
      <div>{{ $users->links() }}</div>
    </div>
  </div>
</div>
@endsection