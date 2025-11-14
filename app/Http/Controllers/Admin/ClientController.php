<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        // base query conservée
        $query = Client::with('creatorAgent')->orderBy('created_at', 'desc');

        // filtres (ajout sans casser l’existant)
        $q         = trim($request->get('q',''));
        $status    = $request->get('status','');
        $date_from = $request->get('date_from','');
        $date_to   = $request->get('date_to','');

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('first_name','like',"%{$q}%")
                    ->orWhere('last_name','like',"%{$q}%")
                    ->orWhere('phone','like',"%{$q}%")
                    ->orWhere('indicatif','like',"%{$q}%")
                    ->orWhere('address','like',"%{$q}%");
            });
        }

        if (in_array($status, ['active','inactive','1','0'], true)) {
            $query->where('statut', in_array($status, ['active','1'], true));
        } else {
            $status = '';
        }

        // filtre par date de création (YYYY-MM-DD)
        $from = null; $to = null;
        try { if ($date_from) $from = Carbon::createFromFormat('Y-m-d', $date_from)->startOfDay(); } catch (\Throwable $e) { $date_from = ''; }
        try { if ($date_to)   $to   = Carbon::createFromFormat('Y-m-d', $date_to)->endOfDay();   } catch (\Throwable $e) { $date_to = ''; }

        if ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        } elseif ($from) {
            $query->where('created_at', '>=', $from);
        } elseif ($to) {
            $query->where('created_at', '<=', $to);
        }

        $clients = $query->paginate(20)->appends($request->query());

        // on expose les valeurs des filtres à la vue
        return view('pages.app.admin.clients.index', compact('clients','q','status','date_from','date_to'));
    }

    public function show(Request $request, int $id): View
    {
        $client = Client::with('creatorAgent')->findOrFail($id);
        return view('pages.app.admin.clients.show', compact('client'));
    }

    public function edit(Request $request, int $id): View
    {
        $user = $request->user();
        if (! in_array($user->role, ['admin','super_admin'])) {
            abort(403);
        }

        $client = Client::findOrFail($id);
        return view('pages.app.admin.clients.edit', compact('client'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $user = $request->user();
        if (! in_array($user->role, ['admin','super_admin'])) {
            abort(403);
        }

        $client = Client::findOrFail($id);

        $data = $request->validate([
            'first_name'    => ['required','string','max:120'],
            'last_name'     => ['nullable','string','max:120'],
            'indicatif'     => ['nullable','string','max:50'],
            'phone'         => ['nullable','string','max:30'],
            'address'       => ['nullable','string','max:255'],
            'notes'         => ['nullable','string'],
            'statut'        => ['nullable','in:0,1'],
            'photo_profil'  => ['nullable','image','max:2048'],
        ]);

        if ($request->hasFile('photo_profil')) {
            $newPath = $request->file('photo_profil')->store('clients/photos', 'public');
            if (! empty($client->photo_profil)) {
                Storage::disk('public')->delete($client->photo_profil);
            }
            $client->photo_profil = $newPath;
        }

        $client->first_name = $data['first_name'];
        $client->last_name  = $data['last_name'] ?? null;
        $client->indicatif  = $data['indicatif'] ?? null;
        $client->phone      = $data['phone'] ?? null;
        $client->address    = $data['address'] ?? null;
        $client->notes      = $data['notes'] ?? null;
        $client->statut     = $request->has('statut') ? $request->boolean('statut') : $client->statut;

        $client->save();

        return redirect()->route('admin.clients.show', $client)->with('success', 'Client mis à jour.');
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        $user = $request->user();
        if (! in_array($user->role, ['admin','super_admin'])) {
            abort(403);
        }

        // charger même les entrées soft-deleted
        $client = Client::withTrashed()->findOrFail($id);

        if (! empty($client->photo_profil)) {
            Storage::disk('public')->delete($client->photo_profil);
        }

        // suppression définitive
        $client->forceDelete();

        return redirect()->route('admin.clients.index')->with('success', 'Client supprimé définitivement.');
    }
}