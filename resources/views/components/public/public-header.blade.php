<nav data-public-header class="bg-white/85 backdrop-blur-sm border-b border-gray-100 shadow-sm">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}" class="flex items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Mbenda Gest" class="h-8 md:h-9 lg:h-10 w-auto object-contain">
                </a>
            </div>

            <!-- Desktop links -->
            <div class="hidden md:flex items-center space-x-4">
                <a href="{{ url('/') }}"
                   class="{{ 'inline-block px-3 py-2 rounded-md text-sm transition-colors duration-50 focus:outline-none focus:ring-0 ' . (request()->is('/') ? 'font-semibold text-[#0078B7] underline decoration-2 underline-offset-8 decoration-[#0078B7]' : 'text-gray-700 hover:text-[#0078B7] hover:bg-gray-50 hover:border-b-2 hover:border-gray-200') }}"
                   aria-current="{{ request()->is('/') ? 'page' : '' }}">
                    Accueil
                </a>

                <a href="{{ route('about') }}"
                   class="{{ 'inline-block px-3 py-2 rounded-md text-sm transition-colors duration-50 focus:outline-none focus:ring-0 ' . (request()->routeIs('about') ? 'font-semibold text-[#0078B7] underline decoration-2 underline-offset-8 decoration-[#0078B7]' : 'text-gray-700 hover:text-[#0078B7] hover:bg-gray-50 hover:border-b-2 hover:border-gray-200') }}"
                   aria-current="{{ request()->routeIs('about') ? 'page' : '' }}">
                    À propos
                </a>

                <a href="{{ route('contact') }}"
                   class="{{ 'inline-block px-3 py-2 rounded-md text-sm transition-colors duration-50 focus:outline-none focus:ring-0 ' . (request()->routeIs('contact') ? 'font-semibold text-[#0078B7] underline decoration-2 underline-offset-8 decoration-[#0078B7]' : 'text-gray-700 hover:text-[#0078B7] hover:bg-gray-50 hover:border-b-2 hover:border-gray-200') }}"
                   aria-current="{{ request()->routeIs('contact') ? 'page' : '' }}">
                    Contact
                </a>
            </div>

            <div class="hidden md:flex items-center">
                <a href="{{ route('login') }}"
                   class="ml-4 inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-[#0078B7] hover:bg-[#006aa0] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0078B7] transition transform hover:-translate-y-0.5">
                    Connexion
                </a>
            </div>

            <!-- Mobile: hamburger -->
            <div class="md:hidden flex items-center">
                <button
                    data-toggle="mobile-menu"
                    aria-expanded="false"
                    aria-controls="public-nav-mobile"
                    class="p-2 rounded-md text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0078B7]"
                    aria-label="Toggle navigation"
                >
                    <svg data-menu-icon="open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg data-menu-icon="close" class="h-6 w-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div id="public-nav-mobile" x-show="open" x-cloak x-transition class="md:hidden border-t bg-white hidden">
        <div class="px-4 py-3 space-y-2">
            <a href="{{ url('/') }}" class="block px-3 py-2 text-center rounded-md text-base transition-colors duration-150 focus:outline-none focus:ring-0 {{ request()->is('/') ? 'font-semibold text-[#0078B7] bg-gray-50' : 'text-gray-700 hover:text-[#0078B7] hover:bg-gray-50 hover:border-b-2 hover:border-gray-200' }}">Accueil</a>
            <a href="{{ route('about') }}" class="block px-3 py-2 text-center rounded-md text-base transition-colors duration-150 focus:outline-none focus:ring-0 {{ request()->routeIs('about') ? 'font-semibold text-[#0078B7] bg-gray-50' : 'text-gray-700 hover:text-[#0078B7] hover:bg-gray-50 hover:border-b-2 hover:border-gray-200' }}">À propos</a>
            <a href="{{ route('contact') }}" class="block px-3 py-2 text-center rounded-md text-base transition-colors duration-150 focus:outline-none focus:ring-0 {{ request()->routeIs('contact') ? 'font-semibold text-[#0078B7] bg-gray-50' : 'text-gray-700 hover:text-[#0078B7] hover:bg-gray-50 hover:border-b-2 hover:border-gray-200' }}">Contact</a>
            <a href="{{ route('login') }}" class="block w-full mt-2 text-center px-3 py-2 rounded-md text-white bg-[#0078B7] hover:bg-[#006aa0] transition">Connexion</a>
        </div>
    </div>
</nav>

<!-- Responsive behavior: fixe sur desktop uniquement + toggle mobile sans Alpine -->
<style>
  @media (min-width: 768px) {
    body > main { padding-top: var(--public-header-height, 64px); }
  }
</style>
<script>
  (function () {
    const nav = document.querySelector('[data-public-header]');
    const main = document.querySelector('body > main');
    const btn  = nav.querySelector('[data-toggle="mobile-menu"]');
    const menu = document.getElementById('public-nav-mobile');
    const icoOpen  = btn.querySelector('[data-menu-icon="open"]');
    const icoClose = btn.querySelector('[data-menu-icon="close"]');
    const mq = window.matchMedia('(min-width: 768px)');
    let isOpen = false;

    function setDesktopMode() {
      // header fixe
      nav.classList.add('fixed','inset-x-0','top-0','z-50');
      // padding du contenu
      const h = nav.offsetHeight || 64;
      if (main) main.style.paddingTop = h + 'px';
      // fermer le menu si ouvert
      setMenu(false);
    }
    function setMobileMode() {
      // header non fixe
      nav.classList.remove('fixed','inset-x-0','top-0');
      if (main) main.style.paddingTop = '';
    }
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

    // Mode initial + changements de viewport
    function applyMode() { mq.matches ? setDesktopMode() : setMobileMode(); }
    applyMode();
    if (mq.addEventListener) mq.addEventListener('change', applyMode);
    else mq.addListener(applyMode);

    // Recalcule le padding quand la hauteur du header change
    if ('ResizeObserver' in window) {
      new ResizeObserver(() => { if (mq.matches) setDesktopMode(); }).observe(nav);
    } else {
      window.addEventListener('resize', () => { if (mq.matches) setDesktopMode(); });
    }
  })();
</script>