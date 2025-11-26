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

        // S'assurer que les autres admins n'ont pas le flag super-admin
        User::where('role', 'admin')
            ->where('id', '!=', $super->id)
            ->update(['is_super_admin' => false]);
    }
}
