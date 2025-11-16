<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Confidentiality;

class ConfidentialitySeeder extends Seeder
{
    public function run()
    {
        $items = [
            ['code'=>'PUBLIC','label'=>'Public'],
            ['code'=>'INTERNE','label'=>'Interne'],
            ['code'=>'CONFIDENTIEL','label'=>'Confidentiel'],
            ['code'=>'RESTREINT','label'=>'Accès restreint']
        ];
        foreach ($items as $it) { Confidentiality::firstOrCreate($it); }
    }
}
