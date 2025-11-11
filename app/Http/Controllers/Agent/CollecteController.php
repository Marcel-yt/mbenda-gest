<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Collecte;
use App\Models\Tontine;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;

class CollecteController extends Controller
{
    /**
     * Affiche le calendrier des collectes pour une tontine donnée.
     * Requiert ?tontine_id= ; si absent redirige vers la liste des tontines.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $tontineId = $request->query('tontine_id');
        if (empty($tontineId)) {
            return redirect()->route('agent.tontines.index');
        }

        $tontine = Tontine::with('client')->findOrFail($tontineId);

        // Charger l'agent (pour accéder à sa couleur) et trier par date réelle
        $collectes = \App\Models\Collecte::where('tontine_id', $tontine->id)
            ->with(['agent:id,first_name,last_name,color_hex'])
            ->orderBy('created_at')
            ->get();

        // Grouper par jour de calendrier si présent (for_date), sinon par created_at
        $byDate = $collectes->groupBy(function ($c) {
            if (!empty($c->for_date)) {
                return \Illuminate\Support\Carbon::parse($c->for_date)->toDateString();
            }
            return optional($c->created_at)->toDateString();
        });

        $days = [];
        $start = Carbon::parse($tontine->start_date);
        $totalDays = intval($tontine->duration_days ?: 31);

        for ($i = 0; $i < $totalDays; $i++) {
            $date = $start->copy()->addDays($i);
            $dateKey = $date->toDateString();
            $items = $byDate->get($dateKey, collect());
            $days[] = [
                'date' => $dateKey,
                'day' => $i + 1,
                'is_today' => $date->isToday(),
                'collectes' => $items,
            ];
        }

        return view('pages.app.agent.collectes.index', compact('tontine', 'days'));
    }

    /**
     * Formulaire de création d'une collecte.
     * Accepte ?tontine_id= pour pré-remplir la tontine/client.
     */
    public function create(Request $request): View
    {
        $tontine = $request->filled('tontine_id')
            ? Tontine::with('client')->findOrFail($request->query('tontine_id'))
            : null;

        return view('pages.app.agent.collectes.create', compact('tontine'));
    }

    /**
     * Enregistre une collecte.
     * Montant non stocké ici : la valeur est implicite (tontine.daily_amount).
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'tontine_id' => 'required|exists:tontines,id',
            'notes' => 'nullable|string',
            'confirmed' => 'accepted',
            'date' => 'nullable|date', // jour cliqué dans le calendrier
        ]);

        $tontine = Tontine::findOrFail($data['tontine_id']);

        $collecte = Collecte::create([
            'tontine_id' => $tontine->id,
            'client_id' => $tontine->client_id,
            'agent_id' => $request->user()->id,
            'notes' => $data['notes'] ?? null,
            'for_date' => !empty($data['date']) ? \Illuminate\Support\Carbon::parse($data['date'])->toDateString() : null,
        ]);

        // Incrément du total collecté
        $tontine->increment('collected_total', $tontine->daily_amount);

        // Mettre à jour le statut de la tontine
        $tontine->updateStatusAfterCollecte();

        return redirect()->route('agent.collectes.index', ['tontine_id' => $tontine->id])
            ->with('success', 'Collecte enregistrée.');
    }

    /**
     * Affiche une collecte spécifique.
     */
    public function show(Collecte $collecte): View
    {
        $collecte->load(['tontine.client','agent']);
        return view('pages.app.agent.collectes.show', compact('collecte'));
    }
}