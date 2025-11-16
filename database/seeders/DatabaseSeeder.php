<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create or update admin user
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'email' => 'admin@example.com',
                'password' => Hash::make('adm123'),
                'role' => 'admin',
            ]
        );
        if (!$admin->wasRecentlyCreated) {
            $admin->update([
                'email' => 'admin@example.com',
                'password' => Hash::make('adm123'),
                'role' => 'admin',
            ]);
        }

        // Create agent for admin with matricule MAT_ADMIN12
        $adminAgent = \App\Models\Agent::firstOrCreate(
            ['matricule' => 'MAT_ADMIN12'],
            [
                'nom' => 'Administrateur',
                'prenom' => 'Système',
                'ministere_rattachement' => 'Ministère du Travail',
                'direction' => 'Direction Générale',
                'service' => 'Administration',
                'user_id' => $admin->id,
            ]
        );
        if (!$adminAgent->wasRecentlyCreated) {
            $adminAgent->update([
                'user_id' => $admin->id,
            ]);
        }

        // Create or update Rakoto user
        $rakoto = User::firstOrCreate(
            ['username' => 'rakoto'],
            [
                'email' => 'rakoto@gmail.com',
                'password' => Hash::make('Rakoto123'),
                'role' => 'utilisateur',
            ]
        );
        if (!$rakoto->wasRecentlyCreated) {
            $rakoto->update([
                'email' => 'rakoto@gmail.com',
                'password' => Hash::make('Rakoto123'),
                'role' => 'utilisateur',
            ]);
        }

        // Call other seeders
        $this->call([
            \Database\Seeders\DocumentTypeSeeder::class,
            \Database\Seeders\DirectionSeeder::class,
            \Database\Seeders\ServiceSeeder::class,
            \Database\Seeders\ConfidentialitySeeder::class,
            \Database\Seeders\AgentSeeder::class,
        ]);
    }
}
