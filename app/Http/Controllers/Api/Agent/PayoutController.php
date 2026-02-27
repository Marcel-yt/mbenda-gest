<?php

namespace App\Http\Controllers\Api\Agent;

use App\Http\Controllers\Api\ApiController;
use App\Models\Payout;
use App\Models\Tontine;
use App\Models\Collecte;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PayoutController extends ApiController
{
    /**
     * GET /api/agent/payouts
     * Params: q_client, paid_from, paid_to, per_page
     */
    public function index(Request $request): JsonResponse
    {
        $qClient  = trim($request->get('q_client', ''));
        $paidFrom = $request->get('paid_from', '');
        $paidTo   = $request->get('paid_to', '');
        $perPage  = min((int) $request->get('per_page', 20), 100);

        $query = Payout::with(['client:id,first_name,last_name,phone', 'tontine:id,code,daily_amount', 'admin:id,first_name,last_name'])
            ->orderByDesc('id');

        if ($qClient !== '') {
            $query->where(function ($w) use ($qClient) {
                $w->whereHas('client', function ($c) use ($qClient) {
                    $c->where('first_name', 'like', "%{$qClient}%")
                      ->orWhere('last_name',  'like', "%{$qClient}%")
                      ->orWhere('phone',      'like', "%{$qClient}%");
                })->orWhereHas('tontine', function ($t) use ($qClient) {
                    $t->where('code', 'like', "%{$qClient}%");
                });
            });
        }

        $from = $to = null;
        try { if ($paidFrom) $from = Carbon::createFromFormat('Y-m-d', $paidFrom)->startOfDay(); } catch (\Throwable $e) {}
        try { if ($paidTo)   $to   = Carbon::createFromFormat('Y-m-d', $paidTo)->endOfDay();   } catch (\Throwable $e) {}

        if ($from && $to) $query->whereBetween('paid_at', [$from, $to]);
        elseif ($from)    $query->where('paid_at', '>=', $from);
        elseif ($to)      $query->where('paid_at', '<=', $to);

        return $this->paginated($query->paginate($perPage));
    }

    /**
     * GET /api/agent/payouts/{id}
     */
    public function show(int $id): JsonResponse
    {
        $payout = Payout::with(['tontine.client', 'admin:id,first_name,last_name'])->findOrFail($id);

        $data        = $payout->toArray();
        // Ajouter l'URL du reçu si disponible
        $data['receipt_url'] = $payout->receipt_path && Storage::disk('public')->exists($payout->receipt_path)
            ? asset('storage/'.$payout->receipt_path)
            : null;

        return $this->success($data);
    }

    /**
     * GET /api/agent/payouts/summary/{tontine_id}
     * Calcul du résumé avant paiement (montant brut, commission, net).
     */
    public function summary(int $tontine_id): JsonResponse
    {
        $tontine = Tontine::with('client')->findOrFail($tontine_id);

        if (Payout::where('tontine_id', $tontine->id)->exists()) {
            return $this->error('Paiement déjà enregistré pour cette tontine.', [], 422);
        }

        $collectesQuery = Collecte::where('tontine_id', $tontine->id);
        $daysCollected  = $collectesQuery->when(
            \Schema::hasColumn('collectes', 'for_date'),
            fn($q) => $q->distinct('for_date')->count('for_date'),
            fn()   => $collectesQuery->count()
        );

        $effectiveDays      = min($daysCollected, (int) ($tontine->duration_days ?? 0));
        $daily              = (float) ($tontine->daily_amount ?? 0);
        $amount_gross       = round($daily * max(0, $effectiveDays), 2);
        $commission_amount  = round($daily, 2);
        $amount_net         = round(max(0, $amount_gross - $commission_amount), 2);
        $currency           = $tontine->settings['currency'] ?? 'XAF';

        return $this->success([
            'tontine_id'        => $tontine->id,
            'tontine_code'      => $tontine->code,
            'daily_amount'      => $daily,
            'days_collected'    => $daysCollected,
            'effective_days'    => $effectiveDays,
            'amount_gross'      => $amount_gross,
            'commission_amount' => $commission_amount,
            'amount_net'        => $amount_net,
            'currency'          => $currency,
            'client'            => $tontine->client ? [
                'id'   => $tontine->client->id,
                'name' => trim(($tontine->client->first_name ?? '').' '.($tontine->client->last_name ?? '')),
            ] : null,
        ]);
    }

    /**
     * POST /api/agent/payouts
     * Body: tontine_id, notes?
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'tontine_id' => 'required|exists:tontines,id',
            'notes'      => 'nullable|string|max:2000',
        ]);

        $tontine = Tontine::with('client')->findOrFail($data['tontine_id']);

        if (Payout::where('tontine_id', $tontine->id)->exists()) {
            return $this->error('Paiement déjà enregistré pour cette tontine.', [], 422);
        }

        $payout = DB::transaction(function () use ($tontine, $data) {
            $collectesQuery = Collecte::where('tontine_id', $tontine->id);
            $daysCollected  = $collectesQuery->when(
                \Schema::hasColumn('collectes', 'for_date'),
                fn($q) => $q->distinct('for_date')->count('for_date'),
                fn()   => $collectesQuery->count()
            );

            $effectiveDays     = min($daysCollected, (int) ($tontine->duration_days ?? 0));
            $daily             = (float) ($tontine->daily_amount ?? 0);
            $amount_gross      = round($daily * max(0, $effectiveDays), 2);
            $commission_amount = round($daily, 2);
            $amount_net        = round(max(0, $amount_gross - $commission_amount), 2);

            $payout = Payout::create([
                'tontine_id'        => $tontine->id,
                'client_id'         => $tontine->client_id,
                'paid_by_admin_id'  => auth()->id(),
                'paid_at'           => now(),
                'amount_gross'      => $amount_gross,
                'commission_amount' => $commission_amount,
                'amount_net'        => $amount_net,
                'receipt_path'      => null,
                'notes'             => $data['notes'] ?? null,
            ]);

            $tontine->status  = 'paid';
            $tontine->paid_at = now();
            $tontine->save();

            return $payout;
        });

        // Génération PDF reçu (hors transaction)
        try {
            Storage::disk('public')->makeDirectory('payouts');
            $payout->load(['tontine.client', 'admin']);

            $pdf = app('dompdf.wrapper')
                ->loadView('pages.app.agent.payouts.receipt', [
                    'payout'   => $payout,
                    'skipLogo' => ! extension_loaded('gd'),
                ])
                ->setPaper('A4');

            $relPath = 'payouts/receipt_'.$payout->id.'.pdf';
            Storage::disk('public')->put($relPath, $pdf->output());

            if (Storage::disk('public')->exists($relPath)) {
                $payout->update(['receipt_path' => $relPath]);
            }
        } catch (\Throwable $e) {
            Log::error('API: Erreur génération reçu payout '.$payout->id.' : '.$e->getMessage());
        }

        $receiptUrl = $payout->receipt_path && Storage::disk('public')->exists($payout->receipt_path)
            ? asset('storage/'.$payout->receipt_path)
            : null;

        return $this->success([
            'payout'      => $payout->toArray(),
            'receipt_url' => $receiptUrl,
        ], 'Paiement confirmé.', 201);
    }

    /**
     * GET /api/agent/payouts/{id}/receipt
     * Retourne l'URL du reçu PDF (pour téléchargement mobile).
     */
    public function receipt(int $id): JsonResponse
    {
        $payout = Payout::findOrFail($id);

        if (! $payout->receipt_path || ! Storage::disk('public')->exists($payout->receipt_path)) {
            return $this->error('Reçu non disponible.', [], 404);
        }

        return $this->success([
            'receipt_url' => asset('storage/'.$payout->receipt_path),
        ]);
    }
}
