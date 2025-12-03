<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ config('app.name', 'Mbenda Gest') }} - À propos</title>
    <meta name="description" content="À propos de Mbenda Gest — notre mission, nos valeurs et notre parcours pour faciliter l'épargne quotidienne au Gabon.">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:title" content="À propos — {{ config('app.name') }}">
    <meta property="og:description" content="À propos de Mbenda Gest — notre mission, nos valeurs et notre parcours pour faciliter l'épargne quotidienne au Gabon.">
    <meta property="og:image" content="{{ asset('icon-192x192.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('icon-192x192.png') }}">
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- PWA -->
    <link rel="manifest" href="/manifest.json" />
    <meta name="theme-color" content="#0078B7" />
    <link rel="apple-touch-icon" href="/icon-192x192.png" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --mb-primary: #0078B7;
            --mb-secondary: #7FBC47;
            --mb-tertiary: #F7A52C;
        }
        html,body { height:100%; }
    </style>
</head>
<body class="antialiased bg-white text-gray-900 min-h-screen flex flex-col">
    <header>
        <x-public.public-header />
    </header>

    <main class="flex-1">
        <section id="hero">
            <x-public.about.hero />
        </section>

        <section id="mission-vision">
            <x-public.about.mission-vision />
        </section>

        <section id="nos-valeurs">
            <x-public.about.nos-valeurs />
        </section>

        <section id="notre-parcours">
            <x-public.about.notre-parcours />
        </section>

        <section id="impact-chiffres">
            <x-public.about.impact-chiffres />
        </section>

        <section id="cta" bg-gray-50">
            <x-public.about.cta />
        </section>
    </main>

    <footer>
        <x-public.public-footer />
    </footer>

    {{-- Bouton PWA --}}
    <x-public.pwa-button />

</body>
</html>