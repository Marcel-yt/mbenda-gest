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
    public function create(Request $request): View|RedirectResponse
    {
        $tontine = $request->filled('tontine_id')
            ? Tontine::with('client')->findOrFail($request->query('tontine_id'))
            : null;

        // Bloquer si la tontine est payée
        if ($tontine && $tontine->status === 'paid') {
            return redirect()->route('agent.collectes.index', ['tontine_id' => $tontine->id])
                ->with('error', 'Cette tontine a été payée. Il n\'est plus possible d\'enregistrer de nouvelles collectes.');
        }

        // Bloquer si la tontine est annulée
        if ($tontine && $tontine->status === 'cancelled') {
            return redirect()->route('agent.tontines.show', $tontine->id)
                ->with('error', 'Cette tontine est annulée ; il n\'est pas possible d\'ajouter de collectes.');
        }

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
            'date' => 'nullable|date', // (ignored) jour cliqué dans le calendrier
            'days' => 'nullable|integer|min:1', // nombre de jours à collecter
        ]);

        $tontine = Tontine::findOrFail($data['tontine_id']);

        // Bloquer si la tontine est payée
        if ($tontine->status === 'paid') {
            return redirect()->route('agent.collectes.index', ['tontine_id' => $tontine->id])
                ->with('error', 'Cette tontine a été payée. Il n\'est plus possible d\'enregistrer de nouvelles collectes.');
        }

        // Bloquer si la tontine est annulée
        if ($tontine->status === 'cancelled') {
            return redirect()->route('agent.collectes.index', ['tontine_id' => $tontine->id])
                ->with('error', 'Cette tontine est annulée ; il n\'est pas possible d\'ajouter de collectes.');
        }

        // Determine number of days to collect (default 1)
        $daysToCollect = isset($data['days']) ? (int) $data['days'] : 1;

        // Build existing collectes grouped by for_date to find next uncollected day
        $existing = Collecte::where('tontine_id', $tontine->id)
            ->get()
            ->groupBy(function ($c) {
                return !empty($c->for_date) ? \Illuminate\Support\Carbon::parse($c->for_date)->toDateString() : optional($c->created_at)->toDateString();
            });

        $start = \Illuminate\Support\Carbon::parse($tontine->start_date);
        $totalDays = intval($tontine->duration_days ?: 31);

        // find first uncollected date
        $firstUncollected = null;
        for ($i = 0; $i < $totalDays; $i++) {
            $date = $start->copy()->addDays($i)->toDateString();
            if (! $existing->has($date) || $existing->get($date)->isEmpty()) {
                $firstUncollected = $date;
                break;
            }
        }

        if (! $firstUncollected) {
            return redirect()->route('agent.collectes.index', ['tontine_id' => $tontine->id])
                ->with('error', 'Aucune date restante à collecter pour cette tontine.');
        }

        // Cap daysToCollect to remaining days
        $remaining = 0;
        for ($i = 0; $i < $totalDays; $i++) {
            $date = $start->copy()->addDays($i)->toDateString();
            if (! $existing->has($date) || $existing->get($date)->isEmpty()) {
                $remaining++;
            }
        }

        $daysToCreate = min($daysToCollect, $remaining);

        // Create collectes for daysToCreate starting from firstUncollected
        $current = \Illuminate\Support\Carbon::parse($firstUncollected);
        $createdCount = 0;
        for ($j = 0; $j < $daysToCreate; $j++) {
            $dateFor = $current->copy()->addDays($j)->toDateString();

            // skip if already exists just in case
            if ($existing->has($dateFor) && $existing->get($dateFor)->isNotEmpty()) continue;

            Collecte::create([
                'tontine_id' => $tontine->id,
                'client_id' => $tontine->client_id,
                'agent_id' => $request->user()->id,
                'notes' => $data['notes'] ?? null,
                'for_date' => $dateFor,
            ]);

            // increment collected total by daily_amount for each created collecte
            $tontine->increment('collected_total', $tontine->daily_amount);
            $createdCount++;
        }

        // Update status after creations
        $tontine->updateStatusAfterCollecte();

        return redirect()->route('agent.collectes.index', ['tontine_id' => $tontine->id])
            ->with('success', $createdCount > 0 ? "{$createdCount} collecte(s) enregistrée(s)." : 'Aucune collecte enregistrée.');
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