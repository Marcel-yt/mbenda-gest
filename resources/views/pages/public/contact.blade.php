<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Contact — {{ config('app.name') }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
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

</body>
</html>