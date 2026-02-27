<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\Agent\DashboardController as AgentDashboardController;
use App\Http\Controllers\Api\Agent\TontineController as AgentTontineController;
use App\Http\Controllers\Api\Agent\ClientController as AgentClientController;
use App\Http\Controllers\Api\Agent\CollecteController as AgentCollecteController;
use App\Http\Controllers\Api\Agent\PayoutController as AgentPayoutController;
use App\Http\Controllers\Api\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Api\Admin\TontineController as AdminTontineController;
use App\Http\Controllers\Api\Admin\CollecteController as AdminCollecteController;
use App\Http\Controllers\Api\Admin\PayoutController as AdminPayoutController;

/*
|--------------------------------------------------------------------------
| API Routes — Version Mobile
|--------------------------------------------------------------------------
|
| Toutes les routes API utilisent l'authentification Sanctum (token).
| Préfixe : /api  (défini automatiquement par Laravel)
|
*/

// ─────────────────────────────────────────────
//  Auth (public)
// ─────────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('login',           [AuthController::class, 'login']);
    Route::post('logout',          [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('me',               [AuthController::class, 'me'])->middleware('auth:sanctum');
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password',  [AuthController::class, 'resetPassword']);
});

// ─────────────────────────────────────────────
//  Routes authentifiées
// ─────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Profil
    Route::get('profile',                 [ProfileController::class, 'show']);
    Route::put('profile',                 [ProfileController::class, 'update']);
    Route::put('profile/password',        [ProfileController::class, 'changePassword']);
    Route::delete('profile',              [ProfileController::class, 'destroy']);

    // ─── AGENT ────────────────────────────────
    Route::middleware('role:agent')->prefix('agent')->group(function () {

        // Dashboard
        Route::get('dashboard',       [AgentDashboardController::class, 'index']);
        Route::get('dashboard/stats', [AgentDashboardController::class, 'stats']);

        // Tontines
        Route::get('tontines',                        [AgentTontineController::class, 'index']);
        Route::post('tontines',                       [AgentTontineController::class, 'store']);
        Route::get('tontines/clients/search',         [AgentTontineController::class, 'searchClients']);
        Route::get('tontines/{tontine}',              [AgentTontineController::class, 'show']);
        Route::put('tontines/{tontine}',              [AgentTontineController::class, 'update']);
        Route::post('tontines/{tontine}/cancel',      [AgentTontineController::class, 'cancel']);

        // Clients
        Route::get('clients',         [AgentClientController::class, 'index']);
        Route::post('clients',        [AgentClientController::class, 'store']);
        Route::get('clients/{id}',    [AgentClientController::class, 'show']);

        // Collectes
        Route::get('collectes',       [AgentCollecteController::class, 'index']);
        Route::post('collectes',      [AgentCollecteController::class, 'store']);
        Route::get('collectes/{id}',  [AgentCollecteController::class, 'show']);

        // Payouts
        Route::get('payouts',                        [AgentPayoutController::class, 'index']);
        Route::post('payouts',                       [AgentPayoutController::class, 'store']);
        Route::get('payouts/{id}',                   [AgentPayoutController::class, 'show']);
        Route::get('payouts/{id}/receipt',           [AgentPayoutController::class, 'receipt']);
        Route::get('payouts/summary/{tontine_id}',   [AgentPayoutController::class, 'summary']);
    });

    // ─── ADMIN ────────────────────────────────
    Route::middleware('role:admin')->prefix('admin')->group(function () {

        // Dashboard
        Route::get('dashboard',       [AdminDashboardController::class, 'index']);
        Route::get('dashboard/stats', [AdminDashboardController::class, 'stats']);

        // Users
        Route::get('users',           [AdminUserController::class, 'index']);
        Route::post('users',          [AdminUserController::class, 'store']);
        Route::get('users/{id}',      [AdminUserController::class, 'show']);
        Route::put('users/{id}',      [AdminUserController::class, 'update']);
        Route::delete('users/{id}',   [AdminUserController::class, 'destroy']);

        // Clients
        Route::get('clients',         [AdminClientController::class, 'index']);
        Route::get('clients/{id}',    [AdminClientController::class, 'show']);
        Route::put('clients/{id}',    [AdminClientController::class, 'update']);
        Route::delete('clients/{id}', [AdminClientController::class, 'destroy']);

        // Tontines
        Route::get('tontines',                        [AdminTontineController::class, 'index']);
        Route::get('tontines/{id}',                   [AdminTontineController::class, 'show']);
        Route::put('tontines/{id}',                   [AdminTontineController::class, 'update']);
        Route::delete('tontines/{id}',                [AdminTontineController::class, 'destroy']);
        Route::post('tontines/{id}/pay',              [AdminTontineController::class, 'pay']);
        Route::post('tontines/{id}/finalize',         [AdminTontineController::class, 'finalize']);
        Route::post('tontines/{id}/cancel',           [AdminTontineController::class, 'cancel']);

        // Collectes
        Route::get('collectes',       [AdminCollecteController::class, 'index']);
        Route::get('collectes/{id}',  [AdminCollecteController::class, 'show']);

        // Payouts
        Route::get('payouts',                       [AdminPayoutController::class, 'index']);
        Route::post('payouts',                      [AdminPayoutController::class, 'store']);
        Route::get('payouts/{id}',                  [AdminPayoutController::class, 'show']);
        Route::get('payouts/summary/{tontine_id}',  [AdminPayoutController::class, 'summary']);
    });
});
