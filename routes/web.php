<?php

use Illuminate\Support\Facades\Route;

// Routes accessibles uniquement aux administrateurs
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Route::get('/admin', [\App\Http\Controllers\AdminController::class, 'index'])->name('admin.home');
});

// Routes accessibles uniquement aux agents
Route::middleware(['auth', 'role:agent'])->group(function () {
    // Route::get('/agent', [\App\Http\Controllers\AgentController::class, 'index'])->name('agent.home');
});

// Exemple : route protégée par une permission spécifique
Route::middleware(['auth', 'permission:manage settings'])->group(function () {
    // Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
});

// Public pages (harmonisés avec resources/views/pages/public/*.blade.php)
Route::view('/', 'pages.public.welcome')->name('home');
Route::view('/about', 'pages.public.about')->name('about');
Route::view('/contact', 'pages.public.contact')->name('contact');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Charger les routes d'auth (Breeze) si présentes
if (file_exists(__DIR__ . '/auth.php')) {
    require __DIR__ . '/auth.php';
}
