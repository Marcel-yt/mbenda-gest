<?php

namespace App\Http\Controllers\Api\Agent;

use App\Http\Controllers\Api\ApiController;
use App\Models\Collecte;
use App\Models\Tontine;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DashboardController extends ApiController
{
    /**
     * GET /api/agent/dashboard
     * Retourne les statistiques complètes du tableau de bord agent.
     */
    public function index(Request $request): JsonResponse
    {
        return $this->stats($request);
    }

    /**
     * GET /api/agent/dashboard/stats
     */
    public function stats(Request $request): JsonResponse
    {
        $agentId = $request->user()->id;
        $today   = Carbon::today();

        // Montant collecté aujourd'hui
        $todayAmount = (float) Collecte::where('agent_id', $agentId)
            ->whereDate('collectes.created_at', $today)
            ->join('tontines', 'tontines.id', '=', 'collectes.tontine_id')
            ->where('tontines.status', '<>', 'cancelled')
            ->sum('tontines.daily_amount');

        // Tontines actives (globales)
        $activeTontines = (int) Tontine::where('status', 'active')->count();

        // Clients de l'agent
        $clientsCount = (int) Client::where('created_by_agent_id', $agentId)->count();

        // Collectes du jour
        $collectesActionToday = Collecte::where('agent_id', $agentId)
            ->whereDate('created_at', $today)
            ->with([
                'tontine:id,client_id,daily_amount,status',
                'tontine.client:id,first_name,last_name,phone,address',
            ])
            ->orderByDesc('id')
            ->get()
            ->filter(fn($c) => optional($c->tontine)->status !== 'cancelled')
            ->values();

        // Statistiques par client
        $clientDailyStats = $collectesActionToday
            ->groupBy(fn($c) => optional($c->tontine->client)->id)
            ->map(function ($grp) {
                $client = $grp->first()->tontine->client ?? null;
                if (! $client) return null;
                $amount = $grp->sum(fn($r) => $r->tontine?->daily_amount ?? 0);
                $last   = $grp->sortByDesc('created_at')->first()?->created_at;
                return [
                    'id'              => $client->id,
                    'name'            => trim(($client->first_name ?? '').' '.($client->last_name ?? '')) ?: '—',
                    'address'         => $client->address ?? '—',
                    'amount'          => (float) $amount,
                    'count'           => $grp->count(),
                    'last_created_at' => $last ? $last->toIso8601String() : null,
                ];
            })
            ->filter()
            ->sortByDesc('amount')
            ->values()
            ->all();

        // Courbe 30 jours
        $from30   = Carbon::today()->subDays(29);
        $rawDaily = Collecte::selectRaw('DATE(collectes.created_at) as d, SUM(tontines.daily_amount) as total')
            ->where('collectes.agent_id', $agentId)
            ->whereDate('collectes.created_at', '>=', $from30)
            ->join('tontines', 'tontines.id', '=', 'collectes.tontine_id')
            ->where('tontines.status', '<>', 'cancelled')
            ->groupBy('d')
            ->orderBy('d')
            ->get()
            ->keyBy('d');

        $dailyLabels = [];
        $dailyValues = [];
        for ($i = 0; $i < 30; $i++) {
            $d = $from30->copy()->addDays($i);
            $key             = $d->format('Y-m-d');
            $dailyLabels[]   = $d->format('d/m');
            $dailyValues[]   = (float) ($rawDaily[$key]->total ?? 0);
        }

        // Donut timing
        $hasForDate = Schema::hasColumn('collectes', 'for_date');
        $base       = Collecte::where('agent_id', $agentId)->whereDate('created_at', $today)
            ->whereHas('tontine', fn($q) => $q->where('status', '<>', 'cancelled'));

        if ($hasForDate) {
            $onTime = (int) (clone $base)->whereDate('for_date', '=', $today)->count();
            $early  = (int) (clone $base)->whereDate('for_date', '>', $today)->count();
            $late   = (int) (clone $base)->whereDate('for_date', '<', $today)->count();
        } else {
            $onTime = (int) (clone $base)->count();
            $early  = 0;
            $late   = 0;
        }

        return $this->success([
            'today_amount'                => $todayAmount,
            'active_tontines'             => $activeTontines,
            'clients_count'               => $clientsCount,
            'clients_collected_today'     => count($clientDailyStats),
            'client_daily_stats'          => $clientDailyStats,
            'daily_labels'                => $dailyLabels,
            'daily_values'                => $dailyValues,
            'timing'                      => [
                'on_time' => $onTime,
                'early'   => $early,
                'late'    => $late,
            ],
        ]);
    }
}
