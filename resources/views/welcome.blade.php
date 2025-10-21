<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ config('app.name', 'Mbenda Gest') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Vite (Tailwind + JS) -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

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

    {{-- Header (public, non-authenticated) --}}
    <header>
        <x-public-header />
    </header>

    <main class="flex-1">
        {{-- 01 - Hero --}}
        <section id="hero" class="py-12">
            <x-accueil.hero />
        </section>

        {{-- 02 - Les chiffres --}}
        <section id="les-chiffres" class="py-12 bg-gray-50">
            <x-accueil.chiffres />
        </section>

        {{-- 03 - Pourquoi nous choisir --}}
        <section id="pourquoi-nous-choisir" class="py-12">
            <x-accueil.pourquoi-choisir />
        </section>

        {{-- 04 - Comment ça marche --}}
        <section id="comment-ca-marche" class="py-12 bg-gray-50">
            <x-accueil.comment-ca-marche />
        </section>

        {{-- 05 - Plus-value et exemple --}}
        <section id="plus-value-exemple" class="py-12">
            <x-accueil.plus-value-exemple />
        </section>

        {{-- 06 - Témoignages --}}
        <section id="temoignages" class="py-12 bg-gray-50">
            <x-accueil.temoignages />
        </section>

        {{-- 07 - Pour épargner avec nous --}}
        <section id="epargner-avec-nous" class="py-12">
            <x-accueil.epargner-avec-nous />
        </section>

        {{-- 08 - Call to Action --}}
        <section id="call-to-action" class="py-12 bg-gray-50">
            <x-accueil.cta />
        </section>
    </main>

    {{-- Footer --}}
    <footer>
        <x-public-footer />
    </footer>

</body>
</html>
