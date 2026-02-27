<?php

namespace App\Http\Controllers\Api\Agent;

use App\Http\Controllers\Api\ApiController;
use App\Models\Collecte;
use App\Models\Tontine;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class CollecteController extends ApiController
{
    /**
     * GET /api/agent/collectes?tontine_id=
     * Retourne le calendrier des collectes d'une tontine.
     */
    public function index(Request $request): JsonResponse
    {
        $tontineId = $request->query('tontine_id');

        if (empty($tontineId)) {
            return $this->error('Le paramètre tontine_id est requis.', [], 422);
        }

        $tontine = Tontine::with('client')->findOrFail($tontineId);

        $collectes = Collecte::where('tontine_id', $tontine->id)
            ->with(['agent:id,first_name,last_name,color_hex'])
            ->orderBy('created_at')
            ->get();

        $byDate = $collectes->groupBy(function ($c) {
            if (! empty($c->for_date)) {
                return Carbon::parse($c->for_date)->toDateString();
            }
            return optional($c->created_at)->toDateString();
        });

        $days      = [];
        $start     = Carbon::parse($tontine->start_date);
        $totalDays = intval($tontine->duration_days ?: 31);

        for ($i = 0; $i < $totalDays; $i++) {
            $date    = $start->copy()->addDays($i);
            $dateKey = $date->toDateString();
            $items   = $byDate->get($dateKey, collect());

            $days[] = [
                'date'      => $dateKey,
                'day'       => $i + 1,
                'is_today'  => $date->isToday(),
                'collected' => $items->isNotEmpty(),
                'collectes' => $items->map(fn($c) => [
                    'id'         => $c->id,
                    'notes'      => $c->notes,
                    'for_date'   => $c->for_date,
                    'created_at' => $c->created_at?->toIso8601String(),
                    'agent'      => $c->agent ? [
                        'id'         => $c->agent->id,
                        'name'       => trim(($c->agent->first_name ?? '').' '.($c->agent->last_name ?? '')),
                        'color_hex'  => $c->agent->color_hex,
                    ] : null,
                ])->values()->all(),
            ];
        }

        return $this->success([
            'tontine' => [
                'id'           => $tontine->id,
                'code'         => $tontine->code,
                'status'       => $tontine->status,
                'daily_amount' => $tontine->daily_amount,
                'start_date'   => $tontine->start_date,
                'duration_days'=> $tontine->duration_days,
                'client'       => $tontine->client ? [
                    'id'         => $tontine->client->id,
                    'name'       => trim(($tontine->client->first_name ?? '').' '.($tontine->client->last_name ?? '')),
                    'phone'      => $tontine->client->phone,
                    'address'    => $tontine->client->address,
                ] : null,
            ],
            'days'     => $days,
            'collected'=> $collectes->count(),
            'remaining'=> max(0, $totalDays - $collectes->count()),
        ]);
    }

    /**
     * POST /api/agent/collectes
     * Body: tontine_id, notes?, days?
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'tontine_id' => 'required|exists:tontines,id',
            'notes'      => 'nullable|string',
            'days'       => 'nullable|integer|min:1',
        ]);

        $tontine = Tontine::findOrFail($data['tontine_id']);

        if ($tontine->status === 'paid') {
            return $this->error('Cette tontine a été payée. Il n\'est plus possible d\'enregistrer des collectes.', [], 422);
        }

        if ($tontine->status === 'cancelled') {
            return $this->error('Cette tontine est annulée.', [], 422);
        }

        // Trouver les dates déjà collectées
        $existing = Collecte::where('tontine_id', $tontine->id)
            ->get()
            ->groupBy(function ($c) {
                return ! empty($c->for_date)
                    ? Carbon::parse($c->for_date)->toDateString()
                    : optional($c->created_at)->toDateString();
            });

        $start      = Carbon::parse($tontine->start_date);
        $totalDays  = intval($tontine->duration_days ?: 31);

        // Première date non collectée
        $firstUncollected = null;
        for ($i = 0; $i < $totalDays; $i++) {
            $date = $start->copy()->addDays($i)->toDateString();
            if (! $existing->has($date) || $existing->get($date)->isEmpty()) {
                $firstUncollected = $date;
                break;
            }
        }

        if (! $firstUncollected) {
            return $this->error('Aucune date restante à collecter pour cette tontine.', [], 422);
        }

        // Nombre de jours restants
        $remaining = 0;
        for ($i = 0; $i < $totalDays; $i++) {
            $date = $start->copy()->addDays($i)->toDateString();
            if (! $existing->has($date) || $existing->get($date)->isEmpty()) {
                $remaining++;
            }
        }

        $daysToCollect = isset($data['days']) ? (int) $data['days'] : 1;
        $daysToCreate  = min($daysToCollect, $remaining);

        $current      = Carbon::parse($firstUncollected);
        $createdCount = 0;
        $created      = [];

        for ($j = 0; $j < $daysToCreate; $j++) {
            $dateFor = $current->copy()->addDays($j)->toDateString();

            if ($existing->has($dateFor) && $existing->get($dateFor)->isNotEmpty()) continue;

            $collecte = Collecte::create([
                'tontine_id' => $tontine->id,
                'client_id'  => $tontine->client_id,
                'agent_id'   => $request->user()->id,
                'notes'      => $data['notes'] ?? null,
                'for_date'   => $dateFor,
            ]);

            $tontine->increment('collected_total', $tontine->daily_amount);
            $created[] = $collecte->toArray();
            $createdCount++;
        }

        $tontine->updateStatusAfterCollecte();

        return $this->success([
            'created_count' => $createdCount,
            'collectes'     => $created,
        ], "{$createdCount} collecte(s) enregistrée(s).", 201);
    }

    /**
     * GET /api/agent/collectes/{id}
     */
    public function show(int $id): JsonResponse
    {
        $collecte = Collecte::with(['tontine.client', 'agent:id,first_name,last_name,color_hex'])->findOrFail($id);
        return $this->success($collecte->toArray());
    }
}
