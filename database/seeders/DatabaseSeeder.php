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
            ]
        );
        $agent->assignRole('agent');
    }
}
