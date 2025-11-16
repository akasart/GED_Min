<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentType;

class DocumentTypeSeeder extends Seeder
{
    public function run()
    {
        $types = ['PDF','WORD','EXCEL','IMAGE','SCAN'];
        foreach ($types as $t) { DocumentType::firstOrCreate(['libelle'=>$t]); }
    }
}
