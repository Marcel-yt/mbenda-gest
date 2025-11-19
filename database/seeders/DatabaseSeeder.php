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

        // Création (ou mise à jour) du super-admin
        $super = User::updateOrCreate(
            ['email' => 'hellombenda@gmail.com'],
            [
                'first_name' => 'Hello',
                'last_name' => 'Mbenda',
                'phone' => '+24106083193',
                'password' => Hash::make('ChangeMe123!'),
                'role' => 'admin',
                'color_hex' => '#0078B7', // Bleu
                'is_super_admin' => true,
                'active' => true,
                'photo_profil' => null,
            ]
        );
        $super->assignRole('admin');

        // Création de l'administrateur
        $admin = User::updateOrCreate(
            ['email' => 'yamitmarcel@gmail.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'Mbendatest',
                'phone' => '+237600000000',
                'password' => Hash::make('ChangeMe123!'),
                'role' => 'admin',
                'color_hex' => '#9333EA', // Violet
                'active' => true,
                'photo_profil' => null,
            ]
        );
        $admin->assignRole('admin');

        // Création de l'agent
        $agent = User::updateOrCreate(
            ['email' => 'marceltientcheu4@gmail.com'],
            [
                'first_name' => 'Agent',
                'last_name' => 'Mbendatest',
                'phone' => '+237600000001',
                'password' => Hash::make('ChangeMe123!'),
                'role' => 'agent',
                'color_hex' => '#EAB308', // Jaune
                'active' => true,
                'photo_profil' => null,
            ]
        );
        $agent->assignRole('agent');

        // S'assurer que les autres admins n'ont pas le flag super-admin
        User::where('role', 'admin')
            ->where('id', '!=', $super->id)
            ->update(['is_super_admin' => false]);
    }
}
