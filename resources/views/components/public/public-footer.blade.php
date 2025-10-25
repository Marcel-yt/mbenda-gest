<footer data-public-footer class="pt-12 pb-8">
  <div class="max-w-7xl mx-auto px-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
      <!-- Brand / description -->
      <div>
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-4 ">
          {{-- logo small --}}
          <img src="{{ asset('images/logo2.png') }}" alt="Mbenda Gest" class="w-36 h-auto object-contain" onerror="this.style.display='none'"/>
          <span class="sr-only">Mbenda Gest</span>
        </a>

        <p class="mt-2 text-sm text-white/90 max-w-sm">
          La solution digitale pour gérer vos collectes journalières en toute simplicité et sécurité.
        </p>
      </div>

      <!-- Navigation -->
      <nav aria-label="Footer navigation" class="pt-1">
        <h4 class="text-white font-semibold mb-4">Navigation</h4>
        <ul class="space-y-3 text-sm">
          <li><a href="{{ route('home') }}" class="text-white/90 hover:text-white transition">Accueil</a></li>
          <li><a href="{{ route('about') }}" class="text-white/90 hover:text-white transition">À propos</a></li>
          <li><a href="{{ route('contact') }}" class="text-white/90 hover:text-white transition">Contact</a></li>
        </ul>
      </nav>

      <!-- Contact -->
      <div class="pt-1">
        <h4 class="text-white font-semibold mb-4">Contact</h4>

        <ul class="space-y-3 text-sm">
          <li class="flex items-center gap-3 text-white/90">
            <svg class="w-6 h-6 flex-shrink-0 text-white/90" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8.5v7A2.5 2.5 0 005.5 18h13a2.5 2.5 0 002.5-2.5v-7A2.5 2.5 0 0018.5 6h-13A2.5 2.5 0 003 8.5z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.5l-9 6-9-6" />
            </svg>
            <a href="mailto:contact@mbendagest.com" class="hover:underline">contact@mbendagest.com</a>
          </li>

          <li class="flex items-center gap-3 text-white/90">
            <svg class="w-6 h-6 flex-shrink-0 text-white/90" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.654 2.347a2.25 2.25 0 00-1.99.518L1.08 3.457a2.25 2.25 0 00-.12 3.162l2.2 2.7a2.25 2.25 0 001.98.83c.84-.06 1.73-.12 2.6.18.86.3 1.82.9 2.98 2.06s1.76 2.12 2.06 2.98c.3.87.24 1.76.18 2.6a2.25 2.25 0 00.83 1.98l2.7 2.2a2.25 2.25 0 003.162-.12l.592-.584a2.25 2.25 0 00.518-1.99c-.48-3.06-1.92-6.45-5.24-9.77C10.104 4.27 6.715 2.83 3.654 2.347z"/>
            </svg>
            <span>066083193 / 077402098</span>
          </li>

          <li class="flex items-center gap-3 text-white/90">
            <svg class="w-6 h-6 flex-shrink-0 text-white/90" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 6-7.5 10.5-7.5 10.5S4.5 16.5 4.5 10.5a7.5 7.5 0 0115 0z" />
            </svg>
            <span>Moanda Gabon</span>
          </li>
        </ul>
      </div>
    </div>

    <div class="mt-8 border-t border-white/10 pt-6 footer-divider">
      <p class="text-center text-sm text-white/80">&copy; {{ date('Y') }} Mbenda Gest. Tous droits réservés - Conçu par Brain Nova (+237 656585444)</p>
    </div>
  </div>

  <style>
    /* plus sombre, dégradé et lisibilité */
    [data-public-footer]{
      background: linear-gradient(180deg, rgba(0,120,183,0.98) 0%, rgba(0,86,140,1) 100%);
      color: #fff;
    }

    [data-public-footer] a { color: rgba(255,255,255,0.95); }
    [data-public-footer] a:hover { color: #ffffff; }

    /* fine séparation */
    [data-public-footer] .footer-divider { border-top-color: rgba(255,255,255,0.06); }

    /* mobile spacing */
    @media (max-width: 767px){
      [data-public-footer] { padding-bottom: 2.5rem; }
      [data-public-footer] img { width: 140px; }
    }
  </style>
</footer>