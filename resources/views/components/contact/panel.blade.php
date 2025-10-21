<section class="relative -mt-16 pb-16">
    <div class="max-w-7xl mx-auto px-6">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden grid grid-cols-1 md:grid-cols-2 gap-0">
            <!-- Left: contact card -->
            <div class="p-8 bg-[var(--mb-primary)] text-white flex flex-col justify-between">
                <div>
                    <h2 class="text-2xl font-bold">Nos coordonnées</h2>
                    <p class="mt-3 text-sm text-white/90">Contactez notre équipe pour toute question concernant Mbenda Gest.</p>

                    <ul class="mt-6 space-y-4 text-sm">
                        <li class="flex items-start gap-3">
                            <!-- Mail icon -->
                            <svg class="w-6 h-6 flex-shrink-0 text-white/90" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8.5v7A2.5 2.5 0 005.5 18h13a2.5 2.5 0 002.5-2.5v-7A2.5 2.5 0 0018.5 6h-13A2.5 2.5 0 003 8.5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.5l-9 6-9-6" />
                            </svg>
                            <div>
                                <div class="font-medium">Email</div>
                                <div class="text-white/90 text-sm">contact@mbendagest.com</div>
                            </div>
                        </li>

                        <li class="flex items-start gap-3">
                            <!-- Phone icon (coherent with other icons) -->
                            <svg class="w-6 h-6 flex-shrink-0 text-white/90" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.654 2.347a2.25 2.25 0 00-1.99.518L1.08 3.457a2.25 2.25 0 00-.12 3.162l2.2 2.7a2.25 2.25 0 001.98.83c.84-.06 1.73-.12 2.6.18.86.3 1.82.9 2.98 2.06s1.76 2.12 2.06 2.98c.3.87.24 1.76.18 2.6a2.25 2.25 0 00.83 1.98l2.7 2.2a2.25 2.25 0 003.162-.12l.592-.584a2.25 2.25 0 00.518-1.99c-.48-3.06-1.92-6.45-5.24-9.77C10.104 4.27 6.715 2.83 3.654 2.347z"/>
                            </svg>
                            <div>
                                <div class="font-medium">Téléphone</div>
                                <div class="text-white/90 text-sm">066083193 / 077402098</div>
                            </div>
                        </li>

                        <li class="flex items-start gap-3">
                            <!-- Map pin icon -->
                            <svg class="w-6 h-6 flex-shrink-0 text-white/90" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 6-7.5 10.5-7.5 10.5S4.5 16.5 4.5 10.5a7.5 7.5 0 0115 0z" />
                            </svg>
                            <div>
                                <div class="font-medium">Adresse</div>
                                <div class="text-white/90 text-sm">Moanda — Gabon</div>
                            </div>
                        </li>

                        <li class="flex items-start gap-3">
                            <!-- Globe / Website icon (coherent stroke style) -->
                            <svg class="w-6 h-6 flex-shrink-0 text-white/90" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 2a10 10 0 100 20 10 10 0 000-20z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2 12h20M12 2c3.5 5 3.5 9 0 14M12 2c-3.5 5-3.5 9 0 14" />
                            </svg>
                            <div>
                                <div class="font-medium">Site Web</div>
                                <div class="text-white/90 text-sm"><a href="https://mbendagest.com" target="_blank" rel="noopener" class="text-white/90 hover:text-white no-underline">mbendagest.com</a></div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Right: form (statique) -->
            <div class="p-8">
                <h3 class="text-xl font-semibold text-gray-800">Envoyez-nous un message</h3>
                <p class="mt-2 text-sm text-gray-600">Le formulaire est statique pour l'instant. Pour activer l'envoi, connectez‑le au backend.</p>

                <form class="mt-6 grid grid-cols-1 gap-4">
                    <div class="grid sm:grid-cols-2 gap-4">
                        <input type="text" readonly placeholder="Prénom" class="w-full p-3 border rounded bg-gray-50 text-gray-600" />
                        <input type="text" readonly placeholder="Nom" class="w-full p-3 border rounded bg-gray-50 text-gray-600" />
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <input type="email" readonly placeholder="Email" class="w-full p-3 border rounded bg-gray-50 text-gray-600" />
                        <input type="text" readonly placeholder="Téléphone" class="w-full p-3 border rounded bg-gray-50 text-gray-600" />
                    </div>

                    <textarea rows="6" readonly placeholder="Votre message" class="w-full p-3 border rounded bg-gray-50 text-gray-600"></textarea>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Formulaire désactivé</span>
                        <button type="button" disabled class="px-6 py-2 rounded-md text-white bg-[var(--mb-primary)] opacity-60 cursor-not-allowed">Envoyer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>