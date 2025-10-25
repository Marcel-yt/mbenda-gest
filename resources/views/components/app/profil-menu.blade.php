@props(['role' => null, 'align' => 'right'])

@php
  $u = Auth::user();
  $allowed = $u && ($role ? strtolower(trim((string)$u->role)) === strtolower($role) : true);

  $first = trim((string)($u->first_name ?? ''));
  $last  = trim((string)($u->last_name ?? ''));
  $initials = strtoupper(
    ($first !== '' ? mb_substr($first, 0, 1) : '') .
    ($last  !== '' ? mb_substr($last, 0, 1)  : '')
  );
  if ($initials === '' && !empty($u?->email)) {
    $initials = strtoupper(mb_substr($u->email, 0, 1));
  }
  // Afficher uniquement le prénom ; le nom est commenté pour référence
  $displayName = $first ?: ($u->email ?? '');
@endphp

@if($allowed)
  <x-dropdown :align="$align" width="48">
    <x-slot name="trigger">
      <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-600 bg-white hover:text-gray-800 focus:outline-none transition">
        <div class="mr-2 h-8 w-8 rounded-full text-white flex items-center justify-center text-xs font-semibold" style="background-color: var(--mb-primary, #0078B7);">
          {{ $initials }}
        </div>
        <div>
          {{ $displayName }}
          {{-- Pour afficher prénom + nom : {{ $first }} {{ $last }} --}}
        </div>
        <div class="ml-1">
          <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </div>
      </button>
    </x-slot>

    <x-slot name="content">
      <x-dropdown-link :href="route('profile.edit')">
        {{ __('Profile') }}
      </x-dropdown-link>

      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <x-dropdown-link :href="route('logout')"
                         onclick="event.preventDefault(); this.closest('form').submit();">
          {{ __('Déconnexion') }}
        </x-dropdown-link>
      </form>
    </x-slot>
  </x-dropdown>
@endif