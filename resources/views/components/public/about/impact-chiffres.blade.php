<section aria-labelledby="impact-chiffres" class="py-20" style="background: linear-gradient(180deg, rgba(0,120,183,1) 0%, rgba(0,86,140,1) 100%);">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <h2 id="impact-chiffres" class="text-3xl md:text-4xl font-extrabold text-white mb-8">Notre impact en chiffres</h2>

    <div class="mt-10 grid grid-cols-1 sm:grid-cols-3 gap-8 items-start">
      <div class="flex flex-col items-center">
        <div class="p-4 rounded-lg bg-white/10 mb-4">
          <!-- trend up icon -->
          <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 17l6-6 4 4 8-8"/></svg>
        </div>

        <div class="text-white font-extrabold text-4xl md:text-5xl">+150M</div>
        <div class="mt-2 text-white/80">Francs épargnés</div>
      </div>

      <div class="flex flex-col items-center">
        <div class="p-4 rounded-lg bg-white/10 mb-4">
          <!-- users icon -->
          <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20v-2a4 4 0 00-3-3.87M7 20v-2a4 4 0 013-3.87M12 12a4 4 0 100-8 4 4 0 000 8z"/></svg>
        </div>

        <div class="text-white font-extrabold text-4xl md:text-5xl">5000+</div>
        <div class="mt-2 text-white/80">Clients actifs</div>
      </div>

      <div class="flex flex-col items-center">
        <div class="p-4 rounded-lg bg-white/10 mb-4">
          <!-- ribbon / award icon -->
          <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 21l5-3 5 3V5a2 2 0 00-2-2H9a2 2 0 00-2 2z"/></svg>
        </div>

        <div class="text-white font-extrabold text-4xl md:text-5xl">98%</div>
        <div class="mt-2 text-white/80">Satisfaction</div>
      </div>
    </div>
  </div>

  <style>
    /* small visual tweak to match charte : use CSS variables from welcome */
    #impact-chiffres { /* nothing here - heading styled with utility classes */ }

    /* make icons slightly elevated on hover for subtle feedback */
    [aria-labelledby="impact-chiffres"] .grid > div:hover svg {
      transform: translateY(-4px);
      transition: transform .25s ease;
    }
  </style>
</section>