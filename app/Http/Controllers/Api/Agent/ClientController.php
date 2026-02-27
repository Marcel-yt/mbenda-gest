<?php

namespace App\Http\Controllers\Api\Agent;

use App\Http\Controllers\Api\ApiController;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class ClientController extends ApiController
{
    /**
     * GET /api/agent/clients
     * Params: q, status, date_from, date_to, per_page
     */
    public function index(Request $request): JsonResponse
    {
        $q         = trim($request->get('q', ''));
        $status    = $request->get('status', '');
        $date_from = $request->get('date_from', '');
        $date_to   = $request->get('date_to', '');
        $perPage   = min((int) $request->get('per_page', 20), 100);

        $query = Client::with('creatorAgent:id,first_name,last_name');

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('first_name', 'like', "%{$q}%")
                  ->orWhere('last_name',  'like', "%{$q}%")
                  ->orWhere('phone',      'like', "%{$q}%")
                  ->orWhere('address',    'like', "%{$q}%");
            });
        }

        if (in_array($status, ['active', 'inactive', '1', '0'], true)) {
            $query->where('statut', in_array($status, ['active', '1'], true));
        }

        $from = $to = null;
        try { if ($date_from) $from = Carbon::createFromFormat('Y-m-d', $date_from)->startOfDay(); } catch (\Throwable $e) {}
        try { if ($date_to)   $to   = Carbon::createFromFormat('Y-m-d', $date_to)->endOfDay();   } catch (\Throwable $e) {}

        if ($from && $to)  $query->whereBetween('created_at', [$from, $to]);
        elseif ($from)     $query->where('created_at', '>=', $from);
        elseif ($to)       $query->where('created_at', '<=', $to);

        $paginator = $query->orderByDesc('id')->paginate($perPage);

        return $this->paginated($paginator);
    }

    /**
     * GET /api/agent/clients/{id}
     */
    public function show(int $id): JsonResponse
    {
        $client = Client::with(['creatorAgent:id,first_name,last_name', 'tontines'])->findOrFail($id);
        return $this->success($client->toArray());
    }

    /**
     * POST /api/agent/clients
     * Body: first_name, last_name?, indicatif?, phone?, address?, notes?, statut?, photo_profil?
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'first_name'    => ['required', 'string', 'max:120'],
            'last_name'     => ['nullable', 'string', 'max:120'],
            'indicatif'     => ['nullable', 'string', 'max:50'],
            'phone'         => ['nullable', 'string', 'max:30'],
            'address'       => ['nullable', 'string', 'max:255'],
            'notes'         => ['nullable', 'string'],
            'statut'        => ['nullable', 'in:0,1'],
            'photo_profil'  => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('photo_profil')) {
            $data['photo_profil'] = $request->file('photo_profil')->store('clients/photos', 'public');
        }

        $client = new Client();
        $client->first_name          = $data['first_name'];
        $client->last_name           = $data['last_name'] ?? null;
        $client->indicatif           = $data['indicatif'] ?? null;
        $client->phone               = $data['phone'] ?? null;
        $client->address             = $data['address'] ?? null;
        $client->notes               = $data['notes'] ?? null;
        $client->statut              = isset($data['statut']) ? (bool) $data['statut'] : true;
        $client->photo_profil        = $data['photo_profil'] ?? null;
        $client->created_by_agent_id = $request->user()->id;
        $client->registered_at       = now()->toDateString();
        $client->save();

        return $this->success($client->toArray(), 'Client créé.', 201);
    }
}
