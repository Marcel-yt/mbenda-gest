<section aria-labelledby="les-chiffres-title" class="py-12" style="background: var(--mb-primary);">
  <div class="max-w-7xl mx-auto px-6">
    <h2 id="les-chiffres-title" class="sr-only">Nos chiffres clés</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8 text-center text-white">
      <div>
        <div class="text-4xl md:text-5xl font-extrabold leading-none js-counter" data-target="5000">0+</div>
        <div class="mt-2 text-sm opacity-90">Clients satisfaits</div>
      </div>

      <div>
        <div class="text-4xl md:text-5xl font-extrabold leading-none js-counter" data-target="50">0+</div>
        <div class="mt-2 text-sm opacity-90">Agents de confiance</div>
      </div>

      <div>
        <div class="text-4xl md:text-5xl font-extrabold leading-none js-counter" data-target="98">0%</div>
        <div class="mt-2 text-sm opacity-90">Taux de satisfaction</div>
      </div>

      <div>
        <div class="text-4xl md:text-5xl font-extrabold leading-none js-counter" data-target="3">0</div>
        <div class="mt-2 text-sm opacity-90">Ans d'expérience</div>
      </div>
    </div>
  </div>

  <script>
    (function(){
      const counters = Array.from(document.querySelectorAll('.js-counter'));
      if(!counters.length) return;
      const io = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
          if(!entry.isIntersecting) return;
          const el = entry.target;
          const raw = el.dataset.target || el.textContent || '0';
          const target = parseInt(raw.replace(/[^\d]/g,''), 10) || 0;
          const suffix = raw.trim().endsWith('%') ? '%' : (raw.trim().endsWith('+') ? '+' : '');
          let start = 0;
          const dur = 1000;
          const stepTime = 16;
          const step = Math.max(1, Math.floor(target / (dur / stepTime)));
          const iv = setInterval(() => {
            start += step;
            if(start >= target){
              el.textContent = target + suffix;
              clearInterval(iv);
            } else {
              el.textContent = start + suffix;
            }
          }, stepTime);
          obs.unobserve(el);
        });
      }, { threshold: 0.6 });
      counters.forEach(c => io.observe(c));
    })();
  </script>
</section>