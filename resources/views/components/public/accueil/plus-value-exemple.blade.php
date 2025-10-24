<section class="py-16 bg-white">
  <div class="max-w-7xl mx-auto px-6">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
      <!-- LEFT: texte + checklist -->
      <div class="text-left">
        <h2 id="plus-value-exemple" class="text-3xl md:text-4xl font-extrabold text-[var(--mb-primary)]">
        L'épargne quotidienne qui <span class="text-[#7FBC47]">change des vies</span>
      </h2>
        <p class="mt-4 text-gray-600 max-w-xl">Mbenda Gest n'est pas juste un service d'épargne, c'est un partenaire de confiance qui vous accompagne dans la réalisation de vos projets, petits et grands.</p>

        <ul class="mt-8 space-y-4">
          <li class="flex items-start gap-3">
            <span class="mt-1 inline-flex items-center justify-center w-8 h-8 rounded-full bg-[rgba(127,188,71,0.12)] text-[var(--mb-secondary)]">✓</span>
            <span class="text-gray-700">Collecte quotidienne directement chez vous ou à votre commerce</span>
          </li>
          <li class="flex items-start gap-3">
            <span class="mt-1 inline-flex items-center justify-center w-8 h-8 rounded-full bg-[rgba(127,188,71,0.12)] text-[var(--mb-secondary)]">✓</span>
            <span class="text-gray-700">Aucun frais cachés - Commissions transparentes</span>
          </li>
          <li class="flex items-start gap-3">
            <span class="mt-1 inline-flex items-center justify-center w-8 h-8 rounded-full bg-[rgba(127,188,71,0.12)] text-[var(--mb-secondary)]">✓</span>
            <span class="text-gray-700">Cycle d'épargne de 31 jours adapté à vos revenus</span>
          </li>
          <li class="flex items-start gap-3">
            <span class="mt-1 inline-flex items-center justify-center w-8 h-8 rounded-full bg-[rgba(127,188,71,0.12)] text-[var(--mb-secondary)]">✓</span>
            <span class="text-gray-700">Agents formés et de confiance dans votre quartier</span>
          </li>
          <li class="flex items-start gap-3">
            <span class="mt-1 inline-flex items-center justify-center w-8 h-8 rounded-full bg-[rgba(127,188,71,0.12)] text-[var(--mb-secondary)]">✓</span>
            <span class="text-gray-700">Historique complet de toutes vos économies</span>
          </li>
          <li class="flex items-start gap-3">
            <span class="mt-1 inline-flex items-center justify-center w-8 h-8 rounded-full bg-[rgba(127,188,71,0.12)] text-[var(--mb-secondary)]">✓</span>
            <span class="text-gray-700">Flexibilité de retrait selon vos besoins urgents</span>
          </li>
        </ul>

        <div class="mt-8 flex flex-wrap gap-4">
          <a href="{{ route('contact') }}" class="inline-block px-5 py-3 rounded-full bg-[var(--mb-primary)] text-white font-medium shadow">Parler à un agent</a>
          <a href="{{ route('about') }}" class="inline-block px-5 py-3 rounded-full border border-gray-200 text-gray-700 bg-white">En savoir plus</a>
        </div>
      </div>

      <!-- RIGHT: carte exemple d'épargne -->
      <aside class="w-full">
        <div class="relative bg-white rounded-2xl border border-gray-100 shadow-lg overflow-hidden" style="min-width:320px;">
          <!-- soft decorative gradient -->
          <div class="absolute -inset-6 -z-10 bg-gradient-to-br from-[rgba(0,120,183,0.06)] via-[rgba(127,188,71,0.03)] to-white opacity-90 rounded-2xl"></div>

          <div class="px-8 py-10">
            <h3 class="text-center text-xl font-semibold text-[var(--mb-primary)]">Exemple d'épargne</h3>
            <p class="text-center text-sm text-gray-500 mt-1">Cycle de 31 jours</p>

            <div class="mt-8 space-y-4">
              <div class="flex items-center justify-between bg-gray-50 rounded-lg p-4 border border-gray-100">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-md bg-white flex items-center justify-center text-[var(--mb-primary)] border border-gray-100">
                    <!-- small icon -->
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 11h18M7 7v10M17 7v10"/></svg>
                  </div>
                  <div class="text-sm text-gray-700">Épargne quotidienne</div>
                </div>
                <div class="text-lg font-bold text-[var(--mb-primary)]">1.000 F</div>
              </div>

              <div class="flex items-center justify-between bg-gray-50 rounded-lg p-4 border border-gray-100">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-md bg-white flex items-center justify-center text-[var(--mb-secondary)] border border-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8h18M8 3v10M16 3v10"/></svg>
                  </div>
                  <div class="text-sm text-gray-700">Durée du cycle</div>
                </div>
                <div class="text-lg font-bold text-[var(--mb-secondary)]">31 jours</div>
              </div>

              <div class="rounded-lg p-4" style="background: linear-gradient(90deg, var(--mb-primary), #0071d6);">
                <div class="flex items-center justify-between text-white">
                  <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h4l3 8 4-16 3 8h4"/></svg>
                    <div class="text-sm">Vous recevez</div>
                  </div>
                  <div class="text-2xl font-extrabold">30.000 F</div>
                </div>
              </div>
            </div>

            <div class="mt-6 text-center text-xs text-gray-500">
              Commission de service: 1 jour d'épargne · <samp class="text-[var(--mb-primary)]">Transparent et équitable</samp>
            </div>
          </div>
        </div>
      </aside>
    </div>
  </div>
</section>