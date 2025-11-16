<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Agent;
use Illuminate\Support\Facades\Hash;

class UpdateAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This seeder updates the admin user and creates/updates the admin agent
     */
    public function run(): void
    {
        // Update admin user password to adm123
        $admin = User::where('username', 'admin')->orWhere('role', 'admin')->first();
        
        if ($admin) {
            $admin->update([
                'password' => Hash::make('adm123'),
                'role' => 'admin',
            ]);
            
            // Create or update agent for admin with matricule MAT_ADMIN12
            $adminAgent = Agent::firstOrCreate(
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
            
            // Ensure the agent is linked to admin user
            if ($adminAgent->user_id !== $admin->id) {
                $adminAgent->update([
                    'user_id' => $admin->id,
                ]);
            }
            
            $this->command->info('Admin mis à jour avec succès!');
            $this->command->info('Matricule: MAT_ADMIN12');
            $this->command->info('Mot de passe: adm123');
        } else {
            $this->command->error('Aucun utilisateur admin trouvé!');
        }
    }
}

