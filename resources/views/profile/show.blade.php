@extends(auth()->user()?->role === 'admin' ? 'layouts.app-admin' : 'layouts.app-agent')

@section('title', 'Profil')
@section('page_title', 'Profil')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

  <!-- Breadcrumb -->
  <nav class="flex items-center gap-2 text-sm text-gray-600">
    <a href="{{ auth()->user()?->role === 'admin' ? route('admin.dashboard') : route('agent.dashboard') }}" class="hover:text-gray-900 transition-colors">
      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
        <polyline points="9 22 9 12 15 12 15 22"/>
      </svg>
    </a>
    <svg class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="9 18 15 12 9 6"/>
    </svg>
    <span class="font-medium text-gray-900">Mon Profil</span>
  </nav>

  <!-- Header Card with Profile Picture -->
  <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <!-- Cover Background -->
    <div class="h-32 sm:h-40" style="background: linear-gradient(135deg, var(--mb-primary) 0%, var(--mb-secondary) 100%);">
    </div>
    
    <!-- Profile Content -->
    <div class="px-6 pb-6">
      <div class="sm:flex sm:items-end sm:space-x-5">
        <!-- Avatar -->
        <div class="flex -mt-16 sm:-mt-20">
          <div class="relative">
            <div class="w-32 h-32 sm:w-40 sm:h-40 rounded-full overflow-hidden border-4 border-white bg-white shadow-lg">
              <img class="w-full h-full object-cover"
                   src="{{ $user->photo_profil ? Storage::url($user->photo_profil) . '?t=' . ($user->updated_at ? $user->updated_at->timestamp : time()) : asset('images/default-avatar.png') }}"
                   alt="{{ $user->first_name }} {{ $user->last_name }}">
            </div>
            <!-- Status Badge -->
            <div class="absolute bottom-2 right-2 w-6 h-6 rounded-full border-4 border-white {{ $user->active ? 'bg-green-500' : 'bg-red-500' }} shadow-lg"></div>
          </div>
        </div>

        <!-- Name and Actions -->
        <div class="mt-6 sm:flex-1 sm:min-w-0 sm:flex sm:items-center sm:justify-end sm:space-x-6 sm:pb-1">
          <div class="flex-1 min-w-0 mt-6 sm:block sm:mt-0">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 truncate">
              {{ $user->first_name ?? '—' }} {{ $user->last_name ?? '—' }}
            </h1>
            <div class="flex items-center gap-3 mt-2 flex-wrap">
              <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                  <circle cx="12" cy="7" r="4"/>
                </svg>
                {{ ucfirst($user->role ?? 'Agent') }}
              </span>
              @if(!empty($user->is_super_admin))
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-semibold bg-gradient-to-r from-yellow-400 to-orange-400 text-white shadow-sm">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                  </svg>
                  Super Admin
                </span>
              @endif
              <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium {{ $user->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                <span class="w-2 h-2 rounded-full {{ $user->active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                {{ $user->active ? 'Actif' : 'Désactivé' }}
              </span>
            </div>
          </div>

          <!-- Edit Button -->
          @if(auth()->user()?->role === 'admin')
            <div class="mt-6 flex flex-col sm:mt-0 sm:flex-row sm:flex-wrap sm:space-x-3">
              <a href="{{ route('profile.edit') }}"
                 class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white border-2 border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all shadow-sm hover:shadow-md">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Modifier le profil
              </a>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Information Cards Grid -->
  <div class="grid gap-6 md:grid-cols-2">
    
    <!-- Personal Information Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="px-6 py-4" style="background: linear-gradient(135deg, var(--mb-primary) 0%, #005f8d 100%);">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
            <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
              <circle cx="12" cy="7" r="4"/>
            </svg>
          </div>
          <div>
            <h2 class="text-lg font-semibold text-white">Informations personnelles</h2>
            <p class="text-sm text-blue-100">Identité et coordonnées</p>
          </div>
        </div>
      </div>
      <div class="p-6 space-y-4">
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Nom complet</div>
            <div class="mt-1 text-sm font-medium text-gray-900">{{ $user->first_name ?? '—' }} {{ $user->last_name ?? '—' }}</div>
          </div>
        </div>

        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Adresse email</div>
            <div class="mt-1 text-sm font-medium text-gray-900 break-all">{{ $user->email ?? '—' }}</div>
          </div>
        </div>

        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Téléphone</div>
            <div class="mt-1 text-sm font-medium text-gray-900">{{ $user->phone ?? '—' }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- System Information Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="px-6 py-4" style="background: linear-gradient(135deg, var(--mb-secondary) 0%, #6ba83a 100%);">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
            <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
              <line x1="8" y1="21" x2="16" y2="21"/>
              <line x1="12" y1="17" x2="12" y2="21"/>
            </svg>
          </div>
          <div>
            <h2 class="text-lg font-semibold text-white">Informations système</h2>
            <p class="text-sm text-green-100">Compte et paramètres</p>
          </div>
        </div>
      </div>
      <div class="p-6 space-y-4">
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Rôle utilisateur</div>
            <div class="mt-1">
              <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-semibold bg-indigo-100 text-indigo-800">
                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ ucfirst($user->role ?? 'Agent') }}
              </span>
            </div>
          </div>
        </div>

        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Couleur d'identification</div>
            <div class="mt-1 flex items-center gap-3">
              <span class="inline-block w-10 h-10 rounded-lg border-2 border-gray-200 shadow-sm" style="background: {{ $user->color_hex ?? '#E5E7EB' }};"></span>
              <span class="text-sm font-mono font-medium text-gray-900 bg-gray-100 px-3 py-1 rounded-lg">{{ $user->color_hex ?? '—' }}</span>
            </div>
          </div>
        </div>

        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-500 uppercase tracking-wider">Dernière connexion</div>
            <div class="mt-1 text-sm font-medium text-gray-900">
              {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y à H:i') : 'Jamais' }}
            </div>
            @if($user->last_login_at)
              <div class="text-xs text-gray-500 mt-0.5">{{ $user->last_login_at->diffForHumans() }}</div>
            @endif
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Account Statistics (optional, can be extended) -->
  <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-gray-200 flex items-center justify-center">
          <svg class="w-5 h-5 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="1" x2="12" y2="23"/>
            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
          </svg>
        </div>
        <div>
          <h2 class="text-lg font-semibold text-gray-900">Informations du compte</h2>
          <p class="text-sm text-gray-600">Dates importantes et statut</p>
        </div>
      </div>
    </div>
    <div class="p-6">
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="text-center p-4 bg-blue-50 rounded-lg">
          <div class="text-xs text-blue-600 font-medium uppercase tracking-wider mb-2">Création du compte</div>
          <div class="text-lg font-bold text-blue-900">{{ $user->created_at ? $user->created_at->format('d/m/Y') : '—' }}</div>
          @if($user->created_at)
            <div class="text-xs text-blue-600 mt-1">{{ $user->created_at->diffForHumans() }}</div>
          @endif
        </div>
        
        <div class="text-center p-4 bg-green-50 rounded-lg">
          <div class="text-xs text-green-600 font-medium uppercase tracking-wider mb-2">Statut du compte</div>
          <div class="text-lg font-bold {{ $user->active ? 'text-green-900' : 'text-red-900' }}">
            {{ $user->active ? '✓ Actif' : '✗ Désactivé' }}
          </div>
          <div class="text-xs text-green-600 mt-1">{{ $user->active ? 'Compte opérationnel' : 'Accès restreint' }}</div>
        </div>
        
        <div class="text-center p-4 bg-purple-50 rounded-lg">
          <div class="text-xs text-purple-600 font-medium uppercase tracking-wider mb-2">Dernière mise à jour</div>
          <div class="text-lg font-bold text-purple-900">{{ $user->updated_at ? $user->updated_at->format('d/m/Y') : '—' }}</div>
          @if($user->updated_at)
            <div class="text-xs text-purple-600 mt-1">{{ $user->updated_at->diffForHumans() }}</div>
          @endif
        </div>
      </div>
    </div>
  </div>

</div>
@endsection