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

    public function index(Request $request): View
    {
        // Liste visible par tous les agents : ne pas filtrer par created_by_agent_id
        $tontines = Tontine::with(['client','creator'])
            ->orderByDesc('created_at')
            ->paginate(25);

        return view('pages.app.agent.tontines.index', compact('tontines'));
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