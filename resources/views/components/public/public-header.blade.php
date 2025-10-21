<nav x-data="{ open: false }" class="w-full border-b bg-white" role="navigation" aria-label="Public navigation">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
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
                    @click="open = !open"
                    :aria-expanded="open ? 'true' : 'false'"
                    aria-controls="public-nav-mobile"
                    class="p-2 rounded-md text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0078B7]"
                    aria-label="Toggle navigation"
                >
                    <svg x-show="!open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div id="public-nav-mobile" x-show="open" x-cloak x-transition class="md:hidden border-t bg-white">
        <div class="px-4 py-3 space-y-2">
            <a href="{{ url('/') }}"
               class="block px-3 py-2 rounded-md text-base transition-colors duration-150 focus:outline-none focus:ring-0 {{ request()->is('/') ? 'font-semibold text-[#0078B7] bg-gray-50' : 'text-gray-700 hover:text-[#0078B7] hover:bg-gray-50 hover:border-b-2 hover:border-gray-200' }}"
               aria-current="{{ request()->is('/') ? 'page' : '' }}">
                Accueil
            </a>

            <a href="{{ route('about') }}"
               class="block px-3 py-2 rounded-md text-base transition-colors duration-150 focus:outline-none focus:ring-0 {{ request()->routeIs('about') ? 'font-semibold text-[#0078B7] bg-gray-50' : 'text-gray-700 hover:text-[#0078B7] hover:bg-gray-50 hover:border-b-2 hover:border-gray-200' }}"
               aria-current="{{ request()->routeIs('about') ? 'page' : '' }}">
                À propos
            </a>

            <a href="{{ route('contact') }}"
               class="block px-3 py-2 rounded-md text-base transition-colors duration-150 focus:outline-none focus:ring-0 {{ request()->routeIs('contact') ? 'font-semibold text-[#0078B7] bg-gray-50' : 'text-gray-700 hover:text-[#0078B7] hover:bg-gray-50 hover:border-b-2 hover:border-gray-200' }}"
               aria-current="{{ request()->routeIs('contact') ? 'page' : '' }}">
                Contact
            </a>

            <a href="{{ route('login') }}" class="block w-full mt-2 text-center px-3 py-2 rounded-md text-white bg-[#0078B7] hover:bg-[#006aa0] transition">
                Connexion
            </a>
        </div>
    </div>
</nav>