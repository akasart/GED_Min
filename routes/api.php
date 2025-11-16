<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Kristianina;

Route::get('/api/kristianina/{kristianina}/adidy', function (Kristianina $kristianina) {
    $adidys = $kristianina->adidys()->orderByDesc('annee')->orderBy('mois')->get();
    $moisNoms = [1=>'Janvier',2=>'Février',3=>'Mars',4=>'Avril',5=>'Mai',6=>'Juin',7=>'Juillet',8=>'Août',9=>'Septembre',10=>'Octobre',11=>'Novembre',12=>'Décembre'];
    return $adidys->map(function($a) use ($moisNoms) {
        return [
            'mois' => $a->mois,
            'mois_nom' => $moisNoms[$a->mois] ?? $a->mois,
            'annee' => $a->annee,
            'montant' => $a->montant,
            'paye' => (bool)$a->paye,
        ];
    });
});
