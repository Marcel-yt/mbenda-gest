<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ config('app.name', 'Mbenda Gest') }} - À propos</title>
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
    <header>
        <x-public-header />
    </header>

    <main class="flex-1">
        <section id="hero" class="py-12">
            <x-about.hero />
        </section>

        <section id="mission-vision" class="py-12 bg-gray-50">
            <x-about.mission-vision />
        </section>

        <section id="nos-valeurs" class="py-12">
            <x-about.nos-valeurs />
        </section>

        <section id="notre-parcours" class="py-12 bg-gray-50">
            <x-about.notre-parcours />
        </section>

        <section id="impact-chiffres" class="py-12">
            <x-about.impact-chiffres />
        </section>

        <section id="cta" class="py-12 bg-gray-50">
            <x-about.cta />
        </section>
    </main>

    <footer>
        <x-public-footer />
    </footer>
</body>
</html>