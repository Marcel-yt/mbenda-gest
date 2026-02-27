<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends ApiController
{
    /**
     * GET /api/admin/users
     * Params: q, status, role, per_page
     */
    public function index(Request $request): JsonResponse
    {
        $auth    = $request->user();
        $q       = trim($request->get('q', ''));
        $status  = $request->get('status', '');
        $role    = $request->get('role', '');
        $perPage = min((int) $request->get('per_page', 20), 100);

        // super-admin voit admins + agents ; admin normal voit seulement agents
        if (! empty($auth->is_super_admin)) {
            $query = User::whereIn('role', ['admin', 'agent']);
        } else {
            $query = User::where('role', 'agent');
        }

        $query->where('id', '!=', $auth->id)->with('creator:id,first_name,last_name');

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name',  'like', "%{$q}%")
                    ->orWhere('email',      'like', "%{$q}%")
                    ->orWhere('phone',      'like', "%{$q}%");
            });
        }

        if (! empty($auth->is_super_admin) && in_array($role, ['admin', 'agent'], true)) {
            $query->where('role', $role);
        }

        if (in_array($status, ['active', 'inactive'], true)) {
            $query->where('active', $status === 'active');
        }

        $paginator = $query->orderByDesc('created_at')->paginate($perPage);

        return $this->paginated($paginator);
    }

    /**
     * GET /api/admin/users/{id}
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $auth = $request->user();
        $user = User::with('creator:id,first_name,last_name')->findOrFail($id);

        if (empty($auth->is_super_admin) && $user->role === 'admin') {
            return $this->error('Accès interdit.', [], 403);
        }

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
            'is_super_admin' => $user->is_super_admin,
            'photo_profil'   => $user->photo_profil ? asset('storage/'.$user->photo_profil) : null,
            'last_login_at'  => $user->last_login_at?->toIso8601String(),
            'created_at'     => $user->created_at?->toIso8601String(),
            'creator'        => $user->creator ? [
                'id'   => $user->creator->id,
                'name' => $user->creator->name,
            ] : null,
        ]);
    }

    /**
     * POST /api/admin/users
     * Body: first_name, last_name?, email, phone?, role, color_hex?, active?, password, password_confirmation, photo_profil?
     */
    public function store(Request $request): JsonResponse
    {
        $auth = $request->user();

        $data = $request->validate([
            'first_name'       => ['required', 'string', 'max:120'],
            'last_name'        => ['nullable', 'string', 'max:120'],
            'email'            => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'            => ['nullable', 'string', 'max:30'],
            'role'             => ['required', 'in:admin,agent'],
            'color_hex'        => ['nullable', 'regex:/^#([0-9A-Fa-f]{6})$/', 'unique:users,color_hex'],
            'active'           => ['nullable', 'in:0,1'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
            'photo_profil'     => ['nullable', 'image', 'max:2048'],
        ], [
            'color_hex.unique' => 'Cette couleur est déjà utilisée.',
            'color_hex.regex'  => 'Le format doit être hexadécimal (#RRGGBB).',
        ]);

        if ($request->hasFile('photo_profil')) {
            $data['photo_profil'] = $request->file('photo_profil')->store('users/photos', 'public');
        }

        $user = new User();
        $user->first_name   = $data['first_name'];
        $user->last_name    = $data['last_name'] ?? null;
        $user->email        = $data['email'];
        $user->phone        = $data['phone'] ?? null;
        $user->role         = $data['role'];
        $user->color_hex    = $data['color_hex'] ?? null;
        $user->active       = isset($data['active']) ? (bool) $data['active'] : true;
        $user->password     = Hash::make($data['password']);
        $user->photo_profil = $data['photo_profil'] ?? null;
        $user->created_by   = $auth->id;
        $user->save();

        return $this->success(['id' => $user->id, 'name' => $user->name, 'email' => $user->email, 'role' => $user->role], 'Utilisateur créé.', 201);
    }

    /**
     * PUT /api/admin/users/{id}
     * Body: first_name, last_name?, email, phone?, color_hex?, active?, photo_profil?
     * OR: update_section=password + password + password_confirmation
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $auth = $request->user();
        $user = User::findOrFail($id);

        if (empty($auth->is_super_admin) && $user->role === 'admin') {
            return $this->error('Accès interdit.', [], 403);
        }

        // Changement de mot de passe uniquement
        if ($request->input('update_section') === 'password') {
            $request->validate([
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
            $user->password = Hash::make($request->password);
            $user->save();
            return $this->success(null, 'Mot de passe mis à jour.');
        }

        $data = $request->validate([
            'first_name'   => ['required', 'string', 'max:120'],
            'last_name'    => ['nullable', 'string', 'max:120'],
            'email'        => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone'        => ['nullable', 'string', 'max:30'],
            'color_hex'    => ['nullable', 'regex:/^#([0-9A-Fa-f]{6})$/', 'unique:users,color_hex,'.$user->id],
            'active'       => ['nullable', 'in:0,1'],
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
        $user->email       = $data['email'];
        $user->phone       = $data['phone'] ?? null;
        $user->color_hex   = $data['color_hex'] ?? $user->color_hex;
        $user->active      = array_key_exists('active', $data) ? (bool) $data['active'] : $user->active;
        if (isset($data['photo_profil'])) {
            $user->photo_profil = $data['photo_profil'];
        }
        $user->save();

        return $this->success(['id' => $user->id, 'name' => $user->name], 'Utilisateur mis à jour.');
    }

    /**
     * DELETE /api/admin/users/{id}
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $auth = $request->user();
        $user = User::findOrFail($id);

        if ($user->id === $auth->id) {
            return $this->error('Vous ne pouvez pas supprimer votre propre compte.', [], 422);
        }

        if (empty($auth->is_super_admin) && $user->role === 'admin') {
            return $this->error('Accès interdit.', [], 403);
        }

        if ($user->photo_profil) {
            Storage::disk('public')->delete($user->photo_profil);
        }

        $user->forceDelete();

        return $this->success(null, 'Utilisateur supprimé.');
    }
}
