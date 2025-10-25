<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string $rolesCsv): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(401);
        }

        // si la colonne active existe et est false => bloquer
        if (isset($user->active) && !boolval($user->active)) {
            abort(403, 'Compte inactif.');
        }

        $allowed = collect(explode('|', $rolesCsv))
            ->map(fn ($r) => strtolower(trim($r)))
            ->contains(strtolower((string) $user->role));

        abort_unless($allowed, 403, 'Accès refusé.');

        return $next($request);
    }
}