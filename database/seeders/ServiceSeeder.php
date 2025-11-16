<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\Direction;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $mapping = [
            'DRH' => ['Recrutement','Formation','Paie'],
            'DAF' => ['Comptabilité','Finance','Budget'],
            'DSI' => ['Développement','Infrastructure','Support']
        ];
        foreach ($mapping as $code => $services) {
            $dir = Direction::where('code',$code)->first();
            foreach ($services as $s) {
                Service::firstOrCreate(['direction_id'=>$dir?->id,'name'=>$s]);
            }
        }
    }
}
