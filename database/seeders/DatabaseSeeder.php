<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crée les rôles de base
        $this->call(\Database\Seeders\RolesSeeder::class);

        // Création de l'administrateur initial (si n'existe pas)
        $admin = User::firstOrCreate(
            ['email' => 'admin@mbenda.test'],
            [
                'first_name' => 'Admin',
                'last_name' => 'Mbenda',
                'phone' => '+237600000000',
                'password' => Hash::make('ChangeMe123!'),
                'role' => 'admin',
                'color_hex' => null,
                'active' => true,
                'photo_profil' => null, // ajouté
                // is_super_admin reste false par défaut
            ]
        );
        $admin->assignRole('admin');

        // Création d'un agent de test (si n'existe pas)
        $agent = User::firstOrCreate(
            ['email' => 'agent@mbenda.test'],
            [
                'first_name' => 'Agent',
                'last_name' => 'Demo',
                'phone' => '+237600000001',
                'password' => Hash::make('ChangeMe123!'),
                'role' => 'agent',
                'color_hex' => '#FF5733',
                'active' => true,
                'photo_profil' => null, // ajouté
            ]
        );
        $agent->assignRole('agent');

        // Création (ou mise à jour) du super-admin — doit figurer ici
        $super = User::updateOrCreate(
            ['email' => 'superadmin@mbenda.test'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'phone' => '+237600000009',
                'password' => Hash::make('ChangeMe123!'),
                'role' => 'admin',
                'color_hex' => null,
                'is_super_admin' => true,
                'active' => true,
                'photo_profil' => null, // ajouté
            ]
        );
        $super->assignRole('admin');

        // S'assurer que les autres admins n'ont pas le flag super-admin
        User::where('role', 'admin')
            ->where('id', '!=', $super->id)
            ->update(['is_super_admin' => false]);
    }
}
