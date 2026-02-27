<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends ApiController
{
    /**
     * Connexion : retourne un token Sanctum.
     *
     * POST /api/auth/login
     * Body: { email, password, device_name? }
     */
    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email'       => 'required|email',
            'password'    => 'required|string',
            'device_name' => 'nullable|string|max:255',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les identifiants sont incorrects.'],
            ]);
        }

        if (! $user->active) {
            return $this->error('Votre compte est désactivé. Contactez un administrateur.', [], 403);
        }

        // Mettre à jour last_login_at
        $user->last_login_at = now();
        $user->save();

        $deviceName = $data['device_name'] ?? ($request->userAgent() ?? 'mobile');
        $token = $user->createToken($deviceName)->plainTextToken;

        return $this->success([
            'token'      => $token,
            'token_type' => 'Bearer',
            'user'       => $this->userResource($user),
        ], 'Connexion réussie.');
    }

    /**
     * Déconnexion : révoke le token courant.
     *
     * POST /api/auth/logout
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'Déconnexion réussie.');
    }

    /**
     * Retourne l'utilisateur connecté.
     *
     * GET /api/auth/me
     */
    public function me(Request $request): JsonResponse
    {
        return $this->success($this->userResource($request->user()));
    }

    /**
     * Transforme l'objet User en tableau pour l'API.
     */
    private function userResource(User $user): array
    {
        return [
            'id'              => $user->id,
            'first_name'      => $user->first_name,
            'last_name'       => $user->last_name,
            'name'            => $user->name,
            'email'           => $user->email,
            'phone'           => $user->phone,
            'role'            => $user->role,
            'color_hex'       => $user->color_hex,
            'active'          => $user->active,
            'is_super_admin'  => $user->is_super_admin,
            'photo_profil'    => $user->photo_profil
                ? asset('storage/'.$user->photo_profil)
                : null,
            'last_login_at'   => $user->last_login_at?->toIso8601String(),
            'created_at'      => $user->created_at?->toIso8601String(),
        ];
    }

    /**
     * Envoie un e-mail de réinitialisation de mot de passe.
     *
     * POST /api/auth/forgot-password
     * Body: { email }
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return $this->success(null, 'Un lien de réinitialisation a été envoyé à votre adresse e-mail.');
        }

        return $this->error('Impossible d\'envoyer le lien. Vérifiez l\'adresse e-mail.', [
            'email' => [__($status)],
        ], 422);
    }

    /**
     * Réinitialise le mot de passe à partir du token reçu par e-mail.
     *
     * POST /api/auth/reset-password
     * Body: { email, token, password, password_confirmation }
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token'                 => 'required|string',
            'email'                 => 'required|email',
            'password'              => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])
                     ->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return $this->success(null, 'Mot de passe réinitialisé avec succès. Vous pouvez vous connecter.');
        }

        return $this->error('Lien invalide ou expiré.', [
            'token' => [__($status)],
        ], 422);
    }
}
