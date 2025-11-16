<?php
// Simple API endpoint for AJAX: retourne les mois déjà saisis pour un membre et une année
use Illuminate\Support\Facades\DB;

require_once __DIR__ . '/../../vendor/autoload.php';

if (!isset($_GET['kristianina_id']) || !isset($_GET['annee'])) {
    echo json_encode([]);
    exit;
}
$kristianina_id = intval($_GET['kristianina_id']);
$annee = intval($_GET['annee']);

$mois = DB::table('adidys')
    ->where('kristianina_id', $kristianina_id)
    ->where('annee', $annee)
    ->pluck('mois');
echo json_encode($mois);
