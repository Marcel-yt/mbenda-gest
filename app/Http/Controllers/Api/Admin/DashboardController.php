<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Admin\DashboardController as WebDashboardController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Délègue aux méthodes JSON existantes du contrôleur web Admin Dashboard.
 * Aucune duplication de logique — on réutilise directement computeTotals(),
 * computeSeries(), etc. via le contrôleur web qui expose déjà stats() en JSON.
 */
class DashboardController extends ApiController
{
    private WebDashboardController $web;

    public function __construct()
    {
        $this->web = app(WebDashboardController::class);
    }

    /**
     * GET /api/admin/dashboard
     */
    public function index(Request $request): JsonResponse
    {
        return $this->web->stats($request);
    }

    /**
     * GET /api/admin/dashboard/stats?date_from=Y-m-d&date_to=Y-m-d
     */
    public function stats(Request $request): JsonResponse
    {
        return $this->web->stats($request);
    }
}
