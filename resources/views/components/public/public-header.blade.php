<nav data-public-header class="bg-white/85 backdrop-blur-sm border-b border-gray-100 shadow-sm">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
    <div class="flex items-center gap-3">
      <a href="{{ url('/') }}" class="flex items-center">
        <img src="{{ asset('images/logo.png') }}" alt="Mbenda Gest" class="h-8 md:h-9 lg:h-10 w-auto object-contain">
      </a>
    </div>

    <!-- Desktop links -->
    <div class="hidden md:flex items-center space-x-4">
      <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'font-semibold text-[#0078B7]' : 'text-gray-700 hover:text-[#0078B7]' }}">Accueil</a>
      <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'font-semibold text-[#0078B7]' : 'text-gray-700 hover:text-[#0078B7]' }}">À propos</a>
      <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'font-semibold text-[#0078B7]' : 'text-gray-700 hover:text-[#0078B7]' }}">Contact</a>
    </div>

    <div class="hidden md:flex items-center">
      <a href="{{ route('login') }}" class="ml-4 inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-[#0078B7] hover:bg-[#006aa0]">Connexion</a>
    </div>

    <!-- Mobile: hamburger -->
    <div class="md:hidden flex items-center">
      <button id="public-mobile-toggle" aria-expanded="false" aria-controls="public-nav-mobile"
              class="p-2 rounded-md text-gray-600 hover:bg-gray-100 focus:outline-none"
              aria-label="Toggle navigation">
        <svg data-menu-icon="open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        <svg data-menu-icon="close" class="h-6 w-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
  </div>

  <!-- Mobile menu (positionné sous le header fixe via CSS var) -->
  <div id="public-nav-mobile" class="md:hidden border-t bg-white hidden" style="position:fixed; left:0; right:0; z-index:45; top:var(--public-header-height,64px);">
    <div class="px-4 py-3 space-y-2">
      <a href="{{ url('/') }}" class="block px-3 py-2 text-center rounded-md text-base {{ request()->is('/') ? 'font-semibold text-[#0078B7]' : 'text-gray-700 hover:text-[#0078B7]' }}">Accueil</a>
      <a href="{{ route('about') }}" class="block px-3 py-2 text-center rounded-md text-base {{ request()->routeIs('about') ? 'font-semibold text-[#0078B7]' : 'text-gray-700 hover:text-[#0078B7]' }}">À propos</a>
      <a href="{{ route('contact') }}" class="block px-3 py-2 text-center rounded-md text-base {{ request()->routeIs('contact') ? 'font-semibold text-[#0078B7]' : 'text-gray-700 hover:text-[#0078B7]' }}">Contact</a>
      <a href="{{ route('login') }}" class="block w-full mt-2 text-center px-3 py-2 rounded-md text-white bg-[#0078B7]">Connexion</a>
    </div>
  </div>
</nav>

<style>
  :root { --public-header-height: 64px; }

  /* Header fixe */
  [data-public-header] { position: fixed; inset-inline: 0; top: 0; z-index: 50; }
  body > main { padding-top: var(--public-header-height); }
  #public-nav-mobile { top: var(--public-header-height); }
</style>

<script>
  (function () {
    const nav = document.querySelector('[data-public-header]');
    const btn  = document.getElementById('public-mobile-toggle');
    const menu = document.getElementById('public-nav-mobile');
    const icoOpen  = btn.querySelector('[data-menu-icon="open"]');
    const icoClose = btn.querySelector('[data-menu-icon="close"]');
    let isOpen = false;

    function setMenu(open) {
      isOpen = open;
      btn.setAttribute('aria-expanded', open ? 'true' : 'false');
      if (open) {
        menu.classList.remove('hidden');
        icoOpen.classList.add('hidden');
        icoClose.classList.remove('hidden');
      } else {
        menu.classList.add('hidden');
        icoOpen.classList.remove('hidden');
        icoClose.classList.add('hidden');
      }
    }

    // Toggle
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      setMenu(!isOpen);
    });

    // Fermer après clic d’un lien
    menu.addEventListener('click', function (e) {
      if (e.target.closest('a')) setMenu(false);
    });

    // Initial calc + observer pour recalculer si la hauteur change
    setFixedHeader();
    if ('ResizeObserver' in window && nav) {
      new ResizeObserver(() => updateHeaderHeight()).observe(nav);
    } else {
      window.addEventListener('resize', updateHeaderHeight);
    }
  })();
</script>