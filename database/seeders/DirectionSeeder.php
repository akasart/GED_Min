<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Direction;

class DirectionSeeder extends Seeder
{
    public function run()
    {
        $dirs = [
            ['code'=>'SG','libelle'=>'Secretariat general'],
            ['code'=>'DAF','libelle'=>'Direction des Affaires Financières'],
            ['code'=>'DSI','libelle'=>'Direction des Systèmes d\'Information'],
            ['code'=>'DRH','libelle'=>'Direction des Ressources Humaines']
        ];
        foreach ($dirs as $d) { Direction::firstOrCreate($d); }
    }
}
