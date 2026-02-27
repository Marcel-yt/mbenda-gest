<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureUserHasRole::class,
        ]);

        // (optionnel) groupes si besoin
        // $middleware->group('admin', ['auth', 'verified', 'role:admin']);
        // $middleware->group('agent', ['auth', 'verified', 'role:agent']);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Toujours retourner du JSON pour les routes api/*
        $exceptions->shouldRenderJsonWhen(function ($request, \Throwable $e): bool {
            return $request->is('api/*');
        });

        // Formater les abort(401) et abort(403) du middleware avec notre enveloppe
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            if ($request->is('api/*')) {
                $status = $e->getStatusCode();
                $message = match ($status) {
                    401 => 'Non authentifi\u00e9. Veuillez vous connecter.',
                    403 => $e->getMessage() ?: 'Acc\u00e8s refus\u00e9.',
                    405 => 'M\u00e9thode HTTP non autoris\u00e9e.',
                    default => $e->getMessage() ?: 'Erreur serveur.',
                };
                return response()->json([
                    'status'  => false,
                    'message' => $message,
                    'errors'  => [],
                ], $status);
            }
        });

        // Formater les erreurs de validation (422) avec notre enveloppe
        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Les données envoyées sont invalides.',
                    'errors'  => $e->errors(),
                ], 422);
            }
        });

        // Formater les 404 avec notre enveloppe
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Ressource introuvable.',
                    'errors'  => [],
                ], 404);
            }
        });

        // Formater les 403 (unauthorized) avec notre enveloppe
        $exceptions->render(function (\Illuminate\Auth\Access\AuthorizationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Action non autorisée.',
                    'errors'  => [],
                ], 403);
            }
        });
    })->create();
