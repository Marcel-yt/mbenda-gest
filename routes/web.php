<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

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
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin
Route::middleware(['auth','verified','role:admin'])
    ->prefix('admin')->name('admin.')
    ->group(function () {
        Route::view('/', 'pages.app.admin.dashboard')->name('dashboard');
        // autres routes admin…
    });

// Agent
Route::middleware(['auth','verified','role:agent'])
    ->prefix('agent')->name('agent.')
    ->group(function () {
        Route::view('/', 'pages.app.agent.dashboard')->name('dashboard');
        // autres routes agent…
    });

// Auth Breeze (si présent)
if (file_exists(__DIR__.'/auth.php')) {
    require __DIR__.'/auth.php';
}
