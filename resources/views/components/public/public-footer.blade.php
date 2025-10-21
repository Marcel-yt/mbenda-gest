<footer class="bg-gray-50 border-t mt-12">
    <div class="max-w-7xl mx-auto px-4 py-6 flex items-center justify-between">
        <div class="text-sm text-gray-600">© {{ date('Y') }} Mbenda Gest. Tous droits réservés.</div>
        <div class="flex gap-4">
            <a href="{{ url('/privacy') }}" class="text-sm text-gray-600 hover:text-[var(--mb-primary)]">Politique de confidentialité</a>
            <a href="{{ url('/terms') }}" class="text-sm text-gray-600 hover:text-[var(--mb-primary)]">CGU</a>
        </div>
    </div>
</footer>