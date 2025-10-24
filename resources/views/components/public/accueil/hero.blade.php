<section class="relative min-h-[60vh] md:min-h-[75vh] mt-0 pt-0">
  <!-- Optional subtle background (keeps design neutral, doesn't change structure) -->
  <div class="absolute inset-0 pointer-events-none" style="background: linear-gradient(to bottom, rgba(0,120,183,0.06), rgba(0,120,183,0.02));"></div>

  <div class="relative z-10 max-w-7xl mx-auto px-6 py-12 md:py-20">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
      <!-- LEFT: contenu (texte + boutons + points forts) -->
      <div class="text-center lg:text-left">

        <h1 class="mt-6 text-4xl md:text-5xl lg:text-5xl font-extrabold leading-tight text-[var(--mb-primary)]">
          Épargnez chaque jour,
          <span class="block text-[#7FBC47]">réalisez vos rêves demain</span>
        </h1>

        <p class="mt-4 max-w-3xl text-gray-600 text-lg">
          Avec Mbenda Gest, transformez vos petites économies quotidiennes en grandes réalisations.
          Une épargne simple, sécurisée et adaptée à votre rythme de vie.
        </p>

        <div class="mt-8 flex flex-col sm:flex-row items-center lg:items-start justify-center lg:justify-start gap-4">
          <a href="{{ route('contact') }}" class="inline-flex items-center gap-3 px-6 py-3 rounded-full text-white font-medium shadow" style="background: var(--mb-primary)">
            Commencer à épargner
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
          </a>

          <a href="{{ url('/about') }}" class="inline-flex items-center gap-3 px-6 py-3 rounded-full border bg-white text-gray-800 font-medium shadow-sm">
            Découvrir notre histoire
          </a>
        </div>

        <!-- points forts -->
        <div class="mt-8 flex flex-wrap items-center gap-6 text-sm text-gray-600 justify-center lg:justify-start">
          <div class="flex items-center gap-2">
            <span class="w-7 h-7 flex items-center justify-center rounded-full bg-[rgba(127,188,71,0.12)] text-[var(--mb-secondary)]">✓</span>
            <span>Sans engagement</span>
          </div>
          <div class="flex items-center gap-2">
            <span class="w-7 h-7 flex items-center justify-center rounded-full bg-[rgba(0,120,183,0.08)] text-[var(--mb-primary)]">✓</span>
            <span>Collecte à domicile</span>
          </div>
          <div class="flex items-center gap-2">
            <span class="w-7 h-7 flex items-center justify-center rounded-full bg-[rgba(247,165,44,0.08)] text-[var(--mb-tertiary)]">✓</span>
            <span>Agents certifiés</span>
          </div>
        </div>
      </div>

      <!-- RIGHT: image illustrative (responsive) -->
      <div class="flex items-center justify-center">
        <figure class="w-full max-w-md bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
          <img
            src="{{ asset('images/hero-bg.jpg') }}"
            alt="Illustration Mbenda Gest"
            class="w-full h-auto object-cover block"
          >
        </figure>
      </div>
    </div>
  </div>
</section>