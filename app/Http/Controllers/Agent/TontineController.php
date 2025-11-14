<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Tontine;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TontineController extends Controller
{
    // Note: protège les routes via middleware agent (auth + role:agent)

    public function index(Request $request)
    {
        $q            = trim($request->get('q',''));
        $status       = $request->get('status','');
        $created_from = $request->get('created_from','');
        $created_to   = $request->get('created_to','');

        $allowedStatuses = ['draft','active','completed','paid','archived','cancelled'];

        $query = \App\Models\Tontine::query()
            ->with(['client','creator'])
            ->orderByDesc('id');

        if ($q !== '') {
            $query->where(function ($s) use ($q) {
                $s->whereHas('client', function ($c) use ($q) {
                    $c->where('first_name','like',"%{$q}%")
                      ->orWhere('last_name','like',"%{$q}%")
                      ->orWhere('phone','like',"%{$q}%");
                    if (\Schema::hasColumn('clients','email')) {
                        $c->orWhere('email','like',"%{$q}%");
                    }
                })
                ->orWhere('code','like',"%{$q}%");
            });
        }

        if ($status !== '' && in_array($status, $allowedStatuses, true)) {
            $query->where('status', $status);
        } else {
            $status = '';
        }

        $from = $to = null;
        try { if ($created_from) $from = \Carbon\Carbon::createFromFormat('Y-m-d',$created_from)->startOfDay(); } catch (\Throwable $e) { $created_from=''; }
        try { if ($created_to)   $to   = \Carbon\Carbon::createFromFormat('Y-m-d',$created_to)->endOfDay();   } catch (\Throwable $e) { $created_to=''; }

        if ($from && $to) {
            $query->whereBetween('created_at', [$from,$to]);
        } elseif ($from) {
            $query->where('created_at','>=',$from);
        } elseif ($to) {
            $query->where('created_at','<=',$to);
        }

        $tontines = $query->paginate(20)->appends($request->query());

        return view('pages.app.agent.tontines.index',
            compact('tontines','q','status','created_from','created_to'));
    }

    public function create(): View
    {
        return view('pages.app.agent.tontines.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'daily_amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'duration_days' => 'nullable|integer|min:1',
            'allow_early_payout' => 'boolean',
            'commission_days' => 'nullable|integer|min:0',
        ]);

        // Enforce fixed business rules regardless of user input
        $data['duration_days'] = 31;
        $data['commission_days'] = 1;
        $data['created_by_agent_id'] = $request->user()->id;

        $tontine = Tontine::create($data);

        return redirect()->route('agent.tontines.show', $tontine)->with('success', 'Tontine créée.');
    }

    // Edition et mise à jour côté agent désactivées : gérer depuis l'admin si besoin.

    public function show(Tontine $tontine): View
    {
        // load only existing relations for now
        $tontine->load('client');
        return view('pages.app.agent.tontines.show', compact('tontine'));
    }

    /**
     * AJAX: recherche de clients pour le select (nom / prénom / téléphone)
     */
    public function searchClients(Request $request)
    {
        $q = (string) $request->query('q', '');
        $q = trim($q);
        if (strlen($q) < 1) {
            return response()->json([], 200);
        }

        $items = Client::query()
            ->where(function ($query) use ($q) {
                $query->where('first_name', 'like', "%{$q}%")
                      ->orWhere('last_name', 'like', "%{$q}%")
                      ->orWhere('phone', 'like', "%{$q}%");
            })
            ->limit(10)
            ->get(['id','first_name','last_name','phone'])
            ->map(function ($c) {
                $label = trim(($c->first_name ?? '') . ' ' . ($c->last_name ?? ''));
                if (! empty($c->phone)) $label .= ' · ' . $c->phone;
                return ['id' => $c->id, 'text' => $label];
            });

        return response()->json($items->values(), 200);
    }
}