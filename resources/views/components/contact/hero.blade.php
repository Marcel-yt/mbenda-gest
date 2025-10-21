<section class="relative bg-center bg-cover min-h-[60vh] md:min-h-[75vh]" style="background-image: url('{{ asset('images/contact-hero.jpg') }}');">
    {{-- Overlay couleur au-dessus de l'image --}}
    <div class="absolute inset-0" style="background-color: rgba(0,120,183,0.75);"></div>

    {{-- Contenu au-dessus de l'overlay --}}
    <div class="relative z-10 max-w-7xl mx-auto px-6 py-32 md:py-36 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold text-white drop-shadow-md">Nous contacter</h1>

        <p class="mt-4 max-w-4xl mx-auto text-white/90 text-lg">
            Nous accompagnons chaque client dans la gestion de ses tontines quotidiennes, avec un suivi fiable et automatisé. Contactez notre équipe pour en savoir plus sur nos solutions d’épargne sécurisées.
        </p>
    </div>

    {{-- Optionnel: léger dégradé vers le bas pour meilleure lisibilité sur sections suivantes --}}
    <div class="pointer-events-none absolute inset-x-0 bottom-0 h-24 bg-gradient-to-b from-transparent to-white/80 dark:to-black/80"></div>
</section>