<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\Collecte;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class CollecteController extends ApiController
{
    /**
     * GET /api/admin/collectes
     * Params: q_client, q_agent, date_from, date_to, per_page
     */
    public function index(Request $request): JsonResponse
    {
        $qClient  = trim($request->get('q_client', ''));
        $qAgent   = trim($request->get('q_agent', ''));
        $dateFrom = $request->get('date_from', '');
        $dateTo   = $request->get('date_to', '');
        $perPage  = min((int) $request->get('per_page', 20), 100);

        $query = Collecte::query()
            ->with(['tontine.client:id,first_name,last_name,phone', 'agent:id,first_name,last_name'])
            ->orderByDesc('id');

        if ($qClient !== '') {
            $query->whereHas('tontine.client', function ($c) use ($qClient) {
                $c->where(function ($w) use ($qClient) {
                    $w->where('first_name', 'like', "%{$qClient}%")
                      ->orWhere('last_name',  'like', "%{$qClient}%");
                    if (Schema::hasColumn('clients', 'phone')) {
                        $w->orWhere('phone', 'like', "%{$qClient}%");
                    }
                });
            });
        }

        if ($qAgent !== '') {
            $query->whereHas('agent', function ($u) use ($qAgent) {
                $u->where(function ($w) use ($qAgent) {
                    $w->where('first_name', 'like', "%{$qAgent}%")
                      ->orWhere('last_name',  'like', "%{$qAgent}%");
                });
            });
        }

        $dateColumn = Schema::hasColumn('collectes', 'collected_at') ? 'collected_at' : 'created_at';
        $from = $to = null;
        try { if ($dateFrom) $from = Carbon::createFromFormat('Y-m-d', $dateFrom)->startOfDay(); } catch (\Throwable $e) {}
        try { if ($dateTo)   $to   = Carbon::createFromFormat('Y-m-d', $dateTo)->endOfDay();   } catch (\Throwable $e) {}

        if ($from && $to)  $query->whereBetween($dateColumn, [$from, $to]);
        elseif ($from)     $query->where($dateColumn, '>=', $from);
        elseif ($to)       $query->where($dateColumn, '<=', $to);

        return $this->paginated($query->paginate($perPage));
    }

    /**
     * GET /api/admin/collectes/{id}
     */
    public function show(int $id): JsonResponse
    {
        $collecte = Collecte::with(['tontine.client', 'agent:id,first_name,last_name,color_hex'])->findOrFail($id);
        return $this->success($collecte->toArray());
    }
}
