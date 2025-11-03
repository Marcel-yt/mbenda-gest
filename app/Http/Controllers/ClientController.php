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
     * Liste des clients.
     * Tous les agents (et admins) voient la même liste : tous les clients.
     */
    public function index(Request $request): View
    {
        $clients = Client::query()
            ->with('creatorAgent')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('pages.app.agent.clients.index', compact('clients'));
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
        // registered_at removed; created_at (laravel) will reflect creation time
        $client->save();

        return redirect()->route('clients.show', $client)->with('success', 'Client créé.');
    }

    /**
     * Affiche un client. Tous les agents peuvent voir.
     */
    public function show(Request $request, int $id): View
    {
        $client = Client::with('creatorAgent')->findOrFail($id);

        return view('pages.app.agent.clients.show', compact('client'));
    }

    /**
     * Formulaire d'édition (agents seulement, propriétaire du client).
     */
    public function edit(Request $request, int $id): View
    {
        $user = $request->user();
        $client = Client::findOrFail($id);

        // only owner or admin/super_admin can edit
        if (! ($user->id === $client->created_by_agent_id || in_array($user->role, ['admin','super_admin']))) {
            abort(403);
        }

        return view('pages.app.agent.clients.edit', compact('client'));
    }

    /**
     * Mise à jour du client (agents seulement, propriétaire du client).
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $user = $request->user();
        $client = Client::findOrFail($id);

        // only owner or admin/super_admin can update
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
     * Suppression (soft delete) d'un client (seul l'admin ou super_admin).
     */
    public function destroy(Request $request, int $id): RedirectResponse
    {
        $user = $request->user();
        $client = Client::findOrFail($id);

        // only admin or super_admin can delete
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