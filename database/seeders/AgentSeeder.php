<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Agent;

class AgentSeeder extends Seeder
{
    public function run()
    {
        // Récupération de l'utilisateur Rakoto
        $userRakoto = User::where('username', 'rakoto')->first();

        // Si Rakoto existe
        if ($userRakoto) {
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

            // Mise à jour s'il existait déjà
            if (!$agentRakoto->wasRecentlyCreated) {
                $agentRakoto->update(['user_id' => $userRakoto->id]);
            }
        }

        // Récupération d'un utilisateur standard (non admin)
        $nonAdminUser = User::where('role', 'utilisateur')->first();

        // Liste des autres agents à créer
        $agents = [
            [
                'matricule' => '123457',
                'nom' => 'Rasoana',
                'prenom' => 'Marie',
                'ministere_rattachement' => 'Travail',
                'direction' => 'Direction des Ressources Humaines',
                'service' => 'Formation',
                'user_id' => $nonAdminUser?->id,
            ],
            [
                'matricule' => '123458',
                'nom' => 'Raharimalala',
                'prenom' => 'Paul',
                'ministere_rattachement' => 'Travail',
                'direction' => 'Direction des Systèmes d\'Information',
                'service' => 'Développement',
                'user_id' => $nonAdminUser?->id,
            ],
            [
                'matricule' => '123459',
                'nom' => 'Rabe',
                'prenom' => 'Sophie',
                'ministere_rattachement' => 'Travail',
                'direction' => 'Direction Générale',
                'service' => 'Secrétariat',
                'user_id' => $nonAdminUser?->id,
            ],
        ];

        // Création des agents
        foreach ($agents as $agentData) {
            Agent::firstOrCreate(
                ['matricule' => $agentData['matricule']],
                $agentData
            );
        }

        $this->command->info('Agents créés avec succès!');
        $this->command->info('- Rakoto (123456) - User: ' . ($userRakoto?->email ?? 'N/A'));
    }
}
