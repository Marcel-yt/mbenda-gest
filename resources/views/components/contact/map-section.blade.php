<section class="py-16 bg-[#f0f7fb]">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <h3 class="text-2xl font-semibold text-[var(--mb-primary)]">Où nous trouver ?</h3>
        <p class="mt-3 text-gray-600 max-w-5xl mx-auto">Notre équipe Mbenda Gest est basée à Moanda, au Gabon, au cœur de la communauté que nous accompagnons chaque jour.
Passez nous voir, ou contactez-nous en ligne pour découvrir comment nous pouvons vous aider à gérer et automatiser vos tontines en toute confiance.</p>

        <div class="mt-8 rounded-lg overflow-hidden shadow-md">
            <div id="mbenda-map" class="w-full h-[480px] rounded-lg bg-gray-100"></div>
        </div>

        <p class="mt-4 text-sm text-gray-500">Localisation : Moanda — Gabon</p>
    </div>

    <!-- Leaflet (CDN sans SRI pour éviter blocage) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
    <script>
    (function () {
        function loadScript(src, cb) {
            var s = document.createElement('script');
            s.src = src;
            s.async = true;
            s.onload = function () { cb(null); };
            s.onerror = function () { cb(new Error('failed to load ' + src)); };
            document.head.appendChild(s);
        }

        function initStaticMap() {
            // Moanda coordinates
            const lat = -1.5667;
            const lon = 13.2667;
            const zoom = 12;

            const el = document.getElementById('mbenda-map');
            if (!el) return;

            // create map with interactions disabled
            const map = L.map(el, {
                center: [lat, lon],
                zoom: zoom,
                dragging: false,
                touchZoom: false,
                scrollWheelZoom: false,
                doubleClickZoom: false,
                boxZoom: false,
                keyboard: false,
                zoomControl: false,
                attributionControl: true
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // simple marker
            L.marker([lat, lon]).addTo(map)
                .bindPopup('<strong>Moanda, Gabon</strong>')
                .openPopup();

            // ensure correct rendering
            setTimeout(() => map.invalidateSize(), 200);
        }

        document.addEventListener('DOMContentLoaded', function () {
            if (window.L && window.L.map) {
                initStaticMap();
                return;
            }
            loadScript('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', function (err) {
                if (err) {
                    console.error('Failed to load Leaflet:', err);
                    var el = document.getElementById('mbenda-map');
                    if (el) el.innerHTML = '<div class="p-6 text-gray-700">Impossible de charger la carte.</div>';
                    return;
                }
                initStaticMap();
            });
        });
    })();
    </script>
</section>