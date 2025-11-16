<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ministere;

class MinistereSeeder extends Seeder
{
    public function run()
    {
        $ministeres = [
            ['code' => 'MTEFP', 'libelle' => 'Ministère du Travail, de l\'Emploi et de la Fonction Publique'],
            ['code' => 'MEF', 'libelle' => 'Ministère de l\'Économie et des Finances'],
            ['code' => 'MESUPRES', 'libelle' => 'Ministère de l\'Enseignement Supérieur et de la Recherche Scientifique'],
            ['code' => 'MEN', 'libelle' => 'Ministère de l\'Éducation Nationale'],
            ['code' => 'MSANPF', 'libelle' => 'Ministère de la Santé Publique'],
            ['code' => 'MEEF', 'libelle' => 'Ministère de l\'Environnement et des Eaux et Forêts'],
            ['code' => 'MTPI', 'libelle' => 'Ministère des Travaux Publics et de l\'Infrastructure'],
            ['code' => 'MINT', 'libelle' => 'Ministère de l\'Intérieur'],
            ['code' => 'MAE', 'libelle' => 'Ministère des Affaires Étrangères'],
            ['code' => 'MJ', 'libelle' => 'Ministère de la Justice'],
        ];

        foreach ($ministeres as $ministere) {
            Ministere::firstOrCreate(
                ['code' => $ministere['code']],
                $ministere
            );
        }
    }
}