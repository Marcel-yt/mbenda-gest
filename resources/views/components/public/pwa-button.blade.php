{{-- Bouton d'installation PWA --}}
<button id="pwa-install-btn" class="fixed bottom-6 right-6 bg-[#0078B7] text-white px-5 py-3 rounded-full shadow-2xl hover:bg-[#006aa0] transition-all duration-200 hover:scale-105 z-50 flex items-center gap-2">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
    </svg>
    <span class="font-semibold text-sm">Installer l'app</span>
</button>

<script>
    (function() {
        // Enregistrer le Service Worker
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js').catch(() => {});
        }

        const installBtn = document.getElementById('pwa-install-btn');
        let deferredPrompt;

        // Détection PWA déjà installée
        if (window.matchMedia('(display-mode: standalone)').matches) {
            installBtn.style.display = 'none';
            return;
        }

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            // Le bouton est déjà visible par défaut
        });

        installBtn.addEventListener('click', async () => {
            if (!deferredPrompt) {
                alert('Pour installer l\'application :\n\n• Sur Chrome/Edge : Menu (⋮) → Installer l\'application\n• Sur Safari iOS : Partager → Ajouter à l\'écran d\'accueil');
                return;
            }
            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;
            deferredPrompt = null;
            if (outcome === 'accepted') {
                installBtn.style.display = 'none';
            }
        });

        window.addEventListener('appinstalled', () => {
            installBtn.style.display = 'none';
        });
    })();
</script>
