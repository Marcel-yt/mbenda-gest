<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Collecte;
use App\Models\Tontine;
use App\Models\Client;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $stats = $this->computeStats($request->user()->id);
        // envoie chaque clé de $stats comme variable de vue
        return view('pages.app.agent.dashboard', $stats);
    }

    public function stats(Request $request)
    {
        return response()->json($this->computeStats($request->user()->id));
    }

    protected function computeStats(int $agentId): array
    {
        $today = Carbon::today();

        // Montant collecté aujourd'hui (activité du jour via created_at), montant dérivé daily_amount
        $todayAmount = (float) Collecte::where('agent_id', $agentId)
            ->whereDate('collectes.created_at', $today)
            ->join('tontines', 'tontines.id', '=', 'collectes.tontine_id')
            ->sum('tontines.daily_amount');

        // Total des tontines actives (toutes)
        $activeTontines = (int) Tontine::where('status', 'active')->count();

        // Clients créés par l’agent
        $clientsCount = (int) Client::where('created_by_agent_id', $agentId)->count();

        // Collectes effectuées aujourd’hui (liste)
        $collectesActionToday = Collecte::where('agent_id', $agentId)
            ->whereDate('created_at', $today)
            ->with([
                'tontine:id,client_id,daily_amount',
                'tontine.client:id,first_name,last_name,phone,address'
            ])
            ->orderByDesc('id')
            ->get();

        // Table clients (adresse + dernière date/heure + nb collectes du jour)
        $clientDailyStats = $collectesActionToday
            ->groupBy(fn($c) => optional($c->tontine->client)->id)
            ->map(function ($grp) {
                $client = $grp->first()->tontine->client ?? null;
                if (!$client) return null;
                $amount = $grp->sum(fn($r) => $r->tontine?->daily_amount ?? 0);
                $last = $grp->sortByDesc('created_at')->first()?->created_at;
                return [
                    'id'     => $client->id,
                    'name'   => trim(($client->first_name ?? '') . ' ' . ($client->last_name ?? '')) ?: '—',
                    'address'=> $client->address ?? '—',
                    'amount' => (float) $amount,
                    'count'  => $grp->count(),
                    'last_created_at' => $last ? $last->toIso8601String() : null,
                ];
            })
            ->filter()
            ->sortByDesc('amount')
            ->values()
            ->all();

        $clientsCollectedTodayCount = count($clientDailyStats);

        // Courbe 30 jours (activité du jour)
        $from30 = Carbon::today()->subDays(29);
        $rawDaily = Collecte::selectRaw('DATE(collectes.created_at) as d, SUM(tontines.daily_amount) as total')
            ->where('collectes.agent_id', $agentId)
            ->whereDate('collectes.created_at', '>=', $from30)
            ->join('tontines', 'tontines.id', '=', 'collectes.tontine_id')
            ->groupBy('d')
            ->orderBy('d')
            ->get()
            ->keyBy('d');

        $dailyLabels = [];
        $dailyValues = [];
        for ($i = 0; $i < 30; $i++) {
            $d = $from30->copy()->addDays($i);
            $key = $d->format('Y-m-d');
            $dailyLabels[] = $d->format('d/m');
            $dailyValues[] = (float) ($rawDaily[$key]->total ?? 0);
        }

        // Donut: Collectes du jour (À l’heure / En avance / En retard) basées sur for_date
        $hasForDate = Schema::hasColumn('collectes', 'for_date');
        $base = Collecte::where('agent_id', $agentId)->whereDate('created_at', $today);

        if ($hasForDate) {
            $onTime = (int) (clone $base)->whereDate('for_date', '=', $today)->count();
            $early  = (int) (clone $base)->whereDate('for_date', '>', $today)->count();
            $late   = (int) (clone $base)->whereDate('for_date', '<', $today)->count();
        } else {
            // fallback: tout est considéré "à l’heure" si for_date absent
            $onTime = (int) (clone $base)->count();
            $early = 0; $late = 0;
        }

        $timingCounts = [
            'on_time' => $onTime,
            'early'   => $early,
            'late'    => $late,
        ];

        return [
            'todayAmount'                => $todayAmount,
            'activeTontines'             => $activeTontines,
            'clientsCount'               => $clientsCount,
            'clientsCollectedTodayCount' => $clientsCollectedTodayCount,
            'clientDailyStats'           => $clientDailyStats,
            'dailyLabels'                => $dailyLabels,
            'dailyValues'                => $dailyValues,
            'timingCounts'               => $timingCounts,
        ];
    }
}