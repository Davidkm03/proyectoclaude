<?php
require_once __DIR__ . '/../config/config.php';

header('Content-Type: application/json');

$pacientes = leerJSON(PACIENTES_FILE);
$doctores = leerJSON(DOCTORES_FILE);
$citas = leerJSON(CITAS_FILE);

$citasProgramadas = 0;
foreach ($citas as $cita) {
    if ($cita['Estado'] === 'Programada') {
        $citasProgramadas++;
    }
}

$estadisticas = [
    'pacientes' => count($pacientes),
    'doctores' => count($doctores),
    'citas' => $citasProgramadas
];

echo json_encode($estadisticas);
?>
