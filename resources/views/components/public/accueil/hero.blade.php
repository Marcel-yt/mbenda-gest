<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center py-12">
    <div>
      <span class="inline-block text-xs px-2 py-1 rounded" style="background: rgba(0,120,183,0.08); color: var(--mb-primary)">Nouveau</span>
      <h1 class="mt-4 text-3xl font-semibold">Titre principal de l'accueil</h1>
      <p class="mt-4 text-gray-600">Courte description d'accroche — remplacer par le contenu de Home.tsx.</p>
      <div class="mt-6 flex gap-3">
        <a href="{{ route('register') }}" class="px-4 py-2 rounded text-white" style="background: var(--mb-primary)">Commencer</a>
        <a href="{{ url('/about') }}" class="px-4 py-2 rounded border">En savoir plus</a>
      </div>
    </div>
    <div class="rounded-lg bg-white border p-6 shadow-sm">
      <div class="h-56 flex items-center justify-center text-gray-400">Visuel / illustration</div>
    </div>
  </div>
</section>