<?php
// Script de prueba para verificar el cálculo del tiempo activo

echo "<h2>Prueba de Cálculo de Tiempo Activo</h2>";

function calcularDiasActivo($fechaEmision) {
    if (empty($fechaEmision)) {
        return 0;
    }
    
    try {
        $fechaEmisionObj = new DateTime($fechaEmision);
        $fechaActual = new DateTime();
        $diferencia = $fechaActual->getTimestamp() - $fechaEmisionObj->getTimestamp();
        $dias = floor($diferencia / (60 * 60 * 24));
        return max(0, $dias);
    } catch (Exception $e) {
        return 0;
    }
}

function calcularAnosActivo($dias) {
    if ($dias == 0) return 0;
    return round($dias / 365.25, 2);
}

function formatearAnosTexto($dias) {
    if ($dias == 0) return 'Sin fecha';
    $anos = calcularAnosActivo($dias);
    if ($anos < 1) return 'Menos de 1 año';
    if ($anos == 1) return '1 año';
    return sprintf('%.1f años', $anos);
}

// Fechas de prueba del screenshot
$fechasPrueba = [
    '2023-01-01',
    '2022-09-06', 
    '2000-01-07'
];

foreach ($fechasPrueba as $fecha) {
    $dias = calcularDiasActivo($fecha);
    $anos = formatearAnosTexto($dias);
    
    echo "<div style='border: 1px solid #ccc; margin: 10px; padding: 10px;'>";
    echo "<strong>Fecha:</strong> $fecha<br>";
    echo "<strong>Días activo:</strong> $dias días<br>";
    echo "<strong>Años:</strong> $anos<br>";
    echo "</div>";
}

echo "<p><strong>Fecha actual:</strong> " . date('Y-m-d H:i:s') . "</p>";
?>
