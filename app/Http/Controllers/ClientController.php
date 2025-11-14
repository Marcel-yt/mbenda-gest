<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    /**
     * Liste des clients (avec filtres).
     */
    public function index(Request $request): View
    {
        $q         = trim($request->get('q',''));
        $status    = $request->get('status','');
        $date_from = $request->get('date_from','');
        $date_to   = $request->get('date_to','');

        $query = Client::with('creatorAgent');

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('first_name','like',"%{$q}%")
                  ->orWhere('last_name','like',"%{$q}%")
                  ->orWhere('phone','like',"%{$q}%")
                  ->orWhere('address','like',"%{$q}%");
            });
        }

        if (in_array($status, ['active','inactive','1','0'], true)) {
            $query->where('statut', in_array($status, ['active','1'], true));
        } else {
            $status = '';
        }

        $from = $to = null;
        try { if ($date_from) $from = \Carbon\Carbon::createFromFormat('Y-m-d',$date_from)->startOfDay(); } catch (\Throwable $e) { $date_from=''; }
        try { if ($date_to)   $to   = \Carbon\Carbon::createFromFormat('Y-m-d',$date_to)->endOfDay();   } catch (\Throwable $e) { $date_to=''; }

        if ($from && $to) {
            $query->whereBetween('created_at', [$from,$to]);
        } elseif ($from) {
            $query->where('created_at','>=',$from);
        } elseif ($to) {
            $query->where('created_at','<=',$to);
        }

        $clients = $query->orderByDesc('id')->paginate(20)->appends($request->query());

        return view('pages.app.agent.clients.index', compact('clients','q','status','date_from','date_to'));
    }

    /**
     * Formulaire de création (agents seulement).
     */
    public function create(Request $request): View
    {
        $user = $request->user();
        if ($user->role !== 'agent') {
            abort(403);
        }

        return view('pages.app.agent.clients.create');
    }

    /**
     * Enregistrement d'un nouveau client (created_by_agent_id = auth()->id()).
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        if ($user->role !== 'agent') {
            abort(403);
        }

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
            $data['photo_profil'] = $request->file('photo_profil')->store('clients/photos', 'public');
        }

        $client = new Client();
        $client->first_name = $data['first_name'];
        $client->last_name  = $data['last_name'] ?? null;
        $client->indicatif  = $data['indicatif'] ?? null;
        $client->phone      = $data['phone'] ?? null;
        $client->address    = $data['address'] ?? null;
        $client->notes      = $data['notes'] ?? null;
        $client->created_by_agent_id = $request->user()->id;
        $client->statut     = $request->boolean('statut', true);
        $client->photo_profil = $data['photo_profil'] ?? null;
        $client->save();

        return redirect()->route('clients.show', $client)->with('success', 'Client créé.');
    }

    /**
     * Affiche un client.
     */
    public function show(Request $request, int $id): View
    {
        $client = Client::with('creatorAgent')->findOrFail($id);

        return view('pages.app.agent.clients.show', compact('client'));
    }

    /**
     * Formulaire d'édition (agents seulement, propriétaire ou admin/super_admin).
     */
    public function edit(Request $request, int $id): View
    {
        $user = $request->user();
        $client = Client::findOrFail($id);

        if (! ($user->id === $client->created_by_agent_id || in_array($user->role, ['admin','super_admin']))) {
            abort(403);
        }

        return view('pages.app.agent.clients.edit', compact('client'));
    }

    /**
     * Mise à jour du client.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $user = $request->user();
        $client = Client::findOrFail($id);

        if (! ($user->id === $client->created_by_agent_id || in_array($user->role, ['admin','super_admin']))) {
            abort(403);
        }

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

        $client->indicatif = $data['indicatif'] ?? null;
        $client->first_name = $data['first_name'];
        $client->last_name  = $data['last_name'] ?? null;
        $client->phone      = $data['phone'] ?? null;
        $client->address    = $data['address'] ?? null;
        $client->notes      = $data['notes'] ?? null;
        $client->statut     = $request->has('statut') ? $request->boolean('statut') : $client->statut;
        $client->save();

        return redirect()->route('clients.show', $client)->with('success', 'Client mis à jour.');
    }

    /**
     * Suppression (soft delete) d'un client (admin ou super_admin).
     */
    public function destroy(Request $request, int $id): RedirectResponse
    {
        $user = $request->user();
        $client = Client::findOrFail($id);

        if (! in_array($user->role, ['admin','super_admin'])) {
            abort(403);
        }

        if (! empty($client->photo_profil)) {
            Storage::disk('public')->delete($client->photo_profil);
        }

        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client supprimé.');
    }
}