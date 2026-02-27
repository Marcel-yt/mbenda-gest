<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class ClientController extends ApiController
{
    /**
     * GET /api/admin/clients
     * Params: q, status, date_from, date_to, per_page
     */
    public function index(Request $request): JsonResponse
    {
        $q         = trim($request->get('q', ''));
        $status    = $request->get('status', '');
        $date_from = $request->get('date_from', '');
        $date_to   = $request->get('date_to', '');
        $perPage   = min((int) $request->get('per_page', 20), 100);

        $query = Client::with('creatorAgent:id,first_name,last_name')->orderByDesc('created_at');

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name',  'like', "%{$q}%")
                    ->orWhere('phone',      'like', "%{$q}%")
                    ->orWhere('indicatif',  'like', "%{$q}%")
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

        return $this->paginated($query->paginate($perPage));
    }

    /**
     * GET /api/admin/clients/{id}
     */
    public function show(int $id): JsonResponse
    {
        $client = Client::with(['creatorAgent:id,first_name,last_name', 'tontines'])->findOrFail($id);
        $data   = $client->toArray();
        $data['photo_url'] = $client->photo_profil ? asset('storage/'.$client->photo_profil) : null;
        return $this->success($data);
    }

    /**
     * PUT /api/admin/clients/{id}
     * Body: first_name, last_name?, indicatif?, phone?, address?, notes?, statut?, photo_profil?
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $client = Client::findOrFail($id);

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
            if ($client->photo_profil) {
                Storage::disk('public')->delete($client->photo_profil);
            }
            $client->photo_profil = $request->file('photo_profil')->store('clients/photos', 'public');
        }

        $client->first_name = $data['first_name'];
        $client->last_name  = $data['last_name'] ?? null;
        $client->indicatif  = $data['indicatif'] ?? null;
        $client->phone      = $data['phone'] ?? null;
        $client->address    = $data['address'] ?? null;
        $client->notes      = $data['notes'] ?? null;
        $client->statut     = array_key_exists('statut', $data)
            ? (bool) $data['statut']
            : $client->statut;
        $client->save();

        return $this->success(['id' => $client->id, 'name' => trim(($client->first_name ?? '').' '.($client->last_name ?? ''))], 'Client mis à jour.');
    }

    /**
     * DELETE /api/admin/clients/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        $client = Client::withTrashed()->findOrFail($id);

        if ($client->photo_profil) {
            Storage::disk('public')->delete($client->photo_profil);
        }

        $client->forceDelete();

        return $this->success(null, 'Client supprimé.');
    }
}
