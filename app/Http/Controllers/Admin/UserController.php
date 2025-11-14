<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(): View
    {
        $auth = auth()->user();

        // accès réservé aux admins (incl. super-admin)
        if (! $auth || $auth->role !== 'admin') {
            abort(403);
        }

        // super-admin voit admins + agents ; admin normal voit seulement agents
        if (! empty($auth->is_super_admin)) {
            $query = User::whereIn('role', ['admin', 'agent']);
        } else {
            $query = User::where('role', 'agent');
        }

        $roleFilterEnabled = ! empty($auth->is_super_admin);
        $q      = trim(request('q',''));
        $status = request('status','');
        $role   = request('role','');

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('first_name','like','%'.$q.'%')
                    ->orWhere('last_name','like','%'.$q.'%')
                    ->orWhere('email','like','%'.$q.'%')
                    ->orWhere('phone','like','%'.$q.'%');
            });
        }

        if ($roleFilterEnabled && in_array($role,['admin','agent'], true)) {
            $query->where('role',$role);
        } else {
            $role = '';
        }

        if (in_array($status,['active','inactive'], true)) {
            $query->where('active', $status === 'active');
        } else {
            $status = '';
        }

        $users = $query
            ->where('id','!=',$auth->id)
            ->with('creator')
            ->orderBy('created_at','desc')
            ->paginate(15)
            ->appends(request()->query());

        return view('pages.app.admin.users.index', compact('users','q','status','role','roleFilterEnabled'));
    }

    // Affiche le formulaire de création
    public function create(): View
    {
        return view('pages.app.admin.users.create');
    }

    /**
     * Store a newly created user (admin area) and set created_by = auth()->id().
     */
    public function store(Request $request): RedirectResponse
    {
        $auth = $request->user();

        $data = $request->validate([
            'first_name'       => ['required','string','max:120'],
            'last_name'        => ['nullable','string','max:120'],
            'email'            => ['required','email','max:255','unique:users,email'],
            'phone'            => ['nullable','string','max:30'],
            'role'             => ['required','in:admin,agent'],
            'color_hex'        => ['nullable','regex:/^#([0-9A-Fa-f]{6})$/','unique:users,color_hex'],
            'active'           => ['nullable','in:0,1'],
            'password'         => ['required','string','min:8','confirmed'],
            'photo_profil'     => ['nullable','image','max:2048'],
        ], [
            'color_hex.unique' => 'Cette couleur est déjà utilisée, veuillez choisir une autre.',
            'color_hex.regex'  => 'Le format de la couleur doit être au format hexadécimal (#RRGGBB).',
        ]);

        if ($request->hasFile('photo_profil')) {
            $data['photo_profil'] = $request->file('photo_profil')->store('users/photos','public');
        }

        $user = new User();
        $user->first_name   = $data['first_name'];
        $user->last_name    = $data['last_name'] ?? null;
        $user->email        = $data['email'];
        $user->phone        = $data['phone'] ?? null;
        $user->role         = $data['role'];
        $user->color_hex    = $data['color_hex'] ?? null;
        $user->active       = isset($data['active']) ? (bool)$data['active'] : true;
        $user->password     = Hash::make($data['password']);
        $user->photo_profil = $data['photo_profil'] ?? null;

        // IMPORTANT: enregistrer l'ID du créateur
        // $auth est une instance de User — utiliser la propriété id (pas une méthode)
        if ($auth) {
            $user->created_by = $auth->id;
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé.');
    }

    /**
     * Affiche la fiche d'un utilisateur (admins uniquement).
     */
    public function show(int $id): View
    {
        $auth = auth()->user();
        if (! $auth || $auth->role !== 'admin') {
            abort(403);
        }

        $user = User::findOrFail($id);

        // un admin simple ne doit pas voir/consulter les admins (sauf super-admin)
        if (empty($auth->is_super_admin) && $user->role === 'admin') {
            abort(403);
        }

        return view('pages.app.admin.users.show', compact('user'));
    }

    /**
     * Affiche le formulaire d'édition d'un utilisateur (admins uniquement).
     */
    public function edit(int $id): View
    {
        $auth = auth()->user();
        if (! $auth || $auth->role !== 'admin') {
            abort(403);
        }

        $user = User::findOrFail($id);

        // un admin simple ne doit pas éditer les admins (sauf super-admin)
        if (empty($auth->is_super_admin) && $user->role === 'admin') {
            abort(403);
        }

        return view('pages.app.admin.users.edit', compact('user'));
    }

    /**
     * Met à jour un utilisateur (admins only). Gère photo, couleur et statut.
     * Ne permet plus de nommer / transférer le statut super-admin depuis l'interface.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $auth = auth()->user();
        if (! $auth || $auth->role !== 'admin') {
            abort(403);
        }

        $user = User::findOrFail($id);

        // Un admin simple ne peut pas modifier/voir un autre admin
        if (empty($auth->is_super_admin) && $user->role === 'admin') {
            abort(403);
        }

        // Section mot de passe (formulaire séparé envoie update_section=password)
        if ($request->input('update_section') === 'password') {
            $data = $request->validate([
                'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            ]);

            if (empty($data['password'])) {
                return redirect()->route('admin.users.show', $user)
                    ->with('info', "Aucun changement effectué (mot de passe laissé vide).");
            }

            $user->password = \Illuminate\Support\Facades\Hash::make($data['password']);
            $user->save();

            return redirect()->route('admin.users.show', $user)
                ->with('success', "Mot de passe mis à jour.");
        }

        // Section informations générales
        $data = $request->validate([
            'first_name'   => ['required','string','max:120'],
            'last_name'    => ['nullable','string','max:120'],
            'email'        => ['required','email','max:255','unique:users,email,'.$user->id],
            'phone'        => ['nullable','string','max:30'],
            // unique sur color_hex en ignorant l'utilisateur courant pour éviter la contrainte lors d'update
            'color_hex'    => ['nullable','regex:/^#([0-9A-Fa-f]{6})$/','unique:users,color_hex,'.$user->id],
            'active'       => ['nullable','in:0,1'],
            'photo_profil' => ['nullable','image','max:2048'],
            // role / is_super_admin intentionally not changeable here
        ], [
            'color_hex.unique' => 'Cette couleur est déjà utilisée, veuillez choisir une autre.',
            'color_hex.regex'  => 'Le format de la couleur doit être au format hexadécimal (#RRGGBB).',
        ]);

        // upload photo si présent (disk "public")
        if ($request->hasFile('photo_profil')) {
            $newPath = $request->file('photo_profil')->store('users/photos', 'public');

            if (! empty($user->photo_profil)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->photo_profil);
            }

            $user->photo_profil = $newPath;
        }

        $user->first_name = $data['first_name'];
        $user->last_name  = $data['last_name'] ?? null;
        $user->email      = $data['email'];
        $user->phone      = $data['phone'] ?? null;
        $user->color_hex  = $data['color_hex'] ?? $user->color_hex;
        $user->active     = array_key_exists('active', $data) ? (bool) $data['active'] : $user->active;

        try {
            $user->save();
        } catch (\Illuminate\Database\QueryException $e) {
            // fallback: si une contrainte unique a été violée (par ex. color_hex)
            if ($e->getCode() === '23000') {
                return back()->withInput()->withErrors(['color_hex' => 'Cette couleur est déjà utilisée, veuillez choisir une autre.']);
            }
            throw $e;
        }

        return redirect()->route('admin.users.show', $user)->with('success', 'Utilisateur mis à jour.');
    }

    /**
     * Supprime (soft delete) un utilisateur.
     */
    public function destroy(Request $request, int $id): RedirectResponse
    {
        $auth = $request->user();
        if (! $auth || $auth->role !== 'admin') {
            abort(403);
        }

        $user = User::findOrFail($id);

        // Empêcher la suppression de soi-même
        if ($user->id === $auth->id) {
            return redirect()->route('admin.users.show', $user)
                ->withErrors(['general' => "Vous ne pouvez pas supprimer votre propre compte."]);
        }

        // Un admin non-super-admin ne peut pas supprimer les admins
        if (empty($auth->is_super_admin) && $user->role === 'admin') {
            abort(403);
        }

        // Supprimer la photo de profil si présente
        if (! empty($user->photo_profil)) {
            Storage::disk('public')->delete($user->photo_profil);
        }

        // Soft delete (ou delete définitif selon votre modèle)
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé.');
    }
}