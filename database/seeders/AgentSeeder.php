<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Agent;
use Illuminate\Support\Facades\Hash;

class AgentSeeder extends Seeder
{
    public function run()
    {
        // Get user Rakoto (should be created by DatabaseSeeder)
        $userRakoto = User::where('username', 'rakoto')->first();

        if ($userRakoto) {
            // Create agent for Rakoto
            $agentRakoto = Agent::firstOrCreate(
                ['matricule' => '123456'],
                [
                    'nom' => 'Rakoto',
                    'prenom' => 'Jean',
                    'ministere_rattachement' => 'Travail',
                    'direction' => 'Direction des Affaires Financières',
                    'service' => 'Comptabilité',
                    'user_id' => $userRakoto->id,
                ]
            );

            // Update agent if it exists to link to user
            if (!$agentRakoto->wasRecentlyCreated) {
                $agentRakoto->update([
                    'user_id' => $userRakoto->id,
                ]);
            }
        }

        // Create additional agents
        $agents = [
            [
                'matricule' => '123457',
                'nom' => 'Rasoana',
                'prenom' => 'Marie',
                'ministere_rattachement' => 'Travail',
                'direction' => 'Direction des Ressources Humaines',
                'service' => 'Formation',
            ],
            [
                'matricule' => '123458',
                'nom' => 'Raharimalala',
                'prenom' => 'Paul',
                'ministere_rattachement' => 'Travail',
                'direction' => 'Direction des Systèmes d\'Information',
                'service' => 'Développement',
            ],
            [
                'matricule' => '123459',
                'nom' => 'Rabe',
                'prenom' => 'Sophie',
                'ministere_rattachement' => 'Travail',
                'direction' => 'Direction Générale',
                'service' => 'Secrétariat',
            ],
        ];

        foreach ($agents as $agentData) {
            Agent::firstOrCreate(
                ['matricule' => $agentData['matricule']],
                $agentData
            );
        }

        // Ensure all agents (except admin agent) are linked to non-admin users
        $nonAdminUser = User::where('role', '!=', 'admin')->where('role', 'utilisateur')->first();

        if ($nonAdminUser) {
            // Don't update agents that already have a user_id or are admin agents
            Agent::whereNull('user_id')
                ->where('matricule', '!=', 'MAT_ADMIN12')
                ->update(['user_id' => $nonAdminUser->id]);
        }

        $this->command->info('Agents créés avec succès!');
        $this->command->info('- Rakoto (123456) - User: rakoto@gmail.com / Rakoto123');
        $this->command->info('- Rasoana (123457)');
        $this->command->info('- Raharimalala (123458)');
        $this->command->info('- Rabe (123459)');
    }
}
