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
        ]);
    }

    public function stats(Request $request)
    {
        $totals = $this->computeTotals();
        $series = $this->computeSeries();
        $agents = $this->computeAgents();
        $status = $this->computeStatusCounts();

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
        ]));
    }

    private function computeTotals(): array
    {
        $totalClients   = DB::table('clients')->count();
        $totalTontines  = DB::table('tontines')->count();
        $totalCollectes = DB::table('collectes')->count();
        $totalPayouts   = DB::table('payouts')->count();

        $amountInTotal = (float) DB::table('collectes as c')
            ->join('tontines as t', 't.id', '=', 'c.tontine_id')
            ->sum('t.daily_amount');

        $amountOutTotal = (float) DB::table('payouts')->sum('amount_net');
        $commissionTotal= (float) DB::table('payouts')->sum('commission_amount');
        $netTotal       = $amountInTotal - $amountOutTotal;

        $now = Carbon::now();
        $periods = [
            'today' => $this->ioBetween($now->copy()->startOfDay(), $now->copy()->endOfDay()),
            'week'  => $this->ioBetween($now->copy()->startOfWeek(), $now->copy()->endOfWeek()),
            'month' => $this->ioBetween($now->copy()->startOfMonth(), $now->copy()->endOfMonth()),
            'year'  => $this->ioBetween($now->copy()->startOfYear(), $now->copy()->endOfYear()),
        ];

        return compact(
            'totalClients',
            'totalTontines',
            'totalCollectes',
            'totalPayouts',
            'amountInTotal',
            'amountOutTotal',
            'commissionTotal',
            'netTotal',
            'periods'
        );
    }

    // In/Out/Net/Commission sur période; null=null => global
    private function ioBetween(?Carbon $from, ?Carbon $to): array
    {
        $inQ = DB::table('collectes as c')->join('tontines as t', 't.id', '=', 'c.tontine_id');
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
            ->leftJoin('users as u','u.id','=','c.agent_id')
            ->whereBetween('c.created_at', [$today->copy()->startOfDay(), $today->copy()->endOfDay()])
            ->groupBy('u.id','u.first_name','u.last_name','u.email')
            ->selectRaw('COALESCE(CONCAT(u.first_name," ",u.last_name), u.email, "—") as label, COUNT(*) as ops, SUM(t.daily_amount) as amount')
            ->orderByDesc('amount')->limit(10)->get()
            ->map(fn($r)=>['label'=>$r->label,'ops'=>(int)$r->ops,'amount'=>(float)$r->amount])->toArray();

        $mStart = Carbon::now()->startOfMonth();
        $agentsMonth = DB::table('collectes as c')
            ->join('tontines as t','t.id','=','c.tontine_id')
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
}