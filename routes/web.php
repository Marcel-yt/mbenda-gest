<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Agent\TontineController as AgentTontineController;
use App\Http\Controllers\Admin\TontineController as AdminTontineController;
use App\Http\Controllers\Agent\CollecteController;
use App\Http\Controllers\Admin\CollecteController as AdminCollecteController;
use App\Http\Controllers\Admin\PayoutController as AdminPayoutController;

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

        // Routes pour les collectes (index, create, store, show)
        Route::resource('collectes', CollecteController::class)->only([
            'index', 'create', 'store', 'show'
        ]);

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
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/tontines', [AdminTontineController::class, 'index'])->name('tontines.index');
    Route::get('/tontines/{id}', [AdminTontineController::class, 'show'])->name('tontines.show');
    Route::get('/tontines/{id}/edit', [AdminTontineController::class, 'edit'])->name('tontines.edit');
    Route::put('/tontines/{id}', [AdminTontineController::class, 'update'])->name('tontines.update');
    Route::post('/tontines/{id}/pay', [AdminTontineController::class, 'pay'])->name('tontines.pay');

    Route::get('/collectes', [AdminCollecteController::class, 'index'])->name('collectes.index');
    Route::get('/collectes/{id}', [AdminCollecteController::class, 'show'])->name('collectes.show');

    Route::get('/payouts', [AdminPayoutController::class, 'index'])->name('payouts.index');
    Route::get('/payouts/create/{tontine?}', [AdminPayoutController::class, 'create'])->name('payouts.create');
    Route::post('/payouts', [AdminPayoutController::class, 'store'])->name('payouts.store');
    Route::get('/payouts/{id}', [AdminPayoutController::class, 'show'])->name('payouts.show');
});
