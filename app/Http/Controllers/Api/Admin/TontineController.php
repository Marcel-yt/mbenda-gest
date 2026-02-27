<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\Tontine;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class TontineController extends ApiController
{
    /**
     * GET /api/admin/tontines
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

        $query = Tontine::with(['client:id,first_name,last_name,phone', 'creator:id,first_name,last_name'])->orderByDesc('id');

        if ($q !== '') {
            $query->where(function ($s) use ($q) {
                $s->whereHas('client', function ($c) use ($q) {
                    $c->where('first_name', 'like', "%{$q}%")
                      ->orWhere('last_name', 'like', "%{$q}%")
                      ->orWhere('phone', 'like', "%{$q}%");
                })->orWhere('code', 'like', "%{$q}%");
            });
        }

        // Compteurs par statut (avant filtre status)
        $baseQuery    = clone $query;
        $from = $to   = null;
        try { if ($created_from) $from = Carbon::createFromFormat('Y-m-d', $created_from)->startOfDay(); } catch (\Throwable $e) {}
        try { if ($created_to)   $to   = Carbon::createFromFormat('Y-m-d', $created_to)->endOfDay();   } catch (\Throwable $e) {}

        if ($from && $to)  $baseQuery->whereBetween('created_at', [$from, $to]);
        elseif ($from)     $baseQuery->where('created_at', '>=', $from);
        elseif ($to)       $baseQuery->where('created_at', '<=', $to);

        $counts = [
            'total'     => (clone $baseQuery)->count(),
            'active'    => (clone $baseQuery)->where('status', 'active')->count(),
            'draft'     => (clone $baseQuery)->where('status', 'draft')->count(),
            'completed' => (clone $baseQuery)->where('status', 'completed')->count(),
            'paid'      => (clone $baseQuery)->where('status', 'paid')->count(),
            'cancelled' => (clone $baseQuery)->where('status', 'cancelled')->count(),
        ];

        if ($status !== '' && in_array($status, $allowed, true)) {
            $query->where('status', $status);
        }
        if ($from && $to)  $query->whereBetween('created_at', [$from, $to]);
        elseif ($from)     $query->where('created_at', '>=', $from);
        elseif ($to)       $query->where('created_at', '<=', $to);

        $paginator = $query->paginate($perPage);

        // Inject counts into the response
        $response          = $this->paginated($paginator)->getData(true);
        $response['counts'] = $counts;

        return response()->json($response);
    }

    /**
     * GET /api/admin/tontines/{id}
     */
    public function show(int $id): JsonResponse
    {
        $tontine = Tontine::with('client')->findOrFail($id);
        return $this->success($tontine->toArray());
    }

    /**
     * PUT /api/admin/tontines/{id}
     * Body: daily_amount, start_date?
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $tontine = Tontine::findOrFail($id);

        $data = $request->validate([
            'daily_amount' => 'required|numeric|min:0',
            'start_date'   => 'nullable|date',
        ]);

        if (! empty($data['start_date'])) {
            $tontine->start_date = Carbon::parse($data['start_date']);
            if ($tontine->duration_days) {
                $tontine->expected_end_date = $tontine->start_date->copy()->addDays(max(1, (int) $tontine->duration_days) - 1);
            }
        }

        $tontine->daily_amount = $data['daily_amount'];
        $tontine->save();

        return $this->success($tontine->toArray(), 'Tontine mise à jour.');
    }

    /**
     * DELETE /api/admin/tontines/{id}
     * Supprime définitivement une tontine (uniquement si draft ou cancelled).
     */
    public function destroy(int $id): JsonResponse
    {
        $tontine = Tontine::findOrFail($id);

        if (! in_array($tontine->status, ['draft', 'cancelled'])) {
            return $this->error(
                'Seules les tontines en brouillon ou annulées peuvent être supprimées (statut actuel : '.$tontine->status.').',
                [],
                422
            );
        }

        $tontine->delete();

        return $this->success(null, 'Tontine supprimée.');
    }

    /**
     * POST /api/admin/tontines/{id}/pay
     * Body: force? (boolean) — force si status active
     */
    public function pay(Request $request, int $id): JsonResponse
    {
        $tontine = Tontine::findOrFail($id);

        if (in_array($tontine->status, ['paid', 'cancelled', 'archived', 'draft'])) {
            return $this->error('Paiement non autorisé pour ce statut ('.$tontine->status.').', [], 422);
        }

        if ($tontine->status === 'active' && ! $request->boolean('force')) {
            return $this->error('Confirmation requise pour passer une tontine active à payée.', [
                'force' => 'Envoyer force=true pour confirmer.',
            ], 422);
        }

        $tontine->status  = 'paid';
        $tontine->paid_at = $tontine->paid_at ?: now();
        $tontine->save();

        return $this->success($tontine->toArray(), 'Statut passé à paid.');
    }

    /**
     * POST /api/admin/tontines/{id}/finalize
     * Marque la tontine comme completed.
     */
    public function finalize(Request $request, int $id): JsonResponse
    {
        $tontine = Tontine::findOrFail($id);

        if (! in_array($tontine->status, ['active', 'draft'])) {
            return $this->error('Finalisation non autorisée pour ce statut.', [], 422);
        }

        $tontine->status = 'completed';
        $tontine->save();

        return $this->success($tontine->toArray(), 'Tontine finalisée.');
    }

    /**
     * POST /api/admin/tontines/{id}/cancel
     * Body: cancel_reason?
     */
    public function cancel(Request $request, int $id): JsonResponse
    {
        $tontine = Tontine::findOrFail($id);

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
}
