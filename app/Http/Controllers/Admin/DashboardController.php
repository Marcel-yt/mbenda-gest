<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totals = $this->computeTotals();
        $series = $this->computeSeries();
        $agents = $this->computeAgents();
        $agentCommissions = $this->computeAgentCommissions();
        $status = $this->computeStatusCounts();

        // Par défaut, aucun filtre => totaux globaux (pour la carte filtre)
        $filterTotals = $this->ioBetween(null, null);

        return view('pages.app.admin.dashboard', [
            'totalClients'   => $totals['totalClients'],
            'totalTontines'  => $totals['totalTontines'],
            'totalCollectes' => $totals['totalCollectes'],
            'totalPayouts'   => $totals['totalPayouts'],
            'amountInTotal'  => $totals['amountInTotal'],
            'amountOutTotal' => $totals['amountOutTotal'],
            'netTotal'       => $totals['netTotal'],
            'commissionTotal'=> $totals['commissionTotal'],
            'commissionPlanned'=> $totals['commissionPlanned'],

            'periods'        => $totals['periods'],

            'dailyLabels'    => $series['daily']['labels'],
            'dailyIn'        => $series['daily']['in'],
            'dailyOut'       => $series['daily']['out'],

            'monthlyLabels'  => $series['monthly']['labels'],
            'monthlyIn'      => $series['monthly']['in'],
            'monthlyOut'     => $series['monthly']['out'],

            'commLabels'     => $series['commissions']['labels'],
            'commValues'     => $series['commissions']['values'],

            'agentsToday'    => $agents['today'],
            'agentsMonth'    => $agents['month'],

            'statusCounts'   => $status,
            'filterTotals'   => $filterTotals,
            'agentCommissions' => $agentCommissions,
        ]);
    }

    public function stats(Request $request)
    {
        $totals = $this->computeTotals();
        $series = $this->computeSeries();
        $agents = $this->computeAgents();
        $status = $this->computeStatusCounts();
        $agentCommissions = $this->computeAgentCommissions();

        // Filtres facultatifs
        $from = $request->query('date_from') ? Carbon::createFromFormat('Y-m-d', $request->query('date_from'))->startOfDay() : null;
        $to   = $request->query('date_to')   ? Carbon::createFromFormat('Y-m-d', $request->query('date_to'))->endOfDay()   : null;
        $filterTotals = $this->ioBetween($from, $to);

        return response()->json(array_merge($totals, [
            'dailyLabels'   => $series['daily']['labels'],
            'dailyIn'       => $series['daily']['in'],
            'dailyOut'      => $series['daily']['out'],

            'monthlyLabels' => $series['monthly']['labels'],
            'monthlyIn'     => $series['monthly']['in'],
            'monthlyOut'    => $series['monthly']['out'],

            'commLabels'    => $series['commissions']['labels'],
            'commValues'    => $series['commissions']['values'],

            'agentsToday'   => $agents['today'],
            'agentsMonth'   => $agents['month'],

            'statusCounts'  => $status,
            'filterTotals'  => array_merge($filterTotals, [
                'from' => $from?->toDateString(),
                'to'   => $to?->toDateString(),
            ]),
            'agentCommissions' => $agentCommissions,
        ]));
    }

    private function computeTotals(): array
    {
        $totalClients   = DB::table('clients')->count();
        $totalTontines  = DB::table('tontines')->count();
        $totalCollectes = DB::table('collectes as c')
            ->join('tontines as t', 't.id', '=', 'c.tontine_id')
            ->where('t.status', '<>', 'cancelled')
            ->count();
        $totalPayouts   = DB::table('payouts')->count();

        $amountInTotal = (float) DB::table('collectes as c')
            ->join('tontines as t', 't.id', '=', 'c.tontine_id')
            ->where('t.status', '<>', 'cancelled')
            ->sum('t.daily_amount');

        $amountOutTotal = (float) DB::table('payouts')->sum('amount_net');
        $commissionTotal= (float) DB::table('payouts')->sum('commission_amount');
        // Commission prévue: somme des commissions attendues sur les tontines commencées
        // Par tontine: daily_amount * COALESCE(commission_days, 0)
        $commissionPlanned = (float) DB::table('tontines')
            ->whereNotIn('status', ['draft','cancelled'])
            ->selectRaw('SUM(COALESCE(daily_amount,0) * COALESCE(commission_days,0)) as s')
            ->value('s') ?? 0.0;
        $netTotal       = $amountInTotal - $amountOutTotal;

        $now = Carbon::now();
        $periods = [
            'today' => $this->ioBetween($now->copy()->startOfDay(), $now->copy()->endOfDay()),
            'week'  => $this->ioBetween($now->copy()->startOfWeek(), $now->copy()->endOfWeek()),
            'month' => $this->ioBetween($now->copy()->startOfMonth(), $now->copy()->endOfMonth()),
            'year'  => $this->ioBetween($now->copy()->startOfYear(), $now->copy()->endOfYear()),
        ];

        // Nouveaux KPIs
        $newClientsMonth = DB::table('clients')
            ->where('created_at', '>=', $now->copy()->startOfMonth())
            ->count();

        $tontinesToFinalize = DB::table('tontines')
            ->whereIn('status', ['completed'])
            ->count();

        // Croissance clients (12 derniers mois)
        $clientsGrowth = [];
        for ($i = 11; $i >= 0; $i--) {
            $monthStart = $now->copy()->subMonths($i)->startOfMonth();
            $monthEnd = $monthStart->copy()->endOfMonth();
            $count = DB::table('clients')
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->count();
            $clientsGrowth[] = $count;
        }

        return compact(
            'totalClients',
            'totalTontines',
            'totalCollectes',
            'totalPayouts',
            'amountInTotal',
            'amountOutTotal',
            'commissionTotal',
            'commissionPlanned',
            'netTotal',
            'periods',
            'newClientsMonth',
            'tontinesToFinalize',
            'clientsGrowth'
        );
    }

    // In/Out/Net/Commission sur période; null=null => global
    private function ioBetween(?Carbon $from, ?Carbon $to): array
    {
        $inQ = DB::table('collectes as c')->join('tontines as t', 't.id', '=', 'c.tontine_id')->where('t.status', '<>', 'cancelled');
        if ($from && $to) $inQ->whereBetween('c.created_at', [$from, $to]);

        $outQ = DB::table('payouts');
        if ($from && $to) $outQ->whereBetween('paid_at', [$from, $to]);

        $commQ = DB::table('payouts');
        if ($from && $to) $commQ->whereBetween('paid_at', [$from, $to]);

        $in  = (float) $inQ->sum('t.daily_amount');
        $out = (float) $outQ->sum('amount_net');
        $comm= (float) $commQ->sum('commission_amount');

        return ['in' => $in, 'out' => $out, 'net' => $in - $out, 'comm' => $comm];
    }

    private function computeSeries(): array
    {
        $startDay = Carbon::today()->subDays(29);
        $days = [];
        for ($i=0;$i<30;$i++) $days[] = $startDay->copy()->addDays($i)->toDateString();

        $inByDay = DB::table('collectes as c')
            ->join('tontines as t', 't.id', '=', 'c.tontine_id')
            ->where('t.status', '<>', 'cancelled')
            ->where('c.created_at', '>=', $startDay->toDateString())
            ->selectRaw('DATE(c.created_at) as d, SUM(t.daily_amount) as s')
            ->groupBy('d')->pluck('s', 'd');

        $outByDay = DB::table('payouts')
            ->where('paid_at', '>=', $startDay->toDateString())
            ->selectRaw('DATE(paid_at) as d, SUM(amount_net) as s')
            ->groupBy('d')->pluck('s', 'd');

        $commByDay = DB::table('payouts')
            ->where('paid_at', '>=', $startDay->toDateString())
            ->selectRaw('DATE(paid_at) as d, SUM(commission_amount) as s')
            ->groupBy('d')->pluck('s', 'd');

        $dailyLabels = array_map(fn($d)=>Carbon::parse($d)->format('d/m'), $days);
        $dailyIn  = array_map(fn($d)=>(float)($inByDay[$d] ?? 0), $days);
        $dailyOut = array_map(fn($d)=>(float)($outByDay[$d] ?? 0), $days);
        $commVals = array_map(fn($d)=>(float)($commByDay[$d] ?? 0), $days);

        $startMonth = Carbon::now()->startOfMonth()->subMonths(11);
        $months = [];
        for ($i=0;$i<12;$i++) $months[] = $startMonth->copy()->addMonths($i)->format('Y-m');

        $inByMonth = DB::table('collectes as c')
            ->join('tontines as t', 't.id', '=', 'c.tontine_id')
            ->where('t.status', '<>', 'cancelled')
            ->where('c.created_at', '>=', $startMonth->toDateString())
            ->selectRaw('DATE_FORMAT(c.created_at, "%Y-%m") as m, SUM(t.daily_amount) as s')
            ->groupBy('m')->pluck('s', 'm');

        $outByMonth = DB::table('payouts')
            ->where('paid_at', '>=', $startMonth->toDateString())
            ->selectRaw('DATE_FORMAT(paid_at, "%Y-%m") as m, SUM(amount_net) as s')
            ->groupBy('m')->pluck('s', 'm');

        $monthlyLabels = array_map(fn($m)=>Carbon::createFromFormat('Y-m', $m)->format('m/Y'), $months);
        $monthlyIn  = array_map(fn($m)=>(float)($inByMonth[$m] ?? 0), $months);
        $monthlyOut = array_map(fn($m)=>(float)($outByMonth[$m] ?? 0), $months);

        return [
            'daily' => ['labels'=>$dailyLabels, 'in'=>$dailyIn, 'out'=>$dailyOut],
            'monthly' => ['labels'=>$monthlyLabels, 'in'=>$monthlyIn, 'out'=>$monthlyOut],
            'commissions' => ['labels'=>$dailyLabels, 'values'=>$commVals],
        ];
    }

    private function computeAgents(): array
    {
        $today = Carbon::today();
        $agentsToday = DB::table('collectes as c')
            ->join('tontines as t','t.id','=','c.tontine_id')
            ->where('t.status', '<>', 'cancelled')
            ->leftJoin('users as u','u.id','=','c.agent_id')
            ->whereBetween('c.created_at', [$today->copy()->startOfDay(), $today->copy()->endOfDay()])
            ->groupBy('u.id','u.first_name','u.last_name','u.email')
            ->selectRaw('COALESCE(CONCAT(u.first_name," ",u.last_name), u.email, "—") as label, COUNT(*) as ops, SUM(t.daily_amount) as amount')
            ->orderByDesc('amount')->limit(10)->get()
            ->map(fn($r)=>['label'=>$r->label,'ops'=>(int)$r->ops,'amount'=>(float)$r->amount])->toArray();

        $mStart = Carbon::now()->startOfMonth();
        $agentsMonth = DB::table('collectes as c')
            ->join('tontines as t','t.id','=','c.tontine_id')
            ->where('t.status', '<>', 'cancelled')
            ->leftJoin('users as u','u.id','=','c.agent_id')
            ->where('c.created_at','>=',$mStart)
            ->groupBy('u.id','u.first_name','u.last_name','u.email')
            ->selectRaw('COALESCE(CONCAT(u.first_name," ",u.last_name), u.email, "—") as label, COUNT(*) as ops, SUM(t.daily_amount) as amount')
            ->orderByDesc('amount')->limit(10)->get()
            ->map(fn($r)=>['label'=>$r->label,'ops'=>(int)$r->ops,'amount'=>(float)$r->amount])->toArray();

        return ['today'=>$agentsToday,'month'=>$agentsMonth];
    }

    private function computeStatusCounts(): array
    {
        $raw = DB::table('tontines')
            ->selectRaw('status, COUNT(*) as c')
            ->groupBy('status')
            ->pluck('c','status')
            ->toArray();

        $all = ['draft'=>0,'active'=>0,'completed'=>0,'paid'=>0,'archived'=>0,'cancelled'=>0];
        foreach ($all as $k=>$_) $all[$k] = (int)($raw[$k] ?? 0);
        return $all;
    }

    /**
     * Compute per-agent commission summaries.
     * - planned: sum(daily_amount * commission_days) for tontines created by the agent (exclude draft/cancelled)
     * - real: sum(payouts.commission_amount) for payouts belonging to tontines created by the agent
     *
     * Returns array of ['id'=>..., 'name'=>..., 'email'=>..., 'planned'=>float, 'real'=>float]
     */
    private function computeAgentCommissions(): array
    {
        $plannedQ = DB::table('tontines')
            ->whereRaw("status NOT IN ('draft','cancelled')")
            ->selectRaw("created_by_agent_id as agent_id, SUM(COALESCE(daily_amount,0) * COALESCE(commission_days,0)) as planned")
            ->groupBy('created_by_agent_id');

        $realQ = DB::table('payouts')
            ->join('tontines', 'payouts.tontine_id', '=', 'tontines.id')
            ->selectRaw('tontines.created_by_agent_id as agent_id, SUM(payouts.commission_amount) as real_comm')
            ->groupBy('tontines.created_by_agent_id');

        $users = DB::table('users')
            ->whereRaw("users.role = 'agent'")
            ->leftJoinSub($plannedQ, 'p', 'users.id', '=', 'p.agent_id')
            ->leftJoinSub($realQ, 'r', 'users.id', '=', 'r.agent_id')
            ->selectRaw("users.id, COALESCE(users.first_name,'') as first_name, COALESCE(users.last_name,'') as last_name, users.email, COALESCE(p.planned,0) as planned, COALESCE(r.real_comm,0) as real_amount")
            ->orderByDesc('planned')
            ->get()
            ->map(function ($u) {
                return [
                    'id' => $u->id,
                    'name' => trim(($u->first_name ?? '') . ' ' . ($u->last_name ?? '')) ?: ($u->email ?? ''),
                    'email' => $u->email,
                    'planned' => (float) $u->planned,
                    'real' => (float) ($u->real_amount ?? 0),
                ];
            })->toArray();

        return $users;
    }
}