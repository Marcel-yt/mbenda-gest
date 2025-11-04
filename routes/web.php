<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Agent\TontineController as AgentTontineController;
use App\Http\Controllers\Admin\TontineController as AdminTontineController;

// Page d’accueil publique
Route::view('/', 'pages.public.welcome')->name('home');
Route::view('/about', 'pages.public.about')->name('about');
Route::view('/contact', 'pages.public.contact')->name('contact');

// Redirection générique /dashboard après login (selon rôle)
Route::middleware(['auth','verified'])->get('/dashboard', function () {
    $u = auth()->user();
    return $u->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('agent.dashboard');
})->name('dashboard');

// Profil (Breeze) hors layout admin/agent
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin
Route::middleware(['auth','verified','role:admin'])
    ->prefix('admin')->name('admin.')
    ->group(function () {
        Route::view('/', 'pages.app.admin.dashboard')->name('dashboard');

        // Resource routes pour users (index, create, store, show, edit, update, destroy)
        Route::resource('users', UserController::class)->names('users');

        // Routes pour la gestion des tontines (admin uniquement)
        Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
            Route::resource('tontines', AdminTontineController::class);
            Route::post('tontines/{tontine}/finalize', [AdminTontineController::class, 'finalize'])->name('tontines.finalize');
        });

        // Autres routes admin...
    });

// Agent
Route::middleware(['auth','verified','role:agent'])
    ->prefix('agent')->name('agent.')
    ->group(function () {
        Route::view('/', 'pages.app.agent.dashboard')->name('dashboard');

        // Routes pour la gestion des tontines (agent uniquement)
        Route::resource('tontines', AgentTontineController::class)->only(['index','create','store','show','edit','update']);

        // AJAX search for clients used by agent tontine create/edit
        Route::get('tontines/clients/search', [AgentTontineController::class, 'searchClients'])
            ->name('tontines.clients.search');

        // Autres routes agent...
    });

// Auth Breeze (si présent)
if (file_exists(__DIR__.'/auth.php')) {
    require __DIR__.'/auth.php';
}

// Routes pour la gestion des clients (agents + admins peuvent accéder selon contrôleur)
Route::middleware(['auth'])->group(function () {
    Route::resource('clients', ClientController::class);
});

// Routes pour la gestion des clients (admin uniquement)
Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('clients', AdminClientController::class)->except(['create','store']);
});

// Routes pour la gestion des tontines (agent + admin)
Route::middleware(['auth'])->prefix('agent')->name('agent.')->group(function () {
    // agent can list/create/store/show tontines; edit/update managed by admin
    Route::resource('tontines', AgentTontineController::class)->only(['index','create','store','show']);
    // AJAX search route...
});
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('tontines', AdminTontineController::class);
    Route::post('tontines/{tontine}/finalize', [AdminTontineController::class, 'finalize'])->name('tontines.finalize');
});
