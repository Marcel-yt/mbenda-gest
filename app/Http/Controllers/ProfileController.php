<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Affiche la page profil (toujours rafraîchie depuis la BD).
     */
    public function show(): View
    {
        $user = auth()->user()->fresh();

        return view('profile.show', compact('user'));
    }

    /**
     * Formulaire d'édition du profil (utilisateur connecté).
     */
    public function edit(Request $request): View
    {
        // renvoyer l'utilisateur actuel (rafraîchi)
        return view('profile.edit', ['user' => $request->user()->fresh()]);
    }

    /**
     * Met à jour le profil de l'utilisateur connecté.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'first_name'   => ['required', 'string', 'max:120'],
            'last_name'    => ['nullable', 'string', 'max:120'],
            'phone'        => ['nullable', 'string', 'max:30'],
            'email'        => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'color_hex'    => ['nullable', 'regex:/^#([0-9A-Fa-f]{6})$/'],
            'active'       => ['nullable', 'in:0,1'],
            'photo_profil' => ['nullable', 'image', 'max:2048'],
        ]);

        // upload photo si présent (disk "public")
        if ($request->hasFile('photo_profil')) {
            $newPath = $request->file('photo_profil')->store('users/photos', 'public');

            // supprimer l'ancienne photo s'il y en a une
            if (! empty($user->photo_profil)) {
                Storage::disk('public')->delete($user->photo_profil);
            }

            $user->photo_profil = $newPath;
        }

        // appliquer les autres champs
        $user->first_name = $data['first_name'];
        $user->last_name  = $data['last_name'] ?? null;
        $user->phone      = $data['phone'] ?? null;
        $user->email      = $data['email'];
        $user->color_hex  = $data['color_hex'] ?? $user->color_hex;
        $user->active     = array_key_exists('active', $data) ? (bool) $data['active'] : $user->active;

        $user->save();

        // rediriger vers la page show pour forcer rechargement et voir la nouvelle photo
        return redirect()->route('profile.show')->with('status', 'profile-updated');
    }

    /**
     * Suppression du compte courant (nécessite mot de passe actuel).
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate(['password' => ['required', 'current_password']]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
