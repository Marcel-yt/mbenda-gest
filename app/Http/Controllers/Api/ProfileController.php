<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends ApiController
{
    /**
     * GET /api/profile
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();
        return $this->success([
            'id'             => $user->id,
            'first_name'     => $user->first_name,
            'last_name'      => $user->last_name,
            'name'           => $user->name,
            'email'          => $user->email,
            'phone'          => $user->phone,
            'role'           => $user->role,
            'color_hex'      => $user->color_hex,
            'active'         => $user->active,
            'photo_profil'   => $user->photo_profil
                ? asset('storage/'.$user->photo_profil)
                : null,
            'last_login_at'  => $user->last_login_at?->toIso8601String(),
        ]);
    }

    /**
     * PUT /api/profile
     * Body: first_name, last_name, phone, photo_profil (file)?
     */
    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'first_name'   => ['required', 'string', 'max:120'],
            'last_name'    => ['nullable', 'string', 'max:120'],
            'phone'        => ['nullable', 'string', 'max:30'],
            'photo_profil' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('photo_profil')) {
            if ($user->photo_profil) {
                Storage::disk('public')->delete($user->photo_profil);
            }
            $data['photo_profil'] = $request->file('photo_profil')->store('users/photos', 'public');
        }

        $user->first_name  = $data['first_name'];
        $user->last_name   = $data['last_name'] ?? null;
        $user->phone       = $data['phone'] ?? null;
        if (isset($data['photo_profil'])) {
            $user->photo_profil = $data['photo_profil'];
        }
        $user->save();

        return $this->success([
            'id'           => $user->id,
            'first_name'   => $user->first_name,
            'last_name'    => $user->last_name,
            'name'         => $user->name,
            'phone'        => $user->phone,
            'photo_profil' => $user->photo_profil
                ? asset('storage/'.$user->photo_profil)
                : null,
        ], 'Profil mis à jour.');
    }

    /**
     * PUT /api/profile/password
     * Body: current_password, password, password_confirmation
     */
    public function changePassword(Request $request): JsonResponse
    {
        $user = $request->user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (! Hash::check($request->current_password, $user->password)) {
            return $this->error('Le mot de passe actuel est incorrect.', [
                'current_password' => ['Le mot de passe actuel est incorrect.'],
            ], 422);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return $this->success(null, 'Mot de passe mis à jour.');
    }

    /**
     * DELETE /api/profile
     * Désactive le compte (soft approach) plutôt que suppression directe.
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->validate(['password' => ['required', 'string']]);

        $user = $request->user();

        if (! Hash::check($request->password, $user->password)) {
            return $this->error('Mot de passe incorrect.', [], 422);
        }

        // Révoquer tous les tokens
        $user->tokens()->delete();

        // Soft delete
        $user->delete();

        return $this->success(null, 'Compte supprimé.');
    }
}
