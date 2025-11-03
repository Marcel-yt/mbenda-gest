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
        $clients = Client::with('creatorAgent')->orderBy('created_at', 'desc')->paginate(20);
        return view('pages.app.admin.clients.index', compact('clients'));
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