<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Connexion — {{ config('app.name', 'Mbenda Gest') }}</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        :root {
            --mb-primary: #0078B7;
            --mb-accent: #1e90ff; /* fallback accent if needed */
            --mb-secondary: #7FBC47;
        }
    </style>
</head>
<body class="antialiased bg-gray-50 text-gray-800 min-h-screen flex flex-col">

    {{-- Header --}}
    <header>
        <x-public-header />
    </header>

    {{-- Main --}}
    <main class="flex-1 flex items-center justify-center py-16 px-4">
        <div class="w-full max-w-5xl">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden grid grid-cols-1 md:grid-cols-2">
                <!-- LEFT: form -->
                <div class="p-10 md:p-12">
                    <div class="max-w-md mx-auto">
                        <div class="flex items-center gap-4 mb-6">
                            <h2 class="text-2xl font-extrabold text-gray-900">
                                Connectez-vous à <span class="text-[var(--mb-primary)]">Mbenda Gest</span>
                            </h2>
                        </div>

                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <form method="POST" action="{{ route('login') }}" class="space-y-4">
                            @csrf

                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Entrez votre email" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="password" :value="__('Mot de passe')" />
                                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" placeholder="Mot de passe" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-between">
                                <label for="remember_me" class="inline-flex items-center">
                                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-[var(--mb-primary)] shadow-sm focus:ring-[var(--mb-primary)]" name="remember">
                                    <span class="ml-2 text-sm text-gray-600">Se souvenir de moi</span>
                                </label>

                                @if (Route::has('password.request'))
                                    <a class="text-sm text-[var(--mb-primary)] hover:underline" href="{{ route('password.request') }}">
                                        Mot de passe oublié ?
                                    </a>
                                @endif
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-3 bg-[var(--mb-primary)] hover:bg-[#006aa0] text-white font-semibold rounded-lg shadow">
                                    Se connecter
                                </button>
                            </div>
                        </form>

                        <p class="mt-6 text-sm text-gray-500">
                            Accès uniquement réservé au personnel Mbenda Gest. Les clients n’ont pas besoin de compte pour utiliser nos services.
                        </p>
                    </div>
                </div>

                <!-- RIGHT: info panel -->
                <div class="bg-[var(--mb-primary)] text-white p-8 flex items-center justify-center">
                    <div class="max-w-sm text-center">
                        <h3 class="text-2xl font-bold mb-3">Accès réservé</h3>
                        <p class="text-sm text-white/90 mb-6">
                            Cette section est strictement dédiée aux personnels de Mbenda Gest. Pour en savoir plus ou pour démarrer une tontine, contactez notre équipe commerciale.
                        </p>

                        <div class="space-y-3 mt-4">
                            <div class="text-sm">
                                <div class="font-medium">Support</div>
                                <div class="text-white/90">contact@mbendagest.com</div>
                            </div>

                            <div class="text-sm">
                                <div class="font-medium">Téléphone</div>
                                <div class="text-white/90">066083193 / 077402098</div>
                            </div>

                            <a href="{{ url('/') }}" class="inline-block mt-4 px-4 py-2 bg-white text-[var(--mb-primary)] rounded-md font-medium hover:bg-white/90 transition">
                                Retour au site
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer>
        <x-public-footer />
    </footer>

</body>
</html>
