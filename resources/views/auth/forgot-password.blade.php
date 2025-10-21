<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Réinitialiser le mot de passe — {{ config('app.name', 'Mbenda Gest') }}</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        :root{
            --mb-primary: #0078B7;   /* bleu principal */
            --mb-secondary: #1EA845; /* vert secondaire */
            --mb-surface: #ffffff;
            --mb-muted: #6b7280;     /* gray-500 */
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
        <div class="w-full max-w-4xl">
            <!-- Card now uses primary background -->
            <div class="bg-[var(--mb-primary)] rounded-2xl shadow-lg overflow-hidden p-8 md:p-12 text-white">
                <div class="max-w-xl mx-auto">
                    <div class="mb-6">
                        <h2 class="text-2xl md:text-3xl font-extrabold">Réinitialiser votre mot de passe</h2>
                        <div class="text-sm font-medium text-white/90 mt-1">Mbenda Gest</div>
                    </div>

                    <p class="text-white/90 mb-6">
                        Indiquez l'email associé à votre compte. Nous vous enverrons un lien sécurisé pour choisir un nouveau mot de passe.
                    </p>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4 text-sm text-white/90" :status="session('status')" />

                    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label for="email" class="block text-sm font-medium text-white/90">Email</label>
                            <input id="email" name="email" type="email" required autofocus
                                   value="{{ old('email') }}"
                                   class="mt-1 block w-full rounded-md border border-gray-200 px-3 py-2 bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-white/40" 
                                   placeholder="Entrez votre email">
                            @if($errors->has('email'))
                                <p class="mt-2 text-sm text-white/80">{{ $errors->first('email') }}</p>
                            @endif
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center px-4 py-3 bg-white text-[var(--mb-primary)] font-semibold rounded-lg shadow hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white/40 transition">
                                Envoyer le lien de réinitialisation
                            </button>
                        </div>
                    </form>

                    <p class="mt-6 text-sm text-white/90">
                        Vous n'avez pas reçu l'email ? Vérifiez vos spams ou
                        <a href="{{ route('login') }}" class="text-white underline underline-offset-2">retournez à la connexion</a>.
                    </p>

                    <div class="mt-8 text-sm text-white/80">
                        Ou contactez le support : <strong class="text-white">contact@mbendagest.com</strong>
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
