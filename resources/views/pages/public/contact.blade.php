<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Contact — {{ config('app.name') }}</title>
    <meta name="description" content="Contactez Mbenda Gest — support, assistance et informations commerciales pour démarrer une tontine ou utiliser nos services d'épargne.">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:title" content="Contact — {{ config('app.name') }}">
    <meta property="og:description" content="Contactez Mbenda Gest — support, assistance et informations commerciales pour démarrer une tontine ou utiliser nos services d'épargne.">
    <meta property="og:image" content="{{ asset('icon-192x192.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('icon-192x192.png') }}">
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- PWA -->
    <link rel="manifest" href="/manifest.json" />
    <meta name="theme-color" content="#0078B7" />
    <link rel="apple-touch-icon" href="/icon-192x192.png" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{
            --mb-primary: #0078B7;
            --mb-secondary: #7FBC47;
            --mb-tertiary: #F7A52C;
        }
    </style>
</head>
<body class="antialiased bg-white text-gray-900">

    <header>
        <x-public.public-header />
    </header>

    <main class="min-h-screen">
        {{-- 01 - Hero --}}
        <section id="contact-hero">
            <x-public.contact.hero />
        </section>

        {{-- 02 - Coordonnées et formulaire --}}
        <section id="coordonnees-formulaire">
            <x-public.contact.panel />
        </section>

        {{-- 03 - Où nous trouver --}}
        <section id="ou-nous-trouver">
            <x-public.contact.map-section />
        </section>

        {{-- 04 - Assistance immédiate --}}
        <section id="assistance-immediate">
            <x-public.contact.assistance />
        </section>
    </main>

    <footer>
        <x-public.public-footer />
    </footer>

    {{-- Bouton PWA --}}
    <x-public.pwa-button />

</body>
</html>