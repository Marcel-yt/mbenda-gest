<section aria-labelledby="comment-ca-marche-title" class="py-16 bg-white">
  <div class="max-w-7xl mx-auto px-6">
    <header class="text-center mb-10">
      <h2 id="comment-ca-marche" class="text-3xl md:text-4xl font-extrabold text-[var(--mb-primary)]">
        Comment <span class="text-[#7FBC47]">ça marche</span> ?
      </h2>
      <p class="mt-3 text-gray-600 max-w-2xl mx-auto">Un processus simple en 4 étapes pour commencer votre voyage vers l'indépendance financière</p>
    </header>

    <div class="relative">
      <!-- connector line between cards -->
      <div class="hidden lg:block absolute left-10 right-10 top-1/2 h-px" style="background: linear-gradient(90deg, rgba(127,188,71,0.06), rgba(127,188,71,0.18), rgba(127,188,71,0.06)); transform: translateY(-50%); z-index: 0;"></div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 relative z-10">
        <!-- Step 1 -->
        <article class="group bg-white border rounded-xl shadow-sm p-6">
          <div class="relative">
            <div class="absolute -left-6 -top-6 w-12 h-12 rounded-full flex items-center justify-center text-white font-semibold" style="background: var(--mb-primary); box-shadow: 0 6px 18px rgba(0,120,183,0.18);">1</div>
            <h3 class="text-lg font-semibold text-gray-900 pl-10">Inscription Simple</h3>
            <p class="mt-3 text-sm text-gray-600">Rencontrez un de nos agents dans votre quartier ou contactez-nous. L'inscription est rapide et sans paperasse compliquée.</p>
          </div>
        </article>

        <!-- Step 2 -->
        <article class="group bg-white border rounded-xl shadow-sm p-6">
          <div class="relative">
            <div class="absolute -left-6 -top-6 w-12 h-12 rounded-full flex items-center justify-center text-white font-semibold" style="background: var(--mb-primary); box-shadow: 0 6px 18px rgba(0,120,183,0.18);">2</div>
            <h3 class="text-lg font-semibold text-gray-900 pl-10">Définissez Votre Montant</h3>
            <p class="mt-3 text-sm text-gray-600">Choisissez le montant quotidien qui vous convient : 500F, 1000F, 2000F ou plus. Vous décidez selon vos capacités.</p>
          </div>
        </article>

        <!-- Step 3 -->
        <article class="group bg-white border rounded-xl shadow-sm p-6">
          <div class="relative">
            <div class="absolute -left-6 -top-6 w-12 h-12 rounded-full flex items-center justify-center text-white font-semibold" style="background: var(--mb-primary); box-shadow: 0 6px 18px rgba(0,120,183,0.18);">3</div>
            <h3 class="text-lg font-semibold text-gray-900 pl-10">Collecte Régulière</h3>
            <p class="mt-3 text-sm text-gray-600">Chaque jour, votre agent passe vous voir, collecte votre épargne et note dans votre carnet personnel.</p>
          </div>
        </article>

        <!-- Step 4 -->
        <article class="group bg-white border rounded-xl shadow-sm p-6">
          <div class="relative">
            <div class="absolute -left-6 -top-6 w-12 h-12 rounded-full flex items-center justify-center text-white font-semibold" style="background: var(--mb-primary); box-shadow: 0 6px 18px rgba(0,120,183,0.18);">4</div>
            <h3 class="text-lg font-semibold text-gray-900 pl-10">Récupérez Votre Argent</h3>
            <p class="mt-3 text-sm text-gray-600">Après 31 jours, recevez l'intégralité de votre épargne. Recommencez un nouveau cycle quand vous voulez.</p>
          </div>
        </article>
      </div>
    </div>

    <div class="mt-10 text-center">
      <a href="{{ route('contact') }}" class="inline-block px-6 py-3 rounded-full bg-[var(--mb-primary)] text-white font-semibold shadow hover:shadow-lg transition">Démarrer mon épargne maintenant</a>
    </div>
  </div>

  <style>
    /* adjust numbers position on small screens */
    @media (max-width: 1023px) {
      .relative .absolute.-left-6 { left: 1rem; top: -1.5rem; }
    }
  </style>
</section>