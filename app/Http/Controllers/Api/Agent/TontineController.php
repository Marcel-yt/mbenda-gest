<?php

namespace App\Http\Controllers\Api\Agent;

use App\Http\Controllers\Api\ApiController;
use App\Models\Tontine;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class TontineController extends ApiController
{
    /**
     * GET /api/agent/tontines
     * Params: q, status, created_from, created_to, per_page
     */
    public function index(Request $request): JsonResponse
    {
        $q            = trim($request->get('q', ''));
        $status       = $request->get('status', '');
        $created_from = $request->get('created_from', '');
        $created_to   = $request->get('created_to', '');
        $perPage      = min((int) $request->get('per_page', 20), 100);

        $allowed = ['draft', 'active', 'completed', 'paid', 'archived', 'cancelled'];

        $query = Tontine::with(['client:id,first_name,last_name,phone,address', 'creator:id,first_name,last_name'])
            ->orderByDesc('id');

        if ($q !== '') {
            $query->where(function ($s) use ($q) {
                $s->whereHas('client', function ($c) use ($q) {
                    $c->where('first_name', 'like', "%{$q}%")
                      ->orWhere('last_name', 'like', "%{$q}%")
                      ->orWhere('phone', 'like', "%{$q}%");
                })->orWhere('code', 'like', "%{$q}%");
            });
        }

        if ($status !== '' && in_array($status, $allowed, true)) {
            $query->where('status', $status);
        }

        $from = $to = null;
        try { if ($created_from) $from = Carbon::createFromFormat('Y-m-d', $created_from)->startOfDay(); } catch (\Throwable $e) {}
        try { if ($created_to)   $to   = Carbon::createFromFormat('Y-m-d', $created_to)->endOfDay();   } catch (\Throwable $e) {}

        if ($from && $to)   $query->whereBetween('created_at', [$from, $to]);
        elseif ($from)      $query->where('created_at', '>=', $from);
        elseif ($to)        $query->where('created_at', '<=', $to);

        $paginator = $query->paginate($perPage);

        return $this->paginated($paginator);
    }

    /**
     * POST /api/agent/tontines
     * Body: client_id, daily_amount, start_date, allow_early_payout?
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'client_id'          => 'required|exists:clients,id',
            'daily_amount'       => 'required|numeric|min:0',
            'start_date'         => 'required|date',
            'allow_early_payout' => 'nullable|boolean',
        ]);

        // Règles métier fixes
        $data['duration_days']         = 31;
        $data['commission_days']       = 1;
        $data['created_by_agent_id']   = $request->user()->id;

        $tontine = Tontine::create($data);
        $tontine->load(['client:id,first_name,last_name,phone', 'creator:id,first_name,last_name']);

        return $this->success($tontine->toArray(), 'Tontine créée.', 201);
    }

    /**
     * GET /api/agent/tontines/{tontine}
     */
    public function show(Tontine $tontine): JsonResponse
    {
        $tontine->load('client');
        return $this->success($tontine->toArray());
    }

    /**
     * PUT /api/agent/tontines/{tontine}
     * Body: daily_amount?, start_date?, allow_early_payout?
     * Modification autorisée uniquement sur les tontines en brouillon.
     */
    public function update(Request $request, Tontine $tontine): JsonResponse
    {
        if ($tontine->status !== 'draft') {
            return $this->error(
                'Seules les tontines en brouillon peuvent être modifiées (statut actuel : '.$tontine->status.').',
                [],
                422
            );
        }

        $data = $request->validate([
            'daily_amount'       => 'sometimes|numeric|min:0',
            'start_date'         => 'sometimes|date',
            'allow_early_payout' => 'sometimes|boolean',
        ]);

        if (isset($data['daily_amount']))       $tontine->daily_amount       = $data['daily_amount'];
        if (isset($data['start_date']))         $tontine->start_date         = Carbon::parse($data['start_date']);
        if (isset($data['allow_early_payout'])) $tontine->allow_early_payout = $data['allow_early_payout'];

        $tontine->save();
        $tontine->load(['client:id,first_name,last_name,phone', 'creator:id,first_name,last_name']);

        return $this->success($tontine->toArray(), 'Tontine mise à jour.');
    }

    /**
     * POST /api/agent/tontines/{tontine}/cancel
     * Body: cancel_reason?
     */
    public function cancel(Request $request, Tontine $tontine): JsonResponse
    {
        if ($tontine->status === 'paid') {
            return $this->error('Impossible d\'annuler une tontine déjà payée.', [], 422);
        }

        if ($tontine->status === 'cancelled') {
            return $this->error('La tontine est déjà annulée.', [], 422);
        }

        $data = $request->validate([
            'cancel_reason' => 'nullable|string|max:1000',
        ]);

        $tontine->status           = 'cancelled';
        $tontine->cancelled_by     = $request->user()->id;
        $tontine->cancelled_reason = $data['cancel_reason'] ?? null;
        $tontine->cancelled_at     = now();
        $tontine->save();

        return $this->success($tontine->toArray(), 'Tontine annulée.');
    }

    /**
     * GET /api/agent/tontines/clients/search?q=
     * Recherche AJAX de clients.
     */
    public function searchClients(Request $request): JsonResponse
    {
        $q = trim((string) $request->query('q', ''));
        if (strlen($q) < 1) {
            return $this->success([], 'Résultats vides.');
        }

        $qLower = mb_strtolower($q);
        $items  = Client::query()
            ->where(function ($query) use ($qLower, $q) {
                $query->whereRaw('LOWER(first_name) LIKE ?', ["%{$qLower}%"])
                      ->orWhereRaw('LOWER(last_name) LIKE ?', ["%{$qLower}%"])
                      ->orWhere('phone', 'like', "%{$q}%")
                      ->orWhereRaw("LOWER(CONCAT(COALESCE(first_name,'') , ' ' , COALESCE(last_name,''))) LIKE ?", ["%{$qLower}%"]);
            })
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->limit(50)
            ->get(['id', 'first_name', 'last_name', 'phone'])
            ->map(function ($c) {
                $label = trim(($c->first_name ?? '').' '.($c->last_name ?? ''));
                if (!empty($c->phone)) $label .= ' · '.$c->phone;
                return ['id' => $c->id, 'text' => $label, 'phone' => $c->phone];
            });

        return $this->success($items->values()->all());
    }
}
